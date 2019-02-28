<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-tablet"></i>
				<a href="{pigcms{:U('service_info')}">便民服务</a>
			</li>
			<li class="active">修改信息</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<form  class="form-horizontal" method="post" onSubmit="return check_submit()" action="__SELF__">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="title">标题：</label></label>
									<input class="col-sm-2" size="20" name="title" id="title" type="text" value="{pigcms{$detail.title}"/>
								</div></div>
                               <div class="form-group">
									<label class="col-sm-1">选择分类：</label>
									<select style="margin-right:10px;" class="col-sm-1" name="cat_fid" id="choose_catfid">
                                        <volist name='cat_flist' id='vo'>
                                        	<option value="{pigcms{$key}" <if condition='$detail["cat_fid"] eq $key'>selected="selected"</if>>{pigcms{$vo}</option>
                                        </volist>
										</select>
									<select style="margin-right:10px;" class="col-sm-1" name="cat_id" id="choose_catid">
                                        <volist name='cat_slist' id='vo'>
                                        	<option value="{pigcms{$vo.id}" data-url="{pigcms{$vo.cat_url}" <if condition='$detail["cat_id"] eq $vo["id"]'>selected="selected"</if>>{pigcms{$vo.cat_name}</option>
                                        </volist>
                                     </select>
								</div>
                                
                                <div class="form-group">
									<label class="col-sm-1"><label for="sort">联系号码：</label></label>
									<input type="text" value="{pigcms{$detail['phone']}" id="phone" name="phone" size="20" class="col-sm-2">
                                    <label class="col-sm-3"><span class="red">*&nbsp;&nbsp;可不填写</span></label>
								</div>

                                <div class="form-group">
									<label class="col-sm-1"><label for="cat_url">链接：</label></label>
									<input class="col-sm-2" size="20" name="url" id="url" type="text" value="{pigcms{$detail['url']}"/>
                                    <label style=" margin-left:10px"><a href="#modal-table" class="btn btn-sm btn-success" onClick="addLink('url',0)" id="library-link">从功能库选择</a></label>
								</div>

                                
                                
                                <div class="form-group">
									<label class="col-sm-1">信息图标：</label>
									<a id="J_selectImage" class="btn btn-sm btn-success" href="javascript:void(0)">上传图片</a>
									<label style=" margin-left:10px"><span class="red">*&nbsp;&nbsp;信息图标可不填写</span></label>
								</div>
								<div class="form-group">
									<label class="col-sm-1">图标预览：</label>
									<div id="upload_pic_box">
										<ul id="upload_pic_ul">
                                            <if condition='$detail["img_path"]'><li class="upload_pic_li">
                                                <img src="/upload/service/{pigcms{$detail['img_path']}">
                                                <input type="hidden" value="{pigcms{$detail['img_path']}" name="cat_img"><br>
                                                <a onclick="deleteImg('{pigcms{$detail[\'img_path\']}',this);return false;" href="#">[ 删除 ]</a>
                                            </li></if>
                                        </ul>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="sort">信息排序：</label></label>
									<input type="text" value="{pigcms{$detail['sort']}" id="sort" name="sort" size="20" class="col-sm-2">
                                    <label class="col-sm-3"><span class="red">*&nbsp;&nbsp;可不填写（排序值越大，越靠前显示）</span></label>
								</div>
								<div class="form-group">
									<label class="col-sm-1">状态</label>
									
										<label style="padding-left:0px;padding-right:20px;"><input type="radio" <if condition='$detail["status"] eq 1'>checked="checked"</if> class="ace" value="1" name="status"><span style="z-index: 1" class="lbl">开启</span></label>
										<label style="padding-left:0px;"><input type="radio" <if condition='$detail["status"] eq 0'>checked="checked"</if> class="ace" value="0" name="status"><span style="z-index: 1" class="lbl">关闭</span></label>
								</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit" <if condition="!in_array(174,$house_session['menus'])">disabled="disabled"</if>>
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<style>
.BMap_cpyCtrl{display:none;}
input.ke-input-text {
background-color: #FFFFFF;
background-color: #FFFFFF!important;
font-family: "sans serif",tahoma,verdana,helvetica;
font-size: 12px;
line-height: 24px;
height: 24px;
padding: 2px 4px;
border-color: #848484 #E0E0E0 #E0E0E0 #848484;
border-style: solid;
border-width: 1px;
display: -moz-inline-stack;
display: inline-block;
vertical-align: middle;
zoom: 1;
}
.form-group>label{font-size:12px;line-height:24px;}
#upload_pic_box{margin-top:20px;height:150px;}
#upload_pic_box .upload_pic_li{width:130px;float:left;list-style:none;}
#upload_pic_box img{width:70px;height:70px;border:1px solid #ccc;}
</style>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
function addLink(domid,iskeyword){
	art.dialog.data('domid', domid);
	art.dialog.open('?g=shequ&c=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:760,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
}

KindEditor.ready(function(K) {	
	var editor = K.editor({
		allowFileManager : true
	});
	K('#J_selectImage').click(function(){
		if($('.upload_pic_li').size() >= 1){
			alert('最多上传1个图片！');
			return false;
		}
		editor.uploadJson = "{pigcms{:U('ajax_upload_pic')}";
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				showRemote : false,
				imageUrl : K('#course_pic').val(),
				clickFn : function(url, title, width, height, border, align) {
					$('#upload_pic_ul').append('<li class="upload_pic_li"><img src="'+url+'"/><input type="hidden" name="img_path" value="'+title+'"/><br/><a href="#" onclick="deleteImg(\''+title+'\',this);return false;">[ 删除 ]</a></li>');
					editor.hideDialog();
				}
			});
		});
	});
	})
	
	function deleteImg(path,obj){
		$.post("{pigcms{:U('ajax_del_pic')}",{path:path});
		$(obj).closest('.upload_pic_li').remove();
	}



var cat_id = $('#choose_catfid').val();
//get_son_category(cat_id);
var url = $('#choose_catid').find('option:selected').data('url');
change_url(url);

$('#choose_catfid').change(function(){
		var url = "{pigcms{:U(ajax_get_category)}";
		var cat_id = $(this).val();
		if(!cat_id){
			return;
		}
		
		get_son_category(cat_id);
		
		var url = $('#choose_catid').find('option:selected').data('url');
		change_url(url);
	});
	
	$('#choose_catid').change(function(){
		var url = $(this).find('option:selected').data('url');
		change_url(url);
	});
	
	function change_url(url){
		if(url){
			$('#url').val(url);
			$('#url').attr('readonly',true);
			$('#library-link,#show_map_frame').parent().hide();
		}else{
			$('#url').val('{pigcms{$detail["url"]}');
			$('#library-link,#show_map_frame').parent().show();
			$('#url').attr('readonly',false);
		}
	}
	
	function get_son_category(cat_id){
		$('#url').val('');
		$('#library-link,#show_map_frame').parent().show();
		$('#url').attr('readonly',false);
		
		var url = "{pigcms{:U(ajax_get_category)}";
		$.post(url,{'cat_id':cat_id},function(result){
			var html = '';
			if(result['status'] == 1){
				var cat_list = result.cat_list;
				for(var i in cat_list){
					html +='<option value="'+ cat_list[i]['id'] +'" data-url="'+cat_list[i]['cat_url']+'">' + cat_list[i]['cat_name'] + '</option>';  
				}
                $('#choose_catid').html(html);
				
				url = $('#choose_catid').find('option:selected').data('url');
				change_url(url);
            } else {  
                $("#choose_catid").html(html);
            }
		},'json');
	}

	function check_submit(){
		if(confirm('确认保存？')){
			return true;
		}else{
			return false;
		}
	}
</script>




<include file="Public:footer"/>