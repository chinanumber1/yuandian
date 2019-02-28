<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-file-excel-o"></i>
                <a href="{pigcms{:U('Bbs/index')}">社区论坛</a>
            </li>
            <li><a href="{pigcms{:U('Bbs/index')}">分类管理</a></li>
            <li>报名管理</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                	<th width="10%">ID</th>
                                	<th width="30%">用户名</th>
                                    <th width="20%">手机号</th>
                                    <th width="10%">报名时间</th>
                                   	<th width="10%">状态</th>
                                    <th class="button-column" width="15%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$result['list']">
                                    <volist name="result['list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                        	<td><div class="tagDiv">{pigcms{$vo.id}</div></td>
											<td><div class="tagDiv">{pigcms{$user_list[$vo['uid']]['nickname']}</div></td>
											<td><div class="tagDiv">{pigcms{$user_list[$vo['uid']]['phone']}</div></td>
											<td><div class="tagDiv">{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</div></td>
											<td><div class="tagDiv">已报名</div></td>
											<td><div class="tagDiv">
                                                <if condition="in_array(138,$house_session['menus'])">
												<a style="width:80px;height:26px;line-height:20px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onclick="if(confirm('确认删除？')){location.href='{pigcms{:U('activity_apply_delete',array('id'=>$vo['id']))}}'}">删除</a>
                                                </if>
												
												</div></td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="11" >暂无数据。</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$result['pagebar']}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
	$('.handle_btn').live('click',function(){
		art.dialog.open($(this).attr('href'),{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('iframe_handle',iframe);
			},
			id: 'handle',
			title:'文章详情',
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
</script>
<include file="Public:footer"/>
