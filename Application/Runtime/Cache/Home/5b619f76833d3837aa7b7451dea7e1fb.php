<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript">
	var JErule = '<?php echo U('CreditManage/CreditManage_GetRule');?>';
	var Updturl = '<?php echo U('CreditManage/CreditManage_UpdateRule');?>';
</script>
<link rel="stylesheet" href="http://localhost/zyyj/source/css/Grade_Manage.css">
<script type="text/javascript" src="http://localhost/zyyj/source/js/ExchangeRule.js"></script>
<style type="text/css">
	.textbox{
		height: 20px;
		margin:0;
		padding: 0 2px;
		box-sizing:content-box;
	}
</style>
<table id="Jtable" style="padding:0px" iconCls='icon-search'></table>
<div id='Jtool' style="padding:0px">
	<span>
		<a class="easyui-linkbutton" iconCls='icon-add' plain='true' id='J_add' onclick="JRule_add();">添加</a>
		<a class="easyui-linkbutton" iconCls='icon-edit' plain='true' id='J_edit' onclick="JRule_edit();">修改</a>
		<a class="easyui-linkbutton" iconCls='icon-remove' plain='true' id='J_remove' onclick="JRule_delete('<?php echo U('CreditManage_DeletRule');?>');">删除</a>
		<a class="easyui-linkbutton" iconCls='icon-reload' plain='true' onclick="JRule_reload();">刷新</a>
	</span>
</div>
<div id="J_AddRule" iconCls='icon-save' closed="true" style="padding:5px;width:400px;height:300px;">
	<h3 class="Gtitle">兑换规则信息</h3>
	<form id="J_RuleEdit" style="margin: 0;padding: 10px 80px;" method="post">
		<div class="Gitem">
			<label class="Mtitle">规则名称 :</label>
			<input id="J_Rulename" name='J_Rulename' class='textbox' style="width:100px">  
		</div>
		<div class="Gitem">
			<label class="Mtitle">所需积分 :</label>
			<input type="text" id="J_NeedScore" name='J_NeedScore'  required="true" style="width:106px">
		</div>
		<div class="Gitem">
			<label class="Mtitle">所得学分 :</label>
			<input type="text" id="J_GetCredit" name='J_GetCredit' required="true" style="width:106px">
		</div>
		<div class="Gitem" id='isuse'>
			<label class="Mtitle">使用状态 :</label>
			<select  name="J_Status" id="J_Status" class="easyui-validatebox textbox"  style="width:105px;">
				<option value="1">激活</option>
				<option value="0"  selected="selected" >禁用</option>
			</select>
			<input type="hidden" name="J_type" id='J_type' value="">
			<input type="hidden" name="JRule_id" id='JRule_id' value="">
		</div>
	</form>
</div>