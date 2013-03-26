<?php
/**
 * Created by JetBrains PhpStorm.
 * User: horsley
 * Date: 12-12-31
 * Time: 下午2:52
 * To change this template use File | Settings | File Templates.
 */


function check_login() {
    global $realm, $users;
    //首次进入
    if (empty($_SERVER['PHP_AUTH_DIGEST'])) {
        header('HTTP/1.1 401 Unauthorized');
        header('WWW-Authenticate: Digest realm="'.$realm.
            '",qop="auth",nonce="'.uniqid().'",opaque="'.md5($realm).'"');

        die('Restricted area for ourselves');
    }

    // analyze the PHP_AUTH_DIGEST variable
    if (!($data = http_digest_parse($_SERVER['PHP_AUTH_DIGEST'])) || !isset($users[$data['username']]))
        die('Wrong Credentials!');
    // generate the valid response
    $A1 = md5($data['username'] . ':' . $realm . ':' . $users[$data['username']]);
    $A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
    $valid_response = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);

    if ($data['response'] != $valid_response) {
        header('HTTP/1.1 401 Unauthorized');
    }

    return $data['username'];
}

// function to parse the http auth header
function http_digest_parse($txt)
{
    // protect against missing data
    $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1, 'uri'=>1, 'response'=>1);
    $data = array();
    $keys = implode('|', array_keys($needed_parts));

    preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

    foreach ($matches as $m) {
        $data[$m[1]] = $m[3] ? $m[3] : $m[4];
        unset($needed_parts[$m[1]]);
    }

    return $needed_parts ? false : $data;
}

//单条输出日志文件
function echo_log($log_item, $return = false) {
    $ret = '';
    $ret .= '<blockquote>';
    $ret .= '<p>' . $log_item->content . '</p>';
    $ret .= '<small>'. date('Y/m/d H:i:s ', $log_item->time)  . '</small>';
    $ret .= '</blockquote>';

    if ($return) {
        return $ret;
    } else {
        echo $ret;
    }
}

//写入日志文件
function save_log($log_item, $file) {
    $tmp = json_encode($log_item);

    $handle = fopen($file, 'ab');
    fwrite($handle, $tmp . PHP_EOL);
    fclose($handle);
}

//循环输出日志文件
function get_logs($file) {
    $logs = file($file);

    while ($log = array_pop($logs)) {   //倒序
        if (trim($log) != '') {
            $log = json_decode($log);
            echo_log($log);
        }
    }
}

//输出更新时间的字段
function echo_update_time($file) {
   $time = filemtime($file);
   printf('<input type="hidden" id="modify_timestamp" value="%d">', $time);
}

function keep_online($file) {
    file_put_contents($file, time());
}

function check_online($file) {
    if (time() - filemtime($file) <= 30) { //心跳间隔20s 所以测试在线时间30s
        return true;
    } else {
        return false;
    }
}