<?php
namespace Home\Controller;
use Think\Controller;
class CollegeController extends BaseController {
	//学院管理页面
    public function College_list(){
		$this->display();
	}
	/**
	* 取得学院的所有数据
	*/
	public function College_get_colleges(){
		$College = M('college');
		//分页获取表行的代码
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
		$offset = ($page-1)*$rows;
		$sql="SELECT `zyyj_college`.`college_id`,
				`zyyj_college`.`name`,
				`zyyj_college`.`comment`
				FROM `zyyj`.`zyyj_college`
				ORDER BY college_id DESC
			LIMIT ".$offset.",".$rows."";
		
		$rs = $College->count();
		$result["total"] = $rs;
		$items = array();
		$rs = $College->query($sql);
		$items = $rs;
		$result["rows"] = $items;
		echo json_encode($result);
	}
	
	/**
	* 保存学院信息
	*/
	public function College_save_college(){
		//验证模型进行数据验证
		$rules = array(
			//添加的时候验证name字段是否已存在
			array('name','','college name existed',0,'unique',3),
		);
		$College = M('college');
		$data = $College -> create();
		$Cop=I('Cop');
		//防止重名，Cop含义为College_list operation即college页面返回的操作
		switch($Cop){
			case "add":
						//验证是否重名
						if(!$College->validate($rules)->create()){
							$result=2;
							echo json_encode($result);
							exit();
						}
						$result = $College->add($data);
						if($result!=0) $result = 1;
						echo json_encode($result);
						exit;
						break;
			case "edit":
						//验证是否重名
						if(!$College->validate($rules)->create()){
							$result=2;
							echo json_encode($result);
							exit();
						}
						$where = "college_id='".$data['college_id']."'";
						$result = $College->where($where)->save($data);
						if($result!=0) $result = 1;
						echo json_encode($result);
						exit;
						break;
			case "del":
						$where = "college_id='".$data['college_id']."'";
						$Major = M('major');
						$Class = M('class');
						$majors = $Major->where($where)->field('major_id')->select();
						//删除学院下的所有专业和挂靠班级
						foreach($majors as $m){
							$m_Where = "major_id=".$m['major_id'];
							$c_result = $Class->where($m_Where)->delete();
							$m_result = $Major->where($m_Where)->delete();
							if($c_result==0 || $m_result==0){
								$result = 0;
								echo json_encode($result);
								exit;
							}
						}
						$result = $College->where($where)->delete();
						if($result!=0) $result = 1;
						echo json_encode($result);
						exit;
						break;
		}
	}
}
?>