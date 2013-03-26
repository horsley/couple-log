/**
 * Created with JetBrains PhpStorm.
 * User: horsley
 * Date: 12-12-31
 * Time: 下午8:01
 * To change this template use File | Settings | File Templates.
 */
$(function(){
    //提交新的日志
    $('#post_log').click(function(){
        if ($(this).hasClass('disabled')) return false;

        a_log = $.trim($('#post_form').find('input').val());
        if (a_log != '') {
            $(this).addClass('disabled');
            $.post('ajax.php?action=post_new', {log_content: a_log}, function(data) {
                $('#my_logs').prepend(data);
                $('#post_form').find('input').val('');
                $('#post_log').removeClass('disabled');
            });
        }
        return false;
    });
    $('#post_form').find('input').keypress(function(e) {
        if (e.keyCode == 13) {
            $('#post_log').click();
            return false;
        }
    });

    //定时检查刷新
    var notify = $('.notify');
    $('body').everyTime('20s', function(){ //检查间隔
        $.get('ajax.php?action=chk_status',
            {count: $('#another_logs').find('blockquote').length, timestamp: $('#modify_timestamp').val()},
        function(data){
            if (data.update != 0) {
                var new_notify = data.update + ' 条更新日志，点击查看';
                if (new_notify != notify.find('a').html()) {
                    notify.find('a').html(new_notify);
                    notify.show();
                }
            }
            var name_field = $('#another_logs').parent().find('h4');
            if (data.online == 0) {
                name_field.find('i').remove();
                name_field.append(' <i class="icon-ban-circle"></i>');
            } else {
                name_field.find('i').remove();
                name_field.append(' <i class="icon-ok-circle"></i>');
            }
        }, "json");
    });

    //刷新操作
    notify.find('a').live('click', function(){
        $.get('ajax.php?action=refresh',
            {count: $('#another_logs').find('blockquote').length, timestamp: $('#modify_timestamp').val()},
            function(data){
                if (data != 0) {
                    $('#modify_timestamp').val(data.new_time);
                    $('#another_logs').find('.notify').after(data.new_data);
                }
                notify.find('a').html('');
                notify.hide();
            }, 'json');
        return false;
    })
});