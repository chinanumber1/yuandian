<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('school_save_data')}">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">学校名称</th>
				<td><input type="text" class="input fl" name="school_name" value="{pigcms{$schoolInfo.school_name}" size="75" placeholder="学校名称" validate="maxlength:50,required:true"/></td>
			</tr>
			<tr>
				<th width="80">站点经纬度</th>
				<td id="choose_map" default_long_lat="{pigcms{$schoolInfo.long},{pigcms{$schoolInfo.lat}"></td>
			</tr>
			<tr>
				<th width="80">城市</th>
				<td id="choose_cityarea" circle_id="-1" area_id="-1" province_id="{pigcms{$schoolInfo.province_id}" city_id="{pigcms{$schoolInfo.city_id}"></td>
			</tr>
			<tr>
				<th width="80">详细地址</th>
				<td><input type="text" class="input fl" name="address" value="{pigcms{$schoolInfo.address}" size="75" placeholder="学校地址"></td>
			</tr>
			<tr>
				<th width="80">联系方式</th>
				<td><input type="text" class="input fl" name="phone" value="{pigcms{$schoolInfo.phone}" size="75" placeholder="联系方式" validate="required:true"/></td>
			</tr>


			<tr>
				<th width="80">类别</th>
				<td>
					<select name="school_cat">
						<option value="1" <if condition="$schoolInfo['school_cat'] eq 1"> selected = "selected" </if>>幼儿园</option>
						<option value="2" <if condition="$schoolInfo['school_cat'] eq 2"> selected = "selected" </if>>小学</option>
						<option value="3" <if condition="$schoolInfo['school_cat'] eq 3"> selected = "selected" </if>>初中</option>
						<option value="4" <if condition="$schoolInfo['school_cat'] eq 4"> selected = "selected" </if>>九年一贯制（小学+初中）</option>
						<option value="5" <if condition="$schoolInfo['school_cat'] eq 5"> selected = "selected" </if>>完中（初中+高中）</option>
						<option value="6" <if condition="$schoolInfo['school_cat'] eq 6"> selected = "selected" </if>>大学</option>
					</select>
				</td>
			</tr>

			<tr>
				<td width="80">学校特色</td>
				<td id="stationVal">
					<volist id="traitList" name="traitList">
						<div style="width: 150px; height: 40px; float: left;">
							<input type='checkbox' name='trait[]' value='{pigcms{$traitList.trait_id}' {pigcms{$traitList.checked}>{pigcms{$traitList.trait_name}
						</div>
					</volist>
				</td>
			</tr>

			<tr>
				<th width="80">招生简章</th>
				<td>
					<textarea name="recruit" id="recruit">{pigcms{$schoolInfo.recruit}</textarea>
				</td>
			</tr>
			<tr>
				<th width="80">学校介绍</th>
				<td>
					<textarea name="introduce" id="introduce">{pigcms{$schoolInfo.introduce}</textarea>
				</td>
			</tr>
			<tr>
				<th width="80">招生范围</th>
				<td>
					<textarea name="scope" id="scope">{pigcms{$schoolInfo.scope}</textarea>
				</td>
			</tr>
			<tr>
				<th width="80">入学条件</th>
				<td>
					<textarea name="term" id="term">{pigcms{$schoolInfo.term}</textarea>
				</td>
			</tr>
			<tr>
				<th width="80">描述</th>
				<td>
					<textarea name="describe" id="describe">{pigcms{$schoolInfo.describe}</textarea>
				</td>
			</tr>

			<tr>
				<th width="80">学校类型</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$schoolInfo['school_type'] eq 0">selected</if>"><span>公立</span><input type="radio" name="school_type" value="0" /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$schoolInfo['school_type'] eq 1">selected</if>"><span>私立</span><input type="radio" name="school_type" value="1" checked="checked"/></label></span>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="hidden" name="school_id" value="{pigcms{$schoolInfo.school_id}">
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