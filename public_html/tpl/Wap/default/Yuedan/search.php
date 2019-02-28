
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta http-equiv="Expires" content="-1">
	<meta http-equiv="Cache-Control" content="no-cache">
	<meta http-equiv="Pragma" content="no-cache">
	<meta charset="utf-8">
	<title>搜索</title>
	<script src="{pigcms{$static_path}yuedan/js/jquery-1.9.1.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
	
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/shopBase.css?t=1502871042"/>
	<style>
		body {
		    padding: 0px;
		    margin: 0px auto;
		    font-size: 14px;
		    min-width: 320px;
		    max-width: 640px;
		    background-color: #f0f2f5;
		    color: #333333;
		    position: relative;
		    -webkit-tap-highlight-color: rgba(0,0,0,0);
		}
		/*位置搜索*/
			.searchHeader {
			    top: 0;
			    height: 50px;
			    background:white;
			    /* display: -webkit-box; */
			    position: fixed;
			    width: 100%;
			    z-index: 21;
			}
			.searhBackBtn{
			    position: absolute;
			    width: 50px;
			    height: 100%;
			    top: 0;
			    left: 0;
			}
			.searhBackBtn:after {
			    display: block;
			    content: "";
			    border-top: 1.5px solid #A5A5A5;
			    border-left: 1.5px solid #A5A5A5;
			    width: 11px;
			    height: 11px;
			    -webkit-transform: rotate(315deg);
			    background-color: transparent;
			    position: absolute;
			    top: 19px;
			    left: 19px;
			}
			.searchBtn{
				position: absolute;
			    width: 50px;
			    height: 38px;
			    line-height: 38px;
			    top: 6px;
			    right: 6px;
			    text-align: center;
			    background-color: #B5B5B5;
			    color: white;
			    padding: 0 6px;
			    border-radius: 3px;
			}
			.searchBtn.so{
				background-color: #06c1ae;
			}
			.searchBox{
				background-color: #f4f4f4;
			    height: 38px;
			    margin-left: 50px;
			    margin-right: 74px;
				margin-top:6px;
				position:relative;
			}
			.searchTxt{
			    height: 30px;
			    line-height: 30px;
			    border: none;
			    margin-left: 32px;
			    background: transparent;
				outline:none;
				font-size:14px;
			    padding-top: 5px;
			}
			.searchBox .searchIco{
			    position: absolute;
				left: 10px;
			    top: 12px;
			}
			.searchBox .searchIco:before,.searchBox .searchIco:after {
			    content: '';
			    height: 10px;
			    display: block;
			    position: absolute;
			    top: 0;
			    left: 0;
			}
			.searchBox .searchIco:before {
			    width: 10px;
			    border: 1px #A6A6A6 solid;
			    border-radius: 100%;
			    -webkit-border-radius: 100%;
			    -moz-border-radius: 100%;
			}
			.searchBox .searchIco:after {
			    width: 1px;
			    background: #A6A6A6;
			    transform: rotate(-45deg);
			    -webkit-transform: rotate(-45deg);
			    -moz-transform: rotate(-45deg);
			    -o-transform: rotate(-45deg);
			    -ms-transform: rotate(-45deg);
			    top: 10px;
			    left: 11px;
			    height: 4px;
			}
			.searchBox .delIco{
			    position: absolute;
			    right: 0;
			    top: 0;
			    width: 38px;
			    height: 38px;
				display:none;
			}
			.searchBox .delIco div{
				background-color: #CDCDCD;
				border-radius:100%;
				width:20px;
				height:20px;
				margin-left: 9px;
			    margin-top: 9px;
			}
			.searchBox .delIco div:before, .searchBox .delIco div:after {
				content: '';
			    height: 2px;
			    width: 14px;
			    display: block;
			    background: white;
			    border-radius: 10px;
			    -webkit-border-radius: 10px;
			    -moz-border-radius: 10px;
			    position: absolute;
			    top: 18px;
			    left: 12px;
			    transform: rotate(-45deg);
			    -webkit-transform: rotate(-45deg);
			    -moz-transform: rotate(-45deg);
			    -o-transform: rotate(-45deg);
			    -ms-transform: rotate(-45deg);
			}
			.searchBox .delIco div:after {
			    transform: rotate(45deg);
			    -webkit-transform: rotate(45deg);
			    -moz-transform: rotate(45deg);
			    -o-transform: rotate(45deg);
			    -ms-transform: rotate(45deg);
			}
			.he50{ height: 50px; }
	</style>
	<!--[if lte IE 9]>
	<script src="scripts/html5shiv.min.js"></script>
	<![endif]-->
</head>
<body>
	<div id="serviceSearchHeader" class="searchHeader">
		<div id="serviceSearchBackBtn" class="searhBackBtn"></div>
		<div id="serviceSearchBox" class="searchBox">
			<div class="searchIco"></div>
			<form action="{pigcms{:U('Yuedan/search')}" id="searchForm" method="get">

				<input type="hidden" name="g" value="Wap" />
				<input type="hidden" name="c" value="Yuedan" />
				<input type="hidden" name="a" value="search" />
				<input type="hidden" name="cid" value="{pigcms{$_GET['cid']}">
				<input type="text" id="serviceSearchTxt" name="search" value="{pigcms{$_GET['search']}" class="searchTxt" placeholder="请输入服务标题" autocomplete="off"/>
			</form>
			<div class="delIco" id="serviceSearchDel">
				<div></div>
			</div>
		</div>
		<div id="serviceSearchBtn" class="searchBtn">搜索</div>
	</div>

	<div class="he50"></div>
	<div id="storeList" style=" margin-top: 10px;">
		<dl class="dealcard">
			<if condition="$_GET['search']">
				<if condition="is_array($service_list)">
					<volist name="service_list" id="vo">
						<dd class="page-link">
							<a href="{pigcms{:U('Yuedan/service_detail',array('rid'=>$vo['rid']))}">
								<div class="dealcard-img imgbox"><img src="{pigcms{$vo.listimg}" alt="{pigcms{$vo.title}" width="90" height="60"></div>
								<div class="dealcard-block-right">
									<div class="brand">{pigcms{$vo.title}</div>
									<div class="brand">{pigcms{$vo.price}/{pigcms{$vo.unit}</div>
									<div class="brand">{pigcms{$vo.nickname}</div>
								</div>
							</a>
						</dd>
					</volist>
				<else/>
					<div style="text-align: center; margin-top: 40%; font-size: 20px; color: red;">暂无数据!</div>
				</if>
			</if>
			
		</dl>
	</div>
</body>

</html>

<script>
	$(function(){
		$('#serviceSearchTxt').width($(window).width()-124-32);

		if($("#serviceSearchTxt").val().length > 0){
			$('#serviceSearchDel').show();
			$('#serviceSearchBtn').addClass('so');
		}

		$("#serviceSearchTxt").bind('input', function(e){
			var address = $.trim($(this).val());
			if(address.length > 0){
				$('#serviceSearchDel').show();
				$('#serviceSearchBtn').addClass('so');
			}else{
				$('#serviceSearchDel').hide();
				$('#serviceSearchBtn').removeClass('so');
			}
		});
		$('#serviceSearchDel').click(function(){
			$('#serviceSearchTxt').val('').trigger('input');
		});

		$("#serviceSearchBackBtn").click(function(){
			window.history.back();
		})

		$("#serviceSearchBtn").click(function(){
			var searchVal = $('#serviceSearchTxt').val();
			if(!searchVal){
				layer.open({
					content: '请输入要搜索的服务标题'
					,skin: 'msg'
					,time: 2 
				});
				return false;
			}
			$("#searchForm").submit();
		})
	});
</script>