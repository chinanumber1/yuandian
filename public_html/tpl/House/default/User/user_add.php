<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('User/index')}">业主管理</a>
            </li>
            <li class="active">添加业主</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <style>
		.line-span { line-height:34px;}
		.none { display:none}
	</style>
    <div class="page-content">
        <div class="row">
				<div class="col-xs-12">
					<form  class="form-horizontal" method="post" onSubmit="return check_submit()" action="__SELF__">
						<div class="tab-content">
								<!--div class="form-group">
									<label class="col-sm-1"><label for="usernum">业主编号：</label></label>
									<input class="col-sm-2" size="20" name="usernum" id="usernum" type="text" value=""/>
									<span class="red" style=" line-height:27px; display:none">&nbsp;&nbsp;*&nbsp;&nbsp;业主编号已存在</span>
								</div-->
                                
                                <div class="form-group">
									<label class="col-sm-1"><label for="user_name">业主名：</label></label>
									<input class="col-sm-2" size="20" name="user_name" id="user_name" type="text" value=""/>
								</div>
                                
								<div class="form-group">
									<label class="col-sm-1"><label for="phone">手机号：</label></label>
									<input class="col-sm-2" size="20" name="phone" id="phone" type="text" value=""/> <span class="red line-span none not-phone">&nbsp;&nbsp;此手机号暂时不是平台用户</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="floor_name">单元名称：</label></label>
									<!--input class="col-sm-2" size="20" name="floor_name" id="floor_name" type="text" value=""/-->
									<select class="col-sm-2"  name="floor_id" id="floor_id">
										<volist name='floor_list["list"]' id='floor_info'>
											<option value="{pigcms{$floor_info.floor_id}">{pigcms{$floor_info.floor_name}&nbsp;&nbsp;--&nbsp;&nbsp;{pigcms{$floor_info.floor_layer}</option>
										</volist>
									</select>
									
									
									<!--span class="red" style=" line-height:27px;">&nbsp;&nbsp;*&nbsp;&nbsp;必须为单元列表中的单元名称</span-->
								</div>
								
								<!--div class="form-group">
									<label class="col-sm-1"><label for="floor_layer">楼号：</label></label>
									<input class="col-sm-2" size="20" name="floor_layer" id="floor_layer" type="text" value=""/>
									<span class="red" style=" line-height:27px;">&nbsp;&nbsp;*&nbsp;&nbsp;必须为单元列表中的楼号</span>
								</div-->
								
								<div class="form-group">
									<label class="col-sm-1"><label for="layer_room">层号房间号：</label></label>
									<!--input class="col-sm-2" size="20" name="layer_num" id="layer_num" type="text" value=""/-->
									<select class="col-sm-2" name="layer_room" id="layer_room" >
										<option value="0">请选择</option>
									</select>
									<input name="layer_num" id="layer_num" type="hidden" value="{pigcms{$vacancy_list[0]['layer']}" />
									<input name="room_num" id="room_num" type="hidden" value="{pigcms{$vacancy_list[0]['room']}" />
									<input name="vacancy_id" id="vacancy_id" type="hidden" value="{pigcms{$vacancy_list[0]['pigcms_id']}" />	
								</div>
								
								<!--div class="form-group">
									<label class="col-sm-1"><label for="room_num">门牌号：</label></label>
									<input class="col-sm-2" size="20" name="room_num" id="room_num" type="text" value=""/>
								</div-->
								
								<div class="form-group">
									<label class="col-sm-1"><label for="housesize">房子平方：</label></label>
									<input class="col-sm-2" size="20" name="housesize" id="housesize" type="text" readonly="readonly" value=""/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="water_price">水费总欠费：</label></label>
									<input class="col-sm-2" size="20" name="water_price" id="water_price" type="text" value=""/>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="electric_price">电费总欠费：</label></label>
									<input class="col-sm-2" size="20" name="electric_price" id="electric_price" type="text" value=""/>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="gas_price">燃气费总欠费：</label></label>
									<input class="col-sm-2" size="20" name="gas_price" id="gas_price" type="text" value=""/>
								</div>
								
								<!--div class="form-group">
									<label class="col-sm-1"><label for="property_price">物业总欠费：</label></label>
									<input class="col-sm-2" size="20" name="property_price" id="property_price" type="text" value=""/>
								</div-->
								
								<!-- <div class="form-group">
									<label class="col-sm-1">是否有停车位：</label>
									<label style="padding-left:0px;padding-right:20px;"><input type="radio" checked="" class="ace" value="1" name="park_flag"><span style="z-index: 1" class="lbl">开启</span></label>
									<label style="padding-left:0px;"><input type="radio" class="ace" value="0" name="park_flag"><span style="z-index: 1" class="lbl">关闭</span></label>
								</div> -->
								
								<div class="form-group">
									<label class="col-sm-1"><label for="park_price">停车费总欠费：</label></label>
									<input class="col-sm-2" size="20" name="park_price" id="park_price" type="text" value=""/>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="memo">备注：</label></label>
									<textarea class="col-sm-2" size="20" name="memo" id="memo"/></textarea>
								</div>

								<div class="form-group">
									<label class="col-sm-2"><label for="property_starttime">物业服务开始时间截止至</label></label>
									<input type="text" name="property_starttime" class="input-text" value=""  style="height:42px" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="物业服务时间（开始）"/>
								</div>

								<div class="form-group">
									<label class="col-sm-2"><label for="property_endtime">物业服务时间截止至</label></label>
									<input type="text" name="property_endtime" class="input-text" value=""  style="height:42px" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="物业服务时间（结束）"/>
								</div> 

						</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa fa-check bigger-110"></i>
										添加
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
    </div>
</div>
<script type="text/javascript">
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

$('input[name="park_flag"]').change(function(){
	if($(this).val()==1){
		$('input[name="park_price"]').parent().show();
	}else{
		$('input[name="park_price"]').parent().hide();
	}
})


$('#usernum').blur(function(){
	var usernum = $(this).val();
	var url = "{pigcms{:U('ajax_user_bind')}";
	$.post(url,{'usernum':usernum},function(data){
		if(data.status==1){
			$('#usernum').next('span').show();
		}else{
			$('#usernum').next('span').hide();
		}
	},'json')
});


var url = "{pigcms{:U('ajax_get_layer')}";
$('#floor_id').change(function(){
	var floor_id = $(this).val();
	$.post(url,{'floor_id':floor_id},function(data){
		if(data['status'] == 0){
			alert(data.msg);
		}else{
			var list = data['list'];
			var shtml = '';
			
			if(list){
				shtml += '<option>请选择</option>'	
				for(var i in list){
					shtml += '<option data-layer="'+list[i]['layer']+'" data-room="'+list[i]['room']+'" data-housesize="'+list[i]['housesize']+'" value="'+list[i]['pigcms_id']+'">'+list[i]['layer']+'&nbsp;&nbsp;--&nbsp;&nbsp;'+list[i]['room']+'</option>'
				}
			}else{
				shtml += '<option>请选择</option>'
			}
			$('#layer_room').html(shtml)
			$("#layer_num").val(0);
			$("#room_num").val(0);
			$("#vacancy_id").val(0);
		}
	},'json')
}).trigger('change');

$('#layer_room').change(function(){
	$('#housesize').val($(this).find(':selected').data('housesize'));
	$('#layer_num').val($(this).find(':selected').data('layer'));
	$('#room_num').val($(this).find(':selected').data('room'));
	$('#vacancy_id').val($(this).find(':selected').val());
})

function check_submit(){
	if($('#usernum').val()==''){
		alert('业主编号不能为空！');
		return false;
	}
	
	if($('#user_name').val()==''){
		alert('业主名不能为空！');
		return false;
	}
	
	if($('#phone').val()==''){
		alert('手机号不能为空！');
		return false;
	}
	
	if($('#floor_name').val()==''){
		alert('单元名称不能为空！');
		return false;
	}
	
	if($('#floor_layer').val()==''){
		alert('楼号不能为空！');
		return false;
	}
	
	/* if($('#layer_num').val()==''){
		alert('层号不能为空！');
		return false;
	} 
	
	if($('#room_num').val()==''){
		alert('门牌号不能为空！');
		return false;
	}*/
	
	if($('#housesize').val()=='' || $('#housesize').val()<0){
		alert('房子平方不能为空 / 格式不正确');
		return false;
	}
	
	if($('#water_price').val()<0){
		alert('水费总欠费格式不正确');
		return false;
	}
	
	if($('#electric_price').val()<0){
		alert('电费总欠费格式不正确');
		return false;
	}
	
	if($('#gas_price').val()<0){
		alert('燃气费总欠费格式不正确');
		return false;
	}
	
	if($('#park_price').val()<0){
		alert('停车费总欠费格式不正确');
		return false;
	}
}
</script>
<include file="Public:footer"/>
