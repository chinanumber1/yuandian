<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
                <i class="ace-icon fa fa-gear gear-icon"></i>
                <a href="{pigcms{:U('door_list')}">门禁设置</a>
            </li>
            <li><a href="{pigcms{:U('door_user',array('door_id'=>$door_id))}">用户列表</a></li>
            <li class="active">添加用户</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<form class="form-horizontal" method="post" id="edit_form">
						<div class="tab-content">
							<div class="form-group">
								<label class="col-sm-1">选择全部<input type="checkbox" class="col-sm-3" id="select"/></label>
							</div>
							<div id="txtstore" >
								<div class="form-group">
									<div id="upload_pic_box">
										<ul id="upload_pic_ul">
											<if condition="$aUser">
											<volist name="aUser" id="vo">
												<li class="upload_pic_li">
												<img src="{pigcms{$vo.avatar}" style="width:70px;" title="{pigcms{$vo.name}"/><br/>
												<label for="{pigcms{$vo.pigcms_id}" style="width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><input type="checkbox" name="openid[]" value="{pigcms{$vo.pigcms_id}" id="{pigcms{$vo.pigcms_id}" class="user_select"/>{pigcms{$vo.name}</label>
												</li>
											</volist>
											<else/>
				                                <tr class="odd"><td class="button-column" colspan="11" >用户已经添加完，没有找到新用户。</td></tr>
				                            </if>
										</ul>
									</div>
								</div>
							</div>
							<!--<div class="widget-box">
								<div class="widget-header">
									<h5>选择群发内容</h5>
								</div>

								<div class="widget-body" style="padding:20px;">
									<select name="source_id" id="source_id">
									<volist name="list" id="vo">
									<option value="{pigcms{$vo['pigcms_id']}" <if condition="$other['from_id'] eq $vo['pigcms_id']">selected</if>>{pigcms{$vo['list'][0]['title']}<if condition="$vo['type']">（多图）<else />（单图）</if></option>
									</volist>
									</select>
								</div>
							</div>-->
							<div style="clear:both;"></div>
							<div class="form-actions">
								<button class="btn btn-info" type="button" id="submit">
								<i class="ace-icon fa fa-check bigger-110"></i>
								提交
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
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
#upload_pic_box img{width:100px;height:70px;}

.small_btn{
margin-left: 10px;
padding: 6px 8px;
cursor: pointer;
display: inline-block;
text-align: center;
line-height: 1;
letter-spacing: 2px;
font-family: Tahoma, Arial/9!important;
width: auto;
overflow: visible;
color: #333;
border: solid 1px #999;
-moz-border-radius: 5px;
-webkit-border-radius: 5px;
border-radius: 5px;
background: #DDD;
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#FFFFFF', endColorstr='#DDDDDD');
background: linear-gradient(top, #FFF, #DDD);
background: -moz-linear-gradient(top, #FFF, #DDD);
background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#FFF), to(#DDD));
text-shadow: 0px 1px 1px rgba(255, 255, 255, 1);
box-shadow: 0 1px 0 rgba(255, 255, 255, .7), 0 -1px 0 rgba(0, 0, 0, .09);
-moz-transition: -moz-box-shadow linear .2s;
-webkit-transition: -webkit-box-shadow linear .2s;
transition: box-shadow linear .2s;
outline: 0;
}
.small_btn:active{
border-color: #1c6a9e;
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#33bbee', endColorstr='#2288cc');
background: linear-gradient(top, #33bbee, #2288cc);
background: -moz-linear-gradient(top, #33bbee, #2288cc);
background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#33bbee), to(#2288cc));
}
</style>
<script type="text/javascript">
//$('#submit').click(function(){
//	window.location.href = "{pigcms{:U('door_add_user')}";
//})
$(document).ready(function(){
	var door_id	=	'{pigcms{$door_id}'
	$("#select").click(function(){
		if ($(this).attr('checked')) {
			$('.user_select').attr('checked', true);
		} else {
			$('.user_select').attr('checked', false);
		}
	});
//	$("#submit").attr('disabled', true);
//	get_list(1);

	$('#submit').click(function(){
		var openids = '', pre = '';
		$('.user_select').each(function(){
			if ($(this).attr('checked')) {
				openids += pre + $(this).val();
				pre = ',';
			}
		});

		$.post('{pigcms{:U('door_add_user')}', {'pigcms':openids,'door_id':door_id}, function(data) {
			if (data.status) {
				alert(data.info);
			setTimeout(function(){
				location.href = data.url;
			},1000);
			}else{
				alert(data.info);
			}
		}, 'json');
	});
});

//function get_list(page)
//{
//	$.get("{pigcms{:U('Customer/ajaxsend')}", {'page':parseInt(page + 1)}, function(data){
//		if (data.error_code) {
//			$("#submit").attr('disabled', false);
//			$('.alert-danger').remove();
//		} else {
//			var html = '';
//			$.each(data.data, function(i, val){
//				html += '<li class="upload_pic_li">';
//				html += '<img src="'+val.avatar+'" style="width:70px;"/><br/>';
//				html += '<label for="'+val.openid+'" style="width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><input type="checkbox" name="openid[]" value="'+val.openid+'" id="'+val.openid+'" class="user_select"/>'+val.nickname+'</label>';
//				html += '</li>';
//			});
//			$('#upload_pic_ul').append(html);
//			get_list(data.page);
//		}
//	}, 'json');
//}
</script>
<include file="Public:footer"/>
