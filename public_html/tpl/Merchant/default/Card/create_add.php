<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-credit-card"></i>
				<a href="{pigcms{:U('Card/index')}">微网站</a>
			</li>
			<li>
				<a href="{pigcms{:U('Card/coupon', array('id' => $thisCard['id']))}">优惠券活动</a>
			</li>
			<li class="active">添加优惠券</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="tab-content">
						<div class="grid-view">
							<form enctype="multipart/form-data" class="form-horizontal" method="post" action="">
								<div class="form-group">
									<label class="col-sm-4"><label for="contact_name">{pigcms{$thisCard.cardname}：创建会员卡</label></label>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="contact_name">卡号英文编号</label></label>
									<input type="text" class="col-sm-1" name="title" value="" />
									<span class="form_tips">例：<font style="color:red">BSD</font>-65535 <font style="color:red">BSD</font>就是英文编号</span>
								</div>

								<div class="form-group">
									<label class="col-sm-1"><label for="adress">卡号生成范围</label></label>
									<input type="text" class="hasDatepicker" id="stat" value="" name="stat" />
									到
									<input type="text" class="hasDatepicker" id="end" value="" name="end"/>
									<span class="form_tips"<font style="color:red">最小起始卡为:1,最大结束卡号为:65535,例如输入1到100那么就会创建99张会员卡</font></span>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="endinfo">使用说明</label></label>
									<span class="form_tips">在此说明生成会员卡的说明，每次最多生成100张，请及时关注，会员卡是否被领取完了,全部被发放后请再次生成</span>
								</div>
								
								<div class="clearfix form-actions">
									<div class="col-md-offset-3 col-md-9">
										<button class="btn btn-info" type="submit">
											<i class="ace-icon fa fa-check bigger-110"></i>
											保存
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="./static/js/upyun.js"></script>
<script type="text/javascript" src="/static/js/date/WdatePicker.js"></script>

<link rel="stylesheet" href="./static/kindeditor/themes/default/default.css" />
<link rel="stylesheet" href="./static/kindeditor/plugins/code/prettify.css" />
<script src="./static/kindeditor/kindeditor.js" type="text/javascript"></script>
<script src="./static/kindeditor/lang/zh_CN.js" type="text/javascript"></script>
<script src="./static/kindeditor/plugins/code/prettify.js" type="text/javascript"></script>

<script type="text/javascript">
$(function(){
	 $('.type').change(function(){
		 if($(this).val() == 1){ 
			 $('#pic_src').attr('src','/static/images/cart_info/youhui.jpg');
			 $('#pic').val('/static/images/cart_info/youhui.jpg');
			 $('#cktime').css('display','none');
		 }else{
			 $('#pic_src').attr('src','/static/images/cart_info/daijin.png');
			 $('#pic').val('/static/images/cart_info/lipin.jpg');
			 $('#cktime').css('display','');
		 }
	 });
});

var editor;
KindEditor.ready(function(K) {
editor = K.create('#info', {
resizeType : 1,
allowPreviewEmoticons : false,
allowImageUpload : true,
uploadJson : '/merchant.php?g=Merchant&c=Upyun&a=kindedtiropic',
items : [
'source','undo','redo','copy','plainpaste','wordpaste','clearhtml','quickformat','selectall','fullscreen','fontname', 'fontsize','subscript','superscript','indent','outdent','|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline','hr',
 '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
'insertunorderedlist', '|', 'image','emoticons', 'link', 'unlink']
});
});
</script>
<include file="Public:footer"/>