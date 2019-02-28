<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('owner_arrival')}">功能库</a>
			</li>
			<li class="active">在线付款</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
        <div class="page-content-area">
        	
            <div class="row">
                <div class="col-xs-12">
                <button class="btn btn-success" onclick="owner_arrival_add()">创建订单</button>
				 <span class="red">&nbsp;&nbsp;*目前支持物业缴费，水费，电费，燃气费，停车费,自定义缴费。</span>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
function owner_arrival_add(){
	window.location.href = "{pigcms{:U('owner_arrival_add')}";
}


$('#search').live('click',function(){
	var search_url = "{pigcms{:U('visitor_search')}"
			art.dialog.open(search_url,{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'搜索访客',
				padding: 0,
				width: 720,
				height: 400,
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
		
		
$('.handle_btn').live('click',function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'访客详情',
				padding: 0,
				width: 720,
				height: 520,
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
		
		
$('.chk_visitor_info').click(function(){
	var chk_visitor_info_url ="{pigcms{:U('chk_visitor_info')}";
	var id = $(this).data('id');
	var status = 2;
	$.post(chk_visitor_info_url,{'id':id,'status': status},function(data){
		if(data['status']){
			alert(data['msg']);
			location.reload();
		}else{
			alert(data['msg']);
		}
	},'json')
});
</script>

<include file="Public:footer"/>