<?php
namespace Home\Controller;
use Think\Controller;
class ProgrammeController extends BaseController {
     //展示和查找方案信息
    public function Programme_list_show(){
        $programme = M('programme');

        //搜索条件
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        
        $offset = ($page-1)*$rows;
        // echo $offset;die;
        $result = array();
        
        $where = "";
        $rs = $programme->count();
        // echo $rs;die;
        // $row = mysql_fetch_row($rs);
        
        $result["total"] = $rs;

        $rs = $programme->order("createdate desc")->limit($offset.','.$rows)->select();
        
        $items = array();
        
        $items = $rs;

        $result["rows"] = $items;

        echo json_encode($result);
    }

    //获取一个id的信息
    public function Programme_getInfo(){
        $id = $_POST['id'];
        $programme = M('programme');
        $checkpoint = M('checkpoint');
        $sp = M('student_practice');

        $where = "programme_id='".$id."'";
        $result['c_num'] = $checkpoint->where($where)->count();

        // $where = "programme_id='".$id."'";
        $rs = $programme->where($where)->select();

        $result['user'] = $sp->where($where)->count();
        // echo  $sp->getLastSql();
        $result['others'] = $rs;
        // echo $programme->getLastSql();
        echo json_encode($result);
    }


    //加载视图
    public function Programme_list(){
        $this->display();
    }
    

    //添加方案和修改方案操作
    public function Programme_add(){
        // echo "is comming";
        $programme = M('programme');
        $checkpoint = M('checkpoint');
        $id = $_POST['program_edit_id'];
        $where = "programme_id='".$id."'";
        $data1 = $programme->create();
        $data2 = $checkpoint->create();
        $time = (int)$_POST['programme_cnum'];
        $date = time();  //得到当前日期时间
        // echo date("y-m-d",$time)
        // $checkpoint->where('programme_id = {$id}')->delete();
        // for($i=1;$i<=$time;++$i){
        //     $data2['programme_id'] = $id;
        //     $data2['name'] = "第".i."关";
        //     // $data['major_id'] = $_POST['major'];
        //     $data2['limit_time'] = '10';
        //     $data2['question_num'] = "5:5:5";
        //     $data2['total_score'] = "100";
        //     $data2['pass_score'] = "60";
        //     $result = $checkpoint->add($data1);
        // }
        // echo $id;
        $data1['score'] = $_POST['programme_score'];
        $data1['createdate'] = date("y-m-d H:i:s",$date);
        // $data['major_id'] = $_POST['major'];
        $data1['name'] = $_POST['programme_name'];
        $data1['comment'] = $_POST['programme_comment'];

        


        if($id==''){
            $names = array();
            //判断是否重名
            $names = $programme->select();
            // echo "names:::".$names;
            foreach($names as $key=>$value){  
                foreach($names[$key] as $subkey=>$subval){
                    // echo $subval.' || ';
                    if($subkey == "name"){
                        // echo $subkey.' || ';
                        if($subval == $data1['name']){
                            $result = 2;
                            echo json_encode($result);
                            exit;
                        }
                    }               
                }
            }

            // die;
            $programme->add($data1);

            $id = $programme->getLastInsId();
            for($i=1;$i<=$time;++$i){
                $data2['programme_id'] = $id;
                $data2['name'] = "第".$i."关";
                // $data['major_id'] = $_POST['major'];
                $data2['limit_time'] = '10';
                $data2['question_num'] = "5;5;5";
                $data2['total_score'] = "15";
                $data2['pass_score'] = "";
                $checkpoint->add($data2);
            }
            $result = 1;
        }else{
            $names = array();
            //判断是否重名
            $names = $programme->select();
            foreach($names as $key=>$value){  
                foreach($names[$key] as $subkey=>$subval){
                    if($subkey == "programme_id"){
                        // echo $subkey.' || ';
                        if($subval == $id){
                            // echo "iscoming";
                            break;
                        }
                    }
                    // echo $subval.' || ';
                    if($subkey == "name"){
                        // echo $subkey.' || ';
                        if($subval == $data1['name']){
                            $result = 2;
                            echo json_encode($result);
                            exit;
                        }
                    }               
                }
            }
                // $result = $checkpoint->where($where)->delete();
                // // echo $result;
                // // if($result){
                // for($i=1;$i<=$time;++$i){
                //     $data2['programme_id'] = $id;
                //     $data2['name'] = "第".$i."关";
                //     // $data['major_id'] = $_POST['major'];
                //     $data2['limit_time'] = '10';
                //     $data2['question_num'] = "5;5;5";
                //     $data2['total_score'] = "100";
                //     $data2['pass_score'] = "60";
                //     $checkpoint->add($data2);
                // }
            $programme->where($where)->save($data1);
                // }        

               $result = 3;        
        }
        
        // if($result > 0){
        //     $result = "1";
        //     // echo 'nice';
        // }

        echo json_encode($result);
        // echo var_dump($result);
        exit;
        // echo $data;
    }

    //删除方案
    public function Programme_delete(){
        $id = $_POST['id'];
        $programme = M('programme');
        $checkpoint = M('checkpoint');
        $sp = M('student_practice');
        
        $where = "programme_id='".$id."' and type = '1'";
        $user = $sp->where($where)->count();
        if ($user != 0) {
            $result = 0;
            echo json_encode($result);
            exit;
        }
        $where = "programme_id = {$id}";
        $result = $checkpoint->where($where)->delete();
        // $class = M('class');
        // $where = "id=".$id;  
        $result = $programme->where($where)->delete();
        
        // echo $id;
        // echo $class->getLastSql();
        echo json_encode($result);
        // echo var_dump($result);
        exit;
        // echo $id;
    }


}