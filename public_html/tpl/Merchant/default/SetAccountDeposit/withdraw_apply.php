<include file="Public:header"/>

<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-credit-card"></i>
                <a href="{pigcms{:U('withdraw')}">提现</a>
            </li>
        </ul>
    </div>
	<div class="page-content form-horizontal ">
        <div class="page-content-area">
            <div class="row">
                <div class="col-xs-12">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" action="{pigcms{:U('withdraw')}">
						<div class="tab-content card_new">
							<div class="headings gengduoxian">您的余额为：￥{pigcms{$now_money}  <if condition="$config['company_pay_mer_percent'] gt 0 AND $config['merchant_withdraw_fee_type'] eq 0">提现手续费比例：{pigcms{$config['company_pay_mer_percent']}%<elseif condition="$config['company_pay_mer_money'] gt 0 AND $config['merchant_withdraw_fee_type'] eq 1" />提现手续费金额：{pigcms{$config['company_pay_mer_money']}元<else />无手续费</if></div>
							
							
							<div class="form-group">
								<label class="tiplabel"><label>金额：</label></label>
								<input type="text" class="px" name="money" id="money" value="" />元&nbsp;<label id="percent"></label>
								
							</div>
							<if condition="$deposit.type==3">
							<div class="form-group">
								<label class="tiplabel"><label>银行卡：</label></label>
								<select name="bank_id" id="bank_id" class="px" style="width:300px">
										<volist name="bank_list" id="vo">
											<option value="{pigcms{$vo.id}" >{pigcms{$vo.remark}: {pigcms{$vo.account}</option>	
										</volist>
									</select> 
								<label id="percent"></label>
								
							</div>
							<else />
								<div class="form-group">
									<label class="tiplabel"><label>银行卡：</label></label>
										{pigcms{$deposit.company_info.parentBankName} - {pigcms{$deposit.bank_txt}
									<label id="percent"></label>
									
								</div>
							</if>
							<div class="form-group">
								<label class="tiplabel" style="vertical-align:top;"><label>备注(限定50字符内)：</label></label>
								<textarea name="extendInfo" id="extendInfo" class="px" style="width:410px;height:120px;"></textarea>
							</div>
							
							<div class="form-group">
								<label class="tiplabel"><label><font color="red">*</font>验证码</label></label>
								<input type="text" name="verificationCode" id="verificationCode" class="px" value="" style="width:210px;"/><span class="tip"></span>
								
								<a href="javascript:void(0)" onclick="sendsms(this)" class="btn btn-sm btn-success" id="Create_Allinyun">发送验证码</a>
							</div>
							
								<input type="hidden" name="order_id" value="">
					<input type="hidden" name="orderNo" value="">
							
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa fa-check bigger-110"></i>
										提现
									</button>
								</div>
							</div>
							
							
							
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
</div>
<style>
	.paytype{
		display:none;
	}
	#bank input{
		margin-right:5px;
	}
</style>
<script>
var countdown = 60;
	function sendsms(val){
	
		
			if($("input[name='money']").val()==''){
				alert('金额不能为空');
			}else{
				
				
				if(countdown==60){
					$.ajax({
						url: '{pigcms{:U('SetAccountDeposit/withdraw_apply')}',
						type: 'POST',
						dataType: 'json',
						data: {money: $("input[name='money']").val(),extendInfo:$('#extendInfo').val(),bank_id:$('#bank_id').val()},
						success:function(date){
							if(date.status){
								alert('短信发送成功')
								$('input[name="order_id"]').val(date.url.bizOrderNo)
									$('input[name="orderNo"]').val(date.url.orderNo)
							}else{
								alert(date.info)
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