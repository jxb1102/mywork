<?php
namespace Admin\Model;
use Think\Model;

class RecordModel extends Model {

    public function addrecord($uid,$amount,$type,$leixing,$remark){
        $ruser=M('Users')->find($uid);

                $add=array(
                    'uid'=>$ruser['uid'],
                    'uname'=>$ruser['username'],
                    'type'=>$type,
                    'leixing'=>$leixing,
                    'money'=>$amount,
                    'accountmoney'=>$ruser['money'],
                    'remark'=>$remark,
                    'addtime'=>time(),
                );
                $this->add($add);
    }

    public function addrecordscore($uid,$amount,$type,$leixing,$remark){
        $ruser=M('Users')->find($uid);

                $add=array(
                    'uid'=>$ruser['uid'],
                    'uname'=>$ruser['username'],
                    'type'=>$type,
                    'leixing'=>$leixing,
                    'money'=>$amount,
                    'accountmoney'=>$ruser['huascore'],
                    'remark'=>$remark,
                    'addtime'=>time(),
                );
                $this->add($add);
    }

}

