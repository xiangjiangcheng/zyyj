<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<script type="text/javascript" src="http://localhost/zyyj/easyui/jquery.min.js"></script>
<script type="text/javascript" src="http://localhost/zyyj/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="http://localhost/zyyj/easyui/locale/easyui-lang-zh_CN.js" ></script>
<link rel="stylesheet" type="text/css" href="http://localhost/zyyj/easyui/themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="http://localhost/zyyj/easyui/themes/icon.css" />

</head>
<include file='./Application/Home/View/<?php echo ($view); ?>' ></include>
<?php echo ($view); ?>
</html>