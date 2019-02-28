<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('school_add_data')}">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">学校名称</th>
				<td><input type="text" class="input fl" name="school_name" size="75" placeholder="学校名称" validate="maxlength:50,required:true"/></td>
			</tr>
			<tr>
				<th width="80">站点经纬度</th>
				<td id="choose_map"></td>
			</tr>
			<tr>
				<th width="80">站点所在地</th>
				<td id="choose_cityarea" area_id="-1" circle="-1"></td>
			</tr>
			<tr>
				<th width="80">详细地址</th>
				<td><input type="text" class="input fl" name="address" size="75" placeholder="学校地址"></td>
			</tr>
			<tr>
				<th width="80">联系方式</th>
				<td><input type="text" class="input fl" name="phone" size="75" placeholder="联系方式" validate="required:true"/></td>
			</tr>

			<tr>
				<th width="80">类别</th>
				<td>
					<select name="school_cat">
						<option value=""> == 请选择学校类别 == </option>
						<option value="1">幼儿园</option>
						<option value="2">小学</option>
						<option value="3">初中</option>
						<option value="4">九年一贯制（小学+初中）</option>
						<option value="5">完中（初中+高中）</option>
						<option value="6">大学</option>
					</select>
				</td>
			</tr>

			<tr>
				<td width="80">学校特色</td>
				<td id="stationVal">
					<volist id="traitList" name="traitList">
						<div style="width: 150px; height: 40px; float: left;">
							<input type='checkbox' name='trait[]' value='{pigcms{$traitList.trait_id}' checked='checked'>{pigcms{$traitList.trait_name}
						</div>
					</volist>
				</td>
			</tr>


			<tr>
				<th width="80">招生简章</th>
				<td>
					<textarea name="recruit" id="recruit"></textarea>
				</td>
			</tr>
			<tr>
				<th width="80">学校介绍</th>
				<td>
					<textarea name="introduce" id="introduce"></textarea>
				</td>
			</tr>
			<tr>
				<th width="80">招生范围</th>
				<td>
					<textarea name="scope" id="scope"></textarea>
				</td>
			</tr>
			<tr>
				<th width="80">入学条件</th>
				<td>
					<textarea name="term" id="term"></textarea>
				</td>
			</tr>
			<tr>
				<th width="80">描述</th>
				<td>
					<textarea name="describe" id="describe"></textarea>
				</td>
			</tr>
			
			<tr>
				<th width="80">学校类型</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>公立</span><input type="radio" name="school_type" value="0" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>私立</span><input type="radio" name="school_type" value="1" /></label></span>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
	<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
	<script type="text/javascript">
		KindEditor.ready(function(K){
			kind_editor = K.create("#recruit",{
				width:'350px',
				height:'150px',
				resizeType : 1,
				allowPreviewEmoticons:false,
				allowImageUpload : true,
				filterMode: true,
				items : [
					'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
					'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
					'insertunorderedlist', '|', 'emoticons', 'image', 'link'
				],
				emoticonsPath : './static/emoticons/',
				uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=fc/school"
			});


			kind_editor = K.create("#introduce",{
				width:'402px',
				height:'150px',
				resizeType : 1,
				allowPreviewEmoticons:false,
				allowImageUpload : true,
				filterMode: true,
				items : [
					'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
					'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
					'insertunorderedlist', '|', 'emoticons', 'image', 'link'
				],
				emoticonsPath : './static/emoticons/',
				uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=fc/school"
			});


			kind_editor = K.create("#scope",{
				width:'402px',
				height:'150px',
				resizeType : 1,
				allowPreviewEmoticons:false,
				allowImageUpload : true,
				filterMode: true,
				items : [
					'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
					'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
					'insertunorderedlist', '|', 'emoticons', 'image', 'link'
				],
				emoticonsPath : './static/emoticons/',
				uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=fc/school"
			});


			kind_editor = K.create("#term",{
				width:'402px',
				height:'150px',
				resizeType : 1,
				allowPreviewEmoticons:false,
				allowImageUpload : true,
				filterMode: true,
				items : [
					'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
					'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
					'insertunorderedlist', '|', 'emoticons', 'image', 'link'
				],
				emoticonsPath : './static/emoticons/',
				uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=fc/school"
			});

			kind_editor = K.create("#describe",{
				width:'402px',
				height:'150px',
				resizeType : 1,
				allowPreviewEmoticons:false,
				allowImageUpload : true,
				filterMode: true,
				items : [
					'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
					'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
					'insertunorderedlist', '|', 'emoticons', 'image', 'link'
				],
				emoticonsPath : './static/emoticons/',
				uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=fc/school"
			});
		});
	</script>
	
<include file="Public:footer"/>