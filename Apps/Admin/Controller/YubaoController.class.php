<?php
namespace Admin\Controller;
use Think\Controller;
class YubaoController extends AdminController {
    public function index(){
        $this->display();
    }
    public function lists(){
    	$REQUEST    =   (array)I('request.');
        if ($REQUEST['status']) {
            $where['status']=$REQUEST['status'];
            $this->assign('status',$REQUEST['status']);
        }
        $keyword=I('get.keyword');
            if ($keyword) {
                // $where['_string']="(kdsn like '%'.$keyword.'%') OR (kdname like '%'.$keyword.'%') OR (bgname like '%'.$keyword.'%') OR (uname like '%'.$keyword.'%') OR (remark like '%'.$keyword.'%')"
                $map['kdsn']=array('like','%'.$keyword.'%');
                $map['uname']=array('like','%'.$keyword.'%');
                $map['bgname']=array('like','%'.$keyword.'%');
                $map['remark']=array('like','%'.$keyword.'%');
                $map['kdname']=array('like','%'.$keyword.'%');
                $map['kuwei']=array('like','%'.$keyword.'%');
                $map['_logic']="or";
                $where['_complex']=$map;
            }
    	$yb=M('Yubao');
    	if( isset($REQUEST['r']) ){
            $listRows = (int)$REQUEST['r'];
        }else{
            $listRows = C('LIST_ROWS') > 0 ? C('LIST_ROWS') : 10;
        }
            $total= $yb->where($where)->count();// 查询满足要求的总记录数
        $page= new \Think\Page($total, $listRows, $REQUEST);
        if($total>$listRows){
            $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
        }

        $p =$page->show();
    	$yblist=$yb->where($where)->limit($page->firstRow.','.$page->listRows)->order('status asc,id desc')->select();
    	foreach ($yblist as $key => $value) {
    		$yblist[$key]['user']=get_user($value['uid']);
            $yblist[$key]['dd']=M('Yundan')->find($value['ydid']);
    	}
        $kwlist=M('Kuwei')->where('pid>0')->order('title')->select();
        $kwlistnew=array();
        foreach ($kwlist as $key => $value) {
            $kwlist[$key]['shuliang']=M('Yubao')->where('status=2 AND kuwei="'.$value['title'].'"')->count();
            if ($kwlist[$key]['shuliang']<$value['maxshuliang']) {
                $kwlistnew[$key]=$value;
                $kwlistnew[$key]['shuliang']=$kwlist[$key]['shuliang'];
            }
        }
        $this->assign('kwlist',$kwlistnew);
        $this->assign('data',$REQUEST);
    	$this->assign('_page', $p? $p: '');
    	$this->assign('list',$yblist);
    	$this->display();
    }
    public function getyubao($id){
        $data=M('Yubao')->find($id);
        $this->ajaxReturn($data);
    }
    public function delete($id){
        if (false!==M('Yubao')->where('id='.$id)->delete()) {
            $this->success('预报信息删除成功',U('Yubao/lists'));
        }else{
            $this->error('删除失败'.M('Yubao')->getError());
        }
    }
    public function edit(){
        $data=M('Yubao');
        if (!I('post.bgname')) {
            $this->error('请填写包裹名称');
        }
        if (!I('post.shuliang')) {
            $this->error('请填写正确数量');
        }
        if (!I('post.shenbao')) {
            $this->error('请填写申报价值');
        }
        $uname=I('post.uname');
        $uinfo=M('Users')->where('username="'.$uname.'"')->find();
        if ($uinfo) {
            $uid=$uinfo['uid'];
        }else{
            $this->error('没有此用户，请确认');
        }
        $list=array(
            'id'=>I('post.id'),
            'uid'=>$uid,
            'uname'=>$uname,
            'bgname'=>I('post.bgname'),
            'shuliang'=>I('post.shuliang'),
            'shenbao'=>I('post.shenbao'),
            'remark'=>I('post.remark'),
            'weight'=>I('post.weight'),
            'bgleixing'=>I('post.bgleixing'),
            'edittime'=>time(),
        );
        if ($data->create($list)) {
            $id=$data->save();
            if (false!==$id) {
                $this->success('编辑成功',U('Yubao/lists'));
            }else{
                $this->error('编辑失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function ruku(){
        $data=M('Yubao');
        $id=I('post.id');
        $yubaoinfo=$data->where('id='.$id)->find();
        $kdsn=$yubaoinfo['kdsn'];
        $kdname=$yubaoinfo['kdname'];
        $uid=$yubaoinfo['uid'];
        $user=D('Users');
        $userinfo=$user->where('uid='.$uid)->find();
        $weight=I('post.weight');
        if (!empty($id)) {
        //     if (empty($weight)) {
        //     $this->error('请填写正确重量');
        // }
        if (!I('post.kuwei')) {
            $this->error('请先选择一个库位');
        }
        $list=array(
            'id'=>I('post.id'),
            'kuwei'=>I('post.kuwei'),
            'weight'=>I('post.weight'),
            'status'=>2,
            'rukutime'=>time(),
        );
        if ($data->create($list)) {
            if (false!==$data->save()) {
                $info=$data->find($id);
                if ($info['dgid']>0) {
                    M('DaigouGoods')->where('kdsn="'.$info['kdsn'].'"')->setField('status',5);
                    $count1=$data->where('dgid='.$info['dgid'])->count();
                    $count2=$data->where('dgid='.$info['dgid'].' AND status=2')->count();
                    if ($count1==$count2) {
                        M('Daigou')->where('id='.$info['dgid'])->setField('status',5);
                    }
                }
                $sendresult=send_mail($userinfo['email'],'','神州代运包裹入库','尊敬的'.$userinfo['username'].'：<br>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;您的包裹已存入ProcurementChina上海站仓库。<br>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;国内快递名称：'.$kdname.' 国内快递单号：'.$kdsn.'  <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请您尽快安排充值付款事宜，以便我司物流部能及时安排出运您的包裹。<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ProcurementChina官方网址是：www.procurementchina.com<br>
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;如有任何疑问，请联系在线客服或发邮件至： service@procurementchina.com <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
投诉及建议，请发送邮箱至：complaint@procurementchina.com<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;神州代运官网<br><br>若不是您本人注册ProcurementChina会员，请忽略此邮件。<br>此邮件由ProcurementChina注册系统自动发出，请勿回复本邮件。<br><br><br>Dear '.$userinfo['username'].'：<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

Your packages/documents have been stored in Shanghai warehouse.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Domestic express Name:  '.$kdname.'   tracking number of domestic express: '.$kdsn.' <br>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;               
Please make the payment as soon as possible in order to ensure your packages/documents will be shipped by our express/logistics timely.<br>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Official Website: www.procurementchina.com<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Should you have any question, please feel free to contact our online customer service or  send an email to service@procurementchina.com<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Should you have any complaints and suggestions, please email to complaint@procurementchina.com <br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ProcurementChina<br><br>
If this email arrives at you by mistake, please skip it.<br>
This is an automatic email sent out by ProcurementChina system, please do not reply.',null);
                $this->success('入库成功',U('Yubao/lists','status=2'));
            }else{
                $this->error('入库失败');
            }
        }else{
            $this->error('数据创建失败');
        }

        }else{
            $this->error('数据提交错误');
        }
    }
    public function kuaisuruku(){
        $kwlist=M('Kuwei')->where('pid>0')->order('title')->select();
        $kwlistnew=array();
        foreach ($kwlist as $key => $value) {
            $kwlist[$key]['shuliang']=M('Yubao')->where('status=2 AND kuwei="'.$value['title'].'"')->count();
            if ($kwlist[$key]['shuliang']<$value['maxshuliang']) {
                $kwlistnew[$key]=$value;
                $kwlistnew[$key]['shuliang']=$kwlist[$key]['shuliang'];
            }
        }
        $users=M('Users')->order('username')->select();
        $this->assign('selectusers',$users);
        $this->assign('kwlist',$kwlistnew);
        $this->display();
    }
    public function getbykdsn($kdsn){
        $data=M('Yubao')->where('kdsn="'.$kdsn.'"')->find();
        if ($data) {
            if ($data['status']>1) {
                $this->ajaxReturn(2);
            }else{
                $uname=$data['uname'];
                $uid=$data['uid'];
                $kuwei=M('Kuwei')->where('uid='.$uid)->order('title desc')->select();
                if ($kuwei) {
                    foreach ($kuwei as $key => $value) {
                        if ($value['shuliang']<$value['maxshuliang']) {
                            $kuweiselect=M('Kuwei')->where('id='.$value['id'])->find();
                        }
                        
                    }
                    if (empty($kuweiselect)) {
                        foreach ($kuwei as $key => $value) {
                            $kuweiselect=M('Kuwei')->where('uid=0 AND pid>0')->order('title asc')->find();
                        }
                    }
                }else{
                    $kuweiselect=M('Kuwei')->where('uid=0 AND pid>0')->order('title asc')->find();
                }
                $data['kuwei']=$kuweiselect['title'];
                //dump($data);
                $this->ajaxReturn($data);
            }
        }else{
            $this->ajaxReturn(1);
        }
        
    }
    public function kuaisurukuadd(){
        $id=I('post.id');
        $yb=M('Yubao');
        if ($id) {
            // if (!I('post.weight')) {
            //     $this->error('请输入重量');
            // }
            if (!I('post.kuwei')) {
                $this->error('请选择库位');
            }
            $kuweii=M('Kuwei')->where('title="'.I('post.kuwei').'"')->find();
            $yirucount=M('Yubao')->where('kuwei="'.I('post.kuwei').'" AND status=2')->count();
            if ($yirucount>=$kuweii['maxshuliang']) {
                $this->error('库位包裹已达到库位上限，请更改库位');
            }
            $data=array(
                'id'=>$id,
                'kuwei'=>I('post.kuwei'),
                'shuliang'=>I('post.shuliang'),
                'weight'=>I('post.weight'),
                'rukutime'=>time(),
                'status'=>2,
            );
            if ($yb->create($data)) {
                if (false!==$yb->save()) {
                    $info=$yb->find($id);
                if ($info['dgid']>0) {
                    M('DaigouGoods')->where('kdsn="'.$info['kdsn'].'"')->setField('status',5);
                    $count1=$yb->where('dgid='.$info['dgid'])->count();
                    $count2=$yb->where('dgid='.$info['dgid'].' AND status=2')->count();
                    if ($count1==$count2) {
                        M('Daigou')->where('id='.$info['dgid'])->setField('status',5);
                    }
                }
                    $this->success('入库成功');
                }else{
                    $this->error('入库失败');
                }
            }else{
                $this->error('数据创建失败');
            }
        }else{
            // if (!I('post.weight')) {
            //     $this->error('请输入重量');
            // }
            if (!I('post.kdname')) {
                $this->error('请输入快递公司');
            }
            if (!I('post.uname')) {
                $this->error('请输入用户名');
            }
            if (!I('post.bgname')) {
                $this->error('请输入包裹名称');
            }
            if (!I('post.kuwei')) {
                $this->error('请选择库位');
            }
            if (!I('post.kdsn')) {
                $this->error('快递单号错误');
            }
            $kdsn=trim(I('post.kdsn'));
            $kuweii=M('Kuwei')->where('title="'.I('post.kuwei').'"')->find();
            $yirucount=M('Yubao')->where('kuwei="'.I('post.kuwei').'" AND status=2')->count();
            if ($yirucount>=$kuweii['maxshuliang']) {
                $this->error('库位包裹已达到库位上限，请更改库位');
            }
            $yiruku=$yb->where('kdsn="'.$kdsn.'"')->find();
            if ($yiruku) {
                $this->error('快递单号已经存在，请确认快递单号');
            }
            $user=M('Users');
            $uinfo=$user->where('username="'.I('post.uname').'"')->find();
            if (empty($uinfo)) {
                $this->error('该用户不存在,请确认用户名');
            }
            $data=array(
                'kdsn'=>$kdsn,
                'kdname'=>I('post.kdname'),
                'bgname'=>I('post.bgname'),
                'shuliang'=>I('post.shuliang'),
                'weight'=>I('post.weight'),
                'uname'=>I('post.uname'),
                'uid'=>$uinfo['uid'],
                'kuwei'=>I('post.kuwei'),
                'addtime'=>time(),
                'edittime'=>time(),
                'rukutime'=>time(),
                'status'=>2,
            );
            if ($yb->create($data)) {
                $ybid=$yb->add();
                if ($ybid>0) {
                    $this->success('添加入库成功');
                }else{
                    $this->error('添加入库失败');
                }
            }else{
                $this->error('数据创建失败');
            }
        }
    }
    public function store(){
        $data=M('Kuwei');
            $id=I('get.id');
            if ($id) {
                $info=$data->find($id);
                $this->assign('info',$info);
            }
            $kwid=I('get.kwid');
            if ($kwid) {
                $list=$data->where('pid='.$kwid)->order('title')->select();
                foreach ($list as $key => $value) {
                    $list[$key]['shuliang']=M('Yubao')->where('status=2 AND kuwei="'.$value['title'].'"')->count();
                }
                $this->assign('kwid',$kwid);
                $this->assign('list',$list);
            }else{
                $list=$data->where('pid=0')->order('title')->select();
                foreach ($list as $key => $value) {
                    $kwtitle=$data->where('pid='.$value['id'])->getField('title',true);
                    $kwhere['kuwei']=array('in',$kwtitle);
                    $kwhere['status']=2;
                    $list[$key]['shuliang']=M('Yubao')->where($kwhere)->count();
                }
                $this->assign('list',$list);
            }
            $kwlist=$data->where('pid=0')->select();
            $this->assign('kwlist',$kwlist);
            $this->display();
    }
    public function storeadd(){
        if (!I('post.title')) {
            $this->error('名称必须填写');
        }
        $data=array(
            'title'=>I('post.title'),
            'maxshuliang'=>I('post.shuliang'),
            'pid'=>I('post.pid'),
        );
        if (M('Kuwei')->create($data)) {
            if (M('Kuwei')->add()) {
                $this->success('添加成功');
            }else{
                $this->error('添加失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function storesave(){
        if (!I('post.title')) {
            $this->error('名称必须填写');
        }
        $data=array(
            'id'=>I('post.id'),
            'title'=>I('post.title'),
            'maxshuliang'=>I('post.shuliang'),
            'pid'=>I('post.pid'),
        );
        if (M('Kuwei')->create($data)) {
            if (M('Kuwei')->save()) {
                $this->success('编辑成功');
            }else{
                $this->error('编辑失败');
            }
        }else{
            $this->error('数据创建失败');
        }
    }
    public function storedel(){
        $id=I('post.id');
        $info=M('Kuwei')->find($id);
        if ($info) {
        if ($info['pid']==0) {
            $list=M('Kuwei')->where('pid='.$id)->select();
            if ($list) {
                $this->error('请删除此货架上的库位，再删除此货架');
            }else{
                M("Kuwei")->where('id='.$id)->delete();
                $this->success('删除成功');
            }
        }else{
            $list=M('Yubao')->where('kuwei="'.$info['title'].'" AND status=2')->select();
            if ($list) {
                $this->error('此货架有货物，暂时不能删除');
            }else{
                M("Kuwei")->where('id='.$id)->delete();
                $this->success('删除成功');
            }
        }
        }else{
            $this->error('信息错误');
        }
    }
    public function exportexcel(){
        if (I('request.status')) {
            $where['status']=I('request.status');
        }
        $keyword=I('get.keyword');
            if ($keyword) {
                // $where['_string']="(kdsn like '%'.$keyword.'%') OR (kdname like '%'.$keyword.'%') OR (bgname like '%'.$keyword.'%') OR (uname like '%'.$keyword.'%') OR (remark like '%'.$keyword.'%')"
                $map['kdsn']=array('like','%'.$keyword.'%');
                $map['uname']=array('like','%'.$keyword.'%');
                $map['bgname']=array('like','%'.$keyword.'%');
                $map['remark']=array('like','%'.$keyword.'%');
                $map['kdname']=array('like','%'.$keyword.'%');
                $map['kuwei']=array('like','%'.$keyword.'%');
                $map['_logic']="or";
                $where['_complex']=$map;
            }
        $list=M('Yubao')->where($where)->order('status asc,id desc')->select();
        foreach ($list as $key => $value) {
            $list[$key]['user']=get_user($value['uid']);
            $list[$key]['leixing']=getbgleixing($value['bgleixing']);
            $list[$key]['zhuangtai']=getyubaostatus($value['status']);
            if ($value['chaibao']==1) {
                $list[$key]['baozhuang']="拆包";
            }else{
                $list[$key]['baozhuang']="不拆包";
            }
        }
        if (empty($list)){
            $this->error('没有可以导出的内容');
        }
        import("Org.Util.PHPExcel");
        $fileName = $this->fileName;
        $fileName = empty($fileName)?'预报单导出-'.date('Y-m-d',time()):$fileName;
        
        $PHPExcel = new \PHPExcel();
        
        //设置基本信息
        $PHPExcel->getProperties()->setCreator("zzqss")
        ->setLastModifiedBy("zzqss")
        ->setTitle("郑州全搜索")
        ->setSubject("全搜索")
        ->setDescription("")
        ->setKeywords("全搜索")
        ->setCategory("");
        $PHPExcel->setActiveSheetIndex(0);
        $PHPExcel->getActiveSheet()->setTitle($fileName);
        
        //存储Excel数据源到其他工作薄
        $PHPExcel->createSheet();
 
        
        //填入主标题
        $PHPExcel->getActiveSheet()->setCellValue('A1', C('DOMAIN_TITLE').'预报入库单');
        //填入副标题
        $PHPExcel->getActiveSheet()->setCellValue('A2', '导出日期：'.date("Y/m/d",time()));
        //$PHPExcel->getActiveSheet()->setCellValue('H2', '导出日期：'.date("Y/m/d",time()));
        
        //填入表头
        $PHPExcel->getActiveSheet()->setCellValue('A3', '序号');
        $PHPExcel->getActiveSheet()->setCellValue('B3', '用户');
        $PHPExcel->getActiveSheet()->setCellValue('C3', '快递单号');
        $PHPExcel->getActiveSheet()->setCellValue('D3', '快递名称');
        $PHPExcel->getActiveSheet()->setCellValue('E3', '包裹名称');
        $PHPExcel->getActiveSheet()->setCellValue('F3', '申报价值');
        $PHPExcel->getActiveSheet()->setCellValue('G3', '包裹类型');
        $PHPExcel->getActiveSheet()->setCellValue('H3', '拆包装');
        $PHPExcel->getActiveSheet()->setCellValue('I3', '备注');
        $PHPExcel->getActiveSheet()->setCellValue('J3', '状态');
        $PHPExcel->getActiveSheet()->setCellValue('K3', '时间');
        
        //填入列表
        $k = 1;
        foreach ($list as $key => $value){
            $k++;
            
            $PHPExcel->getActiveSheet()->setCellValue('A'.($key+4), ($key+1));
            $PHPExcel->getActiveSheet()->setCellValue('B'.($key+4), $value['user']['username']);
            $PHPExcel->getActiveSheet()->setCellValue('C'.($key+4), ' '.$value['kdsn']);
            //$PHPExcel->getActiveSheet()->getStyle('B'.($key+4))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
            $PHPExcel->getActiveSheet()->setCellValue('D'.($key+4), $value['kdname']);

            $PHPExcel->getActiveSheet()->setCellValue('E'.($key+4), $value['bgname']);
            
            $PHPExcel->getActiveSheet()->setCellValue('F'.($key+4), $value['shenbao']);
            $PHPExcel->getActiveSheet()->getStyle('E'.($key+4))->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
            $PHPExcel->getActiveSheet()->setCellValue('G'.($key+4), $value['leixing']);
            $PHPExcel->getActiveSheet()->setCellValue('H'.($key+4), $value['baozhuang']);

            $PHPExcel->getActiveSheet()->setCellValue('I'.($key+4), $value['remark']);
            
            $PHPExcel->getActiveSheet()->setCellValue('J'.($key+4), $value['zhuangtai']);
            $PHPExcel->getActiveSheet()->setCellValue('K'.($key+4), date('Y-m-d',$value['addtime']));
        
            //设置每一行行高
            $PHPExcel->getActiveSheet()->getRowDimension($key+4)->setRowHeight(20);
        }

        // $PHPExcel->getActiveSheet()->setCellValue('A'.($k+3), '序号');
        // $PHPExcel->getActiveSheet()->setCellValue('B'.($k+3), '快递单号');
        // $PHPExcel->getActiveSheet()->setCellValue('C'.($k+3), '快递名称');
        // $PHPExcel->getActiveSheet()->setCellValue('D'.($k+3), '包裹名称');
        // $PHPExcel->getActiveSheet()->setCellValue('E'.($k+3), '品牌');
        // $PHPExcel->getActiveSheet()->setCellValue('F'.($k+3), '单位');
        // $PHPExcel->getActiveSheet()->setCellValue('G'.($k+3), '数量');
        // $PHPExcel->getActiveSheet()->setCellValue('H'.($k+3), '单价');
        // $PHPExcel->getActiveSheet()->setCellValue('I'.($k+3), $list['jine']);
        // $PHPExcel->getActiveSheet()->getStyle('I'.($k+3))->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_NUMBER_00);
        // $PHPExcel->getActiveSheet()->setCellValue('A'.($k+4), '备注：'.$list['cgbeizhu']);
        // $PHPExcel->getActiveSheet()->setCellValue('A'.($k+5), '经办人：'.$list['add_by']);
        // $PHPExcel->getActiveSheet()->setCellValue('D'.($k+5), '采购签字：_______________         验收签字：_______________        ');
        
        //合并单元格
        $PHPExcel->getActiveSheet()->mergeCells('A1:K1');
        $PHPExcel->getActiveSheet()->mergeCells('A2:K2');
        // $PHPExcel->getActiveSheet()->mergeCells('A'.($k+4).':I'.($k+4));
        // $PHPExcel->getActiveSheet()->mergeCells('A'.($k+5).':C'.($k+5));
        // $PHPExcel->getActiveSheet()->mergeCells('D'.($k+5).':I'.($k+5));
        
        //设置单元格宽度
        $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(12);
        $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(9);
        $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(17);
        $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(9);
        $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(9);
        $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(7);
        $PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(17);
        $PHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(7);
        $PHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(11);

        
        //设置表头行高
        $PHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(35);
        $PHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(22);
        $PHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(20);
        //$PHPExcel->getActiveSheet()->getRowDimension(($k+3))->setRowHeight(20);
        // $PHPExcel->getActiveSheet()->getRowDimension(($k+4))->setRowHeight(22);
        // $PHPExcel->getActiveSheet()->getRowDimension(($k+5))->setRowHeight(22);
        
        //设置字体样式
        $PHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setName('微软雅黑');
        $PHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
        $PHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $PHPExcel->getActiveSheet()->getStyle('A3:K3')->getFont()->setBold(true);
        $PHPExcel->getActiveSheet()->getStyle('A'.($k+3).':K'.($k+3))->getFont()->setBold(true);
            
        $PHPExcel->getActiveSheet()->getStyle('A2:K2')->getFont()->setSize(10);
        
        $PHPExcel->getActiveSheet()->getStyle('A3:K'.($k+5))->getFont()->setSize(10);
        //设置居中
        //$PHPExcel->getActiveSheet()->getStyle('A1:I'.($k+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('A1:K1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('H2:K2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $PHPExcel->getActiveSheet()->getStyle('A3:K3')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('A'.($k+3).':K'.($k+3))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $PHPExcel->getActiveSheet()->getStyle('D'.($k+5))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        //所有垂直居中
        $PHPExcel->getActiveSheet()->getStyle('A1:K'.($k+5))->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            
        //设置单元格边框
        $PHPExcel->getActiveSheet()->getStyle('A3:K'.($k+2))->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
        
        //设置自动换行
        $PHPExcel->getActiveSheet()->getStyle('A3:K'.($k+3))->getAlignment()->setWrapText(true);

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
    public function zhinengruku(){
        $kwlist=M('Kuwei')->where('pid>0')->order('title')->select();
        $kwlistnew=array();
        foreach ($kwlist as $key => $value) {
            $kwlist[$key]['shuliang']=M('Yubao')->where('status=2 AND kuwei="'.$value['title'].'"')->count();
            if ($kwlist[$key]['shuliang']<$value['maxshuliang']) {
                $kwlistnew[$key]=$value;
                $kwlistnew[$key]['shuliang']=$kwlist[$key]['shuliang'];
            }
        }
        $users=M('Users')->order('username')->select();
        $this->assign('selectusers',$users);
        $this->assign('kwlist',$kwlistnew);
        $this->display();
    } 
}