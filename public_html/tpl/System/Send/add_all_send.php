<include file="Public:header"/>
	<div class="mainbox">
		<div id="nav" class="mainnav_title">
			<a href="{pigcms{:U('Send/all_send')}" >群发列表</a>
			<a href="{pigcms{:U('Send/add_all_send')}" class="on">创建群发</a>
		</div>
		<form method="post" id="myform" action="{pigcms{:U('multi')}" refresh="true" >
			<input type="hidden" name="id" value="{pigcms{$_GET.id}"/>
			<table cellpadding="0" cellspacing="0" class="table_form" width="100%">
			<tr>
				<th valign="top"><label for="keyword">图文消息</label></th>
				<td>
					<table cellpadding="0" cellspacing="0" class="table_form" width="100%">
							
							<tr>
								<th  width="120">群发标题：</td>
								<td><input type="text" class="input-text"  name="title" value="{pigcms{$send_log['title']}" validate="required:true,maxlength:16" <if condition="$_GET['edit'] eq 0 ">disabled</if> /></td>
								
								
							</tr>
							<if condition="$_GET['edit'] neq 0 ">
							<tr>
								<th  width="120">选择图文消息：</th>
								<td><a href="###" onclick="addImgMessage()" class="a_choose ">添加图文消息</a>&nbsp;&nbsp;<a href="###" onclick="clearMessage()" class="a_clear <if condition="$_GET['edit'] eq 0  AND isset($_GET['edit'])">hidden</if>">清空重选</a></td>
							</tr>
							</if>
							<tr>
								<th  width="120">消息群发方式：</th>
								<td><select name="send_type" class="send_type" style="width:100px;" <if condition="$_GET['edit'] eq 0 ">disabled</if>>
									<option value="0" <if condition="$send_log['send_type'] eq 0"> selected="selected"</if>>按粉丝等级</option>
									<option value="1" <if condition="$send_log['send_type'] eq 1"> selected="selected"</if>>指定粉丝</option>
									<option value="2" <if condition="$send_log['send_type'] eq 2"> selected="selected"</if>>所有粉丝</option>
								</select>
								</td>
							</tr>
							<tr>
								<th  width="120">发送对象：</th>
								<td>
									<div class="class_0">等级选择</div>
									<select name="level" class="class_0" style="width:100px;" <if condition="$_GET['edit'] eq 0 ">disabled</if>>
									<volist name="levelarr" id="vo">
										<option value="{pigcms{$vo['level']}" <if condition="$send_log['send_to'] eq $vo['level']"> selected="selected"</if>>{pigcms{$vo['lname']}</option>
									</volist>
									</select>
							
									<if condition="$_GET['edit'] neq 0 ">
									<a href="###" onclick="addFans()" class="a_choose class_1">添加粉丝</a>&nbsp;&nbsp;<a href="###" onclick="clearUsers()" class="a_clear class_1">清空重选</a></if>
									<div class="tags class_1" id="tags" tabindex="1"> 
										<if condition="!empty($user_list)">
											<volist name="user_list" id="vo">
												<span class='tag'>{pigcms{$vo.nickname}<if condition="$_GET['edit'] neq 0"><button class='close' data-uid="{pigcms{$vo.uid}" type='button'>×</button></if></span>
											</volist>
										</if>
										<input id="form-field-tags" type="text" placeholder="请输入标签 ..." value="Tag Input Control" name="tags" style="display: none;"/>

										<input type="text"  class="tags_enter" autocomplete="off">
										
										<input type="hidden" class="px" id="uids" value="{pigcms{$send_log.send_to}" name="uids" style="width:300px" >
									</div>
									<div class="class_2">所有用户</div>
								</td>
							</tr>
							<tr>
								<th  width="120">发送方式：</th>
								<td>
									<select name="send_now" class="send_now" style="width:100px;" <if condition="$_GET['edit'] eq 0 ">disabled</if>>
										<option value="0" <if condition="$send_log['send_now'] eq 0"> selected="selected"</if>>指定时间</option>
										<option value="1" <if condition="$send_log['send_now'] eq 1"> selected="selected"</if>>立刻发送</option>
										
									</select>
								</td>
							</tr>
							
							<tr class="send_on_time" <if condition="$send_log['send_now'] eq 1">style="display:none"</if>>
								<th  width="120">指定时间：</th>
								<td><input type="text" class="input-text" name="send_time" style="width:160px;" id="d4311" <if condition="$_GET['edit'] eq 0 ">disabled</if>  value="<if condition="$send_log.dateline gt 0">{pigcms{$send_log.dateline|date="Y-m-d H:i:s",###}</if>" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:00'})"/></td>
							</tr>
					</table>
				</td>
				<td>
				
					<div class="chatPanel" style="width:280px;<if condition="!empty($image_text) AND count($image_text) gt 1">display:none;</if>" id="singlenews">
						<div un="item_1741035" class="chatItem you"> 
							<a target="ddd" href="javascript:void(0);">
							<div class="media mediaFullText" id="titledom">
								<div class="mediaPanel">
									<div class="mediaHead"><span class="title" id="zbt">图文消息标题</span><span class="time"><?php echo date('Y-m-d',time());?></span>
										<div class="clr"></div>
									</div>
									<div class="mediaImg"><img id="suicaipic1" src="<if condition="!empty($image_text) AND count($image_text) eq 1">{pigcms{$image_text.0.cover_pic}<else />/tpl/Static/default/images/oid.jpg</if>"></div>
									<div class="mediaContent mediaContentP">
										<p id="zinfo"><if condition="!empty($image_text) AND count($image_text) eq 1">{pigcms{$image_text.0.title}<else />消息简介</if></p>
									</div>
									<div class="mediaFooter">
										<span class="mesgIcon right"></span><span style="line-height:50px;" class="left">查看全文</span>
										<div class="clr"></div>
									</div>
								</div>
							</div>
							</a>
						</div>
					</div>  
					<div style="clear:both"></div>
					<input type="hidden" class="px" id="imgids" value="{pigcms{$ids}" name="imgids" style="width:300px" >  <br>
					<div class="media_preview_area" id="multinews" <if condition="empty($image_text) OR  count($image_text) elt 1">style="display:none"</if>>
						<div class="appmsg multi editing">
							<div id="js_appmsg_preview" class="appmsg_content">
								<if condition="!empty($image_text) AND count($image_text) gt 1">
									<volist name="image_text" id="vo">
										<php>if($i==1){</php>
										<div id="appmsgItem1" data-fileid="" data-id="1" class="js_appmsg_item ">
											<div class="appmsg_info">
												<em class="appmsg_date"></em>
											</div>
											<div class="cover_appmsg_item" id="multione">
												<h4 class="appmsg_title"><a href="javascript:void(0);" onClick="return false;" target="_blank">{pigcms{$vo.title}</a></h4><div class="appmsg_thumb_wrp"><img style="border:1px solid #ddd" class="js_appmsg_thumb appmsg_thumb" src="{pigcms{$vo.cover_pic}"><i class="appmsg_thumb default" style="background:url({pigcms{$vo.cover_pic});background-size:100% 100%">&nbsp;</i></div>
											</div>
										</div>
										<php>}else{</php>
										<div id="appmsgItem4" data-fileid="" data-id="4" class="appmsg_item js_appmsg_item "><img class="js_appmsg_thumb appmsg_thumb" src="{pigcms{$vo.cover_pic}"><i class="appmsg_thumb default" style="background:url({pigcms{$vo.cover_pic});background-size:100% 100%">&nbsp;</i><h4 class="appmsg_title"><a onClick="return false;" href="javascript:void(0);" target="_blank">{pigcms{$vo.title}</a></h4></div>
										
										<php>}</php>
									</volist>
								<else />
									<div id="appmsgItem1" data-fileid="" data-id="1" class="js_appmsg_item ">
										<div class="appmsg_info">
											<em class="appmsg_date"></em>
										</div>
										<div class="cover_appmsg_item" id="multione"></div>
									</div>
								</if>
							</div>
						</div>
					</div>		  
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr <if condition="$_GET['edit'] eq 0 AND isset($_GET['edit'])">class="hidden"</if>>
				<th></th>
				<td><button type="submit" class="button" >保存</button>　<a href="javascript:history.go(-1);" class="button">取消</a></td>
			</tr>
				
			</table>
			
		</form>
	</div>
<script>
$(function() {
	$(".tags_enter").blur(function() { //焦点失去触发 
		var txtvalue=$(this).val().trim();
		if(txtvalue!=''){
			addTag($(this));
			$(this).parents(".tags").css({"border-color": "#d5d5d5"})
		}
	}).keydown(function(event) {
		var key_code = event.keyCode;
		var txtvalue=$(this).val().trim(); 
		if (key_code == 13&& txtvalue != '') { //enter
			addTag($(this));
		}
		if (key_code == 32 && txtvalue!='') { //space
			addTag($(this));
		}
	});
	$(".close").live("click", function() {
		$(this).parent(".tag").remove();
		var delete_name = $(this).data('uid')+',';
		var userlist = $('#uids').val();
		$('#uids').val(userlist.replace(delete_name,''))
		
	});
	$(".tags").click(function() {
		$(this).css({"border-color": "#f59942"})
	}).blur(function() {
		$(this).css({"border-color": "#d5d5d5"})
	})
	$(".class_0,.class_1,.class_2").hide();
	<if condition="isset($send_log['send_type'])">
	$(".class_{pigcms{$send_log['send_type']}" ).show();
	<else />
	$(".class_0" ).show();
	</if>
	$('.send_type').change(function(){
		$(".class_0,.class_1,.class_2").hide();
		$(".class_" + $(this).val()).show();
	})
	
	$('.send_now').change(function(){
		console.log($(this).val())
		if($(this).val()==1){
			$(".send_on_time").hide();
		}else{
			$(".send_on_time").show();
		}
	})
})
function addTag(obj) {
	var tag = obj.val();
	if (tag != '') {
		var i = 0;
		$(".tag").each(function() {
			if ($(this).text() == tag + "×") {
				$(this).addClass("tag-warning");
				setTimeout("removeWarning()", 400);
				i++;
			}
		})
		obj.val('');
		if (i > 0) { //说明有重复
			return false;
		}
		$("#form-field-tags").before("<span class='tag'>" + tag + "<button class='close' type='button'>×</button></span>"); //添加标签
	}
}
function removeWarning() {
	$(".tag-warning").removeClass("tag-warning");
}

function addImgMessage(){
	art.dialog.data('titledom', 'titledom');
	art.dialog.data('imgids', 'imgids');
	art.dialog.data('multinews', 'multinews');
	art.dialog.data('singlenews', 'singlenews');
	art.dialog.data('js_appmsg_preview', 'js_appmsg_preview');
	art.dialog.data('multione', 'multione');
	art.dialog.open('?g=System&c=Weixin_article&a=select_img',{lock:true,title:'选择图文消息',width:600,height:400,yesText:'关闭',background: '#000',opacity: 0.45});
}

function addFans(){
	art.dialog.data('titledom', 'titledom');
	art.dialog.data('imgids', 'imgids');
	art.dialog.data('multinews', 'multinews');
	art.dialog.data('singlenews', 'singlenews');
	art.dialog.data('js_appmsg_preview', 'js_appmsg_preview');
	art.dialog.data('multione', 'multione');
	art.dialog.open('?g=System&c=User&a=select_user',{lock:true,title:'选择粉丝',width:600,height:400,yesText:'关闭',background: '#000',opacity: 0.45});
}
 function clearMessage(){
 	document.getElementById('titledom').innerHTML='';
 	document.getElementById('imgids').value='';
 	document.getElementById('js_appmsg_preview').innerHTML='<div class="appmsg_info"><em class="appmsg_date"></em></div><div class="cover_appmsg_item" id="multione"></div>';
 	document.getElementById('multinews').style.display='none';
 	document.getElementById('singlenews').style.display='';
}

 function clearUsers(){
 	$('.tag').remove();
}
</script> 
		  
	
<style>
  html, body {
	color:#222;
	font-family:Microsoft YaHei, Helvitica, Verdana, Tohoma, Arial, san-serif;
	background-color:#ffffff;
	margin:0;
	padding: 0;
	text-decoration: none;
}
body >.tips {
	position:fixed;
	display:none;
	top:50%;
	left:50%;
	z-index:100;
	text-align:center;
	padding:20px;
	width:200px;
}
body, div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6, pre, code, form, fieldset, legend, input, textarea, p, blockquote, th, td {
	margin:0;
	padding:0;
}
table {
	border-collapse:collapse;
	border-spacing:0;
}
.text img {
	max-width: 100%;
}
fieldset, img {
	border:0;
}
address, caption, cite, code, dfn, em, th, var {
	font-style:normal;
	font-weight:normal;
}
ol, ul {
	list-style: none outside none;
	margin:0;
	padding: 0;
}
caption, th {
	text-align:left;
}
h1, h2, h3, h5, h6 {
	font-size:100%;
	font-weight:normal;
}
a {
	color:#000000;
	text-decoration: none;
}
.left {
	float:left
}
.right {
	float:right
}
#activity-detail {
	padding:15px 15px 0;
	background:#EFEFEF;
}
.clr {
	display:block;
	clear:both;
	height:1px;
	overflow:hidden;
}
/*文本*/
#iphone {
	background:url(../images/iPhone-render.png) no-repeat 0 0;
	height: 743px;
	position:relative;
	margin: 0 auto;
	overflow:hidden;
	width: 380px;
}
#iphone #activity-detail {
	height: 414px;
	left: 33px;
	overflow: auto;
	padding: 0;
	position: absolute;
	top: 197px;
	width: 319px;
	background:#EFEFEF;
}
#iphone .nickname {
	color: #CCCCCC;
	display: block;
	font-weight: bold;
	line-height: 45px;
	position: absolute;
	text-align: center;
	text-shadow: 0 1px 3px #000000;
	top: 152px;
	left: 33px;
	width: 320px;
}
#news-render {
}
#news-text { 
}
.keywordtext {
	background-color: #F3F1DA;
	height: 366px;
	overflow: auto;
	padding: 0;
	width: 319px;
	left: 33px;
	top: 197px;
	position: absolute;
}
.keywordtext .me {
	margin-top:30px
}
.you {
	float:left;
	width:100%; /*ie6 hack*/
	_background:none;
	_border:none;
}
.me {
	float:right;
	width:100%;
}
.chatItemContent {
	cursor:pointer;
}
.cloudPannel {
	position: relative;
	_position:static;
}
.chatItem {
	padding:4px 0px 10px 0px;
	_background:none;
	_border:none;
}
.chatItem .avatar {
	width:38px;
	height:38px;
	border:1px solid #ccc\9;
	border: 1px solid #CCCCCC;
	box-shadow: 0 1px 3px #D3D4D5;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
}
.chatItem .cloud {
	max-width:240px; /*border-radius:11px; border-width:1px; border-style:solid; */
	cursor:default;
	position: static;
}
.chatItem .cloud {/*for ie*/
	/*position: relative;*/
		padding: 0px;
	margin: 0px;
}
.me .avatar {
	float:right;
}
.me .cloud { /*position:relative;*/
	float:right;
	min-width:50px;
	max-width:200px;
	margin:0 15px 0 0;
}
.chatItem .cloudContent { /* position:relative;for ie*/
	text-align:left; /*padding:2px; line-height:1.2; */
	font-weight:normal;
	font-size:16px;
	min-height:20px;
	word-wrap:break-word;
}
.me .cloudText .cloudBody {
	-moz-border-top-colors:none;
	-moz-border-right-colors:none;
	-moz-border-bottom-colors:none;
	-moz-border-left-colors:none;
	border-color:transparent;
	border:1px solid #AFAFAF;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	box-shadow: 0px 1px 3px #D5D5D5;
	border:1px solid #9f9f9f\9;
	background:#ECECEC\9;
	border-radius:6px\9;
	margin-left:8px;
}
.me .cloudContent {
	border:1px solid #eee\9;
	border-top:1px solid #FFF;
	border-bottom:1px solid #F2F2F2;
	padding:13px\9;
	border-radius:13px\9;
	border-radius:4px;
	-moz-border-radius:4px;
	-webkit-border-radius:4px;
	overflow:hidden;
	color:#000;
	text-shadow:none;
	background-color:#ECECEC;
	background:-webkit-gradient(linear,  left top, left bottom,  from(#F4F4F4), to(#E5E5E5),  color-stop(0.1, #F3F3F3), color-stop(0.3, #F1F1F1), color-stop(0.5, #ECECEC), color-stop(0.7, #E9E9E9), color-stop(0.9, #E6E6E6), color-stop(1.0, #E5E5E5));
	background-image:-moz-linear-gradient(top, #F3F3F3 10%, #F1F1F1 30%, #ECECEC 50%, #E9E9E9 70%, #E6E6E6 90%, #E5E5E5 100%);
}/*.cloudText*/
.me .cloudText .cloudArrow {
	position: absolute;
	right: -10px;
	top: 11px;
	width: 13px;
	height: 24px;
	background: url(../images/bubble_right.png) no-repeat;
}
.me .cloudText .cloudContent {
	background-color:#E5E5E5;
	vertical-align: top;
	padding:7px 10px;
	background-color:#ECECEC\9;
}
.you .avatar {
	float:left;
}
.you .cloud { /*position:relative;8.3*/
	float:left;
	min-width:50px;
	max-width:200px;
	margin:0 0 0 15px;
}
.you .cloudText .cloudBody {
	-moz-border-top-colors:none;
	-moz-border-right-colors:none;
	-moz-border-bottom-colors:none;
	-moz-border-left-colors:none;
	border-color:transparent;
	border: 1px solid #7AA23F;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	box-shadow: 0px 1px 3px #8DA254;
	border:1px solid #73972a\9;
	border-radius:6px\9;
		background-color: #AEDC43;
}
.you .cloudText .cloudContent {
	padding:5px 13px\9;
	border-radius:13px\9;
	border-radius:5px;
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	padding:7px 10px;
	text-shadow:none;
	color:#030303;
	border-top: 1px solid #DCE6C8;
	border-bottom: 1px solid #B9CF8B;
	border-right: 1px solid #CCDEA3;
}
.you .cloudText .cloudArrow {
	position: absolute;
	left: -6px;
	top: 11px;
	width: 13px;
	height: 24px;
	background: url(../images/bubble_left.png) no-repeat;
}
/*单条多条图文*/
.chatPanel .media a {
	display:block;
}
.chatPanel .media {
	border:1px solid #cdcdcd;
	box-shadow:0 3px 6px #999999;
	-webkit-border-radius:12px;
	-moz-border-radius:12px;
	border-radius:12px;
	width:285px;
	background-color:#FFFFFF;
	background:-webkit-gradient(linear,  left top, left bottom,  from(#FFFFFF), to(#FFFFFF));
	background-image:-moz-linear-gradient(top, #FFFFFF 0%, #FFFFFF 100%);
	margin:0px auto;
}
.chatPanel .media .mediaPanel {
	padding:0px;
	margin:0px;
}
.chatPanel .media .mediaImg {
	margin: 25px 15px 15px;
	width: 255px;
	position: relative;
}
.chatPanel .media .mediaImg .mediaImgPanel {
	position:relative;
	padding:0px;
	margin:0px;
	max-height:164px;
	overflow:hidden;
}
.chatPanel .media .mediaImg img {
		width:255px;
}
.chatPanel .media .mediaImg .mediaImgFooter {
	position: absolute;
	bottom: 0;
	height:29px;
	background-color:#000;
	background-color:rgba(0, 0, 0, 0.4);
	text-shadow:none;
	color:#FFF;
	text-align:left;
	padding:0px 11px;
	line-height:29px;
	width:233px;
}
.chatPanel .media .mediaImg .mediaImgFooter a:hover p {
	color:#B8B3B3;
}
.chatPanel .media .mediaImg .mediaImgFooter .mesgTitleTitle {
	line-height:28px;
	color:#FFF;
	max-width:240px;
	height:26px;
	white-space:nowrap;
	text-overflow:ellipsis;
	-o-text-overflow:ellipsis;
	overflow:hidden;
	width: 240px;
}
.chatPanel .media .mesgIcon {
	display:inline-block;
	height:19px;
	width:13px;
	margin:8px 0px -2px 4px;
	/*background:url(../images/mesgIcon.png) no-repeat;*/
}
.chatPanel .media .mediaContent {
	margin:0px;
	padding:0px;
}
.chatPanel .media .mediaContent .mediaMesg {
	border-top:1px solid #D7D7D7;
	padding:10px;
}
.chatPanel .media .mediaContent .mediaMesg .mediaMesgDot {
	display: block;
	position:relative;
	top: -3px;
	left:20px;
	height:6px;
	width:6px;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 3px;
}
.chatPanel .media .mediaContent .mediaMesg .mediaMesgTitle:hover p {
	color:#1A1717;
}
.chatPanel .media .mediaContent .mediaMesg .mediaMesgTitle a {
	color:#707577;
}
.chatPanel .media .mediaContent .mediaMesg .mediaMesgTitle a:hover p {
	color:#444440;
}
.chatPanel .media .mediaContent .mediaMesg .mediaMesgIcon {
}
.chatPanel .media .mediaContent .mediaMesg .mediaMesgTitle p {
	line-height:1.5em;
	max-height: 45px;
	max-width: 220px;
	min-width:176px;
	margin-top:2px;
	color:#5D6265;
	text-overflow:ellipsis;
	-o-text-overflow:ellipsis;
	overflow:hidden;
	text-align: left;
	text-overflow:ellipsis;
}
.chatPanel .media .mediaContent .mediaMesg .mediaMesgIcon img {
	height:45px;
	width:45px;
}
/*media mesg detail*/
	.chatPanel .media .mediaHead {
	/*height:48px;*/
		padding:0px 15px 4px;
	border-bottom:0px solid #D3D8DC;
	color:#000000;
	font-size:20px;
}
.chatPanel .media .mediaHead .title {
	line-height:1.2em;
	margin-top: 22px;
	display:block;
	max-width:312px;
	text-align: left;/*height:25px;
		white-space:nowrap;
		text-overflow:ellipsis;
		-o-text-overflow:ellipsis;
		overflow:hidden;*/
	}
.chatPanel .mediaFullText .mediaImg {
	width:255px;
	padding:0;
	margin:0 15px;
	overflow:hidden;
	max-height:164px;
}
.chatPanel .mediaFullText .mediaContent {
	padding:0 0 15px;
	font-size:16px;
	line-height: 1.5em;
	text-align:left;
	color:#222222;
}
.chatPanel .mediaFullText .mediaContentP {
	margin:12px 15px 0px;
	border-bottom:1px solid #D3D8DC;
}
.chatPanel .media .mediaHead .time {
	margin:0px;
	margin-top: 21px;
	color:#8C8C8C;
	background:none;
	width:auto;
	font-size:12px;
}
.chatPanel .media .mediaFooter {
	-webkit-border-radius:0px 0px 12px 12px;
	-moz-border-radius:0px 0px 12px 12px;
	border-radius:0px 0px 12px 12px;
	padding: 0 15px;
}
.chatPanel .media .mediaFooter a {
	color:#222222;
	font-size:16px;
	padding:0;
}
.chatPanel .media .mediaFooter .mesgIcon {
	margin:15px 3px 0px 0px;
}
.chatPanel .media a:hover {
	cursor: pointer;
}
.chatPanel .media a:hover .mesgIcon {
}
.mediaContent a:hover {
	background-color: #F6F6F6;
}
.mediaContent .last:hover {
	-webkit-border-radius:0px 0px 12px 12px;
	-moz-border-radius:0px 0px 12px 12px;
	border-radius:0px 0px 12px 12px;
	background-color: #F6F6F6;
}
.mediaFullText:hover {
	background-color: #F6F6F6;
	background:-webkit-gradient(linear,  left top, left bottom,  from(#F6F6F6), to(#F6F6F6));
	background-image:-moz-linear-gradient(top, #F6F6F6 0%, #F6F6F6 100%);
}
.a_choose {
  background-image: none ;
  border: none !important;
  text-shadow: none ;
  margin-left: 5px;
  padding: 2px 8px ;
  cursor: pointer ;
  display: inline-block ;
  overflow: visible ;
  border-radius: 2px ;
  -moz-border-radius: 2px ;
  -webkit-border-radius: 2px ;
  background-color: #44b549 ;
  color: #fff ;
  font-size: 14px ;
  /* line-height: 1.5 ; */
}
a:hover{
text-decoration:none;
}
.appmsg{position:relative;overflow:hidden;margin-bottom:20px;border:1px solid #d3d3d3;background-color:#fff;box-shadow:0 1px 0 rgba(0,0,0,0.1);-moz-box-shadow:0 1px 0 rgba(0,0,0,0.1);-webkit-box-shadow:0 1px 0 rgba(0,0,0,0.1);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px}.appmsg_info{font-size:13px;line-height:20px;padding-bottom:6px}.appmsg_date{font-weight:400;font-style:normal}.appmsg_content{padding:0 14px;border-bottom:1px solid #d3d3d3;position:relative;*zoom:1}.appmsg_title{font-weight:400;font-style:normal;font-size:16px;padding-top:6px;line-height:28px;max-height:56px;overflow:hidden;white-space:pre-wrap;word-wrap:normal;word-break:normal}.appmsg_title a{display:block;color:#222}.appmsg_thumb_wrp{height:160px;overflow:hidden}.appmsg_thumb{width:100%}.appmsg_desc{padding:5px 0 10px;white-space:pre-wrap;word-wrap:normal;word-break:normal}.appmsg_opr{background-color:#f4f4f4}.appmsg_opr ul{overflow:hidden;*zoom:1}.appmsg_opr_item{float:left;line-height:44px;height:44px}.appmsg_opr_item a{display:block;border-right:1px solid #d3d3d3;box-shadow:1px 0 0 0 #fff;-moz-box-shadow:1px 0 0 0 #fff;-webkit-box-shadow:1px 0 0 0 #fff;text-align:center;line-height:20px;margin-top:12px}.appmsg_opr_item a.no_extra{border-right-width:0}.appmsg_item{*zoom:1;position:relative;padding:12px 14px;border-top:1px solid #d3d3d3}.appmsg_item:after{content:" ";display:block;height:0;clear:both}.appmsg_item .appmsg_title{line-height:24px;max-height:48px;overflow:hidden;*zoom:1;margin-top:14px}.appmsg_item .appmsg_thumb{float:right;width:78px;height:78px;margin-left:14px}.multi .appmsg_info{padding-top:4px;padding-left:14px;padding-right:14px}.multi .appmsg_content{padding:0}.multi .appmsg_title{font-size:14px;padding-top:0}.cover_appmsg_item{position:relative;margin:0 14px 14px}.cover_appmsg_item .appmsg_title{position:absolute;bottom:0;left:0;width:100%;background:rgba(0,0,0,0.6)!important;filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#99000000',endcolorstr = '#99000000')}.cover_appmsg_item .appmsg_title a{padding:0 4px;color:#fff}.appmsg_mask{display:none;position:absolute;top:0;left:0;width:100%;height:100%;background-color:#000;filter:alpha(opacity = 60);-moz-opacity:.6;-khtml-opacity:.6;opacity:.6;z-index:1}.appmsg .icon_appmsg_selected{display:none;position:absolute;top:50%;left:50%;margin-top:-30px;margin-left:-33px;line-height:999em;overflow:hidden;z-index:1}.dialog_wrp .appmsg:hover{cursor:pointer}.appmsg:hover .appmsg_mask{display:block}.appmsg.selected .appmsg_mask{display:block}.appmsg.selected .icon_appmsg_selected{display:inline-block}.icon_appmsg_selected{background:transparent url(/mpres/htmledition/images/icon/media/icon_appmsg_selected1ccaec.png) no-repeat 0 0;width:75px;height:60px;vertical-align:middle;display:inline-block}.appmsg_thumb.default{display:block;color:#c0c0c0;text-align:center;line-height:160px;font-weight:400;font-style:normal;background-color:#ececec;font-size:22px}.appmsg_item .appmsg_thumb.default{line-height:78px;font-size:16px}.appmsg_edit_mask{display:none;position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(229,229,229,0.85)!important;filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr='#d9e5e5e5',endcolorstr = '#d9e5e5e5');text-align:center}.appmsg_item .appmsg_edit_mask{line-height:102px}.cover_appmsg_item .appmsg_edit_mask{line-height:160px}.appmsg_edit_mask a{margin-left:8px;margin-right:8px}.editing .cover_appmsg_item:hover .appmsg_edit_mask,.editing .appmsg_item:hover .appmsg_edit_mask{display:block}.editing .appmsg_thumb{display:none}.editing .appmsg_thumb.default{display:block}.editing .has_thumb .appmsg_thumb{display:block}.editing .has_thumb .appmsg_thumb.default{display:none}.editing .appmsg_content{box-shadow:none;-moz-box-shadow:none;-webkit-box-shadow:none;border-bottom-width:0}.editing.multi .appmsg_content{border-bottom-width:1px}.appmsg_add{text-align:center;padding:12px 14px;line-height:72px}.appmsg_add a{display:block;font-size:0;text-decoration:none;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;border:3px dotted #b8b8b8;height:72px}.appmsg_add a i{*vertical-align:baseline}.tab_content .appmsg{width:320px}.appmsg_list{text-align:justify;text-justify:distribute-all-lines;text-align-last:justify;font-size:0;padding-top:38px;margin:0 46px;letter-spacing:-4px}.appmsg_list:after{display:inline-block;width:100%;height:0;font-size:0;margin:0;padding:0;overflow:hidden;content:"."}.appmsg_col{display:inline-block;*display:inline;*zoom:1;vertical-align:top;width:48%;font-size:14px;text-align:left;font-size:14px;letter-spacing:normal}.media_dialog.appmsg_list{position:relative;padding:28px 140px;height:340px;margin-bottom:0;overflow-y:scroll}.page_appmsg_edit .tool_area{clear:both;margin:0;padding:20px 0}.page_appmsg_edit .tool_bar{margin-left:0;margin-right:0}.page_appmsg_edit .appmsg{min-height:180px}.page_appmsg_edit .upload_file_box{top:22px;left:-12px;width:377px;border-color:#d3d3d3;border-radius:0;-moz-border-radius:0;-webkit-border-radius:0}.page_appmsg_edit .upload_preview img{max-width:100px;max-height:100px}.media_preview_area{float:left;width:320px;margin-right:14px}.media_edit_area{display:table-cell;vertical-align:top;float:none;width:auto;*display:block;*zoom:1}.media_edit_area:after{content:" . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . ";visibility:hidden;clear:both;height:0!important;display:block;line-height:0}.edui_editor_wrp{position:relative;z-index:0}.appmsg_edit_item{padding-bottom:1em}.editor_extra_info{text-align:right;padding-top:6px}.editor_extra_info a{color:#a3a3a3}.editor_extra_info a:hover{color:#2e7dc6}

.tags {
	background-color: #fff;
	border: 1px solid #d5d5d5;
	color: #777;
	padding: 4px 6px;
	width: 406px;
	/*height: 24px;*/
	margin:12px 0px 0px 0px;
}
.tags:hover {
	border-color: #f59942;
	outline: 0 none;
}
.tags[class*="span"] {
	float: none;
	margin-left: 0;
}
.tags input[type="text"], .tags input[type="text"]:focus {
	border: 0 none;
	box-shadow: none;
	display: inline;
	line-height: 22px;
	margin: 0;
	outline: 0 none;
	padding: 4px 6px; 
}
.tags .tag {
	background-color: #91b8d0;
	color: #fff;
	display: inline-block;
	font-size: 12px;
	font-weight: normal;
	margin-bottom: 3px;
	margin-right: 3px;
	padding: 4px 22px 5px 9px;
	position: relative;
	text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.15);
	transition: all 0.2s ease 0s;
	vertical-align: baseline;
	white-space: nowrap;
}
.tags .tag .close {
	bottom: 0;
	color: #fff;
	float: none;
	font-size: 12px;
	line-height: 20px;
	opacity: 1;
	position: absolute;
	right: 0;
	text-align: center;
	text-shadow: none;
	top: 0;
	width: 18px;
}
.tags .tag .close:hover {
	background-color: rgba(0, 0, 0, 0.2);
}
.close {
	color: #000;
	float: right;
	font-size: 21px;
	font-weight: bold;
	line-height: 1;
	opacity: 0.2;
	text-shadow: 0 1px 0 #fff;
}
.close:hover, .close:focus {
	color: #000;
	cursor: pointer;
	opacity: 0.5;
	text-decoration: none;
}
button.close {
	background: transparent none repeat scroll 0 0;
	border: 0 none;
	cursor: pointer;
	padding: 0;
}
.tags .tag-warning {
	background-color: #ffb752;
}
</style>

<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="./static/js/upyun.js"></script>

<include file="Public:footer"/>