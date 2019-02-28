<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('service_category')}">便民服务</a>
			</li>
            <li><a href="{pigcms{:U('service_category',array('cat_id'=>$_GET['cat_id']))}">{pigcms{$detail.cat_name}</a></li>
			<li class="active">便民分类</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
        <div class="page-content-area">
            <div class="row">
                <div class="col-xs-12">
                <button class="btn btn-success" onclick="service_catgory_add()" <if condition="!in_array(169,$house_session['menus'])">disabled="disabled"</if>>添加子级分类</button>
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="20%">子分类名称</th>
                                    <th width="20%">子分类图标</th>
                                    <th width="20%">子分类链接</th>
                                    <th width="5%">排序值</th>
                                    <th width="10%">是否首页显示</th>
                                    <th width="10%">状态</th>
                                    <th class="button-column" width="15%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$list['list']">
                                    <volist name="list['list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.id}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.cat_name}</div></td>
                                           	<if condition='$vo["cat_img"]'>
                                                <php> if (strpos($vo['cat_img'],'tpl/Wap/')===false) { </php>
												<td><div class="tagDiv"><img src="{pigcms{$config.site_url}/upload/service/{pigcms{$vo.cat_img}" width="50" height="50"/></div></td>
                                               <php>} else { </php>
                                                
                                                 <td><div class="tagDiv"><img src="{pigcms{$vo.cat_img}" width="50" height="50"/></div></td>
                                               <php>}</php>
												
												
                                            	
                                            <else/>
                                            	<td><div class="tagDiv red">无图片</div></td>
                                            </if>
                                            <if condition='$vo["cat_url"]'>
                                            	<td><div class="tagDiv"><a href="{pigcms{$vo.cat_url}" target="_blank">访问链接</a></div></td>
                                            <else/>
                                            	<td><div class="tagDiv red">无链接</div></td>
                                            </if>
                                            <td><div class="tagDiv">{pigcms{$vo.sort}</div></td>
                                            
                                            <if condition='$vo["is_index_show"] eq 0'>
                                            	<td><div class="tagDiv red">关闭</div></td>
                                            <else/>
                                            	<td><div class="tagDiv green">开启</div></td>
                                            </if>
                                            <td><div class="tagDiv">
                                           		<if condition='$vo["status"] eq 0'>
                                                	<div class="tagDiv red">关闭</div>
                                                <else />
                                                	<div class="tagDiv green">开启</div>
                                                </if>
                                            </div></td>
                                            
                                            <td class="button-column">
                                            
                                           <a style="width: 60px;" class="label label-sm label-info" title="修改" href="{pigcms{:U('service_category_edit',array('id'=>$vo['id']))}">修改</a>
                                            <if condition="in_array(171,$house_session['menus'])">
                                                <a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onClick="if(confirm('确认删除该条信息？')){location.href='{pigcms{:U('service_category_del',array('id'=>$vo['id']))}'}">删除</a>
                                            </if>
                                           </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="8" >没有任何子分类。</td></tr>
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
$('.handle_btn').live('click',function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'分类详情',
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

function service_catgory_add(){
	var url = "{pigcms{:U('service_category_add')}";
	var cat_id = "{pigcms{$_GET.cat_id}";
	if(cat_id){
		url+='&cat_id='+cat_id;
	}
	window.location.href = url;
}
</script>

<include file="Public:footer"/>