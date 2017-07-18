<?php
namespace Home\Controller;
use Think\Controller;
class MajorController extends BaseController {
	//专业管理页面
    public function Major_list(){
		$this->display();
	}
	
	/**
	* 取得专业的所有数据
	*/
	public function Major_get_majors(){
		$Major = M('major');
		//搜索条件
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
		$offset = ($page-1)*$rows;
        $college = I('college');

		$where = "";
		$where2 = "";
		
		$where = "and c.college_id = ".session('college_id');
		$where2 = "zyyj_major.college_id =".session('college_id');
		$sql = "SELECT c.name as college_name,m.name as name,m.comment as comment, m.major_id as major_id
			FROM zyyj.zyyj_college c, zyyj.zyyj_major m
			where c.college_id = m.college_id ".$where." ORDER BY major_id DESC LIMIT ".$offset.",".$rows."";
		$rs = $Major->where($where2)->count();
		$result["total"] = $rs;
		$items = array();
		$rs = $Major->query($sql);
		$items = $rs;
		$result["rows"] = $items;
		echo json_encode($result);
	}
	
	/**
	* 保存专业信息
	*/
	public function Major_save_major(){
		//验证模型进行数据验证
		$rules = array(
			//添加的时候验证name字段是否已存在
			array('name','','major name existed',0,'unique',3),
		);
		$Major = M('major');
		$data = $Major -> create();
		$data['college_id']=$_SESSION['college_id'];		
		$op=I('op');
		//$op:operation
		switch($op){
			case "add":
						//验证是否重名,如果学院内已存在该专业名，则不能保存
						//已弃用：：：if(!$Major->validate($rules)->create()){
						//验证是否重名,如果学院内已存在该专业名，则不能保存
						$test_where="name='".$data['name']."' and college_id=".$data['college_id'];
						$test=$Major->where($test_where)->select();
						if($test!=0){
							$result=2;
							echo json_encode($result);
							exit();
						}
						$result = $Major->add($data);
						if($result!=0) $result = 1;
						echo json_encode($result);
						exit;
						break;
			case "edit":
						//由于编辑模式弹出的hieasyui下拉框中初始加载的选项中只有文本域而不包含值域（需要重新选定才可得到）
						//，所以在此作一下修正（根据学院名提取出学院的id）
					/*	if($data['college_id']==0){
							$College = M('college');
							$data['college_id']=$College->where('name="'.$data['college_id'].'"')->getField('college_id');
						}
					*/
						$where = "major_id=".$data['major_id'];
						//验证是否重名,如果学院内已存在该专业名，则不能保存
						$test_where="name='".$data['name']."' and college_id=".$data['college_id'];
						$test=$Major->where($test_where)->select();
						if($test!=0){
							$result=2;
							echo json_encode($result);
							exit();
						}
						$result = $Major->where($where)->save($data);
						if($result!=0) $result = 1;
						echo json_encode($result);
						exit;
						break;
			case "del":
						$where = "major_id=".$data['major_id'];
						$Class = M('class');
						$Class->where($where)->delete();
						$result = $Major->where($where)->delete();
						echo json_encode($result);
						exit;
						break;
		}
	}
	
	/*
	* 获取下拉框选项
	*/
	public function Major_get_options(){
		$College = M('college');
		$sql = "SELECT college_id,name FROM zyyj.zyyj_college where college_id=".$_SESSION['college_id'].";";
		
		$op = $College->query($sql);
		
		echo json_encode($op);
	}
	
	//获取学院
    public function Major_getCollege(){
       $college = M('college');
        $result = $college->select();
        echo json_encode($result);
    }
}
?>