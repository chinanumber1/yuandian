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
					<form enctype="multipart/form-data" class="form-horizontal" method="post" action="">
						<div class="tab-content card_new">
							<div class="headings gengduoxian">您的余额为：￥{pigcms{$now_village['money']} <if condition="$config['company_pay_village_percent'] gt 0">提现手续费比例：{pigcms{$config['company_pay_village_percent']}%<else />无手续费</if></div>
							
							<div class="form-group">
								<label class="tiplabel"><label>真实姓名：</label></label>
								<input type="text" class="px" name="name" id="name" value="" />
								
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>金额：</label></label>
								<input type="text" class="px" name="money" id="money" value="" />元&nbsp;<label id="percent">(最低提现额：{pigcms{$config['min_withdraw_money']}元) </label>
								
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>提款至：</label></label>
								<select name="withdraw_type" id="sys_card_bg" class="pt" style="width:160px;">
									<if condition="$config.company_bank_pay eq 1"><option value="0">银行卡</option>	</if>
									<if condition="$config.company_alipay_pay eq 1"><option value="1">支付宝</option>	</if>
									<option value="2">平台</option>	
								</select>
								<span class="tip"></span>
							</div>
							
							<div class="form-group paytype" id="alipay">
								<label class="tiplabel"><label>支付宝账号：</label></label>
								<input type="text" class="px" name="alipay_account" value=""  placeholder="请填写支付宝账号"/>
								
							</div>
							
							<div class="form-group paytype" id="bank">
								<label class="tiplabel"><label>银行卡：</label></label>
								<input type="text" class="px" name="card_username" value="" placeholder="请填写持卡人姓名" style="margin-right: 5px;" />
								<input type="text" class="px" name="card_number" value="" placeholder="请填写卡号" style="margin-right: 5px;"/>
								<input type="text" class="px" name="bank" value="" placeholder="请填写所属银行" />
								
								
								
							</div>
							
							
							<div class="form-group">
								<label class="tiplabel" style="vertical-align:top;"><label>备注：</label></label>
								<textarea name="info" id="info" class="px" style="width:410px;height:120px;"></textarea>
							
							</div>
							
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
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
	
</div>
<style>
	.paytype{
		display:none;
	}
</style>
<script>
$(document).ready(function() {
		var paytype = $('#sys_card_bg').val();
		withdraw_type(paytype)
	$('#sys_card_bg').change(function(event) {
		var paytype = $(this).val();
		withdraw_type(paytype)
	});
	
	$('input[name="card_number"]').change(function(){
		$.ajax({
			url: '{pigcms{:U('get_bank_name')}',
			type: 'POST',
			dataType: 'json',
			data: {card_number: $('input[name="card_number"]').val()},
			success:function(data){
				if(data.status){
					$('input[name="bank"]').val(data.info)
			
				}else{
			
					alert('没有查询到相关银行，请手动输入')
				}
				
			}
		});
	});
});

function withdraw_type(paytype){
	if(paytype==2){
			$('.paytype').hide();
		}else if (paytype==1){
			$('#alipay').show();
			$('#bank').hide();

		}else if(paytype==0){
			$('#alipay').hide();
			$('#bank').show();
		}
}
	
</script>


<link rel="stylesheet" href="{pigcms{$static_path}css/card_new.css"/>
<include file="Public:footer"/>