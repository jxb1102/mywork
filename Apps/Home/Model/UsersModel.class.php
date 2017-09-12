<?php
namespace Home\Model;
use Think\Model;

/**

 * 用户模型

 * @author 麦当苗儿 <zuojiazi@vip.qq.com>

 */



class UsersModel extends Model {



    protected $_validate = array(

        array('username', '1,16', '昵称长度为1-16个字符', self::EXISTS_VALIDATE, 'length'),

        array('username', '', '昵称被占用', self::EXISTS_VALIDATE, 'unique'), //用户名被占用

    );



    public function lists($status = 1, $order = 'uid DESC', $field = true){

        $map = array('status' => $status);

        return $this->field($field)->where($map)->order($order)->select();

    }



    /**

     * 登录指定用户

     * @param  integer $uid 用户ID

     * @return boolean      ture-登录成功，false-登录失败

     */

    public function login($username,$password){

        /* 检测是否在当前应用注册 */
        $map['username']=$username;
        $map['email']=$username;
        $map['_logic'] = 'or';
        $user = $this->field(true)->where($map)->find();


        if(is_array($user) && $user['status']) {
            if(md5($password) === $user['password']){
                $this->autoLogin($user);
                return $user['uid']; //登录成功，返回用户ID
            } else {
                return -1;
            }
        }else{
            return -2;
        }

    }

    public function reg($username,$password,$email){
        if ($this->where('username="'.$username.'"')->find()) {
            return -1;
            exit();
        }
        if ($this->where('email="'.$email.'"')->find()) {
            return -2;
            exit();
        }
        
        $data=array(
            'username'=>$username,
            'nickname'=>$username,
            'password'=>md5($password),
            'email'=>$email,
            'reg_ip'=>get_client_ip(1),
            'reg_time'=>time(),
        );
        if (C('user_reg')==1) {
            $data['status']=0;
            $uid=$this->add($data);
            if ($uid) {
                return -4;
            }else{
                return -3;
            }
        }else{
            $data['status']=1;
            $uid=$this->add($data);
            if ($uid) {
                return $uid;
            }else{
                return -3;
            }
        }
        
        

    }

    public function logout(){
        session('user_auth', null);
        session('user_auth_sign', null);
    }

    private function autoLogin($user){

        /* 更新登录信息 */

        $data = array(
            'uid'             => $user['uid'],
            'login'           => array('exp', '`login`+1'),
            'last_login_time' => NOW_TIME,
            'last_login_ip'   => get_client_ip(1),
        );
        $this->save($data);
        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'uid'             => $user['uid'],
            'username'        => $user['username'],
            'nickname'        =>$user['nickname'],
            'email'           =>$user['email'],
            'last_login_time' => $user['last_login_time'],
            'ziliao'          =>$user['ziliao']
        );
        session('user_auth', $auth);
        // session(array('user_auth'=>$auth,'expire'=>60));
    }
    public function getnickname($uid){
        return $this->where(array('uid'=>(int)$uid))->getField('nickname');
    }



}

