<?php
namespace Admin\Controller;
use Think\Controller;
class MsgController extends AdminController {
    public function index(){
        $this->display();
    }
    public function liuyan(){
    	$REQUEST    =   (array)I('request.');
    	$msg=M('Msg');
        $msg->where('hasview=0')->setField('hasview',1);
    	$where['type']="liuyan";
    	if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $total= $msg->where($where)->count();
    	$page= new \Think\Page($total, $listRows);
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
    	$list=$msg->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
    	foreach ($list as $key => $value) {
    		$list[$key]['user']=get_user($value['uid']);
            $list[$key]['mem']=get_member($value['mid']);
    	}
    	$this->assign('list',$list);
    	$this->assign('_page', $p? $p: '');
    	$this->display();
    }
    public function reply(){
        $reply=I('post.reply');
        $id=I('post.id');
        $msg=M('Msg');
        $data=array('reply'=>$reply,'reply_time'=>time(),'mid'=>session('user_auth_admin.uid'));
        $msg->where('id='.$id)->setField($data);
        $this->success('回复成功');
    }
    public function del(){
        $id=I('post.id');
        $type=I('post.type');
        $del=M('Msg')->where('id='.$id)->delete();
        if (false!==$del) {
            $this->success('删除信息成功',U('Msg/'.$type));
        }else{
            $this->error('删除失败'.M('Users')->getError());
        }
    }
    public function pinglun(){
        $this->display();
    }
    public function notice(){
        $REQUEST    =   (array)I('request.');
        $msg=M('Msg');
        $where['type']="notice";
        if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $total= $msg->where($where)->count();
        $page= new \Think\Page($total, $listRows);
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $list=$msg->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
        foreach ($list as $key => $value) {
            if ($value['uid']) {
                $list[$key]['user']=get_user($value['uid']);
            }else{
                $list[$key]['to']='全部';
            }
            $list[$key]['mem']=get_member($value['mid']);
        }
        // dump($list);
        $this->assign('list',$list);
        $this->assign('_page', $p? $p: '');
        $this->display();
    }
    public function getinfo(){
        $id=I('request.id');
        $data=M('Msg');
        $info=$data->find($id);
        $this->ajaxReturn($info);
    }
    public function noticeadd(){
        $msg=M('Msg');
        $title=I('post.title');
        $content=I('post.content');
        if (!$title) {
            $this->error('请填写标题');
        }
        if (!$content) {
            $this->error('请填写内容');
        }
        $data=array(
            'type'=>'notice',
            'title'=>$title,
            'content'=>$content,
            'uid'=>0,
            'mid'=>session('user_auth_admin.uid'),
            'add_time'=>time(),
        );
        if ($msg->create($data)) {
            $id=$msg->add();
            if ($id>0) {
                $this->success('添加成功',U('Msg/notice'));
            }else{
                $this->error('添加失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function noticeedit(){
        $msg=M('Msg');
        $title=I('post.title');
        $content=I('post.content');
        if (!$title) {
            $this->error('请填写标题');
        }
        if (!$content) {
            $this->error('请填写内容');
        }
        $data=array(
            'id'=>I('post.id'),
            'type'=>'notice',
            'title'=>$title,
            'content'=>$content,
            'uid'=>0,
            'mid'=>session('user_auth_admin.uid'),
            'add_time'=>time(),
        );
        if ($msg->create($data)) {
            $id=$msg->save();
            if ($id!==false) {
                $this->success('编辑成功',U('Msg/notice'));
            }else{
                $this->error('编辑失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }

    public function send(){
        $REQUEST    =   (array)I('request.');
        $msg=M('Msg');
        $where['type']="send";
        if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $total= $msg->where($where)->count();
        $page= new \Think\Page($total, $listRows);
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $list=$msg->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
        foreach ($list as $key => $value) {
            if ($value['uid']) {
                $list[$key]['user']=get_user($value['uid']);
            }else{
                $list[$key]['to']='全部';
            }
            $list[$key]['mem']=get_member($value['mid']);
        }
        // dump($list);
        $this->assign('list',$list);
        $this->assign('_page', $p? $p: '');
        $this->display();
    }
     //群发email
    public function sendadd(){
        $msg=M('Msg');
        $title=I('post.title');
        $content=I('post.content');
        if (!$title) {
            $this->error('请填写标题');
        }
        if (!$content) {
            $this->error('请填写内容');
        }
        $data=array(
            'type'=>'send',
            'title'=>$title,
            'content'=>$content,
            'uid'=>0,
            'mid'=>session('user_auth_admin.uid'),
            'add_time'=>time(),
        );
        $user=M('Users');
        $arr=$user->field('email,nickname')->select();
        foreach ($arr as $k => $v) {
            $mail=$v['email'];
            $nick=$v['nickname'];
            $result=send_mail($mail,$nick,$title,$content,null);
        }
        if($result==true){
            if ($msg->create($data)) {
                $id=$msg->add();
                if ($id>0) {
                    $this->success('发送成功并添加成功',U('Msg/send'));
                }else{
                    $this->error('添加失败');
                }
            }else{
                $this->error('数据创建失败');
            }
        }else{
            $this->error('发送Email失败');
        }
    }
    
    public function senddel(){
        $id=I('post.id');
        $type=I('post.type');
        $del=M('Msg')->where('id='.$id)->delete();
        if (false!==$del) {
            $this->success('删除信息成功',U('Msg/'.$type));
        }else{
            $this->error('删除失败'.M('Users')->getError());
        }
    }

    public function active(){
        $data=M('Active');
        if(I('request.r') ){
            $listRows = (int)I('request.r');
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $total= $data->where($where)->count();
        $page= new \Think\Page($total, $listRows, I('request.'));
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');
        $info=$data->where($where)->limit($page->firstRow.','.$page->listRows)->order('sort desc,id desc')->select();
        $this->assign('list',$info);
        $this->display();
    }

    public function activesave(){
        if (!I('post.list')) {
            $this->error('商品类别不能为空');
        }
        if (!I('post.url')) {
            $this->error('网站地址不能为空');
        }
        if (!I('post.web')) {
            $this->error('网站名称不能为空');
        }
        if (!I('post.time')) {
            $this->error('活动时间不能为空');
        }
        $config = array(   
            'maxSize'    =>    2*1024*1024,
            'rootPath'   =>    './Uploads/active/',
            'saveName'   =>    array('uniqid',''), 
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),  
            'autoSub'    =>    true,   
            'subName'    =>    array('date','Ymd'),
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info=$upload->upload();
        if($info) {                        // 上传错误提示错误信息
            $pic=__ROOT__.'/Uploads/active/'.$info['filepic']['savepath'].$info['filepic']['savename'];
            $add=array(
                'list'=>I('post.list'),
                'url'=>I('post.url'),
                'pic'=>$pic,
                'status'=>I('post.status'),
                'sort'=>I('post.sort'),
                'content'=>I('post.content'),
                'web'=>I('post.web'),
                'time'=>I('post.time'),
            );
            if (M('Active')->create($add)) {
                if (M('Active')->add()) {
                    $this->success('添加成功',U('Msg/active'));
                }else{
                    $this->error('添加失败');
                }
            }else{
                $this->error('数据创建失败');
            }
        }else{                                          // 上传成功 获取上传文件信息
            $this->error($upload->getError());//获取失败信息
        }
    }
    public function activeadd(){
        $data=M('Active');
        $id=I('get.id');
        $where['id']=$id;
        $info=$data->where($where)->find();
        $this->assign('info',$info);
        $this->display();
    }

    public function activeedit(){
        if (!I('post.list')) {
            $this->error('商品类别不能为空');
        }
        if (!I('post.url')) {
            $this->error('网站地址不能为空');
        }
        if (!I('post.web')) {
            $this->error('网站名称不能为空');
        }
        if (!I('post.time')) {
            $this->error('活动时间不能为空');
        }
        $config = array(   
            'maxSize'    =>    2*1024*1024,
            'rootPath'   =>    './Uploads/active/',
            'saveName'   =>    array('uniqid',''), 
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),  
            'autoSub'    =>    true,   
            'subName'    =>    array('date','Ymd'),
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info=$upload->upload();
        if ($info) {
            $pic=__ROOT__.'/Uploads/active/'.$info['filepic']['savepath'].$info['filepic']['savename'];
            $f='.'.I('post.pic');
            unlink($f);
        }else{
            $pic=I('post.pic');
        }
        $save=array(
                'id'=>I('post.id'),
                'list'=>I('post.list'),
                'url'=>I('post.url'),
                'pic'=>$pic,
                'status'=>I('post.status'),
                'sort'=>I('post.sort'),
                'content'=>I('post.content'),
                'web'=>I('post.web'),
                'time'=>I('post.time'),
            );
            if (M('Active')->create($save)) {
                if (M('Active')->save()) {
                    $this->success('编辑成功',U('Msg/active'));
                }else{
                    $this->error('编辑失败');
                }
            }else{
                $this->error('数据创建失败');
            }
    }

    public function activedel(){
        $id=I('post.id');
        if (empty($id)) {
            $this->error('ID错误');
        }
        $info=M('Active')->find($id);
        if(false!==M('Active')->where('id='.$id)->delete()){
            unlink('.'.$info['pic']);
            $this->success('删除成功',U('Msg/active'));
        }else{
            $this->error('删除失败');
        }
    }

    public function wuliu(){
        $data=M('Gjwu');
        $id=I('get.id');
        $where['id']=$id;
        $info=$data->where($where)->find();
        $this->assign('info',$info);
        $list=$data->order('sort desc,id desc')->select();
        $this->assign('list',$list);
        $this->display();
    }
    
    public function wuliuadd(){
        $title=I('post.title');
        if($title==""){
           $this->error('标题不能为空');
        }
        $config = array(   
            'maxSize'    =>    2*1024*1024,
            'rootPath'   =>    './Uploads/wuliu/',
            'saveName'   =>    array('uniqid',''), 
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),  
            'autoSub'    =>    true,   
            'subName'    =>    array('date','Ymd'),
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info=$upload->upload();
        if($info) {                        // 上传错误提示错误信息
            $pic=__ROOT__.'/Uploads/wuliu/'.$info['filepic']['savepath'].$info['filepic']['savename'];
            $add=array(
                'pic'=>$pic,
                'status'=>I('post.status'),
                'sort'=>I('post.sort'),
                'title'=>$title,
            );
            if (M('Gjwu')->create($add)) {
                if (M('Gjwu')->add()) {
                    $this->success('添加成功',U('Msg/wuliu'));
                }else{
                    $this->error('添加失败');
                }
            }else{
                $this->error('数据创建失败');
            }
        }else{                                          // 上传成功 获取上传文件信息
            $this->error($upload->getError());//获取失败信息
        }
    }

    public function wuliuedit(){
        $title=I('post.title');
        if($title==""){
           $this->error('标题不能为空');
        }
        $config = array(   
            'maxSize'    =>    2*1024*1024,
            'rootPath'   =>    './Uploads/wuliu/',
            'saveName'   =>    array('uniqid',''), 
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),  
            'autoSub'    =>    true,   
            'subName'    =>    array('date','Ymd'),
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info=$upload->upload();
        if ($info) {
            $pic=__ROOT__.'/Uploads/wuliu/'.$info['filepic']['savepath'].$info['filepic']['savename'];
            $f='.'.I('post.pic');
            unlink($f);
        }else{
            $pic=I('post.pic');
        }
        $save=array(
                'id'=>I('post.id'),
                'pic'=>$pic,
                'status'=>I('post.status'),
                'sort'=>I('post.sort'),
                'title'=>$title,
            );
            if (M('Gjwu')->create($save)) {
                if (M('Gjwu')->save()) {
                    $this->success('编辑成功',U('Msg/wuliu'));
                }else{
                    $this->error('编辑失败');
                }
            }else{
                $this->error('数据创建失败');
            }
    }
     
    public function wuliudel(){
        $id=I('post.id');
        if (empty($id)) {
            $this->error('ID错误');
        }
        $info=M('Gjwu')->find($id);
        if(false!==M('Gjwu')->where('id='.$id)->delete()){
            unlink('.'.$info['pic']);
            $this->success('删除成功',U('Msg/wuliu'));
        }else{
            $this->error('删除失败');
        }
    }

    public function pay(){
        $data=M('Pay');
        $id=I('get.id');
        $where['id']=$id;
        $info=$data->where($where)->find();
        $this->assign('info',$info);
        $list=$data->order('sort desc,id desc')->select();
        $this->assign('list',$list);
        $this->display();
    }

    public function payadd(){
        $title=I('post.title');
        if($title==""){
           $this->error('标题不能为空');
        }
        $config = array(   
            'maxSize'    =>    2*1024*1024,
            'rootPath'   =>    './Uploads/pay/',
            'saveName'   =>    array('uniqid',''), 
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),  
            'autoSub'    =>    true,   
            'subName'    =>    array('date','Ymd'),
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info=$upload->upload();
        if($info) {                        // 上传错误提示错误信息
            $pic=__ROOT__.'/Uploads/pay/'.$info['filepic']['savepath'].$info['filepic']['savename'];
            $add=array(
                'pic'=>$pic,
                'status'=>I('post.status'),
                'sort'=>I('post.sort'),
                'title'=>$title,
            );
            if (M('Pay')->create($add)) {
                if (M('Pay')->add()) {
                    $this->success('添加成功',U('Msg/pay'));
                }else{
                    $this->error('添加失败');
                }
            }else{
                $this->error('数据创建失败');
            }
        }else{                                          // 上传成功 获取上传文件信息
            $this->error($upload->getError());//获取失败信息
        }
    }

    public function payedit(){
        $title=I('post.title');
        if($title==""){
           $this->error('标题不能为空');
        }
        $config = array(   
            'maxSize'    =>    2*1024*1024,
            'rootPath'   =>    './Uploads/pay/',
            'saveName'   =>    array('uniqid',''), 
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),  
            'autoSub'    =>    true,   
            'subName'    =>    array('date','Ymd'),
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info=$upload->upload();
        if ($info) {
            $pic=__ROOT__.'/Uploads/pay/'.$info['filepic']['savepath'].$info['filepic']['savename'];
            $f='.'.I('post.pic');
            unlink($f);
        }else{
            $pic=I('post.pic');
        }
        $save=array(
                'id'=>I('post.id'),
                'pic'=>$pic,
                'status'=>I('post.status'),
                'sort'=>I('post.sort'),
                'title'=>$title,
            );
            if (M('Pay')->create($save)) {
                if (M('Pay')->save()) {
                    $this->success('编辑成功',U('Msg/wuliu'));
                }else{
                    $this->error('编辑失败');
                }
            }else{
                $this->error('数据创建失败');
            }
    }

    public function paydel(){
        $id=I('post.id');
        if (empty($id)) {
            $this->error('ID错误');
        }
        $info=M('Pay')->find($id);
        if(false!==M('Pay')->where('id='.$id)->delete()){
            unlink('.'.$info['pic']);
            $this->success('删除成功',U('Msg/pay'));
        }else{
            $this->error('删除失败');
        }
    }
}