<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('Unit/parking_management')}">车位管理</a>
            </li>
            <li class="active">车位信息修改</li>
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
										<volist name="garage_list" id="vol">
											<option value='{pigcms{$vol.garage_id}' <if condition="$vol[garage_id] eq $position_list[garage_id]">selected</if>>{pigcms{$vol.garage_num}-{pigcms{$vol.garage_position}</option>
										</volist>
									</select>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="position_num">车位号</label></label>
									<input class="col-sm-2" size="20" name="position_num" id="position_num" type="text"  value="{pigcms{$position_list.position_num}" />
								</div>
								
						<!-- 		<div class="form-group">
									<label class="col-sm-1"><label for="position_type">车位类型</label></label>
									<select name="position_type" id="position_type">
										<option value='1' <if condition="$position_list.position_type eq '1'">selected</if>>产权车位</option>
										<option value='2' <if condition="$position_list.position_type eq '2'">selected</if>>租赁车位</option>
										<option value='3' <if condition="$position_list.position_type eq '3'">selected</if>>临停车位</option>
									</select>
								</div> -->

								<div class="form-group">
									<label class="col-sm-1"><label for="position_area">车位面积</label></label>
									<input class="col-sm-2" size="20" name="position_area" id="position_area" type="text"  value="{pigcms{$position_list.position_area}" />
									<label><span class="green">单位：平方米</span></label>
								</div>
								
								<div class="form-group" >
									<label class="col-sm-1"><label>备注</label></label>
									<label><textarea name="position_note" id="position_note" maxlength="255" style="width:286px;height:90px;resize:none" placeholder="最多输入255个字">{pigcms{$position_list.position_note}</textarea></label>
								</div>

								<div class="form-group">
									<label class="col-sm-1"><label for="position_status">车位状态</label></label>
										<if condition="$position_list.position_status eq '1'"><label>未使用</label></if>
										<if condition="$position_list.position_status eq '2'"><label>已使用</label></if>
								</div>

								<div class="form-group">
									<label class="col-sm-1" for="park_flag">收费项</label>
									<if condition="in_array(261,$house_session['menus'])">
									<a class="bind_info btn label-info" href="{pigcms{:U('payment_add',array('position_id'=>$position_list['position_id']))}">添加收费项</a>
									<else/>
									<button class="btn" disabled="disabled">添加收费项</button>
									</if>
									<div class="grid-view" style="width:80%;margin-left: 8%">
										<table class="table table-striped table-bordered table-hover">
										    <thead>
										        <tr>
										            <th>收费项目</th>
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
											                <td>{pigcms{$vo.payment_name}</td>
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
															<if condition="in_array(262,$house_session['menus'])">
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
								<script>
									function payment_del(bind_id) {
										var payment_del_url = "{pigcms{:U('User/payment_del')}";
										var position_id = "{pigcms{$_GET['position_id']}";
										layer.confirm('您确定要删除这条收费项吗？', {
											btn: ['确定','取消'] //按钮
										}, function(){
											$.post(payment_del_url,{bind_id:bind_id,position_id:position_id},function(data){
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
							</div>
						</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info submit_info" type="button" <if condition="!in_array(47,$house_session['menus'])">disabled="disabled"</if>>
										<i class="ace-icon fa fa-check bigger-110"></i>
										修改
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
	var garage_id = $('#garage_id').val(); //区域ID
	if(!garage_id){
		layer.msg('请选择位置!',{icon:2});
		return false;
	}	
	var position_num = $('#position_num').val();
	if(!position_num){
		layer.msg('车位号不能为空!',{icon:2});
		return false;
	}

	var position_area = $('#position_area').val();//区域面积
	if(!position_area){
		layer.msg('车位面积不能为空!',{icon:2});
		return false;
	}
	// var position_status = $('#position_status').val();//车位状态
	// if(!position_status){
	// 	layer.msg('车位状态不能为空!',{icon:2});
	// 	return false;
	// }

	// var position_type = $('#position_type').val();//车位状态
	// if(!position_type){
	// 	layer.msg('车位类型不能为空!',{icon:2});
	// 	return false;
	// }
	var position_note = $('#position_note').val();//车主备注
	$.post("{pigcms{:U('parking_edit')}",{'position_id':{pigcms{$position_list.position_id},'position_num':position_num,'garage_id':garage_id,'position_area':position_area,'position_note':position_note},function(data){
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