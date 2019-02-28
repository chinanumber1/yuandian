<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('User/addlevel')}" frame="true" refresh="true">
		<input type="hidden" name="lid" value="{pigcms{$leveldata['id']}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<td width="80">等级名称：</td>
				<td>
				<input type="text" class="input fl" name="lname" value="{pigcms{$leveldata['lname']}" placeholder="请填写一个等级名" tips="如vip1，vip2等">
				&nbsp;&nbsp;&nbsp;<span class="red">例如：1=>VIP1,2=>VIP2 等</span>
				</td>
			</tr>
			<tr>
				<td width="80">等级级别：</td>
				<td>
				<span class="input fl" style="width: 140px;">{pigcms{$leveldata['level']}</span>
				&nbsp;&nbsp;&nbsp;<span class="red">例如：1=>VIP1,2=>VIP2 等</span>
				</td>
			</tr>
			<tr>
				<td width="80">等级{pigcms{$config['score_name']}：</td>
				<td>
				<input type="text" class="input fl" name="integral" value="{pigcms{$leveldata['integral']}" placeholder="请填写一个对应数字" onkeyup="value=value.replace(/[^1234567890]+/g,'')" tips="成为该等级会员所需要的{pigcms{$config['score_name']}数">
				&nbsp;&nbsp;&nbsp;<span class="red">客户想成为该等级会员所需要消耗的{pigcms{$config['score_name']}数</span>
				</td>
			</tr>
			<tr>
				<td width="80">等级余额：</td>
				<td>
				<input type="text" class="input fl" name="use_money" value="{pigcms{$leveldata['use_money']}" placeholder="请填写一个对应数字" onkeyup="value=value.replace(/[^1234567890]+/g,'')" tips="成为该等级会员所需要的余额">
				&nbsp;&nbsp;&nbsp;<span class="red">客户想成为该等级会员所需要消耗的余额，0为不使用</span>
				</td>
			</tr>
			<if condition="$config['level_balance']">
			<tr>
				<td width="80">等级有效期：</td>
				<td>
				<input type="text" class="input fl" name="validity" value="{pigcms{$leveldata['validity']}" placeholder="请填写一个对应天数" onkeyup="value=value.replace(/[^1234567890]+/g,'')" tips="等级持续有效期，单位/天">
				&nbsp;&nbsp;&nbsp;<span class="red">0 表示不限制 30 表示30天</span>
				</td>
			</tr>
			</if>
			
			<if condition="$config['spread_user_give_type'] eq 1 OR $config['spread_user_give_type'] eq 2">
			<tr>
				<td width="80">推荐用户奖励金额：</td>
				<td>
				<input type="text" class="input fl" name="spread_user_give_moeny" value="{pigcms{$leveldata['spread_user_give_moeny']}" placeholder="请填写金额数" onkeyup="value=value.replace(/[^1234567890]+/g,'')">
				&nbsp;&nbsp;&nbsp;<span class="red">0 表示奖励 30 表示奖励30元余额</span>
				</td>
			</tr>
			</if>
			<if condition="$config['spread_user_give_type'] eq 0 OR $config['spread_user_give_type'] eq 2">
			<tr>
				<td width="80">推荐用户奖励{pigcms{$config['score_name']}：</td>
				<td>
				<input type="text" class="input fl" name="spread_user_give_score" value="{pigcms{$leveldata['spread_user_give_score']}" placeholder="请填写{pigcms{$config['score_name']}数" onkeyup="value=value.replace(/[^1234567890]+/g,'')">
				&nbsp;&nbsp;&nbsp;<span class="red">0 表示奖励 30 表示奖励30{pigcms{$config['score_name']}</span>
				</td>
			</tr>
			</if>
			
			<if condition="$config['score_clean_time'] neq ''">
			<tr>
				<td width="80">{pigcms{$config['score_name']}清理时间</td>
				<td>
				<input type="text" class="input fl" name="score_clean_time" value="{pigcms{$leveldata['score_clean_time']}"  onfocus="WdatePicker({readOnly:true,dateFmt:'MM-dd'})">
				&nbsp;&nbsp;&nbsp;<span class="red">设置后会在这一天清零积分，清零前一天会有微信模板消息提醒用户</span>
				</td>
			</tr>
			<tr>
				<td width="80">{pigcms{$config['score_name']}清理比例</td>
				<td>
				<input type="text" class="input fl" name="score_clean_percent" value="{pigcms{$leveldata['score_clean_percent']}" placeholder="请填写0-100的数字" onkeyup="value=value.replace(/[^1234567890]+/g,'')">
				&nbsp;&nbsp;&nbsp;<span class="red">0 表示不清理 100表示全部清理 30 清理30%</span>
				</td>
			</tr>
			</if>
			<tr>
				<td width="80">等级图标：</td>
				<td>
				    <input type="hidden" name="icon" value="{pigcms{$leveldata['icon']}"/>
					<a href="javascript:void(0)" class="btn btn-sm btn-success J_selectImage">上传图片</a>
				    <img src="{pigcms{$leveldata['icon']}" width="50px" <if condition="!empty($leveldata['icon'])"> style="margin-left: 30px;"<else/>style="margin-left: 30px;display:none;"</if> />
				</td>
			</tr>
		   <tr>
				<td width="80">等级福利：</td>
				<td>优惠&nbsp;
				<select name="fltype">
				<option value="0">无</option>
				<option value="1" <if condition="$leveldata['type'] eq 1">selected="selected"</if>>百分比（%）</option>
				<option value="2" <if condition="$leveldata['type'] eq 2">selected="selected"</if>>立减</option>
				</select>
				&nbsp;&nbsp;&nbsp;
				 <input type="text" class="input" name="boon" value="{pigcms{$leveldata['boon']}" placeholder="请填写一个优惠值数字" onkeyup="value=value.replace(/[^1234567890]+/g,'')" >
				</td>
			</tr>
			<tr>
				<td width="80">等级介绍：</td>
				<td><textarea name="description" rows="10" cols="40"  placeholder="写上一些等级介绍说明文字">{pigcms{$leveldata['description']|htmlspecialchars_decode=ENT_QUOTES|strip_tags=###}</textarea></td>
			</tr>
		   <tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">

KindEditor.ready(function(K){
	var editor = K.editor({
		allowFileManager : true
	});
	 //var islock=false;
	K('.J_selectImage').click(function(){
		var obj=$(this);
		editor.uploadJson = "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=system/image";
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				showRemote : false,
				imageUrl : K('#course_pic').val(),
				clickFn : function(url, title, width, height, border, align) {
					obj.siblings('input').val(url);
					editor.hideDialog();
					obj.siblings('img').attr('src',url).show();
					//window.location.reload();
				}
			});
		});
	   
	});

	kind_editor = K.create("#description",{
		width:'480px',
		height:'380px',
		minWidth:'480px',
		resizeType : 1,
		allowPreviewEmoticons:false,
		allowImageUpload : true,
		filterMode: true,
		items : [
			'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist', '|', 'emoticons', 'image', 'link'
		],
		emoticonsPath : './static/emoticons/',
		uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=system/image"
	});
});
</script>
