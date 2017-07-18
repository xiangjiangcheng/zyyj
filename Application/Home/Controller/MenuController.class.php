<?php
namespace Home\Controller;
use Think\Controller;
class MenuController extends BaseController {
    // 加载菜单管理界面
    public function Menu_index(){
        $this->display();
    }
    //获取菜单信息
    public function Menu_get_menu(){
        $id = I('id');      //父节点id
        if($id==""||$id==null){
            $id=0;
        }
        //设置条件
        $map['nid']=$id;
        $post=M('post');
        $menu=M('menu');
        $res=$menu->where($map)->select();
        echo json_encode($res);
    }
    //菜单的添加或修改
    function Menu_add(){
        //接收参数
        $id=I('id');
        $text=I('text');
        $nid=I('nid');
        $url=I('url');

        $state="open";  //二级菜单
        if($nid==0){    
            $state="closed";  //初级菜单
            $url="";
        }
        $menu=M('menu');

        if($id==-1){//插入操作
            $map['text']=$text;
            $map['url']=$url;
            $map['state']=$state;
            $map['nid']=$nid;
            $res=$menu->data($map)->add();
            $data['code']=1;
        }else{//修改
            $map['id']=$id;
            $map['url']=$url;
            $map['text']=$text;
            $res=$menu->data($map)->save();
            $data['code']=2;
        }
        echo json_encode($data);
    }
    //删除菜单
    function Menu_delete(){
        //接收参数
        $id=I('id');
        $nid=I('nid');
        $menu=M('menu');

        if($nid==0){    //一级菜单
            $map['nid']=$id;
            $res=$menu->where($map)->select();
            if(count($res)>0){  //有子菜单
                $data['code']=2;
            }else{  //没有子菜单
                $arr['id']=$id;
                $menu->where($arr)->delete();
                $data['code']=1;
            }
        }else{  //二级菜单，直接删除
            $arr['id']=$id;
            $menu->where($arr)->delete();
            $data['code']=1;
        }
        echo json_encode($data);
    }
    //加载角色绑定菜单界面
    function Menu_menu_to_post_index(){
        $this->display();
    }

    //获取所有角色信息
    function Menu_get_post(){
        $post=M('post');
        $menu=M('menu');
        $res=$post->select();
        $json_array=array();
        for($i=0;$i<count($res);$i++){
            $json_array[$i]=array(
                'id'    =>$res[$i]['post_id'],  //id
                'text'  =>$res[$i]['name'], //文本显示内容
                'state' =>'open'           //状态
            );
        }
        echo json_encode($json_array);
    }

    //获取角色的菜单
    function Menu_get_post_menu(){
        $role_id = $_GET['post_id']; 
        $id = I('id');      //父节点id
        if($id==""||$id==null){
            $id=0;
        }
        $map['nid']=$id;
        $map1['post_id']=$role_id;
        $post=M('post');
        $menu=M('menu');
        $res1=$post->where($map1)->select();//读取用户所拥有的菜单
        $menu_array=explode(";", $res1['0']['menu_id']);//分隔菜单id
        $all_menu=$menu->where($map)->select(); //获取所有菜单
        $i=0;
        $json_array=array();
        foreach ($all_menu as $row) {   //遍历所有菜单
            $json_array[$i]=array(
                'id'  =>$row['id'],
                'text'=>$row['text'],
                'state'=>$row['state'],
                'nid'   =>$row['nid'],
                'checked'=>false
            );
            for($j=0;$j<count($menu_array);$j++){
                if($row['id']==$menu_array[$j]){   //将所有绑定给改角色的菜单，前面打上√
                    $json_array[$i]['checked']=true;
                }
            }
            if($row['nid']==0){ //一级菜单不选中
                $json_array[$i]['checked']=false;
            }
            $i++;
        }
        echo json_encode($json_array);
    }

    //将菜单绑定给角色
    function Menu_menu_to_post(){
        $map['post_id']=I('post_id');
        $map['menu_id']=I('menu_id');
        $post=M('post');
        $data['code']=0;
        if($post->data($map)->save()){
            $data['code']=1;
        }
        echo json_encode($data);
    }
}