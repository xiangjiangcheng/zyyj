<?php
namespace Home\Controller;
use Think\Controller;
class CourseController extends BaseController {
	//学院管理页面
    public function Course_Management(){
		$this->display();
	}
	
	//获取科目页面展示信息
	public function Course_get_courses(){
		$Course = M('course');
		$sql="SELECT `zyyj_course`.`course_id`,
					`zyyj_course`.`parent_id` as _parentId,
					`zyyj_course`.`name`,
					`zyyj_course`.`tree_code`,
					`zyyj_course`.`introduction`,
					`zyyj_course`.`photo`
			FROM `zyyj`.`zyyj_course`;";
		$rows=$Course->query($sql);
		$total=$Course->count();
		$rs["total"]=$total;
		$rs["rows"]=$rows;
		echo json_encode($rs);
	}
	
	//保存科目信息
	public function Course_save_course(){
		//验证模型进行数据验证
		$rules = array(
			//添加或编辑的时候验证name字段是否已存在
			array('name','','course name existed',0,'unique',3),
		);
		
		$Course = M('course');
		$data = $Course->create();
		$op=I('op');
		$_parentId=I('_parentId');
		$data['parent_id']=$_parentId;
		if($data['parent_id']!=-1){
			$temp=$Course->where('course_id='.$data['parent_id'])->find();
			$data['tree_code']=$temp['tree_code']+1;
		}else{
			$data['tree_code']=1;
			$data['parent_id']=null;
		}
		date_default_timezone_set('prc');		//设置时区，中国  
		$time=time();
		if ($_FILES['imgfile']['name']!='') {	//如果有上传的文件
			$photo=$time."".rand(1,100000);		//时间戳加随机数作为文件名
			$upload = new \Think\Upload();		// 实例化上传类
			$upload->maxSize=3145728 ;			// 设置附件上传大小
			$upload->exts=array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
	//		$upload->rootPath = '__UPLOAD__'; // 设置附件上传根目录
			$upload->saveName=$photo;		//设置上传文件后的文件名字
			$upload->savePath=''; 		// 设置附件上传（子）目录
			//上传文件 
			$info=$upload->uploadOne($_FILES['imgfile']);
			if(!$info) {		// 上传错误提示错误信息
				$result=4;
				echo json_encode($result);
				exit ;
			}else{			// 上传成功
				$data['photo']=$info['savepath']."".$info['savename'];
			}
		} else if($op=="add"){
				$result=4;
				echo json_encode($result);
				exit ;
			}
		switch($op){
			case "add":
						//验证是否重名
						$where_tree_code = "tree_code=".$data['tree_code'];
						if(!$Course->where($where_tree_code)->validate($rules)->create()){
							$result=2;
							echo json_encode($result);
							exit();
						}
						$result = $Course->add($data);
					//	echo json_encode($data);
						if($result>0) $result = 1;
						echo json_encode($result);
						exit;
						break;
			case "edit":
						//验证父科目是否合法
						$check=$Course->getField('course_id,parent_id');
						$check[$data['course_id']]=$_parentId;
						if(Check_parent_id($check, $data['course_id']) == false){
							echo json_encode(3);
							exit;
						}
						$where = "course_id=".$data['course_id'];
						//验证是否重名
						$where_tree_code = "tree_code=".$data['tree_code'];
						if(!$Course->where($where_tree_code)->validate($rules)->create()){
							$result=2;
							echo json_encode($result);
							exit();
						}
						
						//更改相应的tree_code
						if($data['parent_id']!=null){
							$parent=$Course->where("course_id=".$data['parent_id'])->find();
							$data['tree_code']=$parent['tree_code']+1;
						} else {
							$data['tree_code']=1;
						}
						
						if($result>0) $result = 1;
						$result = $Course->where($where)->save($data);
						echo json_encode($result);
						exit;
						break;
			case "del":
						$where = "course_id=".I('course_id');
						$Kp = M('knowledgepoint');
						$Chp = M('chapter');
						
						$chp_id=$Chp->where($where)->field('chapter_id')->select();
						//删除所有选中章节的所有知识点和题目
						foreach($chp_id as $i){
							$where1 = "chapter_id=".$i;
							$kp_id = $Kp->where($where1)->field('know_id')->select();
							foreach($kp_id as $j){
								M('question')->where('know_id='.$j)->delete();
							}
							$Kp->where($where1)->delete();
						}
						//删除章节
						$Chp->where($where)->delete();
						//删除科目
						$result = $Course->where($where)->delete();
						if($result>0) $result = 1;
					//	echo $where;
						echo json_encode($result);
						exit;
						break;
		}
	}
	
	//得到所属科目的下拉框选项
	public function Course_get_parent_course(){
		$Course = M('college');
		$sql = "SELECT course_id as parent_id,name FROM zyyj.zyyj_course;";
		
		$op = $Course->query($sql);
		//设置空选项，即所建科目不从属于任何科目
		array_unshift($op,$op[0]);
		$op[0]['parent_id']="-1";
		$op[0]['name']="-----------------------";
		echo json_encode($op);
	}
	
	//获取所有没有下级的科目的接口
	public function get_all_nochield_courses(){
		$status = 1;
		$message = "Success";
		$Course = M('course');
		$all = $Course->field('course_id, parent_id, name, introduction, photo')->select();
		$data = array();
		foreach($all as $i){
			$flag = true;
			foreach($all as $j){
				if($i['course_id']==$j['parent_id']){
					$flag = false;
				}
			}
			if($flag){
				array_push($data,$i);
			}
		}
		
		if(count($data)==0){
			$status = -4;
			$message = "Meet the conditions of the data is empty, failed to load";
		}
		if($all==false){
			$status = -3;
			$message = "Unable to query the database, failed to load.";
		}
		if($Course==null){
			$status = -2;
			$message = "Unable to connect to the database, failed to load.";
		}
		
		$result = array();
		array_push($result,$status);
		array_push($result,$message);
		array_push($result,$data);
		echo json_encode($result);
	}
	
	/*
	//获取一级科目的接口
	public function get_super_course_data(){
		$status = 1;
		$message = "Success";
		$Course = M('course');
		$all = $Course->select();
		$data = $Course->where("parent_id is NULL")->field('course_id, name, introduction, photo')->select();
		foreach($data as &$i){
			$i['haveChild']=false;
			foreach($all as $j){
				if($i['course_id']==$j['parent_id']){
					$i['haveChild']=true;
					break;
				}
			}
		}
		
		if(count($data)==0){
			$status = -4;
			$message = "Meet the conditions of the data is empty, failed to load";
		}
		if($all==false){
			$status = -3;
			$message = "Unable to query the database, failed to load.";
		}
		if($Course==null){
			$status = -2;
			$message = "Unable to connect to the database, failed to load.";
		}
		
		
		$result = array();
		array_push($result,$status);
		array_push($result,$message);
		array_push($result,$data);
		echo json_encode($result);
	}
	
	//获取二级科目的接口
	public function get_children_course_data(){
		$status = 1;
		$message = "Success";
		$parent_id = I('super_course_id');
		$Course = M('course');
		$all = $Course->field('course_id, parent_id, name, introduction, photo')->select();
		$data = array();
		foreach($all as $item){
			if($item['parent_id']==$parent_id){
				array_push($data,$item);
			}
		}
		
		if(count($data)==0){
			$status = -4;
			$message = "Meet the conditions of the data is empty, failed to load";
		}
		if($all==false){
			$status = -3;
			$message = "Unable to query the database, failed to load.";
		}
		if($Course==null){
			$status = -2;
			$message = "Unable to connect to the database, failed to load.";
		}
		if($parent_id==""){
			$status = -1;
			$message = "Request parameter (super_course_id) is empty, failed to load";
			unset($data);
		}
		
		$result = array();
		array_push($result,$status);
		array_push($result,$message);
		array_push($result,$parent_id);
		array_push($result,$data);
		echo json_encode($result);
	}
	*/
}
