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
            <li><a href="{pigcms{:U('Bbs/aricle_list',array('cat_id'=>$cat_id))}">文章列表</a></li>
            <li>评论列表</li>
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
                                	<th width="5%">No.</th>
                                	<th width="10%">评论父ID</th>
                                    <th width="10%">用户ID</th>
                                   	<th width="40%">评论内容</th>
                                   	<th width="10%">状态</th>
                                   	<th width="10%">评论时间</th>
                                    <th class="button-column" width="15%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$aBbsComment">
                                    <volist name="aBbsComment" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                        	<td><div class="tagDiv">{pigcms{$vo.comment_id}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.comment_fid}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.uid}</div></td>
                                            <td style="word-break:break-all"><div class="tagDiv">{pigcms{$vo.comment_content}</div></td>
                                            <if condition="$vo.comment_status eq 1">
                                            	<td><div class="tagDiv" style="color:green;">审核通过</div></td>
                                            <elseif condition="$vo.comment_status eq 3" />
                                            	<td><div class="tagDiv" style="color:red;">审核不通过</div></td>
                                            <elseif condition="$vo.comment_status eq 2" />
                                            	<td><div class="tagDiv" style="color:#0044BB;">待审核</div></td>
                                            <elseif condition="$vo.comment_status eq 4" />
                                            	<td><div class="tagDiv" style="color:Gray;">用户删除</div></td>
                                            </if>
                                            <td><div class="shopNameDiv">{pigcms{$vo.create_time|date='Y-m-d',###}</div></td>
                                            <td class="button-column">
                                            	<a style="width:80px;height:26px;line-height:20px;" class="label label-sm label-info" title="更改状态" href="{pigcms{:U('comment_status_show',array('comment_id'=>$vo['comment_id'],'aricle_id'=>$vo['aricle_id'],'cat_id'=>$cat_id))}">更改状态</a>
                                            </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="11" >暂无数据。</td></tr>
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
