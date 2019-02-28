<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-group"></i>
			<li class="active">粉丝管理</li>
			<li><a href="{pigcms{:U('Customer/log')}">群发列表</a></li>
			<li>创建群发</li>
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
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<div class="tab-content">
							<div class="form-group">
								<label class="col-sm-1"><label>选择群发对象</label></label>
								<select name="type" id="type">
									<volist name="fans_list" id="oo">
									<option value="{pigcms{$oo['id']}" num="{pigcms{$oo['value']}">{pigcms{$oo['name']}({pigcms{$oo['value']})</option>
									</volist>
								</select>
							</div>
							<div class="form-group">
								<label class="col-sm-1"><label>选择群发内容</label></label>
								<select name="source_id" id="source_id">
									<volist name="list" id="vo">
									<option value="{pigcms{$vo['pigcms_id']}" <if condition="$other['from_id'] eq $vo['pigcms_id']">selected</if>>{pigcms{$vo['list'][0]['title']}<if condition="$vo['type']">（多图）<else />（单图）</if></option>
									</volist>
								</select>
							</div>
							<div class="form-group" >
								<label class="col-sm-2">您当前需要扣除的{pigcms{$config['score_name']}为：<label id="js-deduct">{pigcms{$config['customer_one_score'] * $fans_list[0]['value']}</label>分</label>
							</div>
							<div class="form-group" >
								<label for="phone" class="col-sm-2">您当前的{pigcms{$config['score_name']}为：<label id="js-current-score">{pigcms{$now_merchant.plat_score}</label>分</label>
							</div>
							<div class="form-group js-show-score" >
								<label class="col-sm-2"><label for="phone">您现在的首页排序储存值为 <label id="js-current-storage-indexsort">{pigcms{$now_merchant.storage_indexsort}</label></label></label>
								<input type="text" value="{pigcms{$now_merchant.storage_indexsort}" name='' id='js-change-score' class="col-sm-2" />&nbsp;&nbsp;
								<a id="show_score_frame" class="btn btn-sm btn-success"  href="javascript:void(0);">兑换</a>（一个首页排序储存值可以兑换 {pigcms{$config.customer_one_score_exchange}{pigcms{$config['score_name']}）
								<input type="hidden" value="{pigcms{$config.customer_one_score}" id='js-customer_one_score'/>
							</div>
							<input type='hidden' value="{pigcms{$config['customer_one_score'] * $fans_list[0]['value']}" name='changescore' id='js-change-score-less'>
							<div style="clear:both;"></div>
							<div class="form-actions">
								<button class="btn btn-info" type="submit" id="submit"><i class="ace-icon fa fa-check bigger-110"></i>提交</button>
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
$(document).ready(function(){
	$("#select").click(function(){
		if ($(this).attr('checked')) {
			$('.user_select').attr('checked', true);
		} else {
			$('.user_select').attr('checked', false);
		}
		redudeScore()
	});

	var num = parseInt($('#type').find("option:selected").attr('num'));
	var customer_one_score = parseInt($('#js-customer_one_score').val());
	var palt_score = num * customer_one_score;
	$('#js-deduct').html(palt_score);
	$('#js-change-score-less').val(palt_score);
	
	//$("#submit").attr('disabled', true);
	//get_list(1);
	//redudeScore()
	$('#type').change(function(){
		var num = parseInt($("select[name=type] option[value=" + $(this).val() + "]").attr('num'));
		var customer_one_score = parseInt($('#js-customer_one_score').val());
		var palt_score = num * customer_one_score;
		$('#js-deduct').html(palt_score);
		$('#js-change-score-less').val(palt_score);
	});
	$('#show_score_frame').click(function(){
		if(confirm('确认要兑换吗？')){
			var score = $('#js-change-score').val();
			$.get("{pigcms{:U('Customer/ajaxChangeScore')}", {score:score}, function(data){
	 			if (data.error_code == 0) {
	 				$('#js-current-score').html(data.current_score);
	 				$('#js-current-storage-indexsort').html(data.storage_indexsort)
	 			}
			}, 'json');
		}
	});
	$('#upload_pic_ul li input').click(function(){
		redudeScore();
	})	
});
function redudeScore(){
		$('.js-show-score').show();
		var j=0;
		$('#upload_pic_ul li input').each(function(){
			if ($(this).attr('checked')) {
				j++;
			}  
		})
		var customer_one_score = parseInt($('#js-customer_one_score').val())
		var palt_score = j*customer_one_score;
		$('#js-deduct').html(palt_score);
		$('#js-change-score-less').val(palt_score);
}
function get_list(page)
{
	$.get("{pigcms{:U('Customer/ajaxsend')}", {'page':parseInt(page + 1)}, function(data){
		if (data.error_code) {
			$("#submit").attr('disabled', false);
			$('.alert-danger').remove();
		} else {
			var html = '';
			$.each(data.data, function(i, val){
				html += '<li class="upload_pic_li">';
				html += '<img src="'+val.avatar+'" style="width:70px;"/><br/>';
				html += '<label for="'+val.openid+'" style="width:90px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><input type="checkbox" name="openid[]" value="'+val.openid+'" id="'+val.openid+'" class="user_select"/>'+val.nickname+'</label>';
				html += '</li>';
			});
			$('#upload_pic_ul').append(html);
			get_list(data.page);
			$('#upload_pic_ul li input').click(function(){
				redudeScore();
			})
		}
	}, 'json');
}
</script>
<include file="Public:footer"/>
