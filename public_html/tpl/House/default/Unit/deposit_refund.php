<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('Unit/deposit_management')}">押金管理</a>
            </li>
            <li class="active">押金退款</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<form  class="form-horizontal" method="post">
						<div class="tab-content">
								<div id="basicinfo" class="tab-pane active">
									<div class="form-group">
										<label class="col-sm-1"><label for="room_num">房间编号</label></label>
											<input class="col-sm-2" size="20" name="room_num" id="room_num" type="text"  value="{pigcms{$info_list.room_num}" readonly="readonly" />
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="payment_id">客户</label></label>
									<input class="col-sm-2" size="20" name="payment_id" id="payment_id" type="text"  value="{pigcms{$info_list.name}" readonly="readonly" />
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="deposit_name">退款项目</label></label>
									<input class="col-sm-2" size="20" name="deposit_name" id="deposit_name" type="text"  value="{pigcms{$info_list.deposit_name}" readonly="readonly" />
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="actual_money">可退金额</label></label>
									<input class="col-sm-2" size="20" name="actual_money" id="actual_money" type="text"  value="{pigcms{$info_list.deposit_balance}" readonly="readonly" />
								</div>

								<div class="form-group">
									<label class="col-sm-1"><label for="refund_money">退款金额</label></label>
									<input class="col-sm-2" size="20" name="refund_money" id="refund_money" type="text"  value="" />
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="withhold_money">扣款金额</label></label>
									<input class="col-sm-2" size="20" name="withhold_money" id="withhold_money" type="text"  value="" readonly="readonly" />
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>备注</label></label>
									<label><textarea name="refund_note" id="refund_note" maxlength="255" style="width:286px;height:90px;resize:none" placeholder="最多输入255个字">{pigcms{$info_list.refund_note}</textarea></label>
								</div>
						</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info submit_info" type="button">
										<i class="ace-icon fa fa-check bigger-110"></i>
										退款
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript">

	$('#refund_money').keyup(function() {
		var refund_money= $.trim(this.value);
		var actual_money = $('#actual_money').val();
		var final_money = actual_money-refund_money;
		if(final_money < '0'){
			layer.msg('退款金额不可大于可退金额!',{icon:2});
			return false;
		}
		if(isNaN(final_money)){
			layer.msg('请输入正确格式!',{icon:2});
			return false;
		}
		$('#withhold_money').val(final_money);
	});

	$('.submit_info').click(function(){
		var refund_money= $('#refund_money').val();//退款金额
		var actual_money = $('#actual_money').val();
		var final_money = actual_money-refund_money;
		if(refund_money <= '0' || refund_money == ''){
			layer.msg('退款金额不可小于等于0!',{icon:2});
			return false;
		}
		
		if(final_money < '0'){
			layer.msg('退款金额不可大于可退金额!',{icon:2});
			return false;
		}
		if(isNaN(final_money)){
			layer.msg('请输入正确格式!',{icon:2});
			return false;
		}
		var refund_note = $('#refund_note').val();//退款备注
		$.post("{pigcms{:U('deposit_refund')}",{'refund_money':refund_money,'deposit_id':"{pigcms{$info_list[deposit_id]}",'refund_note':refund_note},function(data){
	                if(data.code == 1){
	                    layer.msg(data.msg,{icon: 1},function(){
	                    	// location.reload();
	                    	location.href='{pigcms{:U('Unit/deposit_management')}';
	                    });
	                }
	                if(data.code == 2){
	                    layer.msg(data.msg,{icon: 2});
	                }
	    },'json');
	})
</script>

<include file="Public:footer"/>