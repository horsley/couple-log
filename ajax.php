<?php
/**
 * Created by JetBrains PhpStorm.
 * User: horsley
 * Date: 12-12-31
 * Time: 下午3:00
 * To change this template use File | Settings | File Templates.
 */
include_once(dirname(__FILE__) . '/init.php');

if (!isset($_GET['action']) || !in_array($_GET['action'],
    array('post_new', 'refresh', 'chk_status'))) {
    die('Invalid Request!');
}

call_user_func($_GET['action']);

function post_new() {
    global $my_log;
    if (isset($_POST['log_content']) && !empty($_POST['log_content'])) {
        $log_item = new stdClass();

        $log_item->content = $_POST['log_content'];
        $log_item->time = time();


        save_log($log_item, $my_log);
        echo_log($log_item);
    } else {
        die('Invalid Request!');
    }
}

function chk_status() {
    global $another_log, $another_name;

    keep_online(LOG_DIR . '/' . $_SESSION['username'] . '.online'); // keep myself online

    if (isset($_GET['timestamp']) && empty($_GET['timestamp'])) {
        die('Invalid Request!');
    }

    $ret = new stdClass();
    $ret->online = intval(check_online(LOG_DIR . '/' . $another_name . '.online'));


    if ($_GET['timestamp'] == filemtime($another_log)) {
        $ret->update = 0;
    } else {
        $tmp = file($another_log);
        $ret->update = count($tmp) - $_GET['count'];
    }

    echo json_encode($ret);
}

function refresh() {
    global $another_log;

    if (isset($_GET['timestamp']) && empty($_GET['timestamp'])) {
        die('Invalid Request!');
    }

    if ($_GET['timestamp'] == filemtime($another_log)) {
        echo 0;
    } else {
        $new_data = '';

        $tmp = file($another_log);
        $tmp = array_slice($tmp, $_GET['count']);

        while ($log = array_pop($tmp)) {   //倒序
            if (trim($log) != '') {
                $log = json_decode($log);
                $new_data .= echo_log($log, true);
            }
        }
        //var_dump($new_data);
        //die();
        //输出
        $ret = new stdClass();
        $ret->new_data = $new_data;
        $ret->new_time = filemtime($another_log);
        echo json_encode($ret);
    }
}


