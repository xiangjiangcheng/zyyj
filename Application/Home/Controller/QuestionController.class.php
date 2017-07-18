<?php
namespace Home\Controller;
use Think\Controller;
class QuestionController extends BaseController {
	//加载题目管理页面
	function Question_index(){
		$this->display();
	}

	//获取题目列表数据
	function Question_get_data(){
		//分页
		$page=I('page');
		$rows=I('rows');
		$offset=($page-1)*$rows;

		$map=array();	//存查询条件的数组
		//获取查询条件中的数据
		$level_id=$_GET['level_id'];
		$know_id=$_GET['know_id'];
		$chapter_id=$_GET['chapter_id'];
		$course_id=$_GET['course_id'];
		//echo $level_id;
		if($level_id!=null&&$level_id!=""){
			$map['zyyj_question.level_id']=$level_id;
		}
		if($know_id!=null&&$know_id!=""){
			$map['zyyj_question.know_id']=$know_id;
		}
		if($chapter_id!=null&&$chapter_id!=""){
			$map['zyyj_question.chapter_id']=$chapter_id;
		}
		if($course_id!=null&&$course_id!=""){
			$map['zyyj_question.course_id']=$course_id;
		}
		//实例化数据库模型
		$question=M('question');

		//定义返回数据的数组
		$info=array();
		
		//分页查询题目信息，按question_id降序,关联查询题目难度，类型，所属知识点
		$res=$question->
		join('zyyj_question_level ON zyyj_question.level_id = zyyj_question_level.level_id')->
		join('zyyj_knowledgepoint ON zyyj_question.know_id = zyyj_knowledgepoint.know_id')->
		join('zyyj_chapter ON zyyj_question.chapter_id = zyyj_chapter.chapter_id')->
		join('zyyj_course ON zyyj_question.course_id = zyyj_course.course_id')->
		field('zyyj_question_level.name level_name, zyyj_knowledgepoint.name know_name, zyyj_course.name course_name, zyyj_question.question_id question_id, zyyj_question.question question, zyyj_chapter.name chapter_name,zyyj_question.rightanswer rightanswer')->
		where($map)->
		order("question_id desc")->
		limit($offset.','.$rows)->
		select();
		//循环遍历所查数据，将数据存入数组
		foreach ($res as $row) {
			$question_q=$row['question'];
			if(mb_strlen($row['question'])>50){
				$question_q=mb_substr($row['question'], 0, 30, 'utf-8')."...";
			}
			$info['rows'][]=array(
				'id' 			=>$row['question_id'],	//题目id
				'level'			=>$row['level_name'],	//题目难度
				'course'		=>$row['course_name'],	//科目
				'chapter'		=>$row['chapter_name'],	//章节
				'ofknow'		=>$row['know_name'],	//所属知识点
				'question'		=>$question_q,		//问题
				'rightanswer'	=>$row['rightanswer'],	//正确答案
				'caozuo'		=>"<button onclick='stepToDetail(".$row['question_id'].")' >查看详情</button>"	//操作
			);
		}
		$res1=$question->
		join('zyyj_question_level ON zyyj_question.level_id = zyyj_question_level.level_id')->
		join('zyyj_knowledgepoint ON zyyj_question.know_id = zyyj_knowledgepoint.know_id')->
		join('zyyj_chapter ON zyyj_question.chapter_id = zyyj_chapter.chapter_id')->
		join('zyyj_course ON zyyj_question.course_id = zyyj_course.course_id')->
		field('zyyj_question_level.name level_name, zyyj_knowledgepoint.name know_name, zyyj_course.name course_name, zyyj_question.question_id question_id, zyyj_question.question question,zyyj_chapter.name chapter_name, zyyj_question.rightanswer rightanswer')->
		where($map)->
		order("question_id desc")->
		select();
		$info['total']=count($res1);
		//返回数据
		echo json_encode($info);
	}

	//获取一个题目的详情
	function Question_get_detail(){
		//获取题目id
		$id=I('id');

		//实例化数据库表
		$question=M('question');
		$question_level=M('question_level');
		$chapter=M('chapter');
		$course=M('course');
		$knowledgepoint=M('knowledgepoint');

		//定义返回数据的数组
		$json_array=array();
		//设置查询条件，通过题目id查询一道题目
		$map['question_id']=$id;
		//进行查询
		$res=$question->where($map)->select();

		if(count($res)<=0){			//该题目不存在
			$json_array['code']=2;
			echo json_encode($json_array);
			exit;
		}
		//分别查询出该题目的难度，类型和所属知识点
		$ql_arr['level_id']=$res['0']['level_id'];
		$ch_arr['chapter_id']=$res['0']['chapter_id'];
		$co_arr['course_id']=$res['0']['course_id'];
		$kp_arr['know_id']=$res['0']['know_id'];

		$ql=$question_level->where($ql_arr)->select();
		$ch=$chapter->where($ch_arr)->select();
		$co=$course->where($co_arr)->select();
		$kp=$knowledgepoint->where($kp_arr)->select();
		$photo_url="default.jpg";
		if($res['0']['photo']!=null&&$res['0']['photo']!=""){
			$photo_url=$res['0']['photo'];
		}
		//将查询到的数据存入数组
		$json_array=array(
			'id' 			=>$res['0']['question_id'],	//题目id
			'question'		=>$res['0']['question'],	//问题
			'level'			=>$ql['0']['name'],	//难度，简单，一般，困难
			'ofknow'		=>$kp['0']['name'],	//所属知识点
			'chapter'		=>$ch['0']['name'],	//章节
			'course'		=>$co['0']['name'],//科目
			'level_id'		=>$res['0']['level_id'],	//难度id
			'know_id'		=>$res['0']['know_id'],	//所属知识点id
			'course_id'		=>$res['0']['course_id'],//科目id
			'chapter_id'	=>$res['0']['chapter_id'],
			'answer1'		=>$res['0']['answer1'],	//答案1
			'answer2'		=>$res['0']['answer2'],	//答案2
			'answer3'		=>$res['0']['answer3'],	//答案3
			'answer4'		=>$res['0']['answer4'],	//答案4
			'rightanswer'	=>$res['0']['rightanswer'],//正确答案
			'photo'			=>$photo_url,	//图片url
			'comment'		=>$res['0']['comment']	//备注
		);
		//返回数据
		$json_array['code']=1;
		echo json_encode($json_array);
	}

	//添加题目
	function Question_add(){
		date_default_timezone_set('prc');		//设置时区，中国  
		$time=time();							//获取当前时间戳
		$data['photo']=null;					//图片路径默认为空
		if ($_FILES['upfile']['name']!='') {	//如果有上传的文件
			$photo=$time."".rand(1,100000);		//时间戳加随机数作为文件名
			$upload = new \Think\Upload();		// 实例化上传类
			$upload->maxSize=3145728 ;			// 设置附件上传大小
	    	$upload->exts=array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
	    	$upload->saveName=$photo;		//设置上传文件后的文件名字
	    	$upload->savePath=''; 		// 设置附件上传（子）目录
	    	//上传文件 
	    	$info=$upload->uploadOne($_FILES['upfile']);
	    	if(!$info) {		// 上传错误提示错误信息
	        	$this->error($upload->getError());
	    	}else{			// 上传成功
	    		$data['photo']=$info['savepath']."".$info['savename'];
	    	}
	    }
	    //获取表单数据
	    $data['question_id']=I('question_id');	//题目id，修改的时候才会有值
	    $data['question']=I('addquestion');	//题目
	    $data['level_id']=I('addlevel');	//难度
	    $data['know_id']=I('addofknow');	//所属知识点
	    $data['answer1']=I('addanswer1');		//答案1
	    $data['answer2']=I('addanswer2');		//答案2
	    $data['answer3']=I('addanswer3');		//答案3
	    $data['answer4']=I('addanswer4');		//答案4
	    $data['course_id']=I('addcourse');
	    $data['chapter_id']=I('addchapter');
	    $data['rightanswer']=I('addrightanswer');	//正确答案
	    $data['comment']=I('addcomment');		//备注
	 	//实例化数据库模型
	    $question=M('question');

	    if($data['question_id']!=null&&$data['question_id']!=""){//有id是修改
	    	$res=$question->data($data)->save();
	    	echo json_encode($res);exit;
	    }			
		//进行添加操作  
	    $res=$question->data($data)->add();
	    //返回操作结果
	    echo json_encode($res);
	    
	}

	//题目删除
	function Question_delete(){
		$data['question_id']=I('id');//获取题目id
		$question=M('question');
		$res['code']=$question->where($data)->delete();
		echo json_encode($res);
	}

	//题目导入
	function Question_import(){
		date_default_timezone_set('prc');		//设置时区，中国  
		$time=time();
		$info=array();
		$count=0;
		$question=M('question');
		//接收表单数据
		$data['know_id']=I('importknow');
		$data['course_id']=I('importcourse');
		$data['chapter_id']=I('importchapter');
		
		if (!empty($_FILES)) {
			$photo=$time."".rand(1,100000);
			$photo_url=date("Y-m-d",$time)."/".$photo;
	       	$upload=new \Think\Upload();// 实例化上传类
			$upload->maxSize=3145728 ;// 设置附件上传大小
	    	$upload->exts=array('xls', 'xlsx', 'png', 'jpeg');// 设置附件上传类型
	    	$upload->saveName=$photo;
	    	$upload->savePath  = ''; // 设置附件上传（子）目录
	    	// 上传文件 
	    	$info=$upload->uploadOne($_FILES['import']);
	    	if(!$info) {// 上传错误提示错误信息
	        	$this->error($upload->getError());
	    	}else{  //上传成功
	     		//不作处理	    
	    	}
	    	Vendor("PHPExcel.PHPExcel");
	        $file_name=$upload->rootPath.$info['savepath'].$info['savename'];;
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
      		for ($i = 2; $i <= $highestRow; $i++) {
				$data['question'] =$objPHPExcel->getActiveSheet()->getCell("A" . $i)->getValue();
				$level=$objPHPExcel->getActiveSheet()->getCell("B" .$i)->getValue();
		        $data['answer1'] =$objPHPExcel->getActiveSheet()->getCell("C" .$i)->getValue();
		        $data['answer2'] =$objPHPExcel->getActiveSheet()->getCell("D" .$i)->getValue();
		        $data['answer3'] = $objPHPExcel->getActiveSheet()->getCell("E". $i)->getValue();
		        $data['answer4'] =$objPHPExcel->getActiveSheet()->getCell("F" .$i)->getValue();
		        $data['rightanswer'] =$objPHPExcel->getActiveSheet()->getCell("G" . $i)->getValue();
		         $data['comment'] =$objPHPExcel->getActiveSheet()->getCell("H" . $i)->getValue();
		         if($level=="难"){
		         	$data['level_id']=3;
		         }else if($level=="中"){
		         	$data['level_id']=2;
		         }else if($level=="易"){
		         	$data['level_id']=1;
		         }
		        $res=$question->data($data)->add();
		        if($res){	
		        	$count+=1;	//导入成功数据数+1
		        }
            }
	    }else{
	    	$this->error("请选择导入的文件");
	    }
	    echo json_encode($count);exit;
	}

	//题库状态
	function Question_status(){
		$this->display();
	}

	//获取题库状态数据
	function Question_get_status(){
		$question=M('question');
		$json_array=array();

		$total=$question->select();
		$json_array['total']=count($total);

		$map['level_id']=3;//困难
		$difficult=$question->where($map)->select();
		$json_array['difficult']=count($difficult);

		$map['level_id']=2;//一般
		$normal=$question->where($map)->select();
		$json_array['normal']=count($normal);

		$map['level_id']=1;//简单
		$easy=$question->where($map)->select();
		$json_array['easy']=count($easy);

		echo json_encode($json_array);
	}

    //获取数据
    function export(){
		$data=array(
				"0"=>array(
					"id"=>1,
					"name"=>"谭继兵"
				),
				"1"=>array(
					"id"=>2,
					"name"=>"xiangjianchegn"
				)
			);
		$title="用户信息";
		$this->downloadPdf_comment($title, $data);
		exit;
	}

	//进行导出
    function downloadPdf_comment($title, $data) {
		//创建对象
		Vendor("PHPExcel.PHPExcel");
		$excel = new \PHPExcel();
		//Excel表格式,这里简略写了2列
		$letter = array('A','B');
		//表头数组
		$tableheader = array('id', '姓名');
		//填充表头信息
		for($i = 0;$i < sizeof($tableheader);$i++) {
			$excel->getActiveSheet()->setCellValue("$letter[$i]1","$tableheader[$i]");
		}
		for ($i = 2;$i <= sizeof($data) + 1;$i++) {
			$j = 0;
			foreach ($data[$i - 2] as $key=>$value) {
				$excel->getActiveSheet()->setCellValue("$letter[$j]$i","$value");
				$j++;
			}
		}
		$write = new \PHPExcel_Writer_Excel5($excel);
		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");;
		header('Content-Disposition:attachment;filename="用户信息.xls"');
		header("Content-Transfer-Encoding:binary");
		$write->save('php://output');
	}
	//获取所有考试等级
	function Question_get_level(){
		$question_level=M('question_level');
		$res=$question_level->order('level_id desc')->select();
		echo json_encode($res);
	}

	//获取所有科目
	function Question_get_course(){
		$course=M('course');
		$res=$course->select();
		echo json_encode($res);
	}

	//获取所有章节
	function Question_get_chapter(){
		$map['course_id']=I('course_id');
		$chapter=M('chapter');
		if($map['course_id']!=null&&$map['course_id']!=""){
			$res['chapter']=$chapter->where($map)->order("chapter_id desc")->select();
			echo json_encode($res);exit;
		}
		$res=$chapter->order("chapter_id desc")->select();
		echo json_encode($res);
	}
	//获取所有知识点
	function Question_get_know(){
		$map['chapter_id']=I('chapter_id');
		$knowledgepoint=M('knowledgepoint');
		if($map['chapter_id']!=null&&$map['chapter_id']!=""){
			$res['know']=$knowledgepoint->where($map)->order("know_id desc")->select();
			echo json_encode($res);exit;
		}
		$res=$knowledgepoint->order("know_id desc")->select();
		echo json_encode($res);	
	}

	//获取说有考试类型
	function Question_get_type(){
		$question_type=M('question_type');
		$res=$question_type->select();
		echo json_encode($res);
	}
	function test(){
		$str="是否1sd是否sdfsfghjkli";	
		echo mb_strlen($str);
		echo mb_substr($str, 0, 16, 'utf-8');
		// if (strlen($str_cut) > 17){
		// 	for($i=0; $i < 16; $i++){
		// 		if (ord($str_cut[$i]) > 128){   
		// 			$i++;
		// 			$str_cut = substr($str_cut,0,$i)."..";
		// 		}
		// 	}
		// }
		// var_dump($str_cut);
	}
}