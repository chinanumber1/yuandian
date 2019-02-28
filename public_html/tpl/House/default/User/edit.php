<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-user"></i>
				<a href="{pigcms{:U('User/index')}">业主管理</a>
			</li>
			<li class="active">业主信息设置</li>
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
					<form  class="form-horizontal" method="post" id="edit_form" action="{pigcms{:U('User/edit')}" onsubmit="return chk_submit()">
						<input  name="pigcms_id" type="hidden"  value="{pigcms{$info.pigcms_id}"/>
						<input  name="usernum" type="hidden"  value="{pigcms{$info.usernum}"/>
						<input type="hidden" name="page" value="{pigcms{$_GET['page']}" />
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								
								<div class="form-group">
									<label class="col-sm-1"><label for="usernum">用户编号</label></label>
									<input class="col-sm-2" size="20" value="{pigcms{$info.usernum}" type="text" style="border:none;background:white!important;" readonly="readonly">
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="name">业主名称</label></label>
									<input class="col-sm-2" size="20" name="name" id="name" type="text" value="{pigcms{$info.name}"/>
								</div>

								<div class="form-group">
									<label class="col-sm-1"><label for="phone">手机号：</label></label>
									<input class="col-sm-2" size="20" name="phone" id="phone" type="text" value="{pigcms{$info.phone}" />
									<span class="red line-span none not-phone">&nbsp;&nbsp;</span>
								</div>
								<script>
									var international_phone = {pigcms{$config.international_phone|intval=###};
									$('#phone').on('blur' , function(){
										phone = $(this).val();
										if(!international_phone && phone.length != 11){
											$(".not-phone").html('&nbsp;&nbsp;* 手机号不正确').show();	
											return false;	
										}
										$.post("{pigcms{:U('ajax_empty_user_info')}" , {phone:phone} , function(data){
											if(data.status==1){
												$(".not-phone").html('&nbsp;&nbsp;* '+data.msg).show();	
											}else{
												$(".not-phone").html('').hide();	
											}	
										},'JSON');
										
									});
								</script>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="floor_name">单元房间：</label></label>
                                    <p style="line-height:30px; color:#666">
                                    <volist name='floor_list["list"]' id='floor_info'>
                                    <if condition='$floor_info["floor_id"] eq $info["floor_id"]'>
									{pigcms{$floor_info.floor_layer}&nbsp;&nbsp;-&nbsp;&nbsp;{pigcms{$floor_info.floor_name}
                                    </if>
                                    </volist>
                                    &nbsp;&nbsp;-&nbsp;&nbsp;
                                    
                                    <volist name='vacancy_list' id='vacancy_info'>
                                    <if condition='$vacancy_info["floor_id"] eq $info["floor_id"] AND $vacancy_info["pigcms_id"] eq $info["vacancy_id"]'>
                                    {pigcms{$vacancy_info.layer}&nbsp;&nbsp;-&nbsp;&nbsp;{pigcms{$vacancy_info.room}
                                    </if>
                                    </volist>
                                    </p>
								</div>
								
								<div class="form-group" style="display:none">
									<label class="col-sm-1"><label for="floor_name">单元名称：</label></label>
									<select class="col-sm-2"  name="floor_id" id="floor_id">
										<volist name='floor_list["list"]' id='floor_info'>
											<option value="{pigcms{$floor_info.floor_id}" <if condition='$floor_info["floor_id"] eq $info["floor_id"]'>selected="selected"</if>>{pigcms{$floor_info.floor_name}&nbsp;&nbsp;--&nbsp;&nbsp;{pigcms{$floor_info.floor_layer}</option>
										</volist>
									</select>
								</div>
									<if condition="!isset($_GET['pigcms_id'])">
								
								<div class="form-group" style="display:none">
									<label class="col-sm-1"><label for="layer_room">层号房间号：</label></label>
									<select class="col-sm-2" name="layer_room" id="layer_room" >
										<option value="0">请选择</option>
										<volist name='vacancy_list' id='vacancy_info'>
											<option data-layer="{pigcms{$vacancy_info['layer']}" value="{pigcms{$vacancy_info['pigcms_id']}" data-room="{pigcms{$vacancy_info['room']}" <if condition='$vacancy_info["pigcms_id"] eq $info["vacancy_id"]'>selected="selected"</if>>{pigcms{$vacancy_info.layer}&nbsp;&nbsp;--&nbsp;&nbsp;{pigcms{$vacancy_info.room}</option>
										</volist>
									</select>
									<input name="layer_num" id="layer_num" type="hidden" value="{pigcms{$info['layer']}"/>
									<input name="room_num" id="room_num" type="hidden" value="{pigcms{$info['room']}"/>
									<input name="vacancy_id" id="vacancy_id" type="hidden" value="{pigcms{$info['vacancy_id']}" />	
								</div>
								</if>
								
								<!--div class="form-group">
									<label class="col-sm-1"><label for="address">住址</label></label>
									<input class="col-sm-2" size="20" name="address" id="address" type="text" value="{pigcms{$info.address}" />
								</div-->
								<div class="form-group">
									<label class="col-sm-1"><label for="water_price">水费总欠费</label></label>
									<input class="col-sm-2" size="10" name="water_price" id="water_price" type="text"  value="{pigcms{$info.water_price|floatval=###}"/>
									<span class="form_tips">元 （支持两位小数）</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="electric_price">电费总欠费</label></label>
									<input class="col-sm-2" size="10" name="electric_price" id="electric_price" type="text"  value="{pigcms{$info.electric_price|floatval=###}"/>
									<span class="form_tips">元 （支持两位小数）</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="gas_price">燃气费总欠费</label></label>
									<input class="col-sm-2" size="10" name="gas_price" id="gas_price" type="text"  value="{pigcms{$info.gas_price|floatval=###}"/>
									<span class="form_tips">元 （支持两位小数）</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="park_price">停车费总欠费</label></label>
									<input class="col-sm-2" size="10" name="park_price" id="park_price" type="text"  value="{pigcms{$info.park_price|floatval=###}"/>
									<span class="form_tips">元 （支持两位小数）</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="housesize">房子平方</label></label>
									{pigcms{$info.housesize}
									<span class="form_tips">m²</span>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="memo">备注</label></label>
									<textarea class="col-sm-2" size="10" name="memo" id="memo" />{pigcms{$info.memo}</textarea>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="unittype">住宅类型</label></label>
									<input class="col-sm-2" size="20" value="{pigcms{$info.floor_type_name}" type="text" style="border:none;background:white!important;" readonly="readonly">
								</div>
								
								<if condition='$info.type gt 0'>
									<div class="form-group">
										<label class="col-sm-1"><label for="unittype">关系</label></label>
										<input class="col-sm-2" size="20" <if condition='$info["type"] eq 1'>value="家人"<elseif condition='$info["type"] eq 2' />value="租客"</if> type="text" style="border:none;background:white!important;" readonly="readonly">
									</div>
								</if>

								<div class="form-group">
									<label class="col-sm-2"><label for="property_starttime">物业服务开始时间</label></label>
									<if condition="$info['property_starttime']">
										<input type="text" name="property_starttime" class="input-text" value="{pigcms{$info.property_starttime|date='Y-m-d',###}"  style="height:42px" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="物业服务时间"/>
									<else/>
										<input type="text" name="property_starttime" class="input-text" value=""  style="height:42px" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="物业服务时间（开始）"/>
									</if>
								</div>

								<div class="form-group">
									<label class="col-sm-2"><label for="property_endtime">物业服务结束时间</label></label>
									<if condition="$info['property_endtime']">
										<input type="text" name="property_endtime" class="input-text" value="{pigcms{$info.property_endtime|date='Y-m-d',###}"  style="height:42px" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="物业服务时间"/>
									<else/>
										<input type="text" name="property_endtime" class="input-text" value=""  style="height:42px" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="物业服务时间（结束）"/>
									</if>
								</div>
								<if condition="$config['PC_write_card'] eq 1">
									<div class="form-group">
										<label class="col-sm-1" for="door_control">门禁控制</label>

										<label style="padding-left:0px;padding-right:20px;">
											<input type="radio" name="door_control" value="0" class="ace" <if condition="$info['door_control'] eq 0">checked="checked"</if>>
											<span class="lbl" style="z-index: 1" >机动车道</span>
										</label>

										<label style="padding-left:0px;padding-right:20px;">
											<input type="radio" name="door_control" value="1" class="ace" <if condition="$info['door_control'] eq 1">checked="checked"</if>>
											<span class="lbl" style="z-index: 1">行人通道</span>
										</label>
										
										<label style="padding-left:0px;padding-right:20px;">
											<input type="radio" name="door_control" value="0,1" class="ace" <if condition="$info['door_control'] eq '0,1'">checked="checked"</if>>
											<span class="lbl" style="z-index: 1" >全部通道</span>
										</label>
									</div>
								</if>
								
								<div class="form-group">
									<label class="col-sm-1" for="park_flag">车位信息</label>
									<if condition="$position_list['list']">
										<table class="table table-striped table-bordered table-hover"  style="width: 40%">
											 <thead>
		                                        <tr>
		                                            <th width="5%">车库</th>
		                                            <th width="5%">车位编号</th>
		                                            <th width="5%">车位面积</th>
		                                            <th width="5%">备注</th>
		                                            <th width="5%">操作</th>
		                                        </tr>
		                                    </thead>

											<volist name="position_list['list']" id="vo">
												<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                                    <td>{pigcms{$vo.garage_num}</td>
                                                    <td>{pigcms{$vo.position_num}</td>
                                                    <td>{pigcms{$vo.position_area}</td>
                                                    <td>{pigcms{$vo.position_note}</td>
                                                    <td>
                                                        <a class="label label-sm label-info" href="javascript:void(0);" onclick="unbind('{pigcms{$vo.bind_id}',1)">解绑</a>
                                                    </td>
                                                </tr>
											</volist>
										</table>
									<else/>
									无
									</if>
									<!-- <label style="padding-left:0px;padding-right:20px;"><input type="radio" name="park_flag" value="1" class="ace" <if condition="$info['park_flag'] eq 1">checked="checked"</if>><span class="lbl" style="z-index: 1">开启</span></label>
									<label style="padding-left:0px;"><input type="radio" name="park_flag" value="0" class="ace" <if condition="$info['park_flag'] eq 0">checked="checked"</if>><span class="lbl" style="z-index: 1" >关闭</span></label> -->
								</div>

								<div class="form-group">
									<label class="col-sm-1" for="park_flag">车辆信息</label>
									<if condition="$car_list">
										<table class="table table-striped table-bordered table-hover"  style="width: 40%">
											 <thead>
		                                        <tr>
		                                            <th width="5%">车位</th>
		                                            <th width="5%">车牌号</th>
		                                            <th width="5%">停车卡号</th>
		                                            <th width="5%">车主姓名</th>
		                                            <th width="5%">车主手机号</th>
		                                            <th width="5%">车辆排量</th>
		                                            <th width="5%">操作</th>
		                                        </tr>
		                                    </thead>

											<volist name="car_list" id="vo">
												<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                                    <td>{pigcms{$vo.position_num}</td>
                                                    <td>{pigcms{$vo.province}{pigcms{$vo.car_number}</td>
                                                    <td>{pigcms{$vo.car_stop_num}</td>
                                                    <td>{pigcms{$vo.car_user_name}</td>
                                                    <td>{pigcms{$vo.car_user_phone}</td>
                                                    <td>{pigcms{$vo.car_displacement}</td>
                                                    <td>
                                                        <a class="label label-sm label-info" href="javascript:void(0);" onclick="unbind('{pigcms{$vo.id}',2)">解绑</a>
                                                    </td>
                                                </tr>
											</volist>
										</table>
									<else/>
									无
									</if>
								</div>
								<div class="form-group">
									<label class="col-sm-1" for="park_flag">收费项</label>
									<if condition="in_array(97,$house_session['menus'])">
									<a class="bind_info btn label-info" href="{pigcms{:U('payment_add',array('pigcms_id'=>$info['pigcms_id']))}">添加收费项</a>
									<else/>
									<button class="btn" disabled="disabled">添加收费项</button>
									</if>
									<div class="grid-view" style="width: 80%;margin-left: 8%" >
										<table class="table table-striped table-bordered table-hover">
										    <thead>
										        <tr>
										            <th width="13%">收费项目</th>
										            <th>收费模式</th>
										            <th>计量方式</th>
										            <th>计量数量</th>
										            <th>收费金额</th>
										            <th>收费周期</th>
										            <th>收费周期上限</th>
										            <th>开始时间</th>
										            <th>结束时间</th>
										            <th>签订周期</th>
										            <th>已缴费周期</th>
										            <!-- <th>备注</th> -->
										            <th>操作</th>
										        </tr>
										    </thead>
										    <tbody class="class-checkbox">
										    	<if condition="is_array($payment_list)">
										    		<volist name="payment_list" id="vo">
											            <tr>
											                <td>
											                <if condition="$vo['garage_num']">
											                {pigcms{$vo.payment_name}({pigcms{$vo.garage_num}-{pigcms{$vo.position_num})
											                <else/>
											                {pigcms{$vo.payment_name}
											                </if>
											            	</td>
											                <td><div class="tagDiv"><if condition="$vo.pay_type eq 1">固定费用<else/>按金额*计量方式</if></div></td>
											                <td>{pigcms{$vo.metering_mode}</td>
											                <td>{pigcms{$vo.metering_mode_val}</td>
											                <td>{pigcms{$vo.pay_money}</td>
											                <td>{pigcms{$vo.pay_cycle} ({pigcms{$cycle_type[$vo['cycle_type']]})&nbsp;/&nbsp;周期</td>
											                <td>{pigcms{$vo.max_cycle} &nbsp;周期</td>
											                <td>{pigcms{$vo.start_time|date="Y-m-d",###}</td>
											                <td>{pigcms{$vo.end_time|date="Y-m-d",###}</td>
											                <td>{pigcms{$vo.cycle_sum}</td>
											                <td>{pigcms{$vo.paid_cycle}</td>
											                <!-- <td>{pigcms{$vo.remarks}</td> -->
											                <td>
															<if condition="in_array(114,$house_session['menus'])">
																<a class="label label-sm label-info" href="javascript:void(0);" onclick="payment_del({pigcms{$vo.bind_id})">删除</a>
															<else/>
																无
															</if>
															</td>
											            </tr>
											        </volist>
											        <else/>
											        <tr><td colspan="11" style="text-align: center; color: red;">暂无收费项</td></tr>
											        
										    	</if>
										        
										    </tbody>
										</table>
									</div>
								</div>

								
								<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
								<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
								<script>
									$('.bind_info').click(function(){
										art.dialog.open($(this).attr('href'),{
											init: function(){
												var iframe = this.iframe.contentWindow;
												window.top.art.dialog.data('iframe_handle',iframe);
											},
											id: 'handle',
											title:'添加收费项',
											padding: 0,
											width: 1000,
											height: 603,
											lock: true,
											resize: false,
											background:'black',
											button: null,
											fixed: false,
											close: null,
											left: '50%',
											top: '38.2%',
											opacity:'0.4'
										});
										return false;
									});
								</script>
								

							</div>
						</div>
						<script>
							function payment_del(bind_id) {
								var payment_del_url = "{pigcms{:U('payment_del')}";
								var pigcms_id = "{pigcms{$_GET['pigcms_id']}";
								layer.confirm('您确定要删除这条收费项吗？', {
									btn: ['确定','取消'] //按钮
								}, function(){
									$.post(payment_del_url,{bind_id:bind_id,pigcms_id:pigcms_id},function(data){
										if(data.error == 1){
											alert(data.msg);
											location.href = location.href;
										}else{
											alert(data.msg);
										}
									},'json');
								});
							}
						</script>

						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit" <if condition="!in_array(93,$house_session['menus'])">disabled="disabled"</if>>
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
<script type="text/javascript" language="javascript">
function chk_submit(){
	/* var ex = /^\d+$/;
	if(!ex.test($('input[name="property_month_num"]').val())){
		alert('物业服务周期必须为整数！');
		return false;
	}
	
	if(!ex.test($('input[name="presented_property_month_num"]').val())){
		alert('赠送物业时间必须为整数！');
		return false;
	} 
	
	if(!confirm('确认进行修改，修改后会微信通知业主。')){
		return false;
	}*/
	
	if(!confirm('确认进行修改?')){
		return false;
	}
}

 function unbind(bind_id,type){
    if(!bind_id || bind_id==''){
        layer.msg('请选择您要解绑的用户!',{icon: 2});
        return false;
    }
    var url;
    if (type==1) {
   		url = "{pigcms{:U('Unit/unbind_position')}";
    }else if(type==2){
   		url = "{pigcms{:U('Unit/unbind_car')}";
    }

    layer.confirm('确认解绑？', {
      btn: ['确定','取消'] //按钮
    }, function(){
        $.post(url,{'bind_id':bind_id},function(data){
            if(data.code == 1){
                layer.msg(data.msg,{icon: 1},function(){
                    location.reload();
                });
            }
            if(data.code == 2){
                layer.msg(data.msg,{icon: 2},function(){
                    location.reload();
                });
            }
        },'json');
    }, function(){
      
    });  

    
}


var url = "{pigcms{:U('ajax_get_layer')}";
$('#floor_id').change(function(){
	var floor_id = $(this).val();
	$.post(url,{'floor_id':floor_id},function(data){
		if(data['status'] == 0){
			alert(data.msg);
		}else{
			var list = data['list'];
			var shtml = '<option>请选择</option>';
			
			if(list){
				for(var i in list){
					shtml += '<option data-layer="'+list[i]['layer']+'" data-room="'+list[i]['room']+'" value="'+list[i]['pigcms_id']+'">'+list[i]['layer']+'&nbsp;&nbsp;--&nbsp;&nbsp;'+list[i]['room']+'</option>'
				}
			}
			$('#layer_room').html(shtml)
		}
	},'json')
});

$('#layer_room').change(function(){
	$('#layer_num').val($(this).find(':selected').data('layer'));
	$('#room_num').val($(this).find(':selected').data('room'));
	$('#vacancy_id').val($(this).find(':selected').val());
})
</script>
<include file="Public:footer"/>