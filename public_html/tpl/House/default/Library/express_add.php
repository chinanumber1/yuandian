<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-tablet"></i>
				<a href="{pigcms{:U('express_service_list')}">功能库列表</a>
			</li>
			<li class="active">添加快递</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<form  class="form-horizontal" method="post" onSubmit="return check_submit()" action="__SELF__">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								
								<div class="form-group">
									<label class="col-sm-1"><label for="express_no">快递单号：</label></label>
									<input class="col-sm-2" size="20" name="express_no" id="express_no" type="text" value=""/>
									<label class="col-sm-3"><span class="red">*&nbsp;&nbsp;手动输入或者用扫码枪</span></label>
								</div>

                            	<div class="form-group">
									<label class="col-sm-1"><label for="express_type">选择分类：</label></label>
									<select name="express_type" id="express_type">
										<option value="0">快递类型</option>
                                        <volist name='express_list' id='vo'>
                                    		<option value="{pigcms{$vo.id}">{pigcms{$vo.name}</option>
                                        </volist>
                                        <option value="255">其他（须备注）</option>
                                    </select>
								</div>

								<script>
									$("#express_no").keydown(function(){
							            if(event.keyCode==13) {
							            	var express_no = $("#express_no").val();
							            	var check_express_no_url = "{pigcms{:U('ajax_check_express_no')}";
							            	$.post(check_express_no_url,{express_no:express_no},function(data){
							            		if(data.error == 1){
							            			alert('单号未识别请手动选择');
							            		}else{
							            			$("#express_type").val(data.id); 
							            		}
							            	},'json');
											return false;
							            }
									});
								</script>

                                <div class="form-group">
									<label class="col-sm-1"><label for="phone">收件人手机号码：</label></label>
									<input class="col-sm-2" size="20" name="phone" id="phone" type="text" value=""/>
									<label id="tips_user" style="color:red;display:none">未查询到该业主或租客家属，只能享受代收功能。</label>
                                    <input type="hidden" name="is_user_phone" id="is_user_phone" value="0">
								</div>
								<if condition="$express_money_status eq 1">
								<div class="form-group" style="display:none" id="unit">
									<label class="col-sm-1"><label for="express_type">选择单元：</label></label>
									<select name="floor_id" id="floor_id">
										
                                    </select>
								</div>
									<if condition="$express_config.free eq 0">
									<div class="form-group" style="display:none" id="money_collect">
										<label class="col-sm-1"><label for="money">代送收费：</label></label>
										<input class="col-sm-2" size="20" name="money" id="money" type="text" value=""/>
									</div>
									</if>
								</if>
                                <div class="form-group">
                                	<label class="col-sm-1"><label for="memo">备　　注：</label></label>
									<textarea id="memo" name="memo"style="width:281px; height:100px"></textarea>
								</div>
							</div>

						
						<div class="space"></div>
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
<script type="text/javascript">

	$(function(){
		
		$('#phone').on('blur' , function(){
			if($(this).val().length > 6){
				$.post("{pigcms{:U('ajax_get_unit')}", {phone: $(this).val()}, function(data) {
					var option_str = '';
					if(data.status==1){
						for(var i in data.floor_list){
							if(data.floor_list[i].address!=''){							
								option_str+='<option value="'+data.floor_list[i].floor_id+'">'+data.floor_list[i].address+'</option>';
							}else{
								option_str+='<option value="'+data.floor_list[i].floor_id+'">'+data.floor_list[i].floor_name+'</option>';
							}
							
						}
						$('#unit').show();
						$('#money_collect').show();
						$('#tips_user').hide();
						$("#is_user_phone").val(0);
						$('#floor_id').html(option_str);
					}else{
						
						$('#tips_user').show();
						$("#is_user_phone").val(1);
						$('#unit').hide();
						$('#money_collect').hide();
					}
				},"JSON");
			}
		});
	});
	function check_submit(){
		if($('#express_type').val()==0){
			alert('快递类型不能为空！');
			return false;
		}

		if($('#express_no').val()==''){
			alert('快递单号不能为空！');
			return false;
		}
		if($('#phone').val()==''){
			alert('手机号码不能为空！');
			return false;
		}
		
		if(($('#express_type').val()==999)&&($('#memo').val()=='')){
			alert('备注不能为空！');
			return false;
		}
		
		if(confirm('确认保存？')){
			return true;
		}else{
			return false;
		}
		<if condition="$express_config.free eq 0">
		if($('#money').val()<0||isNaN($('#money').val())){
			alert('费用有误');
			return false;
		}
		</if>
		
	}
</script>

<include file="Public:footer"/>