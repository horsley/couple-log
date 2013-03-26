双人即时日志系统
=======

当时随手写的代码，很烂，无时间去重构，写来是给我和我女朋友用的，旨在以不打扰对方的方式主动公开自己当前的工作状态，方便对方知晓。功能特色：

+ json文件存储，不需要数据库
+ 简单HTTP用户认证
+ 轮询定时更新
+ 双方在线状态探测

![demo](http://ww4.sinaimg.cn/large/75d0c1edgw1e33qownhlgj.jpg)

*使用本代码需要修改的地方*
===========

* init.php中关于用户名和密码、认证提示信息、数据存放目录的设置

    
        //存放位置
        define('LOG_DIR', dirname(__FILE__) . '/data');
        
        //认证标题
        $realm = 'Vanora & Horsley Log System';
        
        //user => password
        $users = array(
            'vanora' => '111111',
            'horsley' => '222222'
        );

* 关于定时刷新的时间间隔


    + js/main.js中
    
    
            //定时检查刷新
            var notify = $('.notify');
            $('body').everyTime('20s', function(){ //检查间隔
                $.get('ajax.php?action=chk_status',
                    {count: $('#another_logs').find('blockquote').length, timestamp: $('#modify_timestamp').val()},
                function(data){
    
    + functions.php中的在线判定时间间隔，应为检查间隔的1~2倍，这里选1.5倍
        
    
            function check_online($file) {
                if (time() - filemtime($file) <= 30) { //心跳间隔20s 所以测试在线时间30s
                    return true;
                } else {
                    return false;
                }
            }

* 其他关于显示模板的内容，修改index.php。