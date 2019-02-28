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
            <li>文章列表</li>
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
                                	<th width="18%">文章标题</th>
                                    <th width="10%">文章图片</th>
                                    <th width="10%">业主信息</th>
                                    <th width="5%">赞数量</th>
                                   	<th width="5%">评论数</th>
                                   	<th width="5%">状态</th>
                                   	<th width="5%">排序</th>
                                   	<th width="8%">更新时间</th>
                                   	<!--th width="8%">过期时间</th-->
                                    <th class="button-column" width="15%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$aBbsAricle">
                                    <volist name="aBbsAricle" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                        	<td><div class="tagDiv">{pigcms{$vo.aricle_id}</div></td>
                                            <td style="word-break:break-all"><div class="tagDiv">{pigcms{$vo.aricle_title}</div></td>
                                            <td><div class="tagDiv text-center"><if condition="$vo['aricle_img']"><img src="{pigcms{$vo.aricle_img}" width="45" height="45" /><else/>无</if></div></td>

                                            <td>
                                                <div class="tagDiv">姓名： {pigcms{$vo.village_user_info.name|default='--'}</div>
                                                <div class="tagDiv">电话： {pigcms{$vo.village_user_info.phone|default='--'}</div>
                                            </td>

                                            <td><div class="tagDiv">{pigcms{$vo.aricle_praise_num}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.aricle_comment_total}</div></td>
                                            <if condition="$vo.aricle_status eq 1">
                                            	<td><div class="tagDiv" style="color:green;">审核通过</div></td>
                                            <elseif condition="$vo.aricle_status eq 3" />
                                            	<td><div class="tagDiv" style="color:red;">审核不通过</div></td>
                                            <elseif condition="$vo.aricle_status eq 2" />
                                            	<td><div class="tagDiv" style="color:#0044BB;">待审核</div></td>
                                            <elseif condition="$vo.aricle_status eq 4" />
                                            	<td><div class="tagDiv" style="color:Gray;">用户删除</div></td>
                                            </if>
                                            <td><div class="tagDiv">{pigcms{$vo.aricle_sort}</div></td>
                                            <td><div class="shopNameDiv">{pigcms{$vo.update_time|date='Y-m-d',###}</div></td>

                                            <td class="button-column">
                                            	<a style="width:80px;height:26px;line-height:20px;" class="label label-sm label-info" title="更改状态" href="{pigcms{:U('aricle_status_show',array('aricle_id'=>$vo['aricle_id']))}">更改状态</a>
                                                <a style="width:80px;height:26px;line-height:20px;" class="label label-sm label-info handle_btn" title="查看详情" href="{pigcms{:U('aricle_list_details',array('aricle_id'=>$vo['aricle_id'],'cat_id'=>$vo['cat_id']))}">查看详情</a>

                                                <if condition="in_array(134,$house_session['menus'])">
                                                <a style="width:80px;height:26px;line-height:20px;" class="label label-sm label-info" title="查看评论" href="{pigcms{:U('comment_list',array('aricle_id'=>$vo['aricle_id'],'cat_id'=>$vo['cat_id']))}">查看评论</a>
                                                </if>

                                                <if condition="in_array(133,$house_session['menus'])">
												<a style="width:80px;height:26px;line-height:20px;" class="label label-sm label-info" title="删除文章" href="javascript:void(0)" onclick="if(confirm('确认删除该文章及相关评论？')){location.href='{pigcms{:U('comment_delete',array('aricle_id'=>$vo['aricle_id']))}}'}">删除文章</a>
                                                </if>
                                                <if condition="in_array(137,$house_session['menus'])">
    												<if condition='$vo["type"] eq 1'>
    												<a style="width:80px;height:26px;line-height:20px;" class="label label-sm label-info" title="查看详情" href="{pigcms{:U('activity_apply_list',array('aricle_id'=>$vo['aricle_id']))}">查看报名</a>
    												</if>
                                                </if>
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
