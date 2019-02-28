<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Yuedan/authentication_config_add')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">

			<tr>
				<th width="80">认证名称</th>
				<td><input type="text" class="input fl" name="title" id="title" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>

			<tr>
				<th width="80">唯一标识</th>
				<td><input type="text" class="input fl" name="key" id="key" size="20" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>

		  	<tr>
				<th width="80">认证类型</th>
				<td>
					<span class="cb-enable">
						<label class="cb-enable selected">
							<span>文本</span>
							<input type="radio" name="type" value="1" checked="checked" />
						</label>
					</span>
					<span class="cb-disable">
						<label class="cb-disable">
							<span>图片</span>
							<input type="radio" name="type" value="2" />
						</label>
					</span>
				</td>
			</tr>

		</table>
		<div class="btn hidden">
			<input type="submit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>

<include file="Public:footer"/>