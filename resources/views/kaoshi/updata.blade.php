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
<h3>添加页面</h3>
<hr>
{{--<form enctype="multipart/form-data">--}}
    <input type="hidden" name="goods_id" id="goods_id" value="<?php echo $arr->goods_id?>">
    <p>
        商品名称:<input type="text" name="goods_name" id="goods_name">
    </p>
    <p>
        图片:<input type="file" name="goods_img" id="goods_img">
    </p>
    <p>
        <input type="submit" value="UPDATA" id="updata">
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
            $('#updata').click(function() {
                var goods_id = $('#goods_id').val();
                var goods_name = $('#goods_name').val();
                var goods_img = $('#goods_img').val();
                $.ajax({
                    data : {goods_id:goods_id,goods_name:goods_name,goods_img:goods_img},
                    url : "updata_do",
                    type : 'POST',
                    dataType : 'json',
                    success:function(msg){
                        if(msg.errno==50001){
                            layer.msg(msg.msg);
//                            location.href='index';
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
