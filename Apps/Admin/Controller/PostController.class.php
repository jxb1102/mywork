<?php
namespace Admin\Controller;
use Think\Controller;
class PostController extends AdminController {
    public function category(){
    	$data=M('category');
    		$id=I('get.id');
    		if ($id) {
    			$info=$data->find($id);
    			$this->assign('info',$info);
    		};
    		$catelist=$data->order('lang,sort desc,id asc')->select();
    		$this->assign('catelist',$catelist);
            $langlist=M('Lang')->where('status=1')->select();
            $this->assign('langlist',$langlist);
        	$this->display();

    }
    public function categoryadd(){
        $data=M('Category');
        if (!I('post.title')) {
                $this->error('请填写分类名称');
            }
            if (!I('post.lang')) {
                $this->error('请选择语言');
            }
            if (I('post.model')==1) {
                if (!I('post.content')) {
                    $this->error('请填写内容');
                }else{
                    $content=$_POST['content'];
                }
            }else{
                $content='';
            }
            $add=array(
                'title'=>I('post.title'),
                'sort'=>I('post.sort'),
                'status'=>I('post.status'),
                'model'=>I('post.model'),
                'content'=>$content,
                'lang'=>I('post.lang'),
            );
            if ($data->create($add)) {
                $id=$data->add();
                if ($id>0) {
                    $this->success('添加成功',U('Post/category'));
                }else{
                    $this->error('添加失败');
                }
            }else{
                $this->error('数据创建失败');
            }
    }
    public function categoryedit(){
        $data=M('Category');
        if (!I('post.title')) {
                $this->error('请填写分类名称');
            }
            if (!I('post.lang')) {
                $this->error('请选择语言');
            }
            if (I('post.model')==1) {
                if (!I('post.content')) {
                    $this->error('请填写内容');
                }else{
                    $content=$_POST['content'];
                }
            }else{
                $content='';
            }
            $save=array(
                'id'=>I('post.id'),
                'title'=>I('post.title'),
                'sort'=>I('post.sort'),
                'status'=>I('post.status'),
                'model'=>I('post.model'),
                'content'=>$content,
                'lang'=>I('post.lang'),
            );
            if ($data->create($save)) {
                $id=$data->save();
                if ($id!==false) {
                    $this->success('编辑成功',U('Post/category'));
                }else{
                    $this->error('编辑失败');
                }
            }else{
                $this->error('数据创建失败');
            }
    }
    public function categorydel(){
        $id=I('post.id');
        $art=M('Article')->where('cid='.$id)->find();
        if ($art) {
            $this->error('该分类下有文章，请删除文章或者转移文章分类后再删除');
        }
        $del=M('Category')->where('id='.$id)->delete();
        
        if (false!==$del) {
            $this->success('分类删除成功',U('Post/category'));
        }else{
            $this->error('删除失败'.M('Category')->getError());
        }
    }
    public function article(){
        $data=M('Article');
        if (I('get.cid')) {
            $where['cid']=I('get.cid');
        }
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

        $list=$data->where($where)->limit($page->firstRow.','.$page->listRows)->order('sort desc,id desc')->select();
        foreach ($list as $key => $value) {
            $list[$key]['mem']=get_member($value['uid']);
            $list[$key]['cate']=M('Category')->where('id='.$value['cid'])->find();
        }
        $this->assign('list',$list);
        $this->display();
    }
    public function addarticle(){
        $catelist=M('Category')->where('model=0')->order('sort desc,id asc')->select();
        $this->assign('catelist',$catelist);
        $langlist=M('Lang')->select();
        //dump($langlist);
        $this->assign('langlist',$langlist);
        $this->display();
    }
    public function editarticle(){
        $id=I('get.id');
        if ($id) {
            $info=M('Article')->find($id);
            $this->assign('info',$info);
        }else{
            $this->error('没有该文章');
        };
        $catelist=M('Category')->where('model=0')->order('sort desc,id asc')->select();
        $this->assign('catelist',$catelist);
        $this->display('addarticle');
    }
    public function articleadd(){
        $data=M('Article');
        if (!I('post.title')) {
            $this->error('请填写文章标题');
        }
        if (!I('post.cid')) {
            $this->error('请选择文章分类');
        }
        $lang=M('Category')->where('id='.I('post.cid'))->getField('lang');
        if (!I('post.content')) {
            $this->error('请填写内容');
        }
        $add=array(
            'title'=>I('post.title'),
            'status'=>I('post.status'),
            'sort'=>I('post.sort'),
            'jianjie'=>I('post.jianjie'),
            'content'=>$_POST['content'],
            'cid'=>I('post.cid'),
            'addtime'=>time(),
            'edittime'=>time(),
            'uid'=>session('user_auth.uid'),
            'uname'=>session('user_auth.username'),
            'lang'=>$lang,
        );
        if ($data->create($add)) {
            if (false!==$data->add()) {
                $this->success('文章添加成功',U('Post/article'));
            }else{
                $this->error('添加文章失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function articleedit(){
        $data=M('Article');
        if (!I('post.title')) {
            $this->error('请填写文章标题');
        }
        if (!I('post.cid')) {
            $this->error('请选择文章分类');
        }
        if (!I('post.content')) {
            $this->error('请填写内容');
        }
        $lang=M('Category')->where('id='.I('post.cid'))->getField('lang');
        $save=array(
            'id'=>I('post.id'),
            'title'=>I('post.title'),
            'status'=>I('post.status'),
            'sort'=>I('post.sort'),
            'jianjie'=>I('post.jianjie'),
            'content'=>$_POST['content'],
            'cid'=>I('post.cid'),
            'edittime'=>time(),
            'uid'=>session('user_auth.uid'),
            'uname'=>session('user_auth.username'),
            'lang'=>$lang,
        );
        if ($data->create($save)) {
            if (false!==$data->save()) {
                $this->success('文章更新成功',U('Post/article'));
            }else{
                $this->error('文章编辑失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function articledel(){
        $id=I('post.id');
        $del=M('Article')->where('id='.$id)->delete();
        
        if (false!==$del) {
            $this->success('文章删除成功',U('Post/article'));
        }else{
            $this->error('删除失败'.M('Article')->getError());
        }
    }
    public function indexad(){
        $data=M('Indexad');
        $id=I('get.id');
        if ($id) {
            $info=$data->find($id);
            $this->assign('info',$info);
        }
        $list=$data->order('sort desc,id desc')->select();
        $this->assign('list',$list);
        $this->display();
    }
    public function indexadadd(){
        if (!I('post.title')) {
            $this->error('标题不能为空');
        }
        if (!I('post.uri')) {
            $this->error('链接地址不能为空');
        }
        $config = array(   
            'maxSize'    =>    2*1024*1024,
            'rootPath'   =>    './Uploads/indexad/',
            'saveName'   =>    array('uniqid',''), 
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),  
            'autoSub'    =>    true,   
            'subName'    =>    array('date','Ymd'),
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info=$upload->upload();
        if($info) {                        // 上传错误提示错误信息
            $pic=__ROOT__.'/Uploads/indexad/'.$info['filepic']['savepath'].$info['filepic']['savename'];
            $add=array(
                'title'=>I('post.title'),
                'uri'=>I('post.uri'),
                'pic'=>$pic,
                'status'=>I('post.status'),
                'sort'=>I('post.sort'),
                'addtime'=>time(),
            );
            if (M('Indexad')->create($add)) {
                if (M('Indexad')->add()) {
                    $this->success('添加成功',U('Post/indexad'));
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
    public function indexadedit(){
        if (!I('post.title')) {
            $this->error('标题不能为空');
        }
        if (!I('post.uri')) {
            $this->error('链接地址不能为空');
        }
        $config = array(   
            'maxSize'    =>    2*1024*1024,
            'rootPath'   =>    './Uploads/indexad/',
            'saveName'   =>    array('uniqid',''), 
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),  
            'autoSub'    =>    true,   
            'subName'    =>    array('date','Ymd'),
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info=$upload->upload();
        if ($info) {
            $pic=__ROOT__.'/Uploads/indexad/'.$info['filepic']['savepath'].$info['filepic']['savename'];
            $f='.'.I('post.pic');
            unlink($f);
        }else{
            $pic=I('post.pic');
        }
        $save=array(
            'id'=>I('post.id'),
                'title'=>I('post.title'),
                'uri'=>I('post.uri'),
                'pic'=>$pic,
                'status'=>I('post.status'),
                'sort'=>I('post.sort'),
                'addtime'=>time(),
            );
            if (M('Indexad')->create($save)) {
                if (M('Indexad')->save()) {
                    $this->success('编辑成功',U('Post/indexad'));
                }else{
                    $this->error('编辑失败');
                }
            }else{
                $this->error('数据创建失败');
            }
    }
    public function indexaddel(){
        $id=I('post.id');
        if (empty($id)) {
            $this->error('ID错误');
        }
        $info=M('Indexad')->find($id);
        if(false!==M('Indexad')->where('id='.$id)->delete()){
            unlink('.'.$info['pic']);
            $this->success('删除成功',U('Post/indexad'));
        }else{
            $this->error('删除失败');
        }
    }

    public function link(){
        $data=M('Link');
        $id=I('get.lid');
        if ($id) {
            $info=$data->find($id);
            $this->assign('info',$info);
        }
        $list=$data->order('lid desc')->select();
        //dump($list);
        $this->assign('list',$list);
        $this->display();
    }

    public function linkadd(){
        if (!I('post.ltitle')) {
            $this->error('标题不能为空');
        }
        if (!I('post.lurl')) {
            $this->error('链接地址不能为空');
        }
        $config = array(   
            'maxSize'    =>    2*1024*1024,
            'rootPath'   =>    './Uploads/Link/',
            'saveName'   =>    array('uniqid',''), 
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),  
            'autoSub'    =>    true,   
            'subName'    =>    array('date','Ymd'),
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info=$upload->upload();
        if($info) {                        // 上传错误提示错误信息
            $pic=__ROOT__.'/Uploads/Link/'.$info['filepic']['savepath'].$info['filepic']['savename'];
            $add=array(
                'ltitle'=>I('post.ltitle'),
                'lurl'=>I('post.lurl'),
                'limg'=>$pic,
                'lstate'=>I('post.lstate'),
            );
            if (M('Link')->create($add)) {
                if (M('Link')->add()) {
                    $this->success('添加成功',U('Post/Link'));
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
    
    public function linkedit(){
        if (!I('post.ltitle')) {
            $this->error('标题不能为空');
        }
        if (!I('post.lurl')) {
            $this->error('链接地址不能为空');
        }
        $config = array(   
            'maxSize'    =>    2*1024*1024,
            'rootPath'   =>    './Uploads/Link/',
            'saveName'   =>    array('uniqid',''), 
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),  
            'autoSub'    =>    true,   
            'subName'    =>    array('date','Ymd'),
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info=$upload->upload();
        if ($info) {
            $pic=__ROOT__.'/Uploads/Link/'.$info['filepic']['savepath'].$info['filepic']['savename'];
            $f='.'.I('post.limg');
            unlink($f);
        }else{
            $pic=I('post.limg');
        }
        $save=array(
            'lid'=>I('post.lid'),
                'ltitle'=>I('post.ltitle'),
                'lurl'=>I('post.lurl'),
                'limg'=>$pic,
                'lstate'=>I('post.lstate'),
            );
            if (M('Link')->create($save)) {
                if (M('Link')->save()) {
                    $this->success('编辑成功',U('Post/Link'));
                }else{
                    $this->error('编辑失败');
                }
            }else{
                $this->error('数据创建失败');
            }
    }

    public function linkdel(){
        $id=I('post.lid');
        if (empty($id)) {
            $this->error('ID错误');
        }
        $info=M('Link')->find($id);
        if(false!==M('Link')->where('lid='.$id)->delete()){
            unlink('.'.$info['limg']);
            $this->success('删除成功',U('Post/link'));
        }else{
            $this->error('删除失败');
        }
    }

      public function goodcate(){
        $data=M('Goodcate');
            $id=I('get.id');
            if ($id) {
                $info=$data->find($id);
                $this->assign('info',$info);
            };
            $catelist=$data->order('sort desc,id asc')->select();
            $this->assign('catelist',$catelist);
            $this->display();

    }

    public function goodcateadd(){
         $data=M('Goodcate');
        if (!I('post.title')) {
                $this->error('请填写分类名称');
            }
            $add=array(
                'title'=>I('post.title'),
                'sort'=>I('post.sort'),
                'state'=>I('post.state'),
            );
            if ($data->create($add)) {
                $id=$data->add();
                if ($id>0) {
                    $this->success('添加成功',U('Post/goodcate'));
                }else{
                    $this->error('添加失败');
                }
            }else{
                $this->error('数据创建失败');
            }
    }

    public function goodcateedit(){
        $data=M('Goodcate');
        if (!I('post.title')) {
                $this->error('请填写分类名称');
            }
            $save=array(
                'id'=>I('post.id'),
                'title'=>I('post.title'),
                'sort'=>I('post.sort'),
                'state'=>I('post.state'),
            );
            if ($data->create($save)) {
                $id=$data->save();
                if ($id!==false) {
                    $this->success('编辑成功',U('Post/goodcate'));
                }else{
                    $this->error('编辑失败');
                }
            }else{
                $this->error('数据创建失败');
            }
    }

     public function goodcatedel(){
        $id=I('post.id');
        $art=M('Goodlist')->where('fid='.$id)->find();
        if ($art) {
            $this->error('该分类下有网站，请删除网站以后再删除');
        }
        $del=M('Goodcate')->where('id='.$id)->delete();
        
        if (false!==$del) {
            $this->success('分类删除成功',U('Post/goodcate'));
        }else{
            $this->error('删除失败'.M('Goodcate')->getError());
        }
    }

    public function goodlist(){
        $data=M('Goodlist');
        if (I('get.fid')) {
            $where['fid']=I('get.fid');
        }
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

        $list=$data->where($where)->limit($page->firstRow.','.$page->listRows)->order('sort desc,id desc')->select();
        foreach ($list as $key => $value) {
            $list[$key]['cate']=M('Goodcate')->where('id='.$value['fid'])->find();
        }
        //dump($list);
        $this->assign('list',$list);
        $this->display();
    }
    
    public function goodlistadd(){
        $data=M('Goodlist');
        $id=I('get.id');
        if($id){
            $info=$data->find($id);
            $this->assign('info',$info);
        }
        $catelist=M('Goodcate')->where('state=1')->order('sort desc,id asc')->select();
        $this->assign('catelist',$catelist);
        $this->display(); 
    }

    public function goodlistsave(){
        $data=M('Goodlist');
        if (!I('post.title')) {
            $this->error('请填写商城名称');
        }
        if (!I('post.cid')) {
            $this->error('请选择商城分类');
        }
        if (!I('post.start')) {
            $this->error('请填写商城星级');
        }
        if (!I('post.content')) {
            $this->error('请填写内容');
        }
        $config = array(   
            'maxSize'    =>    2*1024*1024,
            'rootPath'   =>    './Uploads/Goodlist/',
            'saveName'   =>    array('uniqid',''), 
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),  
            'autoSub'    =>    true,   
            'subName'    =>    array('date','Ymd'),
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info=$upload->upload();
        if($info) {                        // 上传错误提示错误信息
            $pic=__ROOT__.'/Uploads/Goodlist/'.$info['filepic']['savepath'].$info['filepic']['savename'];
            $add=array(
                'title'=>I('post.title'),
                'url'=>I('post.url'),
                'fid'=>I('post.cid'),
                'start'=>I('post.start'),
                'pic'=>$pic,
                'state'=>I('post.state'),
                'sort'=>I('post.sort'),
                'content'=>I('post.content'),
            );
            if (M('Goodlist')->create($add)) {
                if (M('Goodlist')->add()) {
                    $this->success('添加成功',U('Post/goodlist'));
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
    
     public function goodlistedit(){
        //print_r($_POST);exit;
        $data=M('Goodlist');
        if (!I('post.title')) {
            $this->error('请填写商城名称');
        }
        if (!I('post.cid')) {
            $this->error('请选择商城分类');
        }
        if (!I('post.start')) {
            $this->error('请填写商城星级');
        }
        if (!I('post.content')) {
            $this->error('请填写内容');
        }
        $config = array(   
            'maxSize'    =>    2*1024*1024,
            'rootPath'   =>    './Uploads/Goodlist/',
            'saveName'   =>    array('uniqid',''), 
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),  
            'autoSub'    =>    true,   
            'subName'    =>    array('date','Ymd'),
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info=$upload->upload();
        if ($info) {
            $pic=__ROOT__.'/Uploads/Goodlist/'.$info['filepic']['savepath'].$info['filepic']['savename'];
            $f='.'.I('post.pic');
            unlink($f);
        }else{
            $pic=I('post.pic');
        }
        $save=array(
                'id'=>I('post.id'),
                'title'=>I('post.title'),
                'url'=>I('post.url'),
                'fid'=>I('post.cid'),
                'start'=>I('post.start'),
                'pic'=>$pic,
                'state'=>I('post.state'),
                'sort'=>I('post.sort'),
                'content'=>I('post.content'),
            );
       // print_r($save);exit;
            if (M('Goodlist')->create($save)) {
                if (M('Goodlist')->save()) {
                    $this->success('编辑成功',U('Post/goodlist'));
                }else{
                    $this->error('编辑失败');
                }
            }else{
                $this->error('数据创建失败');
            }
     }

     public function goodlistdel(){
        $id=I('post.id');
        if (empty($id)) {
            $this->error('ID错误');
        }
        $info=M('Goodlist')->find($id);
        if(false!==M('Goodlist')->where('id='.$id)->delete()){
            unlink('.'.$info['pic']);
            $this->success('删除成功',U('Post/goodlist'));
        }else{
            $this->error('删除失败');
        }
    }
    
    public function diancate(){
        $data=M('Diancate');
        $id=I('get.id');
        if($id!=''){
            $info=$data->where('id='.$id)->find();
            $this->assign('info',$info);
        }
        $dian=$data->order('sort desc,id desc')->select();
        $this->assign('dian',$dian);
        $this->display();
    }
    
    public function diancateadd(){
        $data=M('Diancate');
        if(I('post.title')==''){
            $this->error('店铺分类名成不能为空');
        }
        $arr=array(
            'title'=>I('post.title'),
            'status'=>I('post.status'),
            'sort'=>I('post.sort')
            );
        if($data->create($arr)){
            if($data->add()){
                $this->success('数据保存成功',U('Post/diancate'));
            }else{
                $this->errror('数据保存失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }

    public function diancatedel(){
        $id=I('post.id');
        $data=M('Diancate');
        if(empty($id)){
            $this->error('ID错误');
        }
        if(false!==$data->where('id='.$id)->delete()){
           $this->success('删除成功',U('Post/diancate'));
        }else{
            $this->error('删除失败');
        }
    }

    public function diancateedit(){
       $data=M('Diancate');
       if(I('post.title')==''){
          $this->error('店铺分类名成不能为空');
       }
       $arr=array(
             'id'=>I('post.id'),
             'title'=>I('post.title'),
             'status'=>I('post.status'),
             'sort'=>I('post.sort')
        );
       if($data->create($arr)){
           $id=$data->save();
           if($id!==false){
              $this->success('编辑成功',U('Post/diancate'));
           }else{
              $this->error('编辑失败');
           }
       }else{
          $this->error('数据创建失败');
       }
    }

    public function dianlist(){
        $data=M('Dianlist');
        if (I('get.fid')) {
            $where['fid']=I('get.fid');
        }
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

        $list=$data->where($where)->limit($page->firstRow.','.$page->listRows)->order('sort desc,id desc')->select();
        foreach ($list as $key => $value) {
            $list[$key]['cate']=M('Diancate')->where('id='.$value['fid'])->find();
        }
        //dump($list);
        $this->assign('list',$list);
        $this->display();
    }

    public function dianlistadd(){
        $data=M('Diancate');
        $id=I('get.id');
        if($id!=''){
           $dianlist=M('Dianlist');
           $info=$dianlist->where('id='.$id)->find();
           $this->assign('info',$info);
        }
        $dianlei=$data->select();
        $this->assign('dianlei',$dianlei);
        $this->display();
    }

        public function dianlistsave(){
        $data=M('Dianlist');
        if (!I('post.title')) {
            $this->error('请填写店铺名称');
        }
        if (!I('post.cid')) {
            $this->error('请选择店铺分类');
        }
        if (!I('post.jianjie')) {
            $this->error('请填写简介');
        }
        if(!I('post.url')){
            $this->error('请填写链接地址');
        }
        $config = array(   
            'maxSize'    =>    2*1024*1024,
            'rootPath'   =>    './Uploads/dianlist/',
            'saveName'   =>    array('uniqid',''), 
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),  
            'autoSub'    =>    true,   
            'subName'    =>    array('date','Ymd'),
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info=$upload->upload();
        if($info) {                        // 上传错误提示错误信息
            $pic=__ROOT__.'/Uploads/dianlist/'.$info['filepic']['savepath'].$info['filepic']['savename'];
            $add=array(
                'title'=>I('post.title'),
                'url'=>I('post.url'),
                'fid'=>I('post.cid'),
                'pic'=>$pic,
                'status'=>I('post.status'),
                'sort'=>I('post.sort'),
                'jianjie'=>I('post.jianjie'),
            );
            if (M('Dianlist')->create($add)) {
                if (M('Dianlist')->add()) {
                    $this->success('添加成功',U('Post/dianlist'));
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

    public function dianlistedit(){
        $data=M('Dianlist');
        if (!I('post.title')) {
            $this->error('请填写店铺名称');
        }
        if (!I('post.cid')) {
            $this->error('请选择店铺分类');
        }
        if (!I('post.jianjie')) {
            $this->error('请填写简介');
        }
        $config = array(   
            'maxSize'    =>    2*1024*1024,
            'rootPath'   =>    './Uploads/dianlist/',
            'saveName'   =>    array('uniqid',''), 
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),  
            'autoSub'    =>    true,   
            'subName'    =>    array('date','Ymd'),
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info=$upload->upload();
        if ($info) {
            $pic=__ROOT__.'/Uploads/dianlist/'.$info['filepic']['savepath'].$info['filepic']['savename'];
            $f='.'.I('post.pic');
            unlink($f);
        }else{
            $pic=I('post.pic');
        }
        $save=array(
                'id'=>I('post.id'),
                'title'=>I('post.title'),
                'url'=>I('post.url'),
                'fid'=>I('post.cid'),
                'pic'=>$pic,
                'status'=>I('post.status'),
                'sort'=>I('post.sort'),
                'jianjie'=>I('post.jianjie'),
            );
            if (M('Dianlist')->create($save)) {
                if (M('Dianlist')->save()) {
                    $this->success('编辑成功',U('Post/dianlist'));
                }else{
                    $this->error('编辑失败');
                }
            }else{
                $this->error('数据创建失败');
            }
     }

     public function dianlistdel(){
        $id=I('post.id');
        $data=M('Dianlist');
        if(empty($id)){
            $this->error('ID错误');
        }
        if(false!==$data->where('id='.$id)->delete()){
           $this->success('删除成功',U('Post/dianlist'));
        }else{
           $this->error('删除失败');
        }
     } 
}


