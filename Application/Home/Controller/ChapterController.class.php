<?php
namespace Home\Controller;
use Think\Controller;
class ChapterController extends BaseController {
	
	//章节管理页面
    public function Chapter_Management(){
		$this->display();
	}
	
	//获取数据库中的章节信息
	public function Chapter_get_chapter(){
		$Chapter=M('chapter');
		//分页参数
        $page = I('page') ? intval(I('page')) : 1;
        $rows = I('rows') ? intval(I('rows')) : 20;
		$offset = ($page-1)*$rows;
		
		//搜索条件
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
	
	//得到包含科目的下拉框列表
	public function Chapter_get_course_options(){
		$Course = M('course');
		$op=$Course->where('parent_id is not null')->field('course_id, name')->select();
		echo json_encode($op);
	}
	
	public function Chapter_save(){
		//验证模型进行数据验证
		$rules = array(
			//添加的时候验证name字段是否已存在
			array('name','','Chapter name existed',0,'unique',3),
		);
		$Chapter = M('chapter');
		$data = $Chapter -> create();
		$data['course_id']=I('course_name');
		$Chapter_op=I('Chapter_op');
		//$Chapter_op:operation
		switch($Chapter_op){
			case "add":
						//验证是否重名
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
						//由于编辑模式弹出的hieasyui下拉框中初始加载的选项中只有文本域而不包含值域（需要重新选定才可得到）
						//所以在此作一下修正（根据科目名提取出科目的id）
						if($data['course_id']==0){
							$Course = M('course');
							$data['course_id']=$Course->where('name="'.$data['course_id'].'"')->getField('course_id');
						}
						$where = "chapter_id=".$data['chapter_id'];
						//验证是否重名
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
						//删除所有选中知识点的所有题目
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
	//通过导入excel来批量添加章节
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
			
			
			$data['course_id']=I('course_name');
			
			$wrong_rows=array();//将存在错误的行号放在这个数组里面
			
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
	}*/
}
