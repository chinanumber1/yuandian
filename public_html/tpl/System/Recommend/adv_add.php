<include file="Public:header"/>
	<div id="myform">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">城市</th>
				<td>
					<select onclick="province_changed($(this).val())">
                    <option value="0">选择省</option>
                    <if condition="$area_list">
                    <volist name="area_list" id="vo">
                        <option value="{pigcms{$vo.area_id}">{pigcms{$vo.area_name}</option>
                    </volist>
                    </if>
                    </select>
                    <select id="city"></select>
				</td>
			</tr>

			<tr>
				<th width="80">楼盘名称</th>
				<td><input type="text" class="input fl" id="building_name" /></td>
			</tr>
			<tr>
				<style type="text/css">
					.imgs{width:80px;height:50px;border:solid 1px #ccc;margin-right:5px;}
					.imgs img{width: 80%;height: 86%;padding:3px;}
					.imgs a{position: absolute;float:right;color:red;}
				</style>
				<th width="80">上传图片</th>
				<td><div id="main_img"></div><input type="button" id="J_selectImage" value="上传图片" /></td>
			</tr>
			<tr>
				<th width="80">广告链接</th>
				<td><input type="text" class="input f1" id="url" /></td>
			</tr>
			<tr>
				<th width="80">推荐描述</th>
				<td><textarea id="desc"></textarea></td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" onclick="save()" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</div>
	<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
	<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
	<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
	<script type="text/javascript">
		KindEditor.ready(function(K){
			var editor = K.editor({
				allowFileManager : true
			});
			K('#J_selectImage').click(function(){
				editor.uploadJson = "{pigcms{:U('Recommend/ajax_upload_pic')}";
				editor.loadPlugin('image', function(){
					editor.plugin.imageDialog({
						showRemote : false,
						imageUrl : K('#course_pic').val(),
						clickFn : function(url, title, width, height, border, align) {
								var main_img_html = '<div class="col-sm-1 imgs"><img src="'+url+'" /><a href="javascript:;" onclick="del_img(this)">&times</a></div>';
								$('#main_img').html(main_img_html);
								$('#J_selectImage').hide();
							editor.hideDialog();
						}
					});
				});
			});
		});

		function province_changed(province_id){
            var province = parseInt(province_id);
            if(isNaN(province)){
                layer.alert('省份错误');
                return;
            }
            if(province==0){
                $('#city').html('');
                return;
            }

            $.get("{pigcms{:U('Recommend/get_citys')}",{'province_id':province},function(response){
                if(response.code>0){
                    layer.alert(response.msg);
                    return;
                }
                var html = '';
                $.each(response.msg,function(i,v){
                    html += '<option value="'+v.area_id+'" area_url="'+v.area_url+'">'+v.area_name+'</option>';
                });
                $('#city').html(html);
            },'json');
        }

		function del_img(obj){
			$(obj).parent('div').remove();
			var imgs = $('#main_img').children('div').children('img');
			if(imgs.length<=0){
				$('#J_selectImage').show();
			}
		}

		function save(){
			var data = new Object();
			data.city_id = $('#city').val();
			data.building_name = $.trim($('#building_name').val());
		  	data.img = $('#main_img div').children('img').attr('src');
		  	data.url = $('#url').val();
		  	data.desc = $('#desc').val();
		  	if(data.city_id <= 0){
		  		alert('请选择城市');
		  		return;
		  	}
		  	if(data.building_name==''){
		  		alert('请填写楼盘名称');
		  		return;
		  	}
		  	if(data.img==''){
		  		alert('请上传楼盘图片');
		  		return;
		  	}
		  	if(data.url == ''){
		  		alert('请填写广告链接');
		  		return;
		  	}

		  	$.post("{pigcms{:U('save_adv_img')}",data,function(response){
		  		alert(response.err_msg);
		  		if(response.err_code==0){
		  			window.location.reload();
		  		}
		  	},'json');
		}
	</script>
	
<include file="Public:footer"/>