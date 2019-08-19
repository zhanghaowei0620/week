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
    <table border="1">
        <tr>
            <td>ID</td>
            <td>商品名称</td>
            <td>商品图片</td>
            <td>操作</td>
        </tr>
        @foreach($arr as $v)
        <tr goods_id={{ $v->goods_id }}>
            <td>{{$v->goods_id}}</td>
            <td>{{$v->goods_name}}</td>
            <td><img src="http://vm.lianxi.com/{{$v->goods_img}}" width="50" height="50"></td>
            <td>
                <a href="javascript:;" class = "del">删除</a>
                <a href="javascript:;" class = "update">修改</a>
            </td>
        </tr>
        @endforeach
        
    </table>
</body>
</html>
<script src="js/jquery/jquery-1.12.4.min.js"></script>
<script src="layui/layui.js"></script>
<script>
    $(function () {
        layui.use(['form','layer'],function() {
            var form = layui.form();
            var layer=layui.layer;
            $('.update').click(function () {
                var _this=$(this)
                var goods_id = _this.parents('tr').attr('goods_id');
                location.href='updata?goods_id='+goods_id;
            })
            $('.del').click(function () {
                var _this=$(this)
                var goods_id = _this.parents('tr').attr('goods_id');
                location.href='del?goods_id='+goods_id;
            })
        })
    })
</script>