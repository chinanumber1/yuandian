<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-gear gear-icon"></i>
                <a href="{pigcms{:U('Index/worker')}">工作人员管理</a>
            </li>
            <li class="active">工作人员列表</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <if condition="in_array(5,$house_session['menus'])">
        	<a class="btn btn-success" href="{pigcms{:U('Index/worker_add')}">添加工作人员</a>
            <else/>
            <button class="btn btn-success disabled" disabled="disabled">添加工作人员</button>
            </if>
            <style>
                .ace-file-input a {display:none;}
            </style>
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>姓名</th>
                                    <th>电话</th>
                                    <th>微信昵称</th>
                                    <th>入职时间</th>
                                    <th>职务类型</th>
                                    <th>状态</th>
                                    <th>处理次数</th>
                                    <th>被评论数</th>
                                    <th>评分</th>
                                    <th>处理任务详情</th>
                                    <th>是否可以开门</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$workers">
                                    <volist name="workers" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.name}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.phone}</div></td>
                                            <td class="nickname">
                                            <if condition="empty($vo['openid']) AND ($vo['type'] eq 0 OR $vo['type'] eq 1)">
                                            <a href="{pigcms{$config.site_url}/index.php?g=Index&c=Recognition&a=see_tmp_qrcode&qrcode_id={pigcms{$vo['wid'] + 3900000000}&img=1" data-wid="{pigcms{$vo['wid']}" class="see_qrcode" id="qr_{pigcms{$vo['wid']}" >绑定微信账号</a>
                                            <else/>
                                            {pigcms{$vo.nickname}
                                            </if>
                                            </td>
                                            <td><div class="tagDiv"><if condition="empty($vo['create_time'])">--<else />{pigcms{$vo.create_time|date='Y-m-d H:i:s',###}</if></div></td>
                                            <td><div class="tagDiv"><span class="green">{pigcms{$worker_name[$vo['type']]}</span></div></td>
                                            <td>
												<if condition="($vo['type'] eq 0 OR $vo['type'] eq 1)">
													<div class="tagDiv">
														<if condition='$vo["status"] eq 1'>
															<span class="green">正常</span>
                                                        <elseif condition='$vo["status"] eq 4'/>
                                                            <span class="red">禁用</span>
														<elseif condition='$vo["status"] eq 0 && !$vo["openid"]'/>
															<span class="red">暂未绑定微信号</span>
														<else />
															<span class="red">关闭</span>
														</if>
													</div>
												</if>
											</td>
                                            <td>{pigcms{$vo.num}</td>
                                            <td>{pigcms{$vo.reply_count}</td>
                                            <td>{pigcms{$vo.score_mean}</td>
                                            <td><if condition="($vo['type'] eq 0 OR $vo['type'] eq 1)"><a href="{pigcms{:U('Index/worker_order', array('wid'=>$vo['wid']))}">查看任务列表</a></if></td>
                                            <td><if condition="$vo['open_door']">可以<else />不可以</if></td>
                                            <td>
												<if condition='$vo["status"] eq 4'>
													账号被禁用，无法操作
												<else />
													<a style="width: 60px;" class="label label-sm label-info" title="编辑" href="{pigcms{:U('Index/worker_edit', array('wid'=>$vo['wid']))}">编辑</a> &nbsp;
													<a class="label label-warning cancel" title="取消微信绑定" data-wid="{pigcms{$vo['wid']}" <if condition="empty($vo['openid']) || !in_array(6,$house_session['menus'])">style="display:none"</if>>取消微信绑定</a>&nbsp;

                                                    <if condition="in_array(7,$house_session['menus'])">
													<a style="width: 60px;" class="label label-warning" title="长期禁用账号" href="javascript:void(0)" onclick="if(confirm('确认禁用该工作人员？会清除该工作人员的所有信息和绑定的微信，但会保留账号方便以后调用任务查看，删除不可恢复。请慎重使用。')){location.href='{pigcms{:U('Index/worker_delete', array('wid'=>$vo['wid']))}'}">禁用账号</a>
                                                    </if>
												</if>
											</td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="10" >还没有工作人员入职</td></tr>
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
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
var test;
$(document).ready(function(){
	$('.see_qrcode').live('click', function(){
		test = art.dialog.open($(this).attr('href'),{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('iframe_handle',iframe);
			},
			id: 'handle',
			title:'扫描二维码绑定微信号',
			padding: 0,
			width: 430,
			height: 433,
			lock: true,
			resize: false,
			background:'black',
			button: null,
			fixed: false,
			close: function(){clearInterval(t);},
			left: '50%',
			top: '38.2%',
			opacity:'0.4'
		});
		var wid = $(this).attr('data-wid'), obj = $(this);
	 	var t = window.setInterval(function(){
			$.get("{pigcms{:U('Index/check_worker')}", {wid:wid},  function(result){
				if (result.error_code == 0) {
					test.close();
					clearInterval(t);
					obj.parent('td').html(result.nickname).siblings('.button-column').children('.cancel').show();
				}
			}, 'json');
		},3000);
		return false;
	});
	
	$('.cancel').click(function(){
		var wid = $(this).attr('data-wid'), obj = $(this);
		obj.attr('disabled', true);
		$.get("{pigcms{:U('Index/cancel_worker')}", {wid:wid}, function(result){
			obj.attr('disabled', false);
			if (result.error_code == 1) {
				alert(result.msg);
			} else {
				var qrcode_id = 3900000000 + wid;
				obj.hide().parent('td').siblings('.nickname').html('<a href="{pigcms{$config.site_url}/index.php?g=Index&c=Recognition&a=see_tmp_qrcode&qrcode_id=' + qrcode_id + '&img=1" data-wid="' + wid + '" class="see_qrcode">绑定公众号</a>');
			}
		}, 'json');
	});
});
</script>
<include file="Public:footer"/>
