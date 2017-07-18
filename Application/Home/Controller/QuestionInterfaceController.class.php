<?php
namespace Home\Controller;
use Think\Controller;
class QuestionInterfaceController extends BaseController {
	//获取练习题目
	function get_question(){
		$course_id=I("course_id");//获取科目id，多个科目中间用;隔开
		$question_num=I('question_num');//获取各种问题个数
		$checkpoint_id=I('checkpoint_id');//获取关卡id或者考试等级id,都用这个键名
		$stu_id=I("stu_id");	//学生id
		$reduce_score=I('reduce_score');	//获取需要扣掉的积分
	
		$json_array=array();//返回的数据的数组
		if($question_num==""||$question_num==null||$checkpoint_id==""||$checkpoint_id==null){	//参数不完整
			$json_array['status']=-1;
			$json_array['message']="参数不完整";
			$json_array['data']=null;
			echo json_encode($json_array);exit;
		}

		//如果要扣分
		if($reduce_score!=0){
			$date = time();  //得到当前日期时间
			$stu_reduce_score['create_date'] = date("y-m-d H:i:s",$date);
			$rs=M("reduce_score");
			$stu_reduce_score['stu_id']=$stu_id;
			
			//存入一条扣分数据
			$stu_reduce_score['reduce_score']=$reduce_score;
			$rs->add($stu_reduce_score);
		}
		

		$arr=explode(";",$question_num);//数组长度为3，依次为难，中，易的题目
		$difficult=array();	//难题
		$normal=array();//一般
		$easy=array();//简单
		$question=M("question");
		$course=M('course');
		$map['level_id']=3;//题目难度为困难
		$course_array=explode(";",$course_id);
		if(count($course_array)>1){
			$map['course_id']=array('in',str_replace(";",",",$course_id));
		}else{
			//单科
			$map['course_id']=$course_id;
		}
		$difficult_result=$question->where($map)->select();
		if($arr['0']>count($difficult_result)){
			$json_array['status']=0;
			$json_array['message']="数据库题目数不足";
			$json_array['data']=null;
			echo json_encode($json_array);exit;
		}
		$difficult_rand=$this->get_rand($arr['0'],count($difficult_result)-1);
		for($i=0;$i<count($difficult_rand);$i++){
			$difficult[$i]=$difficult_result[$difficult_rand[$i]];
		}

		$map['level_id']=2;//题目难度为一般
		$normal_result=$question->where($map)->select();
		if($arr['1']>count($normal_result)){
			$json_array['status']=0;
			$json_array['message']="数据库题目数不足";
			$json_array['data']=null;
			echo json_encode($json_array);exit;
		}
		$normal_rand=$this->get_rand($arr['1'],count($normal_result)-1);
		for($i=0;$i<count($normal_rand);$i++){
			$normal[$i]=$normal_result[$normal_rand[$i]];
		}

		$map['level_id']=1;//题目难度为简单
		$easy_result=$question->where($map)->select();
		if($arr['2']>count($easy_result)){
			$json_array['status']=0;
			$json_array['message']="数据库题目数不足";
			$json_array['data']=null;
			echo json_encode($json_array);exit;
		}
		$easy_rand=$this->get_rand($arr['2'],count($easy_result)-1);
		for($i=0;$i<count($easy_rand);$i++){
			$easy[$i]=$easy_result[$easy_rand[$i]];
		}
		$result = array_merge($easy, $normal,$difficult);
		
		$result['0']['checkpoint_id']=$checkpoint_id;
		$result['0']['send_course_id']=$course_id;
		$json_array['status']=1;
		$json_array['message']="获取题目成功";
		$json_array['data']=$result;
		echo json_encode($json_array);
	}

	//从0到$total，取$num个随机数
	function get_rand($num,$total){
		$connt = 0;
		while($connt<$num)
		{
        	$a[]=rand(0,$total);//产生随机数
        	$ary=array_unique($a);//遍历数组$a，如有相同的值则剔除该值
        	$connt=count($ary);
		}
		foreach ($ary as $row) {
			$array[]=$row;
		}
		return $array;
	}
	
	//题目提交
	function question_submit(){
		date_default_timezone_set('prc');		//设置时区，中国  
		$time=time();
		$type=I('type');	//类型，1练习，2考试
		$questionstr=I('question');
		//题目字符串，例如：question_id::chooseansweer;;question_id1::chooseanswer,id在前，选择的答案在后，中间用两个引号隔开，每道题之间用两个分号隔开
		$stu_id=I('stu_id');//已登录学生的id
		$checkpoint_id=I('checkpoint_id');//关卡id或者考试等级id
		$course_id=I('course_id');//科目id

		$checkpoint=M('checkpoint');
		$exam_level=M('exam_level');
		$question=M('question');
		$question_record=M('question_record');
		$student_practice=M('student_practice');
		$stu_score=M('stu_score');

		//根据关卡id或者考试等级id，查询出方案id,和关卡的过关分数
		$programme_id=null;	
		$pass_score=null;
		$checkpoint_name="";
		if($type==1){	//练习
			$check_map['checkpoint_id']=$checkpoint_id;
			$check_res=$checkpoint->where($check_map)->select();
			$programme_id=$check_res['0']['programme_id'];//获取方案id
			$pass_score=$check_res['0']['pass_score'];//获取过关分数
			$checkpoint_name=$check_res['0']['name'];//关卡名
		}else{
			$level_map['level_id']=$checkpoint_id;
			$level_res=$exam_level->where($level_map)->select();
			$programme_id=$level_res['0']['program_id'];//获取方案id
			$pass_score=$level_res['0']['pass_score'];//获取过关分数
			$checkpoint_name=$level_res['0']['name'];//关卡名
		}
		$question_arr=explode(";;",$questionstr);	//分隔出每一道题
		//遍历每一道题
		$score=0;//本次得分
		foreach ($question_arr as $row) {
			$arr=explode("::",$row);//分隔出id,和选择的答案
			$q_map['question_id']=$arr['0'];
			$question_res=$question->where($q_map)->select();
			if($question_res['0']['rightanswer']==$arr['1']&&$arr['1']!=""){//答案正确
				$score+=1;
				//存入此题的练习记录
				$record_data['question_id']=$arr['0'];
				$record_data['stu_chose']=$arr['1'];
				$record_data['is_right']=1;
				$record_data['type']=$type;
				$record_res=$question_record->data($record_data)->add();
			}else{	//答案不正确
				//存入此题的练习记录
				$record_data['question_id']=$arr['0'];
				$record_data['stu_chose']=$arr['1'];
				$record_data['is_right']=0;
				$record_data['type']=$type;
				$record_res=$question_record->data($record_data)->add();
			}
		}//for
		//查询是否有记录
		$practice_arr['checkpoint_id']=$checkpoint_id;
		$practice_arr['programme_id']=$programme_id;
		$practice_arr['type']=$type;
		$practice_arr['course_id']=$course_id;
		$practice_arr['stu_id']=$stu_id;
		$practice_res=$student_practice->where($practice_arr)->select();
		if(count($practice_res)>0){//有记录
			//加次数
			$add_num_map['num']=$practice_res['0']['num']+1;
			$add_num_map['practice_id']=$practice_res['0']['practice_id'];
			$student_practice->data($add_num_map)->save();

			if($score>$practice_res['0']['score']){//本次得分为新高
				if($score>$pass_score){	//已过关
					$practice_up['status']=2;	
				}else{//未通关
					$practice_up['status']=1;
				}
				$practice_up['practice_id']=$practice_res['0']['practice_id'];
				$practice_up['score']=$score;
				$practice_up['create_time']=date("Y-m-d H:i:s",$time);
				$psave_res=$student_practice->data($practice_up)->save();
				//把积分加上
				$score_arr['type']=$type;
				$score_arr['programme_id']=$programme_id;
				$score_arr['stu_id']=$stu_id;
				$score_res=$stu_score->where($score_arr)->select();
				if(count($score_res)>0){//此处应该是成立的，为了避免开发时添加的错误数据，故作判断
					$score_up['score']=$score-$practice_res['0']['score']+$score_res['0']['score'];
					$score_up['create_time']=date("Y-m-d H:i:s",$time);
					$score_up['score_id']=$score_res['0']['score_id'];
					$ssave_res=$stu_score->data($score_up)->save();
				}else{//有练习记录没有积分记录，防止错误数据
					$score_arr['create_time']=date("Y-m-d H:i:s",$time);
					$score_arr['score']=$score;
					$sadd_res=$stu_score->data($score_arr)->add();
				}
			}	
		}else{//无记录
			$practice_arr['score']=$score;
			if($score>$pass_score){	//已过关
				$practice_arr['status']=2;	
			}else{//未通关
				$practice_arr['status']=1;
			}
			$practice_arr['create_time']=date("Y-m-d H:i:s",$time);
			$padd_res=$student_practice->data($practice_arr)->add();
			//把积分加上
			$score_arr['type']=$type;
			$score_arr['programme_id']=$programme_id;
			$score_arr['stu_id']=$stu_id;
			$score_res=$stu_score->where($score_arr)->select();
			if(count($score_res)>0){//有记录
				$score_up['score']=$score-$practice_res['0']['score']+$score_res['0']['score'];
				$score_up['create_time']=date("Y-m-d H:i:s",$time);
				$score_up['score_id']=$score_res['0']['score_id'];
				$ssave_res=$stu_score->data($score_up)->save();
			}else{//无记录
				$score_arr['create_time']=date("Y-m-d H:i:s",$time);
				$score_arr['score']=$score;
				$sadd_res=$stu_score->data($score_arr)->add();
			}
		}//else
		//返回的数据
		$info=array(
			'create_date' =>date("Y-m-d",$time),//日期
			'checkpoint_name'=>$checkpoint_name,//关卡名
			'score'	=>$score,//本次得分
			'right'	=>number_format(($score/count($question_arr))*100, 2, '.', ''),//正确率
			'error'	=>100-number_format(($score/count($question_arr))*100, 2, '.', ''),//错误率
			'checkpoint_id'=>$checkpoint_id,//关卡或者考试等级id
		);
		$json_array['status']=1;
		$json_array['message']="提交成功";
		$json_array['data']=$info;
		echo json_encode($json_array);	
	}
}