<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-credit-card"></i>
				<a href="{pigcms{:U('Config/merchant')}">商家设置</a>
				
                
				
            </li>

			<li class="active"><a href="{pigcms{:U('SetAccountDeposit/bindphone')}">绑定手机</a></li>
        </ul>
    </div>
	<div class="page-content form-horizontal ">
        <div class="page-content-area">
            <div class="row">
                <div class="col-xs-12">
					<form class="form" method="post" action="" target="_top" enctype="multipart/form-data">
						<label for="tab1" class="select_tab select" style="cursor:pointer;">绑定手机</label>
				
						<div class="tab-content card_new" id="tab1" >
							
							<div class="form-group">
								<label class="tiplabel"><label>当前手机号码</label></label>
								{pigcms{$deposit.phone}
								
							</div>
						
						
							<div class="form-group">
								<label class="tiplabel"><label><font color="red">*</font>新手机号码</label></label>
								<input type="text" name="phone" id="phone" class="px" value="" style="width:210px;"/><span class="tip"></span>
								
							</div>
							<div class="form-group">
								<label class="tiplabel"><label><font color="red">*</font>验证码</label></label>
								<input type="text" name="code" id="code" class="px" value="" style="width:210px;"/><span class="tip"></span>
								
								<a href="javascript:void(0)" onclick="sendsms(this)" class="btn btn-sm btn-success" id="Create_Allinyun">发送验证码</a>
							</div>
							
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa fa-check bigger-110"></i>
										提交审核
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
		ul, ol { padding: 0;}
		.banners { position: relative; overflow: auto; text-align: center;}
		.banners li { list-style: none; }
		.banners ul li { float: left; }
		#b04 { width: 320px;    overflow: hidden;    left: 40%;display:none;z-index:1000}
		#b04 .dots { position: absolute; left: 0; right: 0; bottom: 20px;}
		#b04 .dots li 
		{ 
			display: inline-block; 
			width: 10px; 
			height: 10px; 
			margin: 0 4px; 
			text-indent: -999em; 
			border: 2px solid #000; 
			border-radius: 6px; 
			cursor: pointer; 
			opacity: .4; 
			-webkit-transition: background .5s, opacity .5s; 
			-moz-transition: background .5s, opacity .5s; 
			transition: background .5s, opacity .5s;
		}

		#b04 .dots li.active {
			background: #000;
			opacity: 1;
		}
		#b04 .arrow { position: absolute; top: 200px;}
		#b04 #al { left: 15px;}
		#b04 #ar { right: 15px;}
		#pwd_bg{
			background-color: #000;
	position: fixed;
	z-index: 999;
	left: 0;
	top: 0;
	
	width: 100%;
	height: 100%;
	opacity: 0.3;
	filter: alpha(opacity=30);
	-moz-opacity: 0.3;
			
		}

</style>	

</div>

<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="./static/js/upyun.js"></script>
<script src="./static/js/cart/jscolor.js" type="text/javascript"></script>
<link rel="stylesheet" href="./static/kindeditor/themes/default/default.css"/>
<link rel="stylesheet" href="./static/kindeditor/plugins/code/prettify.css"/>


    <link rel="stylesheet" href="./static/validate/dist/css/bootstrapValidator.css"/>

    <!-- Include the FontAwesome CSS if you want to use feedback icons provided by FontAwesome -->
    <!--<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" />-->

    <script type="text/javascript" src="./static/validate/vendor/jquery/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="./static/validate/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./static/validate/dist/js/bootstrapValidator.js"></script>
<style>
	.select_tab{
		width:100px;
		height:36px;
		color: #555;
		border: 1px solid #c5d0dc;
		font-size:16px;
		z-index:9;
		line-height: 36px;
    text-align: center;
		position: relative;
	}
	label .select_tab{
		display: inline-block;
		margin: 0 0 -1px;
		padding: 15px 25px;
		font-weight: 600;
		text-align: center;
		color: #bbb;
		border: 1px solid transparent;
	}
	
	.select{
		border-top: 1px solid orange;
		border-bottom: 1px solid #fff;
	}
	.card_new{
		margin-top:-6px;
	}
	.other label,table{
		color:#a0a0a0;
	}
</style>
<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>


<script type="text/javascript">
	
</script>
<script type="text/javascript">
    $(document).ready(function() {

		

    });
	var countdown = 60;
	function sendsms(val){
	
		
			if($("input[name='phone']").val()==''){
				alert('手机号码不能为空！');
			}else{
				
				
				if(countdown==60){
					$.ajax({
						url: '{pigcms{:U('SetAccountDeposit/sendsms')}',
						type: 'POST',
						dataType: 'json',
						data: {phone: $("input[name='phone']").val()},
						success:function(date){
							if(date.error_code){
								
							}
						}

					});
				}
				if (countdown == 0) {
					val.removeAttribute("disabled");
					$(val).html("验证短信");
					countdown = 60;
					//clearTimeout(t);
				} else {
					val.setAttribute("disabled", true);
					$(val).html("重新发送(" + countdown + ")");
					countdown--;
					setTimeout(function() {
						sendsms(val);
					},1000)
				}
			}
		}
	
</script>
<link rel="stylesheet" href="{pigcms{$static_path}css/card_new.css"/>

<include file="Public:footer"/>