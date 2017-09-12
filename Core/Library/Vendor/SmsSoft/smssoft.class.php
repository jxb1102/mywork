<?php
/**
 * Created by PhpStorm.
 * User: jixb
 * Date: 2017/9/11
 * Time: 16:39
 */
class SmsSoft{
    public $info = array(
        'name' => 'SmsSoft',
        'title' => '微网通联',
        'description' => '微网通联短信发送插件 ',
        'status' => 1,
        'author' => 'kyne',
        'version' => '1.0.0'
    );

    public function install()
    {
        return true;
    }

    public function uninstall()
    {
        return true;
    }


    public function sendSms($mobile, $content,$uid,$pwd){
        if (empty($uid) || empty($pwd)) {
            return '管理员还未配置短信信息，请联系管理员配置';
        }


        $target = "http://cf.51welink.com/submitdata/Service.asmx/g_Submit";
        //替换成自己的测试账号,参数顺序和wenservice对应
        $data = array(
            'sname' => $uid,
            'spwd' => $pwd,
            'scorpid' => '',
            'sprdid' => '1012888',
            'sdst' => $mobile,
            'smsg' => $content,

        );

        $post_data = http_build_query($data);
        $gets = $this->Post($post_data, $target);

        $p = xml_parser_create();
        xml_parse_into_struct($p, $gets, $vals, $index);
        xml_parser_free($p);

        $sendCode = null;
        foreach($vals as $v){
            if($v['tag'] == 'STATE'){
                $sendCode = $v['value'];
                break;
            }
        }

        if ($sendCode == 0) {
            return true;
        } else {
            return "发送失败!";
        }

    }

    function Post($data, $target) {
        $url_info = parse_url($target);
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader .= "Host:" . $url_info['host'] . "\r\n";
        $httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader .= "Content-Length:" . strlen($data) . "\r\n";
        $httpheader .= "Connection:close\r\n\r\n";
        //$httpheader .= "Connection:Keep-Alive\r\n\r\n";
        $httpheader .= $data;

        $fd = fsockopen($url_info['host'], 80);
        fwrite($fd, $httpheader);
        $gets = "";
        while(!feof($fd)) {
            $gets .= fread($fd, 128);
        }
        fclose($fd);
        if($gets != ''){
            $start = strpos($gets, '<?xml');
            if($start > 0) {
                $gets = substr($gets, $start);
            }
        }
        return $gets;
    }

}