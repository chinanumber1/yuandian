<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('visitor_list')}">功能库</a>
			</li>
			<li class="active">访客登记</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
        <div class="page-content-area">
        	
            <div class="row">
                <div class="col-xs-12">
                
                <if condition='!$has_visitor'>
                    <div style="margin-top:10px; cursor:pointer" class="alert alert-danger" onClick="window.open('{pigcms{:U('Index/index')}')">
                        <button data-dismiss="alert" class="close" type="button"><i class="ace-icon fa fa-times"></i></button>
                        还未开启&nbsp;&nbsp;<span style="font-weight:bold">访客登记</span>&nbsp;&nbsp;功能，请先到&nbsp;&nbsp;<span style="font-weight:bold">基本信息管理 - 基本信息设置 - 功能库配置&nbsp;&nbsp;</span>开启相应配置。
                    </div>
                <else />
                	<button class="btn btn-success" onclick="visitor_add()" <if condition="!in_array(243,$house_session['menus'])">disabled="disabled"</if>>添加访客</button>&nbsp;&nbsp;
                	<button id="search" class="btn btn-success">搜索</button>&nbsp;&nbsp;
					<button class="btn btn-success" onclick="location.reload()">刷新</button>
            	</if>
                
                
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="10%">访客姓名</th>
                                    <th width="10%">访客手机号码</th>
                                    <th width="10">业主姓名</th>
                                    <th width="10%">业主手机号</th>
                                    <th width="20%">业主住址</th>
                                    <th width="5%">访客类型</th>
                               
                                    <th width="15%">状态</th>
                                    <th class="button-column" width="15%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$list['list']">
                                    <volist name="list['list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.id}</div></td>
                                            <td><if condition='$vo["visitor_name"]'><div class="tagDiv">{pigcms{$vo.visitor_name}</div><else/><div class="tagDiv red">未填写</div></if></td>
                                            <td><div class="tagDiv">{pigcms{$vo.visitor_phone}</div></td>
                                           	<td><div class="tagDiv">{pigcms{$vo.owner_name}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.owner_phone}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.owner_address}</div></td>
                                            <td><div class="tagDiv">
                                            {pigcms{$visitor_type[$vo['visitor_type']]}
                                            </div></td>
                                            
                                            <td><div class="tagDiv">
                                            <if condition='$vo["status"] eq 0'>
                                            	<span class="red">未放行</span>
												<if condition="in_array(217,$house_session['menus'])">
                                                <a href="javascript:void(0)" class="chk_visitor_info" data-id="{pigcms{$vo['id']}">确认放行</a>
                                            	</if>
                                            <elseif condition='$vo["status"] eq 1' />
                                            	<span class="green">已放行（业主）</span>
                                            <else/>
                                            	<span class="green">已放行（社区）</span>
                                            </if>
                                            </div></td>
                                            
                                            <td class="button-column">
                                           		<a style="width: 60px;" class="label label-sm label-info handle_btn" title="详情" href="{pigcms{:U('visitor_detail',array('id'=>$vo['id']))}">详情</a>
												<if condition="in_array(218,$house_session['menus'])">
                                                <a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onClick="if(confirm('确认删除该条信息？')){location.href='{pigcms{:U('visitor_del',array('id'=>$vo['id']))}'}">删除</a>
                                            	</if>
                                           </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="9" >没有任何访客记录。</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$list.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
function visitor_add(){
	window.location.href = "{pigcms{:U('visitor_add')}";
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