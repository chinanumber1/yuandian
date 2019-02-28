<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('Unit/parking_management')}">车位管理</a>
            </li>
            <li class="active">批量增加车位</li>
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
								
								<div class="form-group garage_select">
									<label class="col-sm-1"><label for="garage_id">车库</label></label>
									<select name="garage_id" id="garage_id">
											<option value='0'>--请选择--</option>
										<volist name="garage_list" id="vol">
											<option value='{pigcms{$vol.garage_id}'>{pigcms{$vol.garage_position}</option>
										</volist>
									</select>
								</div>

								<!-- <div class="form-group">
									<label class="col-sm-1"><label for="prefix">车位号前缀</label></label>
									<input name="prefix" id="prefix" type="text" style="width:100px;margin-right:10px;"/>
									<span style="color: green;">*选填</span><span style="color:red;margin-left: 10px;">只能填写纯字母</span>
								</div> -->

								<div class="form-group">
									<label class="col-sm-1"><label for="position_num">车位编号</label></label>
									<input  name="position_num[]" id="start_num" type="text"  value="" style="width:100px;margin-right:10px;"/>----
									<input  name="position_num[]" id="end_num" type="text"  value="" style="width:100px;margin-left:10px;"/>
								</div>

								<!-- <div class="form-group position_select">
									<label class="col-sm-1"><label for="position_type">车位类型</label></label>
									<select name="position_type" id="position_type">
										<option value='0'>--请选择--</option>
										<option value="1">产权车位</option>
										<option value="2">租赁车位</option>
										<option value="3">临时车位</option>
									</select>
								</div> -->

								<div class="form-group status_select">
									<label class="col-sm-1"><label for="position_status">车位状态</label></label>
									<select name="position_status" id="position_status">
										<option value='1' >自用</option>
										<option value='2' >空置</option>
										<option value='3' >出售</option>
										<option value='4' >出租</option>
									</select>
								</div>

								<div class="form-group">
									<label class="col-sm-1"><label for="position_area">车位面积</label></label>
									<input class="col-sm-2" size="20" name="position_area" id="position_area" type="text"  value="" />
									<label><span class="green">单位：平方米</span></label>
								</div>

								
								<div class="form-group" s>
									<label class="col-sm-1"><label>备注</label></label>
									<label><textarea name="position_note" id="position_note" maxlength="255" style="width:286px;height:90px;resize:none" placeholder="最多输入255个字"></textarea></label>
								</div>

							</div>
						</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info submit_info" type="button">
										<i class="ace-icon fa fa-check bigger-110"></i>
										增加
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
		var is_garage = "{pigcms{$garage_list}";
		if(!is_garage || is_garage==null){
			layer.msg('该物业尚未录入车库信息,请先录入车库信息',{icon:7},3000);
			return false;
		}
	});

	$('.submit_info').click(function(){
		var start_num = $('#start_num').val();//开始编号
		var end_num = $('#end_num').val();//结束编号
		var reg=/[a-z]/i;
		
		var garage_id = $('#garage_id').val();
		if(!garage_id || garage_id == '0'){
			layer.msg('请选择车库位置!',{icon:2});
			return false;
		}
		// var position_type = $('#position_type').val();//车位类型
		// if(!position_type || position_type == '0'){
		// 	layer.msg('请选择车位类型!',{icon:2});
		// 	return false;
		// }

		var position_status = $('#position_status').val();//车位状态
		if(!position_status || position_status == '0'){
			layer.msg('请选择车位状态!',{icon:2});
			return false;
		}

		var position_area = $('#position_area').val();//车位面积
		if(!position_area || position_area == '0'){
			layer.msg('请输入车位面积!',{icon:2});
			return false;
		}

		if(start_num>=end_num){
			layer.msg('请输入有效编号!',{icon:2});
			return false;
		}

		// var prefix = $('#prefix').val();//前缀
		// if(prefix !==false){
		// 	var flag = reg.test(prefix);
		// 	if(!flag){
		// 		layer.msg('车位前缀最好为纯字母!',{icon:2});
		// 		return false;
		// 	}
		// }
		var position_note = $('#position_note').val();//备注
		$.post("{pigcms{:U('parking_position_addall')}",{'start_num':start_num,'end_num':end_num,'garage_id':garage_id,'position_status':position_status,'position_area':position_area,'position_note':position_note},function(data){
	                if(data.code == 1){
	                    layer.msg(data.msg,{icon: 1},function(){
	                    	location.reload();
	                	});
	                }
	                if(data.code == 2){
	                    layer.msg(data.msg,{icon: 2});
	                }
	    },'json');
	})


</script>

<include file="Public:footer"/>