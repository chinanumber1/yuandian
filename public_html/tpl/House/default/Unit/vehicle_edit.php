<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('Unit/vehicle_management')}">车辆管理</a>
            </li>
            <li class="active">添加车辆</li>
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
								<!-- <div class="form-group">
									<label class="col-sm-1"><label for="garage_num">所属小区</label></label>
									<input class="col-sm-2" size="20" name="garage_num" id="garage_num" type="text"  value="" />
									<label><span class="red">*必填</span></label>
								</div> -->
                                
								<div class="form-group">
									<label class="col-sm-1"><label for="car_number">车牌号码</label></label>
									<select name="province" id="province" class="fl">
										<option value="">--请选择省份--</option>
										<volist name="city_arr" id="vo">
										<option value="{pigcms{$vo}" <if condition="$vo eq $info_list['province']">selected</if> >{pigcms{$vo}</option>
										</volist>
									</select>
									<input class="col-sm-2" size="20" name="car_number" id="car_number" type="text"  value="{pigcms{$info_list.car_number}" />
									<label><span class="red">*必填</span></label>
								</div>

								<!-- <div class="form-group">
									<label class="col-sm-1"><label for="position_id">车位号</label></label>
									<select name="position_id" id="car_position_id">
										<option value="0">--请选择--</option>
										<volist name="data_list" id="vo">
										<option value="{pigcms{$vo.position_id}" <if condition="$vo[position_id] eq $info_list[car_position_id]">selected</if>>{pigcms{$vo.position_num}</option>
										</volist>
									</select>
								</div> -->
								
								<div class="form-group">
									<label class="col-sm-1"><label for="car_user_name">车主姓名</label></label>
									<input class="col-sm-2" size="20" name="car_user_name" id="car_user_name" type="text"  value="{pigcms{$info_list.car_user_name}" />
									<label><span class="red">*必填</span></label>
								</div>

								<div class="form-group">
									<label class="col-sm-1"><label for="car_user_phone">车主手机号</label></label>
									<input class="col-sm-2" size="20" name="car_user_phone" id="car_user_phone" type="text"  value="{pigcms{$info_list.car_user_phone}" />
									<label><span class="red">*必填</span></label>
								</div>
								<div class="form-group" style="position: relative;">
									<label class="col-sm-1"><label for="position_num">车位号</label></label>
									<div class="col-sm-2" style="padding:0px ;position:relative">
										<input class="col-sm-2" size="20" name="position_num" id="position_num" type="text"  value="{pigcms{$position_info.position_num}" autocomplete="off" style="width:100%" placeholder="输入车位号搜索"/>
										<input id="car_position_id" type="hidden" value="{pigcms{$info_list.position_id}" />
										<div id="searchBox" style="display: none;border:1px solid #F59942;position:absolute;left:0px;width:100%;max-height:300px;overflow-y:auto">
                                		</div>
										<div id="dropdown-menu" class="dropdown-menus " style="display:none">
										</div>
									</div>
									<label>选填</label>
								</div>

								<div class="form-group">
									<label class="col-sm-1"><label for="car_stop_num">停车卡号</label></label>
									<input class="col-sm-2" size="20" name="car_stop_num" id="car_stop_num" type="text"  value="{pigcms{$info_list.car_stop_num}" />
									<label>选填</label>
								</div>

								<div class="form-group">
									<label class="col-sm-1"><label for="car_displacement">车辆排量</label></label>
									<input class="col-sm-2" size="20" name="car_displacement" id="car_displacement" type="text"  value="{pigcms{$info_list.car_displacement}" />
									<label><span class="green">升</span></label>
								</div>
							</div>
						</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info submit_info" type="button" <if condition="!in_array(57,$house_session['menus'])">disabled="disabled"</if>>
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

<style>
	#dropdown-menu{
		font-family: Monospaced Number,Chinese Quote,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,PingFang SC,Hiragino Sans GB,Microsoft YaHei,Helvetica Neue,Helvetica,Arial,sans-serif;
		line-height: 1.5;
		color: rgba(0,0,0,.65);
		margin: 0;
		padding: 0;
		list-style: none;
		-webkit-box-shadow: 0 2px 8px rgba(0,0,0,.15) !important;
		box-shadow: 0 2px 8px rgba(0,0,0,.15) !important;
		border-radius: 4px;
		-webkit-box-sizing: border-box;
		box-sizing: border-box;
		outline: none;
		font-size: 14px;
		min-width: auto;right:0px;left:0px;
		top:33px;
		position:absolute;
		z-index: 10;
		display: block;
		background: #fff;
	}
	#dropdown-menu .ant-select-dropdown-menu-item-active {
		background-color: #e6f7ff;
	}
	#dropdown-menu .ant-select-dropdown-menu-item {
		position: relative;
		display: block;
		padding: 5px 12px;
		line-height: 22px;
		font-weight: 400;
		color: rgba(0,0,0,.65);
		white-space: nowrap;
		cursor: pointer;
		overflow: hidden;
		text-overflow: ellipsis;
		-webkit-transition: background .3s ease;
		transition: background .3s ease;
	}
	#dropdown-menu .ant-select-dropdown-menu-item{line-height:35px;list-style: none;border:none !important;}
	#dropdown-menu .ant-select-dropdown-menu-item:hover {
		background-color: #e6f7ff
	}

	#dropdown-menu .ant-select-dropdown-menu-item:first-child {
		border-radius: 4px 4px 0 0;
	}

	#dropdown-menu .ant-select-dropdown-menu-item:last-child {
		border-radius: 0 0 4px 4px
	}

</style>
<script type="text/javascript">
$('.submit_info').click(function(){
	var car_number = $('#car_number').val();
	if(!car_number){
		layer.msg('车牌号不能为空!',{icon:2});
		return false;
	}
	
	var car_position_id = $('#car_position_id').val();//车位id
	var car_stop_num = $('#car_stop_num').val();//停车卡号
	var car_user_name = $('#car_user_name').val();//车主姓名
	var car_user_phone = $('#car_user_phone').val();//车主电话
	var car_displacement = $('#car_displacement').val();//车辆排量
	var province = $('#province').val();//省份

	if(!car_user_name){
		layer.msg('请输入车主姓名！!',{icon:2});
		return false;
	}
	if(!car_user_phone){
		layer.msg('请输入车主手机号！!',{icon:2});
		return false;
	}
	
	
	$.post("{pigcms{:U('vehicle_edit')}",{'car_id':{pigcms{$info_list.car_id},'car_number':car_number,'car_position_id':car_position_id,'car_stop_num':car_stop_num,'car_user_name':car_user_name,'car_user_phone':car_user_phone,'car_displacement':car_displacement,province:province},function(data){
                if(data.code == 1){
                    layer.msg(data.msg,{icon: 1},function(){
	                    // location.reload();
	                    location.href='{pigcms{:U('Unit/vehicle_management')}';
	                });
                }
                if(data.code == 2){
                    layer.msg(data.msg,{icon: 2});
                }
    },'json');
})

	$('#position_num').keyup(function() {
		var position_num= $.trim(this.value);
		get_position(position_num);
	}).blur(function(){
		 $("#searchBox").html("").hide(); //输入框失去焦点的时候就隐藏搜索框
	});

	$('#position_num').focus(function() {
		var position_num= $.trim(this.value);
		get_position(position_num);
	})

	$(document).on("click",'.ant-select-dropdown-menu-item',function(e){
        position_id = $(this).val();
        $('#car_position_id').val(position_id);
        var position_num = $(this).attr('position_num');
        $('#position_num').val(position_num);
        
        $("#dropdown-menu").html("").hide(); //输入框失去焦点的时候就隐藏搜索框

    })
    $(document).bind("click",function(e){
        var target=$(e.target);
        if(target.closest("#dropdown-menu").length==0){
            $("#dropdown-menu").html("").hide();
        }
    })

function get_position(position_num){
	if(position_num!=""){//检测键盘输入的内容是否为空，为空就不发出请求
        $.post("{pigcms{:U('ajax_get_parking_list')}",{'position_num':position_num},function(data){

            if (data && data!=null && data.length> 0) {//检测返回的结果是否为空
                var str = '<div><ul class="findtype-menu" style="    overflow-y: auto;max-height: 400px;">';
                for(var i in data){ 
                    str += '<li class="ant-select-dropdown-menu-item " value="'+data[i]["position_id"]+'" position_num="'+data[i]["position_num"]+'">'+data[i]["position_num"]+' - '+data[i]["garage_num"]+'</li>';
                }; 

                str+="</ul></div>";  
				console.log(str);
                $("#dropdown-menu").html(str).show();//将搜索到的结果展示出来
            } else {
                $("#dropdown-menu").html("").hide();
            }  
        },'json');
    }else{
       $("#dropdown-menu").html("").hide();  //没有查询结果就隐藏搜索框
    }
}
</script>

<include file="Public:footer"/>