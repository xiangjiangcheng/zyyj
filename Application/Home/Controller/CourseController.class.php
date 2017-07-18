<?php
namespace Home\Controller;
use Think\Controller;
class CourseController extends BaseController {
	//ѧԺ����ҳ��
    public function Course_Management(){
		$this->display();
	}
	
	//��ȡ��Ŀҳ��չʾ��Ϣ
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
	
	//�����Ŀ��Ϣ
	public function Course_save_course(){
		//��֤ģ�ͽ���������֤
		$rules = array(
			//��ӻ�༭��ʱ����֤name�ֶ��Ƿ��Ѵ���
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
		date_default_timezone_set('prc');		//����ʱ�����й�  
		$time=time();
		if ($_FILES['imgfile']['name']!='') {	//������ϴ����ļ�
			$photo=$time."".rand(1,100000);		//ʱ������������Ϊ�ļ���
			$upload = new \Think\Upload();		// ʵ�����ϴ���
			$upload->maxSize=3145728 ;			// ���ø����ϴ���С
			$upload->exts=array('jpg', 'gif', 'png', 'jpeg');// ���ø����ϴ�����
	//		$upload->rootPath = '__UPLOAD__'; // ���ø����ϴ���Ŀ¼
			$upload->saveName=$photo;		//�����ϴ��ļ�����ļ�����
			$upload->savePath=''; 		// ���ø����ϴ����ӣ�Ŀ¼
			//�ϴ��ļ� 
			$info=$upload->uploadOne($_FILES['imgfile']);
			if(!$info) {		// �ϴ�������ʾ������Ϣ
				$result=4;
				echo json_encode($result);
				exit ;
			}else{			// �ϴ��ɹ�
				$data['photo']=$info['savepath']."".$info['savename'];
			}
		} else if($op=="add"){
				$result=4;
				echo json_encode($result);
				exit ;
			}
		switch($op){
			case "add":
						//��֤�Ƿ�����
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
						//��֤����Ŀ�Ƿ�Ϸ�
						$check=$Course->getField('course_id,parent_id');
						$check[$data['course_id']]=$_parentId;
						if(Check_parent_id($check, $data['course_id']) == false){
							echo json_encode(3);
							exit;
						}
						$where = "course_id=".$data['course_id'];
						//��֤�Ƿ�����
						$where_tree_code = "tree_code=".$data['tree_code'];
						if(!$Course->where($where_tree_code)->validate($rules)->create()){
							$result=2;
							echo json_encode($result);
							exit();
						}
						
						//������Ӧ��tree_code
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
						//ɾ������ѡ���½ڵ�����֪ʶ�����Ŀ
						foreach($chp_id as $i){
							$where1 = "chapter_id=".$i;
							$kp_id = $Kp->where($where1)->field('know_id')->select();
							foreach($kp_id as $j){
								M('question')->where('know_id='.$j)->delete();
							}
							$Kp->where($where1)->delete();
						}
						//ɾ���½�
						$Chp->where($where)->delete();
						//ɾ����Ŀ
						$result = $Course->where($where)->delete();
						if($result>0) $result = 1;
					//	echo $where;
						echo json_encode($result);
						exit;
						break;
		}
	}
	
	//�õ�������Ŀ��������ѡ��
	public function Course_get_parent_course(){
		$Course = M('college');
		$sql = "SELECT course_id as parent_id,name FROM zyyj.zyyj_course;";
		
		$op = $Course->query($sql);
		//���ÿ�ѡ���������Ŀ���������κο�Ŀ
		array_unshift($op,$op[0]);
		$op[0]['parent_id']="-1";
		$op[0]['name']="-----------------------";
		echo json_encode($op);
	}
	
	//��ȡ����û���¼��Ŀ�Ŀ�Ľӿ�
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
	//��ȡһ����Ŀ�Ľӿ�
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
	
	//��ȡ������Ŀ�Ľӿ�
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
