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
									<select id="visitor_type" name="visitor_type">
                                        <volist name='visitor_type' id='vo'>
                                        	<option value="{pigcms{$key}">{pigcms{$vo}</option>
                                        </volist>
                                        <option value="255">其他（须备注）</option>
                                    </select>
								</div>
                            
                                <div class="form-group">
                                    <label>
										<input type="text" value="" id="visitor_keyword" name="visitor_keyword" size="40" class="col-sm-2" placeholder="请填写访客姓名或访客手机号">
                                    </label><br>
                                    <label>
										<input type="text" value="" id="owner_keyword" name="owner_keyword" size="40" class="col-sm-2" placeholder="请填写业主姓名或业主手机号">
                                    </label>
                                    <label>
										<input class="col-sm-2 Wdate" type="text" readonly style="height:30px;" placeholder="开始时间" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日',vel:'start_time'})" value=""/>
										<input name="start_time" id="start_time" type="hidden" value=""/>
										&nbsp;&nbsp;至&nbsp;&nbsp;
										<input class="col-sm-2 Wdate" placeholder="结束时间" type="text" readonly style="height:30px;" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日',startDate:'{pigcms{:date('Y-m-d',time())}',vel:'end_time'})" value=""/>
	                                    <input name="end_time" id="end_time" type="hidden" value=""/>
									</label>
								</div>
                                <div class="form-group">
                                	<button type="submit" class="btn btn-info">
                                        <!-- <i class="ace-icon fa fa-check bigger-110"></i> -->
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
$('.btn-info').click(function(){
	var search_url = "__SELF__";
	var visitor_keyword = $('#visitor_keyword').val();
	var owner_keyword = $('#owner_keyword').val();
	var start_time = $('#start_time').val();
	var end_time = $('#end_time').val();
	var visitor_type = $('#visitor_type').val();
	var html = '';
	$('.table-hover').remove();
	
	$.post(search_url,{'visitor_keyword' : visitor_keyword,'owner_keyword':owner_keyword,'start_time':start_time,'end_time':end_time,'visitor_type':visitor_type},function(data){
		if(!data){
			alert('请输入查询条件！');
		}else{
			if(data['status']){
				var power = data.power;
				var data = data.list;
				html += '<table class="table table-striped table-bordered table-hover"> <thead>  <tr><th width="15%">访客姓名</th><th width="20%">访客手机号码</th><th width="15%">业主姓名</th><th width="20%">业主手机号</th><th width="30%" class="button-column">操作</th>  </tr> </thead> <tbody> ';

				for(var i in data){
					var detail_url = "{pigcms{:U('visitor_detail')}";
					var visitor_del_url = "{pigcms{:U('visitor_del')}";
					detail_url+='&id='+data[i]['id']+'&flag=1';
					visitor_del_url +='&id='+data[i]['id'];
					html += '<tr class="even"><td>';
					if(data[i]['visitor_name']){
						html+='<div class="tagDiv">'+data[i]['visitor_name']+'</div>';
					}else{
						html+='<div class="tagDiv red">未填写</div>';
					}
					
					html+='</td>  <td><div class="tagDiv">'+data[i]['visitor_phone']+'</div></td> 	<td><div class="tagDiv">'+data[i]["owner_name"]+'</div></td>  <td><div class="tagDiv">'+data[i]['owner_phone']+'</div></td>';

					if (power) {
						html+='<td class="button-column"> <a href="'+detail_url+'" title="详情" class="label label-sm label-info handle_btn" style="width: 60px;">详情</a>&nbsp;&nbsp;<a onclick="if(confirm(\'确认删除该条信息？\')){location.href=\''+visitor_del_url+'\'}" href="javascript:void(0)" title="删除" class="label label-sm label-info" style="width: 60px;">删除</a></td> </tr>';
					}else{
						html+='<td class="button-column"> <a href="'+detail_url+'" title="详情" class="label label-sm label-info handle_btn" style="width: 60px;">详情</a></td> </tr>';
					}
					
					
				}
				 html +='</tbody></table>';
			}else{
				html+='<table class="table table-striped table-bordered table-hover"> <thead>  <tr><th width="15%">访客姓名</th><th width="20%">访客手机号码</th><th width="15%">业主姓名</th><th width="20%">业主手机号</th>><th width="30%" class="button-column">操作</th>  </tr> </thead> <tbody> <tr class="even"><td colspan="5"><div class="tagDiv" style="color:red; text-align:center">暂无信息</div></td> </tr></tbody></table>';
			}
			$('.tab-content').append(html);
		}
	},'json');
});

</script>
</body>
</html>