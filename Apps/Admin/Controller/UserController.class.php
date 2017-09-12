<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Page;
class UserController extends AdminController {
    public function index(){
        $REQUEST    =   (array)I('request.');
    	$mem=M('Users');
    		$keyword=I('get.keyword');
    		if ($keyword) {
                $where['username']=array('like','%'.$keyword.'%');
                $where['nickname']=array('like','%'.$keyword.'%');
                $where['mobile']=array('like','%'.$keyword.'%');
                $where['email']=array('like','%'.$keyword.'%');
                $where['_logic']="or";
    		}
        if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
        $total= $mem->where($where)->count();// 查询满足要求的总记录数
        $page= new \Think\Page($total, $listRows, $REQUEST);
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }
        $p =$page->show();
        $this->assign('_page', $p? $p: '');
    	$memlist=$mem->where($where)->limit($page->firstRow.','.$page->listRows)->order('uid desc')->select();

        $utype=M('Usertype');
        $utypelist=$utype->where('special=1')->select();
        $this->assign('utypelist',$utypelist);
    		$this->assign('list',$memlist);
            $this->assign('page',$show);
        	$this->display();


    }
    public function getuser(){
        $uid=I('post.id');
        $user=M('Users');
        $info=$user->find($uid);
        //dump($info);
        $this->ajaxReturn($info);
    }
    public function useradd(){
        $mem=M('Member');
        $group=M('AuthGroup');
        $aga=M('AuthGroupAccess');
        if (!I('post.username')) {
                $this->error('请填写用户名');
            }
            if (!I('post.password')) {
                $this->error('请填写密码');
            }
            if (!I('post.nickname')) {
                $this->error('请填写显示名称');
            }
            if (!I('post.groupid')) {
                $this->error('请选择用户组');
            }
            if ($mem->where('username="'.I('post.username').'"')->find()) {
                $this->error('用户名邮箱已经存在');
            }
            $data=array(
                'username'=>I('post.username'),
                'password'=>md5(I('post.password')),
                'nickname'=>I('post.nickname'),
                'email'=>I('post.username'),
                'mobile'=>I('post.mobile'),
                'status'=>I('post.status'),
                'isadmin'=>1,
                'reg_time'=>time(),
            );
            if ($mem->create($data)) {
                $id=$mem->add();
                if ($id>0) {
                    $agadata['uid']=$id;
                    $agadata['group_id']=I('post.groupid');
                    $aga->add($agadata);
                    $this->success('添加成功',U('Member/index'));
                }else{
                    $this->error('添加失败');
                }
            }else{
                $this->error('数据创建失败');
            }
    }
    public function useredit(){
        $mem=M('Users');
        if (!I('post.nickname')) {
                $this->error('请填写昵称');
        }
            $uwhere['nickname']=I('post.nickname');
            $uwhere['uid']=array('neq',I('post.uid'));
            if ($mem->where($uwhere)->find()) {
                $this->error('用户昵称已经存在，请更改');
            }
            $uwhere1['email']=I('post.email');
            $uwhere1['uid']=array('neq',I('post.uid'));
            if ($mem->where($uwhere1)->find()) {
                $this->error('用户邮箱已经存在，请更改');
            }
            if (!I('post.password')) {
                $password=$mem->where('uid='.I('post.uid'))->getField('password');
            }else{
                $password=md5(I('post.password'));
            }
            $data=array(
                'uid'=>I('post.uid'),
                'password'=>$password,
                'nickname'=>I('post.nickname'),
                'email'=>I('post.email'),
                'mobile'=>I('post.mobile'),
                'status'=>I('post.status'),
                'ziliao'=>I('post.ziliao'),
                'utype'=>I('post.utype'),
                'huascore'=>I('post.huascore')+I('post.zengscore')
            );
            if ($mem->create($data)) {
                $id=$mem->save();
                if ($id!==false) {
                    $this->success('编辑成功',U('User/index'));
                }else{
                    $this->error('编辑失败');
                }
            }else{
                $this->error('数据创建失败');
            }
    }
    public function userdel(){
        $id=I('post.id');
        $info=M('Yubao')->where('uid='.$id)->find();
        if ($info) {
            M('Users')->where('uid='.$id)->setField('status',0);
            $this->success('用户已经有预报信息，已设置成禁用',U('User/index'));
        }else{
           $del=M('Users')->where('uid='.$id)->delete();
            if (false!==$del) {
                $this->success('用户删除成功',U('User/index'));
            }else{
                $this->error('删除失败'.M('Users')->getError());
        }
        }
        
    }
    public function usertype(){
        $type=M('Usertype');
        $id=I('get.id');
            if ($id) {
                $info=$type->find($id);
                $this->assign('info',$info);
            }
            $list=$type->where($where)->order('id asc')->select();
            $this->assign('list',$list);
        $this->display();
    }
    public function usertypeadd(){
        $type=M('Usertype');
        if (!I('post.name')) {
                $this->error('请填写名称');
            }
            if (!I('post.special')) {
                if (!I('post.minscore')) {
                $this->error('请填写最小积分');
            }
            if (!I('post.maxscore')) {
                $this->error('请填写最大积分');
            }
            $data=array(
                'name'=>I('post.name'),
                'minscore'=>I('post.minscore'),
                'maxscore'=>I('post.maxscore'),
                'status'=>I('post.status'),
                'special'=>0,
            );
            }else{
                $data=array(
                'name'=>I('post.name'),
                'minscore'=>0,
                'maxscore'=>0,
                'status'=>I('post.status'),
                'special'=>1,
            );
            }
            if ($type->create($data)) {
                $id=$type->add();
                if ($id>0) {
                    $this->success('添加成功',U('User/usertype'));
                }else{
                    $this->error('添加失败');
                }
            }else{
                $this->error('数据创建失败');
            }
    }
    public function usertypeedit(){
        $type=M('Usertype');
        if (!I('post.name')) {
                $this->error('请填写名称');
            }
            if (!I('post.special')) {
                if (!I('post.minscore')) {
                $this->error('请填写最小积分');
            }
            if (!I('post.maxscore')) {
                $this->error('请填写最大积分');
            }
        $data=array(
                'id'=>I('post.id'),
                'name'=>I('post.name'),
                'minscore'=>I('post.minscore'),
                'maxscore'=>I('post.maxscore'),
                'status'=>I('post.status'),
                'special'=>0,
            );
            }else{
                $data=array(
                'id'=>I('post.id'),
                'name'=>I('post.name'),
                'minscore'=>0,
                'maxscore'=>0,
                'status'=>I('post.status'),
                'special'=>1,
            );
            }
            
            if ($type->create($data)) {
                $id=$type->save();
                if ($id!==false) {
                    $this->success('编辑成功',U('User/usertype'));
                }else{
                    $this->error('编辑失败');
                }
            }else{
                $this->error('数据创建失败');
            }
    }
    public function usertypedel(){
        $id=I('post.id');
        $del=M('Usertype')->where('id='.$id)->delete();
        if (false!==$del) {
            $this->success('用户等级删除成功',U('User/usertype'));
        }else{
            $this->error('删除失败'.M('Usertype')->getError());
        }
    }

    public function xinyong(){
        $xinyong=I('post.xinyong');
        $uid=I('post.uid');
        if ((int)$xinyong<0) {
            $this->error('信用额度错误');
        }
        if (empty($uid)) {
            $this->error('用户信息错误');
        }
        M('Users')->where('uid='.$uid)->setField('xinyong',$xinyong);
        $this->success('信用额度设置成功');

    }
    
    public function baoxian(){
        $data=M('Usertype');
        $type=$data->select();
        //dump($type);
        $this->assign('type',$type);
        $baoxian=M('Baoxian');
        $list=$baoxian->select();
        $this->assign('list',$list);
        if(I('get.id')){
            $id=I('get.id');
            $info=$baoxian->where('id='.$id)->find();
            //dump($info);
            $this->assign('info',$info);
        }
        $this->display();
    }

    public function baoxianadd(){
        if(I('post.time')==''){
            $this->error('保险时间不能为空');
        }
        if(I('post.money')==''){
            $this->error('保险额度不能为空');
        }
        if(I('post.nomoney')==''){
            $this->error('非保险额度不能为空');
        }
        if(I('post.biv')==''){
            $this->error('保险比率不能为空');
        }
        if(I('post.huascore')==''){
            $this->error('每次积分消费额度不能为空');
        }
        $baoxian=M('Baoxian');
        if (I('post.rid')) {
            $utype=M('Usertype')->find(I('post.rid'));
        }
        $data=array(
            'rank'=>$utype['name'],
            'rid'=>I('post.rid'),
            'time'=>I('post.time'),
            'nomoney'=>I('post.nomoney'),
            'money'=>I('post.money'),
            'biv'=>I('post.biv'),
            'huascore'=>I('post.huascore')
            );
        //dump($data);exit;
        if ($baoxian->create($data)) {
            if (false!==$baoxian->add()) {
                $this->success('保险添加成功',U('User/baoxian'));
            }else{
                $this->error('添加保险失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }

    public function baoxiandel(){
        $id=I('post.id');
        if (empty($id)) {
            $this->error('ID错误');
        }
        $info=M('Baoxian')->find($id);
        if(false!==M('Baoxian')->where('id='.$id)->delete()){
            $this->success('删除成功',U('User/baoxian'));
        }else{
            $this->error('删除失败');
        }
    }

    public function baoxianedit(){
        $baoxian=M('Baoxian');
        $utype=M('Usertype')->find(I('post.rid'));
        $data=array(
            'id'=>I('post.id'),
            'rank'=>$utype['name'],
            'rid'=>I('post.rid'),
            'time'=>I('post.time'),
            'nomoney'=>I('post.nomoney'),
            'money'=>I('post.money'),
            'biv'=>I('post.biv'),
            'huascore'=>I('post.huascore')
            );
        if ($baoxian->create($data)) {
            if (false!==$baoxian->save()) {
                $this->success('保险编辑成功',U('User/baoxian'));
            }else{
                $this->error('编辑保险失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }

    public function tjuser(){

        $this->display();
    }

    public function tjuseradd(){
        $users=M('Users');
        $username=I('post.username');
        $email=i('post.email');
        if($users->where('username="'.$username.'"')->find()){
            $this->error('用户名已经存在');
        }
        if($users->where('email="'.$email.'"')->find()){
            $this->error('用户邮箱已经存在');
        }
        $data=array(
              'username'=>$username,
              'nickname'=>$username,
              'password'=>md5(I('post.password')),
              'email'=>$email,
              'reg_ip'=>get_client_ip(1),
              'reg_time'=>time()
            );
        if($users->create($data)){
            $info=$users->add();
            if ($info>0) {
                $users->where("uid=$info")->setInc('score',500);//注册完成送积分500
                $users->where("uid=$info")->setInc('huascore',500);
                D('Record')->addrecordscore($info,'500',1,8,'注册送积分');
                $this->success('用户添加成功',U('User/index'));
            }else{
                $this->error('用户添加失败');
            }
        } 
    }

    public function outputexcel(){
        $list=M('Users')->order('uid asc')->select();
        foreach ($list as $key => $value) {
            $list[$key]['dengji']=getusertype($value['score']);
            $list[$key]['status']=getstatus($value['status']);
            if ($value['ziliao']>=1) {
                $list[$key]['pz']="有";
            }else{
                $list[$key]['pz']="无";
            }
        }
        if (empty($list)){
            $this->error('没有可以导出的内容');
        }
        import("Org.Util.PHPExcel");
        $fileName = $this->fileName;
        $fileName = empty($fileName)?'神舟代运用户导出-'.date('Y-m-d',time()):$fileName;
        
        $PHPExcel = new \PHPExcel();
        
        //设置基本信息
        $PHPExcel->getProperties()->setCreator("Procurementchina")
        ->setLastModifiedBy("Procurementchina")
        ->setTitle("神舟代运")
        ->setSubject("神舟代运")
        ->setDescription("")
        ->setKeywords("神舟代运")
        ->setCategory("");
        $PHPExcel->setActiveSheetIndex(0);
        $PHPExcel->getActiveSheet()->setTitle($fileName);
        
        //存储Excel数据源到其他工作薄
        $PHPExcel->createSheet();
 
        
        //填入主标题
        $PHPExcel->getActiveSheet()->setCellValue('A1', C('DOMAIN_TITLE').'用户列表');
        //填入副标题
        $PHPExcel->getActiveSheet()->setCellValue('A2', '导出日期：'.date("Y/m/d",time()));
        //$PHPExcel->getActiveSheet()->setCellValue('H2', '导出日期：'.date("Y/m/d",time()));
        
        //填入表头
        $PHPExcel->getActiveSheet()->setCellValue('A3', '序号');
        $PHPExcel->getActiveSheet()->setCellValue('B3', '账户名');
        $PHPExcel->getActiveSheet()->setCellValue('C3', '姓名');
        $PHPExcel->getActiveSheet()->setCellValue('D3', '邮箱');
        $PHPExcel->getActiveSheet()->setCellValue('E3', '手机');
        $PHPExcel->getActiveSheet()->setCellValue('F3', '积分');
        $PHPExcel->getActiveSheet()->setCellValue('G3', 'qq');
        $PHPExcel->getActiveSheet()->setCellValue('H3', '身份凭证');
        $PHPExcel->getActiveSheet()->setCellValue('I3', '余额');
        $PHPExcel->getActiveSheet()->setCellValue('J3', '状态');
        $PHPExcel->getActiveSheet()->setCellValue('K3', '用户组');
        $PHPExcel->getActiveSheet()->setCellValue('L3', '注册时间');
        $PHPExcel->getActiveSheet()->setCellValue('M3', '最后登录时间');
        
        //填入列表
        $k = 1;
        foreach ($list as $key => $value){
            $k++;
            
            $PHPExcel->getActiveSheet()->setCellValue('A'.($key+4), ($key+1));
            $PHPExcel->getActiveSheet()->setCellValue('B'.($key+4), $value['username']);
            $PHPExcel->getActiveSheet()->setCellValue('C'.($key+4), ' '.$value['xingming']);
            //$PHPExcel->getActiveSheet()->getStyle('B'.($key+4))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $PHPExcel->getActiveSheet()->setCellValue('D'.($key+4), $value['email']);

            $PHPExcel->getActiveSheet()->setCellValue('E'.($key+4), $value['mobile']);
            
            $PHPExcel->getActiveSheet()->setCellValue('F'.($key+4), $value['score']);
            $PHPExcel->getActiveSheet()->setCellValue('G'.($key+4), $value['qq']);
            $PHPExcel->getActiveSheet()->setCellValue('H'.($key+4), $value['pz']);

            $PHPExcel->getActiveSheet()->setCellValue('I'.($key+4), $value['money']);
            
            $PHPExcel->getActiveSheet()->setCellValue('J'.($key+4), $value['status']);
            $PHPExcel->getActiveSheet()->setCellValue('K'.($key+4), $value['dengji']);
            $PHPExcel->getActiveSheet()->setCellValue('L'.($key+4), date('Y-m-d',$value['reg_time']));
            $PHPExcel->getActiveSheet()->setCellValue('M'.($key+4), date('Y-m-d',$value['last_login_time']));
            //设置每一行行高
            $PHPExcel->getActiveSheet()->getRowDimension($key+4)->setRowHeight(20);
        }

        //合并单元格
        $PHPExcel->getActiveSheet()->mergeCells('A1:M1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:M2');
        
        //设置单元格宽度
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(13);
        $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
        $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
        $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
        $PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(7);
        $PHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(7);
        $PHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(11);
        $PHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $PHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);

        
        //设置表头行高
        $PHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(35);
        $PHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(22);
        $PHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(20);
        
        //设置字体样式
        $PHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('微软雅黑');
        $PHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
        $PHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $PHPExcel->getActiveSheet()->getStyle('A3:M3')->getFont()->setBold(true);
        $PHPExcel->getActiveSheet()->getStyle('A'.($k+3).':K'.($k+3))->getFont()->setBold(true);
            
        $PHPExcel->getActiveSheet()->getStyle('A2:M2')->getFont()->setSize(10);
        
        $PHPExcel->getActiveSheet()->getStyle('A3:M'.($k+5))->getFont()->setSize(10);
        //设置居中
        //$PHPExcel->getActiveSheet()->getStyle('A1:I'.($k+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('H2:M2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $PHPExcel->getActiveSheet()->getStyle('A3:M3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('A'.($k+3).':M'.($k+3))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('D'.($k+5))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        //所有垂直居中
        $PHPExcel->getActiveSheet()->getStyle('A1:M'.($k+5))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            
        //设置单元格边框
        $PHPExcel->getActiveSheet()->getStyle('A3:M'.($k+2))->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        
        //设置自动换行
        $PHPExcel->getActiveSheet()->getStyle('A3:M'.($k+3))->getAlignment()->setWrapText(true);

        //页边距
        $sheet = $PHPExcel->getActiveSheet();
        $pageMargins = $sheet->getPageMargins();
       //$margin = '0.5/0.7'; 
        $pageMargins->setTop(0);    //上边距
        $pageMargins->setBottom(0.21); //下
        $pageMargins->setLeft(0.2);   //左
        $pageMargins->setRight(0.2);  //右    

        
        // admin_log('导出采购单编号'.$list['cgsn'].'的Excel文件');
        //保存为2003格式
        $objWriter = new \PHPExcel_Writer_Excel5($PHPExcel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        
        //多浏览器下兼容中文标题
        $encoded_filename = urlencode($fileName);
        $ua = $_SERVER["HTTP_USER_AGENT"];
        if (preg_match("/MSIE/", $ua)) {
            header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
        } else if (preg_match("/Firefox/", $ua)) {
            header('Content-Disposition: attachment; filename*="utf8\'\'' . $fileName . '.xls"');
        } else {
            header('Content-Disposition: attachment; filename="' . $fileName . '.xls"');
        }
        
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
    }

}