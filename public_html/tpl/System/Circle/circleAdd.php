<include file="Public:header"/>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
	<form id="myform" method="post" action="{pigcms{:U('Circle/circleAdd')}" frame="true" refresh="true">
		<input type="hidden" name="id" value="0"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">

			<tr>
				<th width="80">名称</th>
				<td><input type="text" class="input fl" name="name" value="" size="20" placeholder="请输入名称" validate="maxlength:30,required:true"/></td>
			</tr>
              <tr>
				<th width="80">图标</th>
				<td >
				<input type="hidden" class="input fl" name="logo" id="icon_value" value="{pigcms{$now_category.logo}" size="25" placeholder="" />
                
				<img src="{pigcms{$config.site_url}{pigcms{$now_category.logo}" id="icon" style="height:30px;width:30px;">
                
				<a href="javascript:void(0)" class="button" id="image1">更换图标</a>
				</td>
			</tr>
			<tr>
				<th width="80">圈子描述</th>
				<td><input type="text" class="input fl" name="title" id="title" value="{pigcms{$now_category.title}" size="25" /></td>
			</tr>
			
			<tr>
				<th width="80">父类</th>
				<td>
				<select name="cate_id" validate="required:true">
				<volist name="cateList" id="vo">
				<option value='{pigcms{$vo.id}'>{pigcms{$vo.name}</option>
				</volist>
				</select></td>
			</tr>

			<tr>
				<th width="80">状态</th>
				<td>
				<select name="status">
				<option value="0">启用</option>
				<option value="1">不启用</option>
				</select>
				</td>
			</tr>
			
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<script type="text/javascript">
	get_first_word('area_name','area_url','first_pinyin');
</script>	
<script>
            KindEditor.ready(function(K) {
                var editor = K.editor({
                    allowFileManager : true
                });
                K('#image1').click(function() {
                    editor.uploadJson = "{pigcms{:U('Circle/ajax_upload_pic')}";
                    editor.loadPlugin('image', function() {
                        editor.plugin.imageDialog({
                            showRemote : false,
                            //imageUrl : K('#url3').val(),
                            clickFn : function(url, title, width, height, border, align) {
                                K('#icon_value').val(url);
                                var myurl = '{pigcms{$config.site_url}'+url;
                                K('#icon').attr('src',myurl);
                                editor.hideDialog();
                            }
                        });
                    });
                });
             });
                </script>
<include file="Public:footer"/>