<?php
namespace Home\Controller;
use Think\Controller;
class KnowledgepointController extends BaseController {
	
	//知识点管理页面
    public function Knowledgepoint_Management(){
		$this->display();
	}
	
	//获取数据库中的知识点信息
	public function Knowledgepoint_get_knowledgepoint(){
		$Kp=M('knowledgepoint');
		//分页参数
        $page = I('page') ? intval(I('page')) : 1;
        $rows = I('rows') ? intval(I('rows')) : 20;
		$offset = ($page-1)*$rows;
		
		//搜索条件
		$map = "";
		$S_course_id=I('S_course');
		$S_require_id=I('S_require');
		$S_chapter_id=I('S_chapter');
		$S_name=I('S_name');
		
		if($S_course_id!=''){
			$map['zyyj_knowledgepoint.course_id']=$S_course_id;
		}
		if($S_chapter_id!=''){
			$map['zyyj_knowledgepoint.chapter_id']=$S_chapter_id;
		}
		if($S_require_id!=''){
			$map['zyyj_knowledgepoint.require_id']=$S_require_id;
		}
		if($S_name!=''){
			$map['zyyj_knowledgepoint.name']  = array('LIKE', "%".$S_name."%");
		}
		
		$total=$Kp->where($map)->count();
		$data['total']=$total;
		$result=$Kp
			->join('zyyj_course ON zyyj_knowledgepoint.course_id = zyyj_course.course_id' )
			->join('zyyj_chapter ON zyyj_knowledgepoint.chapter_id = zyyj_chapter.chapter_id' )
			->join('zyyj_require ON zyyj_knowledgepoint.require_id = zyyj_require.require_id')
			->field('zyyj_course.name as course_name, zyyj_chapter.name chapter_name,
				know_id, zyyj_knowledgepoint.name name, zyyj_require.name require_name, zyyj_knowledgepoint.comment  comment')
			->where($map)
			->order('know_id DESC')
			->limit($offset.','.$rows)
			->select();
		if($result==null){
			$result=[];
			$data['total']=0;
		}
		$data['rows']=$result;
		echo json_encode($data);
	}
	
	//得到包含科目的下拉框列表
	public function Kp_get_course_options(){
		$Course = M('course');
		$op=$Course->where('parent_id is not null')->field('course_id, name')->select();
		echo json_encode($op);
	}
	
	//得到包含章节的下拉框列表
	public function Kp_get_chapter_options(){
		$c_id = I('get.c_id');
		$Chapter = M('chapter');
		$map['zyyj_knowledgepoint.course_id'] = $c_id;
		$op=$Chapter->where('course_id='.$c_id)->field('chapter_id, name')->select();
		if($op==null){
			$op=array(array('course_id'=>-1, 'name'=>'没有章节'));
		}
		echo json_encode($op);
	}

	//得到包含要求的下拉框列表
	public function Kp_get_require_id_options(){
		$Require=M('require');
		$op = $Require->field('require_id,name')->select();
		echo json_encode($op);
	}
	
	//返回空的下拉框列表
	public function Kp_get_null_option(){
		$op=array(array('course_id'=>-1, 'name'=>'请先选科目'));
		echo json_encode($op);
	}
	
	public function Knowledgepoint_save(){
		//验证模型进行数据验证
		$rules = array(
			//添加的时候验证name字段是否已存在
			array('name','','Knowledgepoint name existed',0,'unique',3),
		);
		$Kp = M('knowledgepoint');
		$data = $Kp -> create();
		$data['course_id']=I('course_name');
		$data['chapter_id']=I('chapter_name');
		$data['require_id']=I('require_name');
		$Kp_op=I('Kp_op');
		//$Kp_op:operation
		switch($Kp_op){
			case "add":
						//验证是否重名
						if(!$Kp->validate($rules)->create()){
							$result=2;
							echo json_encode($result);
							exit();
						}
						$result = $Kp->add($data);
						if($result>0) $result = 1;
						echo json_encode($result);
						exit;
						break;
			case "edit":
						//由于编辑模式弹出的hieasyui下拉框中初始加载的选项中只有文本域而不包含值域（需要重新选定才可得到）
						//所以在此作一下修正（根据科目名提取出科目的id）
						if($data['course_id']==0){
							$Course = M('course');
							$data['course_id']=$Course->where('name="'.$data['course_id'].'"')->getField('course_id');
						}
						if($data['chapter_id']==0){
							$Chapter = M('chapter');
							$data['chapter_id']=$Chapter->where('name="'.$data['chapter_id'].'"')->getField('chapter_id');
						}
						if($data['require_id']==0){
							$Require = M('require');
							$data['require_id']=$Require->where('name="'.$data['require_id'].'"')->getField('require_id');
						}
						$where = "know_id=".$data['know_id'];
						//验证是否重名
						if(!$Kp->validate($rules)->create()){
							$result=2;
							echo json_encode($result);
							exit();
						}
						$result = $Kp->where($where)->save($data);
				//		echo $Kp->getLastSql();
				//		echo json_encode($data);
						if($result>0) $result = 1;
						echo json_encode($result);
						exit;
						break;
			case "del":
						$where = "know_id=".$data['know_id'];
						$r = (M('question')->where($where)->delete());
						$result = $Kp->where($where)->delete();
						if($result>0) $result = 1;
						echo json_encode($result);
						exit;
						break;
		}
	}
	
	//通过导入excel来批量添加知识点
	public function upload_excel(){
		date_default_timezone_set('prc');		//设置时区，中国  
		$time=time();
		if (!empty($_FILES)) {
			$photo=$time."".rand(1,100000);
			$photo_url=date("Y-m-d",$time)."/".$photo;
	       	$upload = new \Think\Upload();// 实例化上传类
			$upload->maxSize=3145728 ;// 设置附件上传大小
	    	$upload->exts=array('xls', 'xlsx');// 设置附件上传类型
	    	//$upload->rootPath = './Public/Uploads/'; // 设置附件上传根目录
	    	$upload->saveName=$photo;
	    	$upload->savePath  = ''; // 设置附件上传（子）目录
	    	// 上传文件
	    	$info   =    $upload->uploadOne($_FILES['upfile']);
	    	if(!$info) {// 上传错误提示错误信息
	        //	$this->error($upload->getError());
	    	}
	        Vendor("PHPExcel.PHPExcel");
	        $file_name=$upload->rootPath.$info['savepath'].$info['savename'];
	        $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
	        //判断导入表格后缀格式
	        if ($extension == 'xlsx') {
	            $objReader =\PHPExcel_IOFactory::createReader('Excel2007');
	            $objPHPExcel =$objReader->load($file_name, $encode = 'utf-8');
	        }else if($extension == 'xls'){
	            $objReader =\PHPExcel_IOFactory::createReader('Excel5');
	            $objPHPExcel =$objReader->load($file_name, $encode = 'utf-8');
	        }
            $sheet =$objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();//取得总行数
        	$highestColumn =$sheet->getHighestColumn(); //取得总列数
			
			
			$data['course_id']=I('file_bc');
			$data['chapter_id']=I('file_chapter');
			$data['require_id']=I('file_rquire');
			
	
			$wrong_rows=array();//将存在错误的行号放在这个数组里面
			$Kp = M('knowledgepoint');
      		for ($i = 2; $i <= $highestRow; $i++) {
		        $data['name'] =$objPHPExcel->getActiveSheet()->getCell("A" .$i)->getValue();
		        $data['comment'] = $objPHPExcel->getActiveSheet()->getCell("B". $i)->getValue();
				if($data['name']!=''){
					$correct=$Kp->add($data);
				} else {
					$correct=false;
				}
				if(!$correct){
					array_push($wrong_rows,$i);
				}
            }
			if(count($wrong_rows)>0){
				//空格是分隔符
				$result='2 ';
				foreach($wrong_rows as $r){
					$result=$result.','.$r;
				}
			} else {
				$result="1";
			}
			
			echo json_encode($result);
			exit ;
        }else{
			$result="0";
			echo json_encode($result);
			exit ;
        }
	}
}
