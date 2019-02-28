<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Group/add_group_center_type_img')}" enctype="multipart/form-data" ">
		<input type="hidden" name="id" value="{pigcms{$_GET['id']}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">前台广告显示模式</th>
				<td>
					<select name="type" class="valid" id="group_main_page_center_type">
						<option value="3" <if condition="$slider.type eq 3">selected</if>>3图 纯图片模式</option>
						<option value="4" <if condition="$slider.type eq 4">selected</if>>4图 纯图片模式</option>
						<option value="5" <if condition="$slider.type eq 5">selected</if>>5图 纯图片模式</option>
						<option value="-3" <if condition="$slider.type eq -3">selected</if>>3图 图文模式</option>
						<option value="-4" <if condition="$slider.type eq -4">selected</if>>4图 图文模式</option>
						<option value="-5" <if condition="$slider.type eq -5">selected</if>>5图 图文模式</option>
					</select>
				</td>
			</tr>
			
			<tr>
				<th width="80">导航名称</th>
				<td><input type="text" class="input fl" name="name" size="20" value="{pigcms{$slider.name}" placeholder="请输入名称" validate="maxlength:20,required:true"/></td>
			</tr>
			<tr id="sub_title" <if condition="$slider['type'] gt 0 OR !isset($slider['type'])">style="display:none"</if>>
				<th width="80">导航副标题</th>
				<td><input type="text" class="input fl" name="sub_name" size="20" value="{pigcms{$slider.sub_name}"  placeholder="请输入名称" validate="maxlength:20"/></td>
			</tr>
			<tr>
				<th width="80">导航图片</th>
				<td><input type="file" class="input fl" name="pic" style="width:180px;" value="{pigcms{$slider.pic}"   placeholder="请上传图片" />
				<em tips="纯图片模式下建议尺寸：
				3图 纯图模式，第一张图206*266，第二、第三张图均是206*133。
				4图 图文模式，四张图均是206*97。
				5图 图文模式，第一、第二张图均是206*120，第三、第四、第五张图均是137*85。

				图文模式下建议尺寸：
				3图 图文模式，第一张图206*225，第二、第三张图均是70*70。
				4图 图文模式，四张图均是70*70。
				5图 图文模式，第一、第二张图均是206*120，第三、第四、第五张图均是137*85。" class="notice_tips"></em></td>
			</tr>
			<tr>
				<th width="80">链接地址</th>
				<td>
				<input type="text" class="input fl" name="url" id="url" style="width:180px;" placeholder="请填写链接地址"  value="{pigcms{$slider.url}" validate="maxlength:200,required:true,url:true"/>
				<if condition="$now_category['cat_type'] neq 1">
				<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url', 0, 0)" data-toggle="modal">从功能库选择</a>
				<else />
				<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url', 0, 1)" data-toggle="modal">从功能库选择</a>
				</if>
				</td>
			</tr>
			<tr>
				<th width="80">导航排序</th>
				<td><input type="text" class="input fl" name="sort" style="width:80px;"  value="{pigcms{$slider.sort}" validate="maxlength:10,required:true,number:true"/>
				<em tips="数值越高，排序靠前" class="notice_tips"></em>
				</td>
			</tr>
			
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
$(function(){
	$("#group_main_page_center_type").change(function(){
		if($(this).val()>0){
			$('#sub_title').hide();
		}else{
			$('#sub_title').show();
		}
	})
})
function addLink(domid,iskeyword, type){
	art.dialog.data('domid', domid);
	if (type == 1) {
		art.dialog.open('?g=Admin&c=LinkPC&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
	} else {
		art.dialog.open('?g=Admin&c=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
	}
}
</script>
<include file="Public:footer"/>