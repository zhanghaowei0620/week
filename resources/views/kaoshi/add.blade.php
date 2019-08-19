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
<form action="add_goods" method="post" enctype="multipart/form-data">
    <p>
        商品名称:<input type="text" name="goods_name" id="goods_name">
    </p>
    <p>
        图片:<input type="file" name="goods_img" id="goods_img">
    </p>
    <p>
        <input type="submit" value="ADD">
    </p>
</form>
</body>
</html>
