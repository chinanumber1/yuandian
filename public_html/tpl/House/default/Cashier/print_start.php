<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/styles.css">
	<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/jquery.ba-bbq.min.js"></script>
	<if condition="$config['site_favicon']">
		<link rel="shortcut icon" href="{pigcms{$config.site_favicon}"/>
	</if>
	<title>{pigcms{$config.site_name} - 社区中心</title>
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
	<script type="text/javascript" src="{pigcms{$static_path}js/jquery.ba-bbq.min.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/ace-extra.min.js"></script>


	<script type="text/javascript" src="{pigcms{$static_path}js/bootstrap.min.js"></script>

	<!-- page specific plugin scripts -->
	<script type="text/javascript" src="{pigcms{$static_path}js/bootbox.min.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui.custom.min.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/jquery.ui.touch-punch.min.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/jquery.easypiechart.min.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/jquery.sparkline.min.js"></script>

	<!-- ace scripts -->
	<script type="text/javascript" src="{pigcms{$static_path}js/ace-elements.min.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/ace.min.js"></script>
	<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/jquery.yiigridview.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui-i18n.min.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui-timepicker-addon.min.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/echarts.min.js"></script>
	<link rel="stylesheet" href="{pigcms{$static_path}layer/css/layui.css"  media="all">
	<script src="{pigcms{$static_path}layer/layer.js"></script>
	<style type="text/css">
		.fl{ float:left;}
		.fr{ float:right;}
		body{
			background-color: #fff;
		}

		.table{border:1px solid #303030;border-collapse: collapse}
		.table tr td{border:1px solid #303030;}
		.table>tbody>tr>td{border:1px solid #303030;}

	</style>
	<script type="text/javascript">
        try{ace.settings.check('navbar' , 'fixed')}catch(e){}
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
        try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
        try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
	</script>

</head>

<body class="no-skin">

<div class="main-content">
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
				.flex{    flex-wrap: nowrap;display:flex;align-items: center;display: -webkit-box;display: -webkit-flex;-webkit-box-align: center;-webkit-align-items: baseline;position:relative;align-items: baseline;}
				.flex-column{flex-direction:column !important;}
				.flex-item{    align-items: baseline !important;}
				.flex-one{webkit-box-flex: 1;-webkit-flex: 1;flex: 1;min-width: 0}
			</style>
			<!--begin-->
			<div id="print_table" class="tab-pane active " style="width:750px;margin:auto">
				<div id="div_1" style="margin-bottom:10px">
					<if condition="$print_template.custom">
						<volist name="print_template['custom']" id="vo">
							<if condition="$vo.type eq '1'">
								<if condition="$vo.title eq '标题'">
									<div id="right_{pigcms{$vo.id}" style="width: 100%;float: left;text-align: center;font-size: 23px;margin-bottom: 8px;">{pigcms{$print_template.title}</div>
									<else/>
									<div id="right_{pigcms{$vo.id}"  style="width: 30%;margin-bottom: 2px;float:left">
										{pigcms{$vo.title}：
										<if condition="$cashier_info[$vo['field_name']]">
											{pigcms{$cashier_info[$vo['field_name']]}
											<else/>
											&#45;&#45;
										</if>
									</div>
								</if>
							</if>
						</volist>
					</if>
					<div style="clear:both"></div>
				</div>
				<div id="div_2">
					<table class="table" width="80%" border="1" cellspacing="0" cellpadding="0">
						<tbody id="head">
						<tr>
							<volist name="print_template['custom']" id="vo">
								<if condition="$vo.type eq '2'">
									<td id="right_{pigcms{$vo.id}" style="text-align: center;">{pigcms{$vo.title}</td>
								</if>
							</volist>
						</tr>
						<if condition="$cashier_info.order_list">
							<volist name="cashier_info.order_list" id="order">
								<tr>
									<volist name="print_template['custom']" id="vo">
										<if condition="$vo.type eq '2'">
											<td style="text-align: center;">
												<if condition="$order[$vo['field_name']]">
													{pigcms{$order[$vo['field_name']]}
													<else/>
													--
												</if>
											</td>
										</if>
									</volist>
								</tr>
							</volist>
						</if>

						</tbody>
					</table>
				</div>
				<div id="div_3">
					<if condition="$print_template.custom">
						<volist name="print_template['custom']" id="vo">
						<if condition="$vo.type eq '3'">
							<if condition="$vo.title eq '说明'">
								<div id="right_{pigcms{$vo.id}" style="width: 100%;float: left;">{pigcms{$vo.title}：{pigcms{$cashier_info[$vo['field_name']]}</div>
							<elseif condition="$vo.title eq '收款备注' || $vo.title eq '合计' " />
								<div id="right_{pigcms{$vo.id}" style="width: 100%;float: left;">
								{pigcms{$vo.title}：
								<if condition="$cashier_info[$vo['field_name']]">
										{pigcms{$cashier_info[$vo['field_name']]}
										<else/>
										--
									</if>
								</div>
							<else/>
								<div id="right_{pigcms{$vo.id}" style="width: 30%;float: left;">
									{pigcms{$vo.title}：
									<if condition="$cashier_info[$vo['field_name']]">
										{pigcms{$cashier_info[$vo['field_name']]}
										<else/>
										--
									</if>
								</div>
							</if>
						</if>
						</volist>
					</if>
				</div>
				<div style="clear:both"></div>
			</div>
			<!--end-->


			<div style="text-align:center;margin-top:55px">
				<a href="javascript:void(0);" class="btn btn-success" onclick="PrintPage()" style="width: 80px">打印</a>
			</div>
		</div>
	</div>
</div>
</body>
<script type="text/javascript">
    /*
    function showPrint() {
        if (window.PrePrint != null) window.PrePrint();
        window.print();
    }
    showPrint();
    */

    var hkey_root,hkey_path,hkey_key
    hkey_root="HKEY_CURRENT_USER"
    hkey_path="\\Software\\Microsoft\\Internet Explorer\\PageSetup\\"

    // 设置页眉页脚为空
    function PageSetup_Null()
    {
        try{
            var RegWsh = new ActiveXObject("WScript.Shell") ;
            hkey_key="header" ;
            RegWsh.RegWrite(hkey_root+hkey_path+hkey_key,"") ;
            hkey_key="footer" ;
            RegWsh.RegWrite(hkey_root+hkey_path+hkey_key,"") ;
        }
        catch(e){}
    }

    // 设置页眉页脚为默认值
    function PageSetup_Default()
    {
        try{
            var RegWsh = new ActiveXObject("WScript.Shell") ;
            hkey_key="header" ;
            RegWsh.RegWrite(hkey_root+hkey_path+hkey_key,"&w&b页码，&p/&P") ;
            hkey_key="footer" ;
            RegWsh.RegWrite(hkey_root+hkey_path+hkey_key,"&u&b&d") ;
        }
        catch(e){}
    }

    // 打印
    function PrintPage()
    {
        PageSetup_Null() ;
        if (window.PrePrint != null) window.PrePrint();
        try{
            print.portrait   =  false    ;//横向打印 
        }catch(e){
            // alert("不支持此方法");
        }
        var bdhtml=window.document.body.innerHTML;//获取当前页的html代码
        // var sprnstr="<!--begin-->";//设置打印开始区域    
        // var eprnstr="<!--end-->";//设置打印结束区域    
        // var prnhtml=bdhtml.substring(bdhtml.indexOf(sprnstr)); //从开始代码向后取html    
        // var prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr));//从结束代码向前取html    
        var prnhtml=$('#print_table').html();//从结束代码向前取html    
        window.document.body.innerHTML=prnhtml;
        window.print();
        // setTimeout("window.close();", 0)
       	window.document.body.innerHTML=bdhtml;
        // PageSetup_Default() ;
    }
	setTimeout("PrintPage();", 10)
    

</script>
</html>