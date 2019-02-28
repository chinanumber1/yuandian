<include file="Public:header"/>
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
	<form id="myform" method="post" action="{pigcms{:U('Circle/douseredit')}" frame="true" refresh="true">
		<input type="hidden" name="id" value="{pigcms{$now_category.id}"/>
		
		
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">用户昵称</th>
				<td><input type="text" class="input fl" name="nickName" id="" value="{pigcms{$now_category.nickName}" size="25" placeholder="" validate="maxlength:20" tips=""/></td>
			</tr>
			
			<tr>
				<th width="80">性别</th>
				<td class="radio_box">
					<span class="cb-enable"><label class="cb-enable <if condition="$now_category['gender'] eq 1">selected</if>"><span>男</span><input type="radio" name="gender" value="1"  <if condition="$now_category['gender'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_category['gender'] eq 0">selected</if>"><span>女</span><input type="radio" name="gender" value="0"  <if condition="$now_category['gender'] eq 0">checked="checked"</if>/></label></span>
				</td>
			</tr>
			<tr>
				<th width="80">省份</th>
				<td><input type="text" class="input fl" name="province" id="" value="{pigcms{$now_category.province}" size="25" placeholder="" validate="maxlength:20" tips=""/></td>
			</tr>
			<tr>
				<th width="80">城市</th>
				<td><input type="text" class="input fl" name="city" id="" value="{pigcms{$now_category.city}" size="25" placeholder="" validate="maxlength:20" tips=""/></td>
			</tr>
			<tr>
				<th width="80">电话</th>
				<td><input type="text" class="input fl" name="phone" id="phone" value="{pigcms{$now_category.phone}" size="25" /></td>
			</tr>
			<tr>
				<th width="80">生日</th>
				<td>
				<input type="text" class="input fl" id="date" name="date" value="{pigcms{$now_category.date}"  onfocus=" WdatePicker()"/>
				
				</td>
			</tr>
			<tr>
				<th width="80">签名</th>
				<td>
				<textarea name="content" id="content">{pigcms{$now_category.content}</textarea>
				</td>
			</tr>
		    <tr>
				<th width="80">分类状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_category['status'] eq 0">selected</if>"><span>启用</span><input type="radio" name="status" value="0"  <if condition="$now_category['status'] eq 0">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_category['status'] eq 1">selected</if>"><span>禁用</span><input type="radio" name="status" value="1"  <if condition="$now_category['status'] eq 1">checked="checked"</if> /></label></span>
				</td>
			</tr>
		</table>

		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
	<script type="text/javascript">
	var international_phone = {pigcms{$config.international_phone|intval=###};
    KindEditor.ready(function(K){
        kind_editor = K.create("#content",{
            width:'350px',
            height:'150px',
            resizeType : 1,
            allowPreviewEmoticons:false,
            allowImageUpload : true,
            filterMode: true,
            items : [
                'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                'removeformat'
            ],
            emoticonsPath : './static/emoticons/',
            uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=Circle/editor"
        });
    });

    $('#phone').blur(function(){
       var phone = $('#phone').val();
       if( phone.length != 0){
        if(!international_phone && !(/^1[34578]\d{9}$/.test(phone))){ 
        alert("手机号码有误，请重填");  
        return false; 
        } 
      }
    });
  
      $('#dosubmit').click(function(){
      	var phone = $('#phone').val();
       if( phone.length != 0){
        if(!international_phone && !(/^1[34578]\d{9}$/.test(phone))){ 
        alert("手机号码有误，请重填");  
        return false; 
        } 
       }
      });
  

</script>
<include file="Public:footer"/>