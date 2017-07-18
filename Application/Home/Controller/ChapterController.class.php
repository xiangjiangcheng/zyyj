<?php
namespace Home\Controller;
use Think\Controller;
class ChapterController extends BaseController {
	
	//�½ڹ���ҳ��
    public function Chapter_Management(){
		$this->display();
	}
	
	//��ȡ���ݿ��е��½���Ϣ
	public function Chapter_get_chapter(){
		$Chapter=M('chapter');
		//��ҳ����
        $page = I('page') ? intval(I('page')) : 1;
        $rows = I('rows') ? intval(I('rows')) : 20;
		$offset = ($page-1)*$rows;
		
		//��������
		$map = "";
		$S_course_id=I('S_course');
		$S_name=I('S_name');
		
		if($S_course_id!=''){
			$map['zyyj_chapter.course_id']=$S_course_id;
		}
		if($S_name!=''){
			$map['zyyj_chapter.name']  = array('LIKE', "%".$S_name."%");
		}
		
		$total=$Chapter->where($map)->count();
		$data['total']=$total;
		$result=$Chapter
			->join('zyyj_course ON zyyj_chapter.course_id = zyyj_course.course_id' )
			->field('zyyj_course.name course_name, chapter_id, zyyj_chapter.name name, zyyj_chapter.comment  comment')
			->where($map)
			->order('chapter_id DESC')
			->limit($offset.','.$rows)
			->select();
		if($result==null){
			$result=[];
			$data['total']=0;
		}
		$data['rows']=$result;
		echo json_encode($data);
	}
	
	//�õ�������Ŀ���������б�
	public function Chapter_get_course_options(){
		$Course = M('course');
		$op=$Course->where('parent_id is not null')->field('course_id, name')->select();
		echo json_encode($op);
	}
	
	public function Chapter_save(){
		//��֤ģ�ͽ���������֤
		$rules = array(
			//��ӵ�ʱ����֤name�ֶ��Ƿ��Ѵ���
			array('name','','Chapter name existed',0,'unique',3),
		);
		$Chapter = M('chapter');
		$data = $Chapter -> create();
		$data['course_id']=I('course_name');
		$Chapter_op=I('Chapter_op');
		//$Chapter_op:operation
		switch($Chapter_op){
			case "add":
						//��֤�Ƿ�����
						if(!$Chapter->validate($rules)->create()){
							$result=2;
							echo json_encode($result);
							exit();
						}
						$result = $Chapter->add($data);
						if($result>0) $result = 1;
						echo json_encode($result);
						exit;
						break;
			case "edit":
						//���ڱ༭ģʽ������hieasyui�������г�ʼ���ص�ѡ����ֻ���ı����������ֵ����Ҫ����ѡ���ſɵõ���
						//�����ڴ���һ�����������ݿ�Ŀ����ȡ����Ŀ��id��
						if($data['course_id']==0){
							$Course = M('course');
							$data['course_id']=$Course->where('name="'.$data['course_id'].'"')->getField('course_id');
						}
						$where = "chapter_id=".$data['chapter_id'];
						//��֤�Ƿ�����
						if(!$Chapter->validate($rules)->create()){
							$result=2;
							echo json_encode($result);
							exit();
						}
						$result = $Chapter->where($where)->save($data);
				//		echo $Chapter->getLastSql();
				//		echo json_encode($data);
						if($result>0) $result = 1;
						echo json_encode($result);
						exit;
						break;
			case "del":
						$where = "chapter_id=".$data['chapter_id'];
						$Kp = M('knowledgepoint');
						$kp_id=$Kp->where($where)->field('know_id')->select();
						//ɾ������ѡ��֪ʶ���������Ŀ
						foreach($kp_id as $i){
							M('question')->where('know_id='.$i)->delete();
						}
						$Kp->where($where)->delete();
						$result = $Chapter->where($where)->delete();
						if($result>0){
							$result = 1;
						} else {
							$result = 0;
						}
						echo json_encode($result);
						exit;
						break;
		}
	}
	
	/*
	//ͨ������excel����������½�
	public function upload_excel(){
		date_default_timezone_set('prc');		//����ʱ�����й�  
		$time=time();
		if (!empty($_FILES)) {
			$photo=$time."".rand(1,100000);
			$photo_url=date("Y-m-d",$time)."/".$photo;
	       	$upload = new \Think\Upload();// ʵ�����ϴ���
			$upload->maxSize=3145728 ;// ���ø����ϴ���С
	    	$upload->exts=array('xls', 'xlsx');// ���ø����ϴ�����
	    	//$upload->rootPath = './Public/Uploads/'; // ���ø����ϴ���Ŀ¼
	    	$upload->saveName=$photo;
	    	$upload->savePath  = ''; // ���ø����ϴ����ӣ�Ŀ¼
	    	// �ϴ��ļ� 
	    	$info   =    $upload->uploadOne($_FILES['upfile']);
	    	if(!$info) {// �ϴ�������ʾ������Ϣ
	        //	$this->error($upload->getError());
	    	}
	        Vendor("PHPExcel.PHPExcel");
	        $file_name=$upload->rootPath.$info['savepath'].$info['savename'];
	        $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
	        //�жϵ������׺��ʽ
	        if ($extension == 'xlsx') {
	            $objReader =\PHPExcel_IOFactory::createReader('Excel2007');
	            $objPHPExcel =$objReader->load($file_name, $encode = 'utf-8');
	        }else if($extension == 'xls'){
	            $objReader =\PHPExcel_IOFactory::createReader('Excel5');
	            $objPHPExcel =$objReader->load($file_name, $encode = 'utf-8');
	        }
            $sheet =$objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();//ȡ��������
        	$highestColumn =$sheet->getHighestColumn(); //ȡ��������
			
			
			$data['course_id']=I('course_name');
			
			$wrong_rows=array();//�����ڴ�����кŷ��������������
			
			$Chapter = M('chapter');
      		for ($i = 2; $i <= $highestRow; $i++) {
		        $data['name'] =$objPHPExcel->getActiveSheet()->getCell("A" .$i)->getValue();
		        $data['comment'] = $objPHPExcel->getActiveSheet()->getCell("B". $i)->getValue();
				if($data['name']!=''){
					$correct=$Chapter->add($data);
				} else {
					$correct=false;
				}
				if(!$correct){
					array_push($wrong_rows,$i);
				}
            }
			if(count($wrong_rows)>0){
				//�ո��Ƿָ���
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
	}*/
}
