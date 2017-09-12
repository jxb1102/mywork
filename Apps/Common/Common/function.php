<?php
header("Content-type:text/html;charset=utf-8");
function p($data){
    echo '<pre>';
    print_r($data);
}
function authcheck($rule,$uid,$relation='or',$tr,$fa){
    if(in_array($uid,C('ADMINISTRATOR'))){    
        return $tr;    //如果是，则直接返回真值，不需要进行权限验证
    }else{
	import('Org.Util.Auth');
	$auth=new Auth();
	return $auth->check($rule,$uid,$relation)?$tr:$fa;
}
}

function get_username($uid){
    $data=M('Users');
    $username=$data->where('uid='.$uid)->getField('username');
    return $username;
}
function get_nickname($uid){
    $data=M('Users');
    $username=$data->where('uid='.$uid)->getField('nickname');
    return $username;
}
function get_user($uid){
    $field="uid,username,nickname,touxiang,sex,email,score";
    $data=M('Users');
    $user=$data->where('uid='.$uid.' AND status=1')->field($field)->find();
    return $user;
}
function getbgleixing($bgleixing){
    if ($bgleixing==0) {
        return '普通货物';
    }elseif($bgleixing==1){
        return '敏感货物';
    }elseif($bgleixing==2){
        return '带电池货物';
    }
}
function getstatus($status){
    if ($status==1) {
        return '启用';
    }else{
        return "禁用";
    }
}
function getyubaostatus($status){
    if ($status==1) {
        return '在途中';
    }elseif($status==2){
        return '已入库';
    }elseif($status==3){
        return '待出库';
    }elseif($status==4){
        return '已出库';
    }
}

function get_pinglun($id){
    $data=M('Pinglun');
    $list=$data->where('postid='.$id.' AND pid=0')->order('id asc')->select();
    foreach ($list as $key => $value) {
        $listson=$data->where('pid='.$value['id'])->select();
        foreach ($listson as $ke => $va) {
            $listson[$ke]['user']=get_user($va['uid']);
        }
        $list[$key]['son']=$listson;
        $list[$key]['username']=get_username($value['uid']);
    }
    return $list;
}
function get_post_img($str){
 preg_match( "<img.*src=[\"](.*?)[\"].*?>", $str, $regs ) ;
//使用获取第一幅图像地址
return $obj = $regs[1] ;
}

function getusertype($score){
    $data=M('Usertype');
    $where['minscore']=array('elt',$score);
    $where['maxscore']=array('egt',$score);
    $list=$data->where($where)->getField('name');
    return $list;
}
function getusertypeid($score){
    $data=M('Usertype');
    $where['minscore']=array('elt',$score);
    $where['maxscore']=array('egt',$score);
    $list=$data->where($where)->getField('id');
    return $list;
}
function get_usertype($typeid){
    $data=M('Usertype');
    $where['id']=$typeid;
    $list=$data->where($where)->getField('name');
    return $list;
}
function get_usertypeid($typeid){
    $data=M('Usertype');
    $where['id']=$typeid;
    $list=$data->where($where)->getField('id');
    return $list;
}
function getstate($state){
    $data=M('State');
    $list=$data->where('id='.$state)->getField('name');
    return $list;
}
function getrecordleixing($status){
    if ($status==1) {
        return '线下充值';
    }elseif($status==2){
        return '支付运费';
    }elseif($status==3){
        return '财付通充值';
    }elseif($status==4){
        return '代充值退款';
    }elseif($status==5){
        return '管理充值';
    }elseif ($status==6) {
        return 'PayPal支付';
    }elseif($status==7){
        return '支付代购';
    }elseif ($status==9) {
    	return '支付宝充值';
    }
}

// function getcoupon($status){
//     if($status==0){
//         return '未使用';
//     }elseif($status==1){
//         return '已使用';
//     }elseif ($status==2) {
//         return '已过期';
//     }
// }
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true){

    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";

    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";

    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";

    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";

    preg_match_all($re[$charset], $str, $match);

    $str_lenth = count($match[0]);

    if(function_exists("mb_substr"))

{ 

        if($suffix && $str_lenth>$length) 

        return mb_substr($str, $start, $length, $charset)."...";

        else

        return mb_substr($str, $start, $length, $charset);

}

    elseif(function_exists('iconv_substr')) {

  if($suffix && $str_lenth>$length) 

       return iconv_substr($str,$start,$length,$charset)."...";

       else

       return iconv_substr($str,$start,$length,$charset); 

    }    

}
    /**
 * 系统邮件发送函数
 * @param string $to    接收邮件者邮箱
 * @param string $name  接收邮件者名称
 * @param string $subject 邮件主题 
 * @param string $body    邮件内容
 * @param string $attachment 附件列表
 * @return boolean 
 */
function send_mail($to, $name, $subject = '', $body = '', $attachment = null){
    //vendor('PHPMailer.class#phpmailer'); //从PHPMailer目录导class.phpmailer.php类文件
    import("Vendor.PHPMailer.phpmailer");
    $mail             = new \PHPMailer(); //PHPMailer对象
    $mail->CharSet    = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP();  // 设定使用SMTP服务
    $mail->SMTPDebug  = 0;                     // 关闭SMTP调试功能
                                               // 1 = errors and messages
                                               // 2 = messages only
    $mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
    $mail->SMTPSecure = 'ssl';                 // 使用安全协议
    $mail->Host       = C('EMAIL_SMTP_HOST');  // SMTP 服务器
    $mail->Port       = C('EMAIL_SMTP_PORT');  // SMTP服务器的端口号
    $mail->Username   = C('EMAIL_SMTP_USER');  // SMTP服务器用户名
    $mail->Password   = C('EMAIL_SMTP_PASS');  // SMTP服务器密码
    $mail->SetFrom(C('EMAIL_FROM_EMAIL'), C('EMAIL_FROM_NAME'));
    $replyEmail       = C('EMAIL_REPLY_EMAIL')?C('EMAIL_REPLY_EMAIL'):C('EMAIL_FROM_EMAIL');
    $replyName        = C('EMAIL_REPLY_NAME')?C('EMAIL_REPLY_NAME'):C('EMAIL_FROM_NAME');
    $mail->AddReplyTo($replyEmail, $replyName);
    $mail->Subject    = $subject;
    $mail->MsgHTML($body);
    $mail->AddAddress($to, $name);
    if(is_array($attachment)){ // 添加附件
        foreach ($attachment as $file){
            is_file($file) && $mail->AddAttachment($file);
        }
    }
    return $mail->Send() ? true : $mail->ErrorInfo;
}

function send_mobile($account,$content){
    import("Vendor.PHPMailer.smssoft");
    $smsSoft             = new \SmsSoft(); //PHPMailer对象
    $uid = C('MOBILE_USER');
    $pwd = C('MOBILE_PWD');
    $res = $smsSoft->sendSMS($account, $content,$uid,$pwd);
    if($res == true){
        return 1;
    }else{
        return 2;
    }
}


?>