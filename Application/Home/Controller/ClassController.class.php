<?php
namespace Home\Controller;
use Think\Controller;
class ClassController extends BaseController {
     //展示和查找班级信息
    public function Class_list_show(){
        $class = M('class');

        //搜索条件
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $major = isset($_POST['major']) ? mysql_real_escape_string($_POST['major']) : '';
        $grade = isset($_POST['grade']) ? mysql_real_escape_string($_POST['grade']) : '';

        $offset = ($page-1)*$rows;

        $result = array();
        
        $where = "";
        $college_id = session('college_id');
        //查询条件组合
        if($major=='' && $grade==''){
            $where = "zyyj_major.college_id ='$college_id'";
        }else if($major!='' && $grade!=''){
            $where = "zyyj_class.major_id = '$major' and zyyj_class.grade_id = '$grade' and zyyj_major.college_id ='$college_id'";
        }else if($major=='' && $grade!=''){
            $where = "zyyj_class.grade_id = '$grade' and zyyj_major.college_id ='$college_id'";
        }else if($major!='' && $grade==''){
            $where = "zyyj_class.major_id = '$major' and zyyj_major.college_id ='$college_id'";
        }
     
        
        $rs = $class->join('zyyj_grade ON zyyj_class.grade_id = zyyj_grade.grade_id' )->join('zyyj_major ON zyyj_class.major_id = zyyj_major.major_id' )->field('zyyj_grade.name gname, zyyj_class.name cname, zyyj_major.name mname, zyyj_class.class_id cid, zyyj_class.comment ccomment')->where($where)->order("zyyj_class.class_id desc")->limit($offset.','.$rows)->select();

        $items = array();
        $items = $rs;
        $result["rows"] = $items;

        $length = sizeof($rs);
        if($length==0){
            $result["total"] = $length;
        }else{
            $result["total"] = $length;
        }
        echo json_encode($result);
    }

    //获取一个id的信息
    public function Class_getInfo(){
        $id = $_POST['id'];
        $class = M('class');
        $where = "class_id='".$id."'";

        $rs = $class->where($where)->join('zyyj_grade ON zyyj_class.grade_id = zyyj_grade.grade_id' )->join('zyyj_major ON zyyj_class.major_id = zyyj_major.major_id' )->field('zyyj_grade.name gname, zyyj_class.name cname, zyyj_major.name mname, zyyj_class.class_id cid,zyyj_class.grade_id gid,zyyj_class.major_id mid, zyyj_class.comment ccomment')->select();
        echo json_encode($rs);
    }


    //加载视图
    public function Class_list(){
        $this->display();
    }
    //获取年级
    public function Class_getGrade(){
        $grade = M('grade');
        $result = $grade->order('convert(name using gb2312) DESC')->select();
        echo json_encode($result);
    }

    //获取专业
    public function Class_getMajor(){
        $major = M('major');
        $where = "college_id='".session('college_id')."'";
        $result = $major->where($where)->select();
        echo json_encode($result);
    }

    //级联获取
    public function Class_cascade(){
        $id = $_POST['id'];
        $model = $_POST['model'];
    }

    //添加班级和修改班级操作
    public function Class_add(){
        // echo "is comming";
        $class = M('class');
        $id = $_POST['edit_id'];
        
        $data = $class->create();
      
        $data['grade_id'] = $_POST['grade'];
        $data['major_id'] = $_POST['major'];
        $data['name'] = $_POST['name'];
        $data['comment'] = $_POST['comment'];
            

        if($id==''){
            //判断是否重名
            $where = "grade_id = '{$data['grade_id']}' and name = '{$data['name']}' and major_id = '{$data['major_id']}'";
            $count = $class->where($where)->count();
            if ($count) {
                $result = 2;
                echo json_encode($result);
                exit;
            }

            $result = $class->add($data);
            $result = 1;
        }else{
            $where = "grade_id = '{$data['grade_id']}' and name = '{$data['name']}' and major_id = '{$data['major_id']}' and class_id != '{$id}'";
            $count = $class->where($where)->count();
            if ($count) {
                // 失败
                $result = 2;
                echo json_encode($result);
                exit;
            }
            $where = "class_id='".$id."'";
            $result = $class->where($where)->save($data);
            $result = 1;
        }
        

        echo json_encode($result);
        // echo var_dump($result);
        exit;
        // echo $data;
    }

    //删除班级
    public function Class_delete(){
        $id = $_POST['id'];
        $student = M('student');
        $result = $student->where("class_id = {$id}")->delete();
        $class = M('class');
        // $where = "id=".$id;  
        $result = $class->where("class_id = {$id}")->delete();
        
        // echo $id;
        // echo $class->getLastSql();
        echo json_encode($result);
        // echo var_dump($result);
        exit;
        // echo $id;
    }

    //修改班级
    public function Class_update(){
        // echo "is comming";
        $class = M('class');
        
        $data = $class->create();

        $data['grade_id'] = $_POST['grade'];
        $data['name'] = $_POST['name'];
        $data['comment'] = $_POST['comment'];

        $result = $class->add($data);


        echo json_encode($result);
        exit;
    }


}