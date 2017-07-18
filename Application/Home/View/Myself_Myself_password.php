<include file="./Application/Home/View/Header.php" />

<head/>

<body>
	<div class="form-box">
		<h3>密码修改</h3>
		<form id="updatepwd-form" action="{:U('Myself_password_handle')}" method="post">
			<input type="text" id="idinput" name="user_id" value="{$data['user_id']}" />
			<div class="input-box">
				<div class="prebox">姓名</div>
				<input class="easyui-validatebox textbox" type="text" name="realname" data-options="required:true" value="{$data['realname']}" disabled="true"/>
			</div>
			<div class="input-box">
				<div class="prebox">旧密码</div>
				<input class="easyui-validatebox textbox" id="oldpwd" type="password" name="password" data-options="required:true" validType="length[6,30]"/>
			</div>	
			<div class="input-box" >
				<div class="prebox">新密码</div>
				<input class="easyui-validatebox textbox" type="password" id='pwd' name="newpwd" data-options="required:true,validType:['length[6,30]','noequals[\'#oldpwd\']']"/>
			</div>
			<div class="input-box">
				<div class="prebox">确认密码</div>
				<input class="easyui-validatebox textbox" type="password" id='rpwd' name="renewpwd" data-options="required:true,validType:['length[6,30]','equals[\'#pwd\']']"/>
			</div>	
			<button type="submit" class="easyui-linkbutton">提交</button>	
		</form>
	</div>	
</div>
<link rel="stylesheet" href="__ROOT__/source/css/Myself.css">
<script type="text/javascript" src='__ROOT__/source/js/ExtendValidate.js'></script>
<script type="text/javascript">
	//表单提交
	$('#updatepwd-form').form({
		onSubmit:function(){
				return $('#updatepwd-form').form('validate');
			},
		success:function(data){
			data = $.parseJSON(data);
			if (data.status == 1) {
				$.messager.alert('提示','修改成功');
			}else{
				if (data.status == 0) {
					$.messager.alert('提示','密码错误!');
				}else{
					$.messager.alert('提示','新旧密码不能相同!');
				}
			}
		}
	});
</script>
</body>
</html>