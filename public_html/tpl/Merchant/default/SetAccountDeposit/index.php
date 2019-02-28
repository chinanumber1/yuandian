<include file="Public:header"/>

<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-bar-chart-o bar-chart-o-icon"></i>
			<li class="active">云商通账户信息</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-sm-12">
					
					
					<div style="margin-top:10px;width:100%;height:240px;background-color:#81d2cf">
						<p style="text-align:center;font-family: 'Arial Normal', 'Arial';font-weight: 400;font-style: normal;font-size: 36px;color: #FFFFFF;padding-top: 36px;">￥{pigcms{$now_money} <!--a href="{pigcms{:U('Merchant_money/mer_recharge')}" style="font-size: 13px;color: #fff;">充值></a--></p>
						<p style="text-align:center;    padding-top: 36px;" class="my_money">
							<a href="{pigcms{:U('setAccountDeposit/withdraw_apply')}">
								<span >申请提现</span>
							</a>
							<a href="{pigcms{:U('setAccountDeposit/money_list')}">
								<span >商家流水</span>
							</a>
							<!--a href="{pigcms{:U('Merchant_money/withdraw_list')}">
								<span >提现记录</span>
							</a-->
						</p>
							
						<p style="text-align:center;padding-top: 20px;" class="my_money">
							
							
							<!--a href="{pigcms{:U('Merchant_money/buy_merchant_service')}" <if condition="$config.buy_merchant_auth eq 0"> onclick="return false;" </if>>
								<span style="padding:9px 30px;<if condition="$config.buy_merchant_auth eq 0">background:gray;border:none;cursor:not-allowed;</if>">购买系统服务</span>
							</a-->
					
							<!--<a href="{pigcms{:U('Merchant_money/buy_system')}" onclick="return false;" title="程序猿正在赶此功能，敬请期待">
								<span style="padding:9px 30px;background:gray;border:none;cursor:not-allowed;">购买系统服务</span>
							</a>-->
						</p>								
						
						
						
					</div>
				
					<form enctype="multipart/form-data" class="form-horizontal" method="post" action="">
						<div class="tab-content card_new">
						
							<div class="form-group">
								<label class="tiplabel"><label>云商通ID：</label></label>
									<if condition="$deposit.bizUserId neq ''">
										{pigcms{$deposit.bizUserId}
									<else />
										<a href="{pigcms{:U('SetAccountDeposit/createAllinyunAccount')}&type=2" class="btn btn-sm btn-success" id="Create_Allinyun">创建企业账号</a>
										
										<a href="{pigcms{:U('SetAccountDeposit/createAllinyunAccount')}&type=3" class="btn btn-sm btn-success" id="Create_Allinyun">创建个人账号</a>
										
										<label class="tips">创建企业账号需要对公账号，个人账号不需要提供</label>
									</if>
								
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>云商通绑定手机：</label></label>
								<if condition="!empty($deposit['phone'] AND $deposit['bind_phone_status'] eq 1 )">
										{pigcms{$deposit.phone}
										<a href="{pigcms{:U('SetAccountDeposit/editphone')}" class="btn btn-sm btn-success" >重置手机</a>
									<else />
										<a href="{pigcms{:U('SetAccountDeposit/bindphone')}" class="btn btn-sm btn-success">绑定手机</a>
									</if>
								
							</div>
							<if condition="$deposit['type'] eq 2 OR empty($deposit) OR $deposit['type'] eq ''">
							<div class="form-group">
								<label class="tiplabel"><label>云商通绑企业审核状态：</label></label>
							
								<a href="{pigcms{:U('SetAccountDeposit/submitVerify')}" class="btn btn-sm btn-success" id="Submit_verify"><if condition="$deposit.status eq 0">提交审核<elseif condition="$deposit.status eq 1" /> 查看企业信息<else />编辑企业信息</if></a>
								
							</div>
							<else />
							<div class="form-group">
								<label class="tiplabel"><label>云商通实名认证：</label></label>
								<a href="<if condition="$deposit.realName eq ''">{pigcms{:U('SetAccountDeposit/verify_real_name')}<else />javascript:void(0);</if>" class="btn btn-sm btn-success" id="Submit_verify"><if condition="$deposit.realName eq ''">去认证<elseif condition="$deposit.realName neq ''" /> 已认证</if></a>
								
							</div>
							
							</if>
							<div class="form-group">
								<label class="tiplabel"><label>云商通电子协议：</label></label>
							
									<if condition="$deposit.sign_status eq 0"><a href="{pigcms{:U('SetAccountDeposit/signConnect')}&sign=1" class="btn btn-sm btn-success" id="signConnect">签约电子协议</a><elseif condition="$deposit.sign_status eq 1" /> 已签约</if>
								
							</div>
								
							<div class="form-group">
								<label class="tiplabel"><label>云商通绑定银行卡：</label></label>
							
								
								<a href="{pigcms{:U('SetAccountDeposit/addBank')}" class="btn btn-sm btn-success" id="signConnect">添加银行卡</a>
								
								<a href="{pigcms{:U('Merchant_money/bank_list')}" class="btn btn-sm btn-success" id="signConnect">
								银行卡列表
								</a>
								
							</div>
								
									
									
							
						</div>
					</form>
						
						
						
					
				</div>
			</div>
		</div>
	</div>
</div>
<link rel="stylesheet" href="{pigcms{$static_path}css/card_new.css"/>
<style>
	.my_money span{
		padding: 9px 42px;
		border: 1px solid #fff;
		margin-left: 10px;
		color:#fff;
		border-radius:1px;
	}
	.my_money a:hover {text-decoration:none;}
	.widget-header {
		height: 100px;
	}
	.widget-header div{
		text-align:center;
		float:left;
		height:100%;
	}
	.widget-header p{
		text-align:center;
	}
	.h_title{
		margin-top:10px;
		font-weight: 400;
		font-style: normal;
		font-size: 14px;
		color:#000;
	}
	
	.h_value{
		font-family: 'Arial Negreta', 'Arial';
		font-weight: 700;
		font-style: normal;
		font-size: 28px;
		color:#000;
	}
	.tab-content a.on {
		background: #498CD0;
		color: #FFF;
		padding: 4px 7px;
		text-decoration: none;
	}
</style>


<include file="Public:footer"/>
