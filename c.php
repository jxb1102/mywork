<?php
$arr = array(
    '0'=>array(
        'name'=>'james',
        'age'=>30,
    ),
    '1'=>array(
        'name'=>'susu',
        'age'=>26,
    ),
    '2'=>array(
        'name'=>'james',
        'age'=>30,
    ),
    'new'=>array(
        'name'=>'kube',
        'age'=>27,
    ),
    'list'=>array(
        'name'=>'kube',
        'age'=>27,
    ),
);
include('Plugin/httpdown.class.php');
$http = new HttpDown();
$return = $http->PrivateInit('http://www.w3school.com.cn/php/func_array_key_exists.asp');
echo $return;
