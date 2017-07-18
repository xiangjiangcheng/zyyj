<?php
/**
 * 积分管理部门接口控制器
 */
namespace Home\Controller;
use Think\Controller;
class ScoreManageController extends Controller {

	/**
	 * 获取积分排行榜方法
	 */

	public function ScoreManage_Rank(){
		//获取学生id
		$stu_id = I('stu_id');
		//$stu_id = 83;
		$findname = M('Student')->where(array('stu_id'=>$stu_id))->field('name,photo')->select();
		$myname = $findname[0]['name'];
		$myphoto = $findname[0]['photo'];
		//查询学生所在年级id
		$getgrade = M("Student")->join('LEFT JOIN zyyj_class ON zyyj_class.class_id=zyyj_student.class_id')->field('zyyj_class.grade_id')->where(array('zyyj_student.stu_id'=>$stu_id))->select();
		$gradeid=$getgrade[0]['grade_id'];
		//设置年级筛选条件
		$map['zyyj_grade.grade_id']=$gradeid;
		//获取年级前三十名的积分排行榜
		$rank = M('Stu_score')
			->join('LEFT JOIN zyyj_student ON zyyj_student.stu_id = zyyj_stu_score.stu_id LEFT JOIN zyyj_class ON zyyj_class.class_id = zyyj_student.class_id LEFT JOIN zyyj_grade ON zyyj_grade.grade_id =  zyyj_class.grade_id')
			->where($map)
			->field('zyyj_stu_score.stu_id,zyyj_student.name as stuname,sum(score) as tscore, programme_id,photo')
			->group('stu_id')
			->select();
		$snum = sizeof($rank);
		//排行榜信息
		$score = $this->ScoreManage_Conversion($rank,$snum,true);
		for($i = 0;$i<$snum;++$i){
			if($score[$i]['stu_id']==$stu_id){
				$myrank = $i+1;
				$myscore = $score[$i]['tscore'];
			}
			if($i>29){
				unset($score[$i]);
			}
		}
		if($snum==0){
			$data['status'] = 0;
			$data['message'] = '无积分信息';
		}else{
			$data['status'] = 1;
			$data['message'] = '积分信息获取成功！';
		}
		$data['myname'] = $myname;
		$data['myphoto'] = $myphoto;
		if($myrank==null){
			$data['myrank'] = $snum+1;
		}else{
			$data['myrank'] = $myrank;
		}
		if($myscore==null){
			$data['myscore'] = 0;
		}else{
			$data['myscore'] = $myscore;
		}
		$data['ranklist'] = $score;
		//print_r($data);
		echo json_encode($data);
	}
	/**
	 * [ScoreManage_Conversion 根据方案信息调整积分]
	 * @param [type] $str  [数据数组]
	 * @param [type] $snum [长度]
	 */
	private function ScoreManage_Conversion($str,$snum,$sort){
		//获取当前方案信息，调整积分
		//由更新时间逆序获取方案表里的方案信息
		$dqp = M('Programme')->order('createdate desc')->select();
		//获当前最新的方案编号
		$dqpid = $dqp[0]['programme_id'];
		//获取当前最新的方案总分
		$dqscore = $dqp[0]['score'];
		$get = array();
		//将方案编号作为key键 保存方案对应的总分。
		$pnum = sizeof($dqp);
		for($i=0;$i<$pnum;$i++){
			$get[$dqp[$i]['programme_id']]=$dqp[$i]['score'];
		}


		//获取减少积分的数组

		$reducelist = M('Reduce_score')->field('stu_id,sum(Reduce_score) as rscore')->group('stu_id')->select();
		$rnum = sizeof($reducelist);
		
		//print_r($reducelist);
		//根据当前方案调整用户的积分
		for($i=0;$i<$snum;++$i){
			if($str[$i]['programme_id']!=$dqpid){
				if($get[$str[$i]['programme_id']]>=$dqscore){
					$str[$i]['tscore'] /=$get[$str[$i]['programme_id']]/$dqscore;
				}else{
					$str[$i]['tscore'] *= $dqscore/$get[$str[$i]['programme_id']];
				}
				unset($str[$i]['programme_id']);
			}
		}
		//判断是否 需要排序
		if($sort){
			//获取需要排序的列
			foreach ($str as $key => $row){
			     $tscore[$key]  = $row['tscore'];
			}
			array_multisort($tscore, SORT_DESC,$str);
		}
		return $str;
	}

	/**
	 * [ScoreManage_exange 积分兑换页信息的获取方法]
	 */
	public function ScoreManage_exange(){
		$stu_id = I('stu_id');
		//查询出兑换规则
		$cgrule = M('Exhcangerule')->where(array('status'=>1))->select();
		$rule['name'] = $cgrule[0]['name'];
		$rule['score']  = $cgrule[0]['score'];
		$rule['integral'] = $cgrule[0]['integral'];
		$data['rule'] = $rule;
		//查找是否有兑换记录
		$search =  M('Exchangeintegral')->where(array('stu_id'=>$stu_id))->select();
		$ischag = sizeof($search);
		$arr['stu_id'] = $stu_id;
		$myscore  = $this->ScoreManage_getmyscore($arr);
		if($myscore==null)
			$myscore=0;
		$data['mysocre'] = $myscore;
		if($ischag!=0){
			$cglist['time'] = $search[0]['createdate'];
			$cglist['score'] = $search[0]['score'];
			$cglist['integral'] = $search[0]['integral'];
			$data['status'] = 0;
			$data['message'] = '您已经兑换过学分了';
			$data['cglist'] = $cglist;
		}else{
			$data['status'] = 1;
			$data['message'] = '还没有兑换过学分';
		}
		echo json_encode($data);
	}

	/**
	 * [ScoreManage_change 积分兑换按钮的响应方法]
	 */
	public function ScoreManage_change(){
		$stu_id = I('stu_id');
		//验证是否兑换过
		$isset = M('exchangeintegral')->where(array('stu_id'=>$stu_id))->count();
		if($isset!=0){
			$data['status'] = 3;
			$data['message'] = '兑换失败,您已兑换过学分';
		}else{
			//查询出当前使用的兑换规则
			$nowrule = M('Exhcangerule')->where(array('status'=>1))->select();
			//获取当前使用的 规则id
			$ruleid = $nowrule[0]['rule_id'];
			//获取当前规则兑换需要的积分和学分
			$needsocre = $nowrule[0]['score'];
			$getintegral = $nowrule[0]['integral'];
			//获取个人积分信息
			$arr['stu_id'] = $stu_id;
			$myscore = $this->ScoreManage_getmyscore($arr);
			//判断积分是否足够
			if($myscore<$needsocre){
				$data['status'] = 0;
				$data['message'] = '积分不足,兑换失败';
			}else{
				$info['score'] = $needsocre;
				$info['integral'] = $getintegral;
				$info['stu_id'] = $stu_id;
				$info['createdate'] = date("y-m-d H:i:s",time());
				$info['rule_id'] = $ruleid;
				$insert = M('exchangeintegral')->add($info);
				if($insert){
					$data['status'] = 1;
					$data['message'] = '兑换成功';
				}else{
					$data['status'] = 2;
					$data['message'] = '兑换失败,请重试';
				}
			}
		}
		//print_r($data);
		echo json_encode($data);
	}

	public function ScoreManage_index(){
		$this->display();
	}
	/**
	 * [ScoreManage_Myscore 获取个人积分详细信息方法]
	 */
	public function ScoreManage_Myscore(){
		$stu_id = I('stu_id');
		//$stu_id = 83;
		$arr['stu_id'] = $stu_id;
		$data['myscore'] = $this->ScoreManage_getmyscore($arr);
		//时间筛选条件
		$tarr = array('EGT',date('y-m-d 00:00:00'));
		$yarr = array('between',array(date("y-m-d 00:00:00",strtotime("-1 day")),date('y-m-d 00:00:00')));

		$map['stu_id'] = $stu_id;
		$map['create_date'] = $tarr;
		$today = $this->ScoreManage_getmyscore($map);
		if($today==null){
			$today = 0;
		}
		//昨天的
		$map['create_date'] = $yarr;
		$yestoday = $this->ScoreManage_getmyscore($map);
		if($yestoday==nuil){
			$yestoday = 0;
		}
		//获取今日记录
		$dayarr['stu_id'] = $stu_id;
		$dayarr['create_date'] = $tarr;
		$tmodel = M('Stu_score')->where($dayarr)->field('create_date,programme_id,score as tscore')->order('create_date desc')->select();
		$num = sizeof($tmodel);
		$todaylist = $this->ScoreManage_Conversion($tmodel,$num,false);
		//获取前一天的记录
		$dayarr['create_date'] = $yarr;
		$ymodel = M('Stu_score')->where($dayarr)->field('create_date,programme_id,score as tscore')->order('create_date desc')->select();
		$num2 = sizeof($ymodel);
		$yestodaylist  = $this->ScoreManage_Conversion($ymodel,$num2,false);

		if($stu_id==''){
			$data['status'] = 0;
			$data['message'] = '获取积分记录失败,请重试';
			$data['data']= null;
		}else{
			$data['status'] = 1;
			$data['message'] = '获取积分记录成功';
			$result = array(
				'today'        => $today,  //今日总分
				'yestoday'     => $yestoday, //昨日总分
				'todaylist'    => $todaylist, //今日获取积分记录
				'yestodaylist' => $yestodaylist, //昨日获取积分记录
			);
			$data['data'] = $result;
		}
		//print_r($data);
		echo json_encode($data);
	}

	/**
	 * [ScoreManage_getmyscore 获取个人积分信息]
	 * @param [int] $stuid [学生id]
	 */
	public function ScoreManage_getmyscore($arr){
		$getscore = M('Stu_score')->where($arr)->field('programme_id,sum(score) as tscore')->group('stu_id')->select();
		$getscore2 = $this->ScoreManage_Conversion($getscore,1,false);
		return $getscore2[0]['tscore'];
	}

}