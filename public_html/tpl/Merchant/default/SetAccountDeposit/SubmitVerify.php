<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-credit-card"></i>
                <a href="{pigcms{:U('index')}">云商通设置</a>
				
            </li>
			<li class=><a href="{pigcms{:U('SetAccountDeposit/submitVerify')}">云商通企业信息设置</a></li>
        </ul>
		
    </div>
	<div class="page-content form-horizontal ">
        <div class="page-content-area">
            <div class="row">
                <div class="col-xs-12">
					<form class="form" method="post" action="" target="_top" enctype="multipart/form-data">
						<label for="tab1" class="select_tab select" style="cursor:pointer;">基本信息</label>
				
						<div class="tab-content card_new" id="tab1" >
							
						<div class="headings gengduoxian">说明:带<a style="color:red;">*</a>为必填项</div>
						
						
							<div class="form-group">
								<label class="tiplabel"><label><font color="red">*</font>企业名称</label></label>
								<input type="text" name="companyName" id="companyName" class="px" value="{pigcms{$company.companyName}" style="width:210px;"/><span class="tip"></span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>企业地址</label></label>
								<input type="text" name="companyAddress" id="companyAddress" class="px" value="{pigcms{$company.companyAddress}" style="width:210px;"/><span class="tip"></span>
							</div>
							
							<div class="form-group">
								<label class="tiplabel"><label><font color="red">*</font>认证类型</label></label>
								<label class="radiolabel first"><span><label><input name="authType" value="1" type="radio" <if condition="$company.authType eq 1 OR empty($company)">checked="checked"</if> /></label>&nbsp;<span>三证</span></span></label>
								<label class="radiolabel"><span><label><input name="authType" value="2" type="radio" <if condition="$company.authType eq 2">checked="checked"</if>/></label>&nbsp;<span>一证</span>&nbsp;</span></label>
							</div>
							
							<div class="form-group one_verify">
								<label class="tiplabel"><label><font color="red">*</font>统一社会信用</label></label>
								<input type="text" name="uniCredit" id="uniCredit" class="px" value="{pigcms{$company.uniCredit}" style="width:210px;"/><span class="tip"></span>
							</div>
							
							<div class="form-group three_verify">
								<label class="tiplabel"><label><font color="red">*</font>营业执照号</label></label>
								<input type="text" name="businessLicense" id="businessLicense" class="px" value="{pigcms{$company.businessLicense}" style="width:210px;"/><span class="tip"></span>
							</div>
							<div class="form-group three_verify">
								<label class="tiplabel"><label><font color="red">*</font>组织机构代码</label></label>
								<input type="text" name="organizationCode" id="organizationCode" class="px" value="{pigcms{$company.organizationCode}" style="width:210px;"/><span class="tip"></span>
							</div>
							
							<div class="form-group three_verify">
								<label class="tiplabel"><label><font color="red">*</font>税务登记证</label></label>
								<input type="text" name="taxRegister" id="taxRegister" class="px" value="{pigcms{$company.taxRegister}" style="width:210px;"/><span class="tip"></span>
							</div>
							
							<div class="form-group">
								<label class="tiplabel"><label>统一社会信用/营业执照号到期时间</label></label>
								
								<input type="text" class="input-text" name="expLicense" style="width:120px;" id="d4311" value="<if condition="$company['expLicense'] gt 0">{pigcms{$company.expLicense|date='Y-m-d',###}</if>" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
								</label>
							</div>
							
							<div class="form-group">
								<label class="tiplabel"><label>联系电话</label></label>
								<input type="text" name="telephone" id="telephone" class="px" value="{pigcms{$company.telephone}" style="width:210px;"/><span class="tip"></span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>法人姓名</label></label>
								<input type="text" name="legalName" id="legalName" class="px" value="{pigcms{$company.legalName}" style="width:210px;"/><span class="tip"></span>
							</div>
							
							<div class="form-group">
								<label class="tiplabel"><label><font color="red">*</font>法人证件号</label></label>
								<input type="text" name="legalIds" id="legalIds" class="px" value="{pigcms{$company.legalIds}" style="width:210px;"/><span class="tip"></span>
							</div>
							
							<div class="form-group">
								<label class="tiplabel"><label><font color="red">*</font>法人手机号码</label></label>
								<input type="text" name="legalPhone" id="legalPhone" class="px" value="{pigcms{$company.legalPhone}" style="width:210px;"/><span class="tip"></span>
							</div>
							
							<div class="form-group">
								<label class="tiplabel"><label><font color="red">*</font>企业对公账户</label></label>
								<input type="text" name="accountNo" id="accountNo" class="px" value="{pigcms{$company.accountNo}" style="width:210px;"/><span class="tip"></span>
							</div>
							
							<div class="form-group">
								<label class="tiplabel"><label><font color="red">*</font>开户银行名称</label></label>
								<input type="text" name="parentBankName" id="parentBankName" class="px" value="{pigcms{$company.parentBankName}" style="width:210px;"/><span class="tip"></span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label><font color="red">*</font>开户银行所在省</label></label>
								<input type="text" name="province" id="province" class="px" value="{pigcms{$company.province}" style="width:210px;"/><span class="tip"></span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label><font color="red">*</font>开户银行所在市</label></label>
								<input type="text" name="city" id="city" class="px" value="{pigcms{$company.city}" style="width:210px;"/><span class="tip"></span>
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>开户行地区代码</label></label>
								<input type="text" name="bankCityNo" id="bankCityNo" class="px" value="{pigcms{$company.bankCityNo}" style="width:210px;"/><span class="tip"></span>
							</div>
							
							<div class="form-group">
								<label class="tiplabel"><label><font color="red">*</font>开户行支行名称</label></label>
								<input type="text" name="bankName" id="bankName" class="px" value="{pigcms{$company.bankName}" style="width:210px;"/><span class="tip"></span>
							</div>
							
							<div class="form-group">
								<label class="tiplabel"><label><font color="red">*</font>支付行号</label></label>
								<input type="text" name="unionBank" id="unionBank" class="px" value="{pigcms{$company.unionBank}" style="width:210px;"/><span class="tip"></span>
							</div>
							
							<if condition="$company['status'] eq 0">
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa fa-check bigger-110"></i>
										提交审核
									</button>
								</div>
							</div>
							</if>
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
	<div id="pwd_bg" style="display:none">

	</div>
	<div class="banners vipcard_box"  id="b04" >
		<ul style="margin:0;">
			<li><img src="{pigcms{$static_path}images/card1.jpg" alt="" style="width:320px;480px;" ></li>
			<li><img src="{pigcms{$static_path}images/card2.jpg" alt="" style="width:320px;480px;" ></li>
			<li><img src="{pigcms{$static_path}images/card3.png" alt="" style="width:320px;480px;" ></li>
			<li><img src="{pigcms{$static_path}images/card4.jpg" alt="" style="width:320px;480px;" ></li>
		</ul>

		<a href="javascript:void(0);" class="unslider-arrow04 prev"><img class="arrow" id="al" src="{pigcms{$static_path}images/arrowl.png" alt="prev"  style="width:20px;35px;"></a>

		<a href="javascript:void(0);" class="unslider-arrow04 next"><img class="arrow" id="ar" src="{pigcms{$static_path}images/arrowr.png" alt="next" style="width:20px;35px;"></a>

	</div>
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
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_path}js/unslider.min.js?2222"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="./static/js/upyun.js"></script>
<script type="text/javascript">
	KindEditor.ready(function(K){
			var site_url = "{pigcms{$config.site_url}";
			var editor = K.editor({
				allowFileManager : true
			});
			$('.J_selectImage').click(function(){
				var upload_file_btn = $(this);
				editor.uploadJson = "{pigcms{:U('Config/ajax_upload_pic_wx')}";
				editor.loadPlugin('image', function(){
					editor.plugin.imageDialog({
						showRemote : false,
						clickFn : function(url, title, width, height, border, align) {
							upload_file_btn.siblings('.input-image').val(url);
							editor.hideDialog();
						}
					});
				});
			});

		});
			<if condition="empty($company) OR $company['authType'] eq 1">
			$('.one_verify').hide();
				$('.three_verify').show();
				
			<elseif condition="$company['authType'] eq 2" />
				$('.one_verify').show();
			$('.three_verify').hide();
			</if>
</script>
<script type="text/javascript">
    $(document).ready(function() {
		
		
		
		$('.form').bootstrapValidator({
	//        live: 'disabled',
			message: 'This value is not valid',
		  
			fields: {
				companyName: {
					validators: {
						notEmpty: {
							message: '企业名称不能为空'
						}
					}
				},
				legalIds: {
					validators: {
						notEmpty: {
							message: '法人证件号不能为空'
						}
					}
				},
				legalPhone: {
					validators: {
						notEmpty: {
							message: '法人手机号码不能为空'
						}
					}
				},
				accountNo: {
					validators: {
						notEmpty: {
							message: '企业对公账户不能为空'
						}
					}
				},
				parentBankName: {
					validators: {
						notEmpty: {
							message: '开户银行名称不能为空'
						}
					}
				},
				province: {
					validators: {
						notEmpty: {
							message: '开户银行所在省不能为空'
						}
					}
				},
				city: {
					validators: {
						notEmpty: {
							message: '开户银行所在市不能为空'
						}
					}
				},
				bankName: {
					validators: {
						notEmpty: {
							message: '开户行支行名称不能为空'
						}
					}
				},
				unionBank: {
					validators: {
						notEmpty: {
							message: '支付行号不能为空'
						}
					}
				},
				
			}
		});
		$('input[name="authType"]').change(function(){
			if($(this).val()==2){
				$('.one_verify').show();
				$('.three_verify').hide();
			}else{
				$('.one_verify').hide();
				$('.three_verify').show();
			}
		});
		
	
    });

	
</script>
<link rel="stylesheet" href="{pigcms{$static_path}css/card_new.css"/>

<include file="Public:footer"/>