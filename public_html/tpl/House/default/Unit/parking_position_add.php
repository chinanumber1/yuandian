<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('Unit/parking_management')}">车位管理</a>
            </li>
            <li class="active">添加单个车位</li>
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
											<option value='{pigcms{$vol.garage_id}'>{pigcms{$vol.garage_num}-{pigcms{$vol.garage_position}</option>
										</volist>
									</select>
								</div>
									
								<!-- <div class="form-group">
									<label class="col-sm-1"><label for="prefix">车位号前缀</label></label>
									<input name="prefix" id="prefix" type="text" style="width:100px;margin-right:10px;"/>
									<span style="color: green;">*选填</span><span style="color:red;margin-left: 10px;">只能填写纯字母</span>
								</div> -->

								<div class="form-group">
									<label class="col-sm-1"><label for="position_num">车位号</label></label>
									<input  name="position_num" id="position_num" type="text"  value="" style="width:100px;margin-right:10px;"/>
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

								<!-- <div class="form-group status_select">
									<label class="col-sm-1"><label for="position_status">车位状态</label></label>
									<select name="position_status" id="position_status">
										<option value='1' >自用</option>
										<option value='2' >空置</option>
										<option value='3' >出售</option>
										<option value='4' >出租</option>
									</select>
								</div> -->
								
								<div class="form-group">
									<label class="col-sm-1"><label for="position_area">车位面积</label></label>
									<input class="col-sm-2" size="20" name="position_area" id="position_area" type="text"  value="{pigcms{$position_list.position_area}" />
									<label><span class="green">单位：平方米</span></label>
								</div>

								
								<div class="form-group" s>
									<label class="col-sm-1"><label>备注</label></label>
									<label><textarea name="position_note" id="position_note" maxlength="255" style="width:286px;height:90px;resize:none" placeholder="最多输入255个字">{pigcms{$position_list.position_note}</textarea></label>
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
$('.submit_info').click(function(){
	var garage_id = $('#garage_id').val();
	if(garage_id==0){
		layer.msg('车库位置不能为空!',{icon:2});
		return false;
	}
	var reg=/[a-z]/i;
	// var prefix = $('#prefix').val();//前缀
	// if(prefix !==false){
	// 	var flag = reg.test(prefix);
	// 	if(!flag){
	// 		layer.msg('车位前缀最好为纯字母!',{icon:2});
	// 		return false;
	// 	}
	// }
	var position_num = $('#position_num').val();
	if(!position_num){
		layer.msg('车位号不能为空!',{icon:2});
		return false;
	}
	// var position_status = $('#position_status').val();//车位状态
	// if(!position_status){
	// 	layer.msg('车位状态不能为空!',{icon:2});
	// 	return false;
	// }
	// var position_type = $('#position_type').val();//车位状态
	// if(position_type==0){
	// 	layer.msg('车位类型不能为空!',{icon:2});
	// 	return false;
	// }
	
	var position_area = $('#position_area').val();//区域面积
	if(!position_area){
		layer.msg('车位面积不能为空!',{icon:2});
		return false;
	}
	var position_note = $('#position_note').val();//备注
	$.post("{pigcms{:U('parking_position_add')}",{'position_num':position_num,'garage_id':garage_id,'position_area':position_area,'position_note':position_note},function(data){
                if(data.code == 1){
                    layer.msg(data.msg,{icon: 1},function(){
	                    location.href='{pigcms{:U('parking_management')}';
	                });
                }
                if(data.code == 2){
                    layer.msg(data.msg,{icon: 2});
                }
    },'json');
})


</script>

<include file="Public:footer"/>