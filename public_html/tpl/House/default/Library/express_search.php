<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/styles.css">
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.ba-bbq.min.js"></script>
<title>{pigcms{$config.site_name} - 商家中心</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<link rel="stylesheet" href="{pigcms{$static_path}css/bootstrap.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/font-awesome.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/jquery-ui.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/jquery-ui.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/ace-fonts.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/ace.min.css" id="main-ace-style">
<link rel="stylesheet" href="{pigcms{$static_path}css/ace-skins.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/ace-rtl.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/global.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/jquery-ui-timepicker-addon.css">
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui.min.js"></script>
</head>

<body style="background:white;">
<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
						<div class="tab-content">
							<div class="tab-pane active" id="basicinfo">
                                <div class="form-group">
                                    <label>
										<input type="text" value="" id="keyword" name="keyword" size="30" class="col-sm-2" placeholder="请填写快递单号或手机号码">
                                    </label>&nbsp;
                                    <label>
<input class="col-sm-2 Wdate" type="text" readonly style="height:30px;" placeholder="开始时间" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日',vel:'start_time'})" value=""/>
									<input name="start_time" id="start_time" type="hidden" value=""/>
&nbsp;&nbsp;至&nbsp;&nbsp;
									<input class="col-sm-2 Wdate" placeholder="结束时间" type="text" readonly style="height:30px;" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日',startDate:'{pigcms{:date('Y-m-d')}',vel:'end_time'})" value=""/>
                                    <input name="end_time" id="end_time" type="hidden" value=""/>
									</label>
								</div>
                                <div class="form-group">
                                	<button type="submit" class="btn btn-info">
                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                            搜索
                                    </button>
                                </div>
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script type="text/javascript">
$(function(){
	if($("#keyword").val()!='' || $("#start_time").val()!='' || $("#end_time").val()!=''){
		$('.btn-info').click();	
	}
})
$('.btn-info').click(function(){
	var search_url = "__SELF__";
	var keyword = $('#keyword').val();
	var start_time = $('#start_time').val();
	var end_time = $('#end_time').val();
	var html = '';
	$('.table-hover').remove();
	
	$.post(search_url,{'keyword' : keyword,'start_time':start_time,'end_time':end_time},function(data){
		if(!data){
			alert('请填写搜索条件！');
		}else{
			if(data['status']){
				var power = data['power'];
				var data = data.list;
				html += '<table class="table table-striped table-bordered table-hover"> <thead>  <tr><th width="5%">ID</th><th width="10%">快递类型</th><th width="20%">快递单号</th><th width="10%">收件人手机号</th><th width="10%">状态</th><th width="20%">操作</th></tr> </thead> <tbody>  ';
				
				for(var i in data){
					var express_del_url = "{pigcms{:U('express_del')}";
					var express_detail_url = "{pigcms{:U('express_detail')}";
					express_del_url +='&id='+data[i]['id'];
					express_detail_url +='&id='+data[i]['id']+'&flag=1';
					

					html += '<tr class="even"><td><div class="tagDiv">'+data[i]['id']+'</div></td>  <td><div class="tagDiv">'+data[i]['express_name']+'</div></td>  <td><div class="tagDiv">'+data[i]['express_no']+'</div></td> <td><div class="tagDiv">'+data[i]['phone']+'</div></td>  <td><div class="tagDiv">';
					
					if(data[i]['status'] == 0){
						html+='<span class="red">未取件</span>';
					}else if(data[i]['status'] == 1){
						 html+=' <span class="green">已取件（业主）</span>';
					}else{
						html+=' <span class="green">已取件（社区）</span>';
					}
					if (power) {
						html+='</div></td> <td class="button-column"><a href="'+express_detail_url+'" title="详情" class="label label-sm label-info handle_btn" style="width: 60px;">详情</a>&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="if(confirm(\'确认删除该条信息？\')){location.href=\''+express_del_url+'\'}" href="javascript:void(0)" title="删除" class="label label-sm label-info" style="width: 60px;">删除</a> </td></tr>';
					} else {
						html+='</div></td> <td class="button-column"><a href="'+express_detail_url+'" title="详情" class="label label-sm label-info handle_btn" style="width: 60px;">详情</a> </td></tr>';
					}
				}
				 html +='</tbody></table>';
			}else{
				html+='<table class="table table-striped table-bordered table-hover"> <thead>  <tr><th width="5%">ID</th><th width="10%">快递类型</th><th width="20%">快递单号</th><th width="10%">收件人手机号</th><th width="10%">状态</th><th width="20%">操作</th></tr> </thead> <tbody><tr>  <td colspan="6"><div class="tagDiv red" style=" text-align:center" >暂无数据</div></td></tr></tbody></table>';
			}
			$('.tab-content').append(html);
		}
	},'json');
});

</script>
</body>
</html>