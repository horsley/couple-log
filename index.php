<?php
/**
 * Created by JetBrains PhpStorm.
 * User: horsley
 * Date: 12-12-31
 * Time: 下午2:40
 * To change this template use File | Settings | File Templates.
 */
include_once(dirname(__FILE__) . '/init.php');

?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>工作汇报</title>
    <link rel="stylesheet" type="text/css" href="http://lib.sinaapp.com/js/bootstrap/latest/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="http://lib.sinaapp.com/js/bootstrap/latest/css/bootstrap-responsive.min.css">

    <style>
        .logs p {
            word-wrap: break-word;
            word-break: break-all;
        }
        .log_content {
            height: 420px;
            overflow-y: auto;
            padding-right: 10px;
        }
        blockquote {
            border-color:#35B396 !important;
            background: #fff;
        }

        .notify {
            text-align: center;
            margin-bottom: 10px;
            background: #dededf;
            padding: 2px;
        }

        .notify a {
            color: #666;
        }

        ::-webkit-scrollbar-track-piece{
            background-color:#fff;/*滚动条的背景颜色*/
            -webkit-border-radius:5px;/*滚动条的圆角宽度*/
        }
        ::-webkit-scrollbar{
            width:12px;/*滚动条的宽度*/
            height:12px;/*滚动条的高度*/
        }
        ::-webkit-scrollbar-thumb:vertical{/*垂直滚动条的样式*/
            height:50px;
            background-color:#ddd;
            -webkit-border-radius:5px;
            outline:2px solid #fff;
            outline-offset:-2px;
            border: 2px solid #fff;
        }
        ::-webkit-scrollbar-thumb:hover{/*滚动条的hover样式*/
            height:50px;
            background-color:#aaa;
            -webkit-border-radius:5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="page-header" ><a href="javascript:location.reload();" style="color: #000" title="点击刷新">即时日志系统</a> <span class="label label-info" style="vertical-align: top;">Alpha</span></h1>
        <div id="post_form">
            <form class="form-inline">
                <div class="row">
                    <div class="input-append input-prepend clearfix span12">
                        <span class="add-on" style="float: left">></span>
                        <input name="log_text" class="input-xxlarge span10" style="float: left"  type="text">
                        <button class="btn btn-primary span2" id="post_log">提交</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="span6" >
                <div class="well logs">
                    <h4><?=ucwords($another_name)?>
                        <?=check_online(LOG_DIR . '/' . $another_name . '.online') ? '<i class="icon-ok-circle"></i>': '<i class="icon-ban-circle"></i>'?></h4><hr />
                    <div class="log_content" id="another_logs">
                        <div class="notify" style="display: none"><a href="#"></a></div>
                        <? get_logs($another_log)?>
                    </div>
                </div>
            </div>
            <div class="span6" >
                <div class="well logs">
                    <h4><?=ucwords($_SESSION['username'])?> <i class="icon-ok-circle"></i></h4><hr />
                    <div class="log_content" id="my_logs">
                        <? get_logs($my_log)?>
                    </div>
                </div>
            </div>
            <? echo_update_time($another_log)?>
        </div>
        <div style="text-align: center; color: #888"><hr />horsley &hearts; vanora</div>
    </div>
</body>
<script type="text/javascript" src="http://lib.sinaapp.com/js/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.timers.js"></script>
<script type="text/javascript" src="js/main.js"></script>
</html>