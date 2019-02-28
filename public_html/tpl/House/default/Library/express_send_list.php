<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('User/index')}">功能库</a>
            </li>
            <li class="active">快递代发</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">

        	<if condition='!$has_express_send'>
                <div style="margin-top:10px; cursor:pointer" class="alert alert-danger" onClick="window.open('{pigcms{:U('Index/index')}')">
                    <button data-dismiss="alert" class="close" type="button"><i class="ace-icon fa fa-times"></i></button>
                    还未开启&nbsp;&nbsp;<span style="font-weight:bold">快递代发</span>&nbsp;&nbsp;功能，请先到&nbsp;&nbsp;<span style="font-weight:bold">社区管理 - 基本信息 - 功能库配置&nbsp;&nbsp;</span>开启相应配置。
                </div>
            <else />
            	<div class="form-group" style="border:1px solid #c5d0dc;padding:10px;">
					<form method="post" id="find-form">
						<input value="{pigcms{$send_phone}" name="send_phone" id="send_phone" placeholder="寄件人手机号检索" type="text" style="margin-right:10px; height:35px;"/>

						快递公司：
						<select name="express" id="express" style="margin-right:10px;height:35px;">
							<option value="">全部快递公司</option>
							<volist name="express_tmp" id="vo">
								<option value="{pigcms{$vo.code}" <if condition="$express eq $vo['code']">selected="selected"</if>>{pigcms{$vo.name}</option>
							</volist>
						</select>

						时间筛选：
						<input type="text" name="add_time_start" id="add_time_start" class="input-text" value="{pigcms{$add_time_start}"  style="height:35px" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="开始"/>
						&nbsp;—&nbsp;
						<input type="text" name="add_time_end" id="add_time_end" class="input-text" value="{pigcms{$add_time_end}"  style="height:35px" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="结束"/>&nbsp;&nbsp;&nbsp;&nbsp;

						<input class="btn btn-success" type="submit" id="find_submit" value="查询" />&nbsp;
						<a class="btn btn-success" onclick="location.href='{pigcms{:U('express_send_list')}'">清空</a>
						<!-- <a onclick="location.href='{pigcms{:U('express_send_export',$_POST)}'" class="btn btn-success fr">批量导出</a> -->
					</form>
				</div>
        	</if>

            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover" style="position:relative;">
                            <thead>
                                <tr>
                                	<th width="3%" style="text-align:center"><input type="checkbox" class="checkbox_all" style="wdith:20px; height:20px;"></th>
                                    <th width="3%">ID</th>
                                    <th width="15%">寄件人信息</th>
                                    <th width="15%">收件人信息</th>
                                    <th width="5%">物品重量</th>
                                    <th width="5%">文件类型</th>
                                    <th width="8%">快递公司</th>
                                    <th width="5%">代发费用</th>
                                    <th width="15%">备注</th>
                                    <th width="10%">提交时间</th>
                                    <th width="10%">最后导出时间</th>
                                </tr>
                            </thead>
                            <tbody class="class-checkbox">
                                <if condition="$send_list">
                                    <volist name="send_list" id="vo">
                                        <tr>
                                            <td align="center"><input type="checkbox" name="delCheckbox[]" value="{pigcms{$vo.send_id}" onclick="return Dcheckbox($(this));"></td>
											<td><div class="tagDiv">{pigcms{$vo.send_id}</div></td>
                                            <td>
                                            	<div class="tagDiv">
	                                            	姓名：{pigcms{$vo.send_uname} <br/>
													联系方式：{pigcms{$vo.send_phone}<br/>
													详细地址：{pigcms{$vo.send_city} {pigcms{$vo.send_adress}
                                            	</div>
                                        	</td>
                                        	<td>
                                            	<div class="tagDiv">
	                                            	姓名：{pigcms{$vo.collect_uname} <br/>
													联系方式：{pigcms{$vo.collect_phone}<br/>
													详细地址：{pigcms{$vo.collect_city} {pigcms{$vo.collect_adress}
                                            	</div>
                                        	</td>
                                        	<td><div class="tagDiv">{pigcms{$vo.weight}（Kg）</div></td>
                                        	<td><div class="tagDiv">{pigcms{$goods_type[$vo['goods_type']]}</div></td>
                                        	<td><div class="tagDiv">{pigcms{$express_list[$vo['express']]}</div></td>
											<td><div class="tagDiv">{pigcms{$vo.send_price} (元)</div></td>
                                        	<td><div class="tagDiv">{pigcms{$vo.remarks}</div></td>
                                        	<td><div class="tagDiv">{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}</div></td>
                                        	<td><div class="tagDiv"><if condition="$vo['export_time']">{pigcms{$vo.export_time|date="Y-m-d H:i:s",###}<else/>暂未导出</if></div></td>
                                        </tr>
                                    </volist>
                                    <tr>
                                    	<td colspan="14">
	                                    	<button class=" btn delete_class" <if condition="!in_array(215,$house_session['menus'])">disabled="disabled"</if>>导出选中</button>
                                    	</td>
                                    </tr>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="11" >没有任何数据。</td></tr>
                                </if>
                            </tbody>
                           
                        </table>
                        {pigcms{$pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
	.modal-content{
		width:400px;	
	}
	.modal-body{
		text-align:center;
		padding:20px 15px;
	}
</style>


<div class="modal fade" id="myModal_alert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">提示信息</h4>
			</div>
			<div class="modal-body">
				请先选择要导出的数据！
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary class-tip">确定</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript" language="javascript">

	$(".checkbox_all").on('click',function(){
		
		if($(this).is(':checked')){
			$("input[name='delCheckbox[]']").prop("checked",true);  
		}else{
			$("input[name='delCheckbox[]']").prop("checked",false);  	
		}	
			
	});

	function Dcheckbox(e){
		var n=0;
		var len = $("input[name='delCheckbox[]']").length;
		for(var i=0;i<len;i++){
			if($("input[name='delCheckbox[]']")[i].checked) n++;		
		}
		if(n==len){
			$(".checkbox_all").prop("checked",true); 
		}else{
			$(".checkbox_all").prop("checked",false); 
		}
	}

	$(".class-tip").on('click',function(){
		$('#myModal_alert').modal('hide');	
	});

	$(".delete_class").on('click',function(){
		var len = $("input[name='delCheckbox[]']:checked").length;
		if(len<=0){
			$('#myModal_alert').modal({
				keyboard: false,
				backdrop: 'static'
			})
		}else{
			var length = $("input[name='delCheckbox[]']").length;
			var value="";
			for(var i=0;i<length;i++){
				if($("input[name='delCheckbox[]']")[i].checked) value += "," + $("input[name='delCheckbox[]']")[i].value;	
			}
			value = value.substring(1);
			if(value){
				var village_id = "{pigcms{$village_info.village_id}";
				location.href = "{pigcms{:U('express_send_export')}&send_id="+value;
			}		
		}
	});	

	function sssss(){
		var send_phone = $("#send_phone").val();
		var express = $("#express").val();
		var add_time_start = $("#add_time_start").val();
		var add_time_end = $("#add_time_end").val();
		alert(send_phone);
		alert(express);
		alert(add_time_start);
		alert(add_time_end);
		if(send_phone){

		}
		if(express){
			
		}
		if(add_time_start){
			
		}
		if(add_time_end){
			
		}
		// location.href = "{pigcms{:U('express_send_export')}"+date;
	}
</script>
<include file="Public:footer"/>
