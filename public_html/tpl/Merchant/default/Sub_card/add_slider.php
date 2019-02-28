<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 免单编辑</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
		<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
		<style>
			a:hover,a:visited{color:#666;}
			.cb-enable, .cb-disable, .cb-enable span, .cb-disable span {
background: url(tpl/System/Static/css/img/form_onoff.png) repeat-x;
display: block;
float: left;
cursor: pointer;
}
.cb-enable .selected {
background-position: 0 -48px;
}
.cb-enable span, .cb-disable span {
font-weight: bold;
line-height: 24px;
background-repeat: no-repeat;
display: block;
}
.cb-enable span {
background-position: left -72px;
padding: 0 10px;
}
.cb-enable .selected span {
background-position: left -120px;
color: #fff;
}
.cb-enable input, .cb-disable input {
display: none;
}
.cb-disable span {
background-position: right -144px;
padding: 0 10px;
}
.cb-disable .selected {
background-position: 0 -24px;
}
.cb-disable .selected span {
background-position: right -168px;
color: #fff;
}
.button{
	    margin-left: 15px;
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
		</style>
	</head>
<body>
	<form id="myform" method="post" action="{pigcms{:U('Sub_card/add_slider')}" enctype="multipart/form-data">
		<input type="hidden" name="store_id" value="{pigcms{$_GET['store_id']}">
		<input type="hidden" name="sub_card_id" value="{pigcms{$_GET['sub_card_id']}">
		<input type="hidden" name="id" value="{pigcms{$_GET['id']}">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">导航名称</th>
				<td><input type="text" class="input fl" name="name" size="20" placeholder="请输入名称" value="{pigcms{$slider.name}" validate="maxlength:20,required:true"/></td>
				<td><font color="red">*最多支持5个字符</font></td>
			</tr>
			<tr>
				<th width="80">导航图片</th>
				<td><input type="file" class="input fl" name="pic" style="width:180px;" placeholder="请上传图片" value="{pigcms{$slider.pic}"/><if condition="$slider.pic neq ''"><img src="/upload/slider/{pigcms{$slider.pic}" style="width:30px;height:30px;    margin-left: 29px; position: absolute;"></if> </td>
				<td><font color="red">*建议大小80*80</font></td>
			</tr>
			<tr>
				<th width="80">链接地址</th>
				<td colspan="2">
				<input type="text" class="input fl" name="url" id="url" style="width:180px;" value="{pigcms{$slider.url}" placeholder="请填写链接地址" validate="maxlength:200,required:true,url:true"/>
				<if condition="$now_category['cat_type'] neq 1">
				<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url', 0, 0)" data-toggle="modal">从功能库选择</a>
				<else />
				<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url', 0, 1)" data-toggle="modal">从功能库选择</a>
				</if>
				</td>
			</tr>
	
			<tr>
				<th width="80">导航状态</th>
				<td colspan="2">
					<span class="cb-enable"><label class="cb-enable <if condition="$slider['status'] eq 1 OR !isset($slider)">selected</if>"><span>启用</span><input type="radio" name="status" value="1" <if condition="$slider['status'] eq 1 OR !isset($slider)">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$slider['status'] eq 0 AND isset($slider)">selected</if>"><span>关闭</span><input type="radio" name="status" value="0" <if condition="$slider['status'] eq 0  AND isset($slider)">checked="checked"</if>/></label></span>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			
		</div>
	</form>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
function addLink(domid,iskeyword, type){
	art.dialog.data('domid', domid);
	if (type == 1) {
		art.dialog.open('?g=Admin&c=LinkPC&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
	} else {
		art.dialog.open('?g=Admin&c=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
	}
}

$('input[name="status"]').click(function(){
	if($(this).val()==1){
		$('.cb-enable').addClass('selected')
		$('.cb-disable').removeClass('selected')
	}else{
		$('.cb-disable').addClass('selected')
		$('.cb-enable').removeClass('selected')
	}
})

</script>

	</body>
</html>