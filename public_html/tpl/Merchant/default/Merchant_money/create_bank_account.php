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
							
							<div class="form-group">
								<label class="tiplabel"><label>持卡人姓名：</label></label>
								<input type="text" class="px" name="account_name" value="{pigcms{$bank.account_name}" placeholder="请填写持卡人姓名"  />
								
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>银行卡号：</label></label>
									<input type="text" class="px" name="account" value="{pigcms{$bank.account}" placeholder="请填写卡号"/>
								
							</div>
							<div class="form-group">
								<label class="tiplabel"><label>所属银行：</label></label>
								<input type="text" class="px" style="width:300px" name="remark" value="{pigcms{$bank.remark}" placeholder="请填写所属银行" />
								<span class="tip"></span>
							</div>
							
							<div class="form-group">
								<label class="tiplabel"><label>是否默认：</label></label>
									<input type="radio" class="px" name="is_default" value="1" <if condition="$bank.is_default eq 1">checked</if>/>是
									<input type="radio" class="px" name="is_default" value="0" <if condition="$bank.is_default eq 0">checked</if>/>否
								
							</div>
							<input type="hidden" name="bank_id" value="{pigcms{$bank.id}">
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
	#bank input{
		margin-right:5px;
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