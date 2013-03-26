<?php
/**
 * Created by JetBrains PhpStorm.
 * User: horsley
 * Date: 12-12-31
 * Time: 下午4:43
 * To change this template use File | Settings | File Templates.
 */

//存放位置
define('LOG_DIR', dirname(__FILE__) . '/data');

//认证标题
$realm = 'Vanora & Horsley Log System';

//user => password
$users = array(
    'vanora' => '111111',
    'horsley' => '222222'
);


session_start();
include_once(dirname(__FILE__) . '/functions.php');

$_SESSION['username'] = check_login();

$my_log = LOG_DIR . '/' . $_SESSION['username'] . '.logs.json';
$another_name = $users;
unset($another_name[$_SESSION['username']]);
$another_name = key($another_name);
$another_log = LOG_DIR . '/' . $another_name . '.logs.json';



//时区设置
ini_set("date.timezone", 'Asia/Shanghai'); // 系统时区