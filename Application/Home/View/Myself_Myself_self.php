<include file="./Application/Home/View/Header.php" />
<head/>
<body>
<script type="text/javascript" src='__ROOT__/source/js/ExtendValidate.js'></script>
	<script type="text/javascript">
		$('#myself-form').form({
			onSubmit:function(){
				return $('#myself-form').form('validate');
			},
			success:function(data){
				data = $.parseJSON(data);
				if(data.success == 1){
					$.messager.alert('提示','修改成功');
					$('.logout').html('您好, '+data.realname+' | <a href="{:U("Login/Login_loginout")}">退出</a>');	
				}else{
					$.messager.alert('提示','修改失败');
				}
			}
		});
		$(function(){
			$("option[value='{$data['gender']}'").attr('selected', 'true');
		})
	</script>
	<div class="form-box">
		<h3>个人信息</h3>
		<form id="myself-form" action="{:U('Myself_self_handle')}" method="post">
			<input type="text" id="idinput" name="id" value="{$data['user_id']}" />
			<div class="input-box">
				<div class="prebox">账号</div>
				<input class="easyui-validatebox textbox" type="text" name="name" value="{$data['name']}" disabled="true" />
			</div>
			<div class="input-box">
				<div class="prebox">姓名</div>
				<input class="easyui-validatebox textbox" type="text" name="realname" required="true" value="{$data['realname']}"/>
			</div>
			<div class="input-box">
				<div class="prebox">性别</div>
				<select class="easyui-validatebox textbox" name="gender" data-options="required:true">
					<option value=1 >男</option>
					<option value=0 >女</option>
				</select>
			</div>
			<div class="input-box">
				<div class="prebox">部门</div>
				<input class="easyui-validatebox textbox" type="text" name="department" value="{$data['department']}" disabled="true" />
			</div>
			<div class="input-box">
				<div class="prebox">邮箱</div>
				<input class="easyui-validatebox textbox" type="text" name="email" required='true'  validType='email' value="{$data['email']}"/>
			</div>
			<div class="input-box">
				<div class="prebox">电话</div>
				<input class="easyui-validatebox textbox" type="text" name="phone" data-options="required:true,validType:'mobile'" value="{$data['phone']}"/>
			</div>
			<button type="submit" class="easyui-linkbutton">提交</button>
		</form>
	</div>
	<link rel="stylesheet" href="__ROOT__/source/css/Myself.css">
</body>