<?php

// +----------------------------------------------------------------------

// | OneThink [ WE CAN DO IT JUST THINK IT ]

// +----------------------------------------------------------------------

// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.

// +----------------------------------------------------------------------

// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>

// +----------------------------------------------------------------------



namespace Admin\Model;

use Think\Model;



/**

 * 用户模型

 * @author 麦当苗儿 <zuojiazi@vip.qq.com>

 */



class MemberModel extends Model {



    protected $_validate = array(

        array('nickname', '1,16', '昵称长度为1-16个字符', self::EXISTS_VALIDATE, 'length'),

        array('nickname', '', '昵称被占用', self::EXISTS_VALIDATE, 'unique'), //用户名被占用

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



    /**

     * 注销当前用户

     * @return void

     */

    public function logout(){

        session('user_auth', null);

        session('user_auth_sign', null);

    }



    /**

     * 自动登录用户

     * @param  integer $user 用户信息数组

     */

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

            'username'        => $user['nickname'],

            'last_login_time' => $user['last_login_time'],

        );



        session('user_auth_admin', $auth);

    }



    public function getNickName($uid){

        return $this->where(array('uid'=>(int)$uid))->getField('nickname');

    }



}

