<script type="text/javascript" src="__SITE__source/js/Question.js"></script>
<div style="float:left;height:100%;width:100%">
<div style="float:left">
	<div style="float:left"><font size="3">所属科目:</font></div>
	<div style="float:left">
	<input id="searchcourse" name="searchcourse" style="width:200px" class="easyui-combobox" />
    </div>
</div>
<div style="float:left">
	<div style="float:left">&nbsp;&nbsp;&nbsp;&nbsp;<font size="3">所属章节:</font></div>
	<div style="float:left">
	<input id="searchchapter" name="searchchapter" style="width:200px" class="easyui-combobox" />
    </div>
</div>
<div style="float:left">
	<div style="float:left">&nbsp;&nbsp;&nbsp;&nbsp;<font size="3">所属知识点:</font></div>
	<div style="float:left">
	<input id="searchknow" name="searchknow" style="width:200px" class="easyui-combobox" />
    </div>
</div>
<div style="float:left">
	<div style="float:left"><font size="3">难&nbsp;&nbsp;度:</font></div>
	<div style="float:left">
	<input id="searchlevel" name="searchlevel" style="width:200px" class="easyui-combobox"/>
    </div>	
</div>

<div style="float:left">
	<div style="float:left">&nbsp;&nbsp;&nbsp;<input type="button" name="searceh" value="查询" iconCls="icon-search" onclick="search()" />&nbsp;&nbsp;&nbsp;&nbsp;</div>
	<div style="float:left"><input type="button" name="searcehall" value="查询全部" onclick="searchall()" /></div>
</div>
<div style="float:left;height:95%;width:100%">
<table id="question_list" ></table>
<div id="question_detail" title="题目详情" style="width:700px;height:550px;" hidden="true">
	<table>
		<tr>
			<td align="right">题目：</td><td align="left"><textarea id="question" name="question" readonly="true" rows="3" cols="59"></textarea></td>
		</tr>
		
		<tr>
			<td align="right">难度：</td><td align="left"><input type="text" name="level" id="level" readonly="true" style="width:435px"></td>
		</tr>
		<tr>
			<td align="right">科目：</td><td align="left"><input type="text" name="course" id="course" readonly="true" style="width:435px"></td>
		</tr>
		<tr>
			<td align="right">章节：</td><td align="left"><input type="text" name="chapter" id="chapter" readonly="true" style="width:435px"></td>
		</tr>
		<tr>
			<td align="right">所属知识点：</td><td align="left"><input type="text" name="ofknow" id="ofknow" readonly="true" style="width:435px"></td>
		</tr>
		
		<tr>
			<td align="right">答案1：</td><td align="left"><input type="text" name="answer1" id="answer1" readonly="true" readonly="true" style="width:435px"></td>
		</tr>
		<tr>
			<td align="right">答案2：</td><td align="left"><input type="text" name="answer2" id="answer2" readonly="true" style="width:435px"></td>
		</tr>
		<tr>
			<td align="right">答案3：</td><td align="left"><input type="text" name="answer3" id="answer3" readonly="true" style="width:435px"></td>
		</tr>
		<tr>
			<td align="right">答案4：</td><td align="left"><input type="text" name="answer4" id="answer4" readonly="true" style="width:435px"></td>
		</tr>
		<tr>
			<td align="right">正确答案：</td><td align="left"><input type="text" name="rightanswer" id="rightanswer" readonly="true" style="width:435px"></td>
		</tr>
		<tr id="look_photo">
			<td align="right">图片：</td><td align="left"><img src="" id="photo" style="width:100px;height:100px" /></td>
		</tr>
		<tr>
			<td align="right">备注：</td><td align="left"><textarea id="comment" name="comment" readonly="true" rows="3" cols="59"></textarea></td>
		</tr>
	</table>
</div>

<div id="question_add" title="题目管理" style="width:700px;height:550px;" hidden="true">
	<form name="add_question" id="add_question" enctype="multipart/form-data" method="post">
	<table>
		<tr>
			<td align="right">题目：</td><td align="left"><textarea id="addquestion" name="addquestion" rows="3" cols="59"></textarea></td>
		</tr>
		<input type="hidden" name="question_id" id="question_id" value=""/>
		
		<tr>
			<td align="right">科目：</td><td align="left"><input id="addcourse" name="addcourse" style="width:435px" class="easyui-combobox" /></td>
		</tr>
		<tr>
			<td align="right">章节：</td><td align="left"><input id="addchapter" name="addchapter" style="width:435px" class="easyui-combobox" /></td>
		</tr>
		<tr>
			<td align="right">所属知识点：</td><td align="left"><input id="addofknow" name="addofknow" style="width:435px" class="easyui-combobox" /></td>
		</tr>
		<tr>
			<td align="right">难度：</td><td align="left"><input id="addlevel" name="addlevel" style="width:435px" class="easyui-combobox" /></td>
		</tr>
		<tr>
			<td align="right">答案1：</td><td align="left"><input type="text" name="addanswer1" id="addanswer1" style="width:435px"></td>
		</tr>
		<tr>
			<td align="right">答案2：</td><td align="left"><input type="text" name="addanswer2" id="addanswer2" style="width:435px"></td>
		</tr>
		<tr>
			<td align="right">答案3：</td><td align="left"><input type="text" name="addanswer3" id="addanswer3" style="width:435px"></td>
		</tr>
		<tr>
			<td align="right">答案4：</td><td align="left"><input type="text" name="addanswer4" id="addanswer4" style="width:435px"></td>
		</tr>
		<tr>
			<td align="right">正确答案：</td><td align="left"><input type="text" name="addrightanswer" id="addrightanswer" style="width:435px"></td>
		</tr>
		<tr id="photo_row">
			<td align="right">已有图片：</td><td align="left"><img src="" id="addphoto" style="width:100px;height:100px" /></td>
		</tr>
		<tr>
			<td align="right">上传图片：</td><td align="left"><input type="file" name="upfile" id="upfile"></td>
		</tr>
		<tr>
			<td align="right">备注：</td><td align="left"><textarea id="addcomment" name="addcomment" rows="3" cols="59"></textarea></td>
		</tr>
		<tr>
			<td align="center" colspan="2"><input type="button" value="提交" onclick="submit_form()"></td>
		</tr>
	</table>
	</form>
</div>
<div id="question_imp" name="question_imp" title="题目导入" style="width:400px;height:200px;" hidden="true">
	<form name="imp_question" id="imp_question" enctype="multipart/form-data" method="post">
		<table>
			<tr>
				<td align="right">模板：</td><td><a href="__SITE__source/题目导入模板.xlsx"><font color="blue">点击下载</font></a></td>
			</tr>
			<tr>
				<td align="right">科目：</td><td align="left"><input id="importcourse" name="importcourse" style="width:200px" class="easyui-combobox" /></td>
			</tr>
			<tr>
				<td align="right">章节：</td><td align="left"><input id="importchapter" name="importchapter" style="width:200px" class="easyui-combobox" /></td>
			</tr>
			<tr>
				<td align="right">所属知识点：</td><td align="left"><input id="importknow" name="importknow" style="width:200px" class="easyui-combobox" /></td>
			</tr>
			
			<tr>
				<td colspan="2" align="center"><input type="file" name="import" id="import"/></td>
			</tr>
		<tr>
			<td align="center" colspan="2"><input type="button" value="提交" onclick="sub_import()"></td>
		</tr>
		</table>
	</form>
</div> 
</div>