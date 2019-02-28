<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Scenic/edit_withdraw')}" frame="true" refresh="true">
		
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<td width="80">金额</td>
				<td>{pigcms{$now_withdraw['money']/100}元
				<input type="hidden"   name="money" value="{pigcms{$now_withdraw.money}"  />
				</td>
			</tr>
			<tr>
				<th width="80">支付备注</th>
				<input type="hidden" name="id" value="{pigcms{$id}" />
				<input type="hidden" name="scenic_id" value="{pigcms{$scenic_id}" />
				<td>
					<textarea  rows="6" cols="40" name="remark" id="reason" ></textarea>
				</td>
			</tr>
			<if condition="false">
			<tr>
				<td width="100">是否要企业付款</td>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>是</span><input type="radio" name="is_online" value="1" checked="checked"/></label></span>
					<span class="cb-disable"><label class="cb-disable "><span>否</span><input type="radio" name="is_online" value="0" /></label></span>
				</td>
			</tr>
			</if>
		
		</table>
		<div class="btn">
			
			<input type="submit" style="float:right;" name="dosubmit" id="dosubmit" value="提交" class="button" />
		</div>
	</form>
	<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
	<script type="text/javascript">
		KindEditor.ready(function(K){
			
			kind_editor = K.create("#content",{
				width:'402px',
				height:'320px',
				resizeType : 1,
				<if condition="$_GET['frame_show']">readonlyMode : true,</if>
				allowPreviewEmoticons:false,
				allowImageUpload : true,
				filterMode: true,
				items : [
					'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
					'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
					'insertunorderedlist', '|', 'emoticons', 'image', 'link'
				],
				emoticonsPath : './static/emoticons/',
				uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=system/news"
			});
			
		});
	</script>
<include file="Public:footer"/>