<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h3>登录页面</h3>
<hr>
{{--<form action="login" method="post">--}}

    <p>
        <input type="text" name="nickname" id="nickname">
    </p>
    <p>
        <input type="password" name="password" id="password">
    </p>
    <p>
        <input type="submit" value="LOGIN" id="log">
    </p>
{{--</form>--}}
</body>
</html>
<script src="js/jquery/jquery-1.12.4.min.js"></script>
<script src="layui/layui.js"></script>
<script type="text/javascript"></script>
<script>
    $(function () {
        layui.use(['form','layer'], function() {
            var form = layui.form();
            var layer = layui.layer;
            $('#log').click(function() {
                var url = 'login';
                var nickname = $('#nickname').val();
                var password = $('#password').val();
                $.ajax({
                    data : {nickname:nickname,password:password},
                    url : url,
                    type : 'post',
                    dataType : 'json',
                    success:function(msg){
                        if(msg.errno==50001){
                            layer.msg(msg.msg);
                            location.href='index';
                        }else if(msg.errno==50002){
                            layer.msg(msg.msg);
//                            location.href='log';
                        }else{
                            layer.msg(msg.msg);
//                            location.href='log';
                        }

                    }
                })
            })
        })
    })
    
</script>

