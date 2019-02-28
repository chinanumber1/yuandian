<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('index')}">小程序设置</a>
			</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<div class="wxapp_tip" style="margin:20px 0;font-size:14px;">
						您当前线上版本号 <font color="red">{pigcms{$now_bind.now_version|default='从未提交'}</font>，已提交代码版本号 <font color="red">{pigcms{$now_bind.commit_version}</font>，<if condition="$now_bind['update_version_id'] gt $now_bind['now_version_id']">正在审核版本号 <font color="red">{pigcms{$now_bind.update_version}</font>，</if>小程序最新版本号 <font color="red">{pigcms{$new_bind.now_version}</font>
						<if condition="$new_bind['now_version_id'] gt $now_bind['commit_version_id']">
							&nbsp;&nbsp;&nbsp;<button class="btn btn-success" onclick="commitVersion()" style="padding:0px 15px;">提交代码</button>
						</if>
						<if condition="$now_bind['commit_version_id'] || $now_bind['now_version']">
							&nbsp;&nbsp;&nbsp;<button class="btn btn-success" onclick="get_qrcode()" style="padding:0px 10px;">获取体验二维码</button>
						</if>
						<if condition="$now_bind['commit_version_id'] gt $now_bind['update_version_id']">
							&nbsp;&nbsp;&nbsp;<button class="btn btn-success" onclick="commit_verify()" style="padding:0px 10px;">提交小程序审核</button>
						</if>
						<if condition="$now_bind['now_version_id']">
							&nbsp;&nbsp;&nbsp;<button class="btn btn-success" onclick="releaseVersion()" style="padding:0px 15px;">发布小程序</button>
						</if>
						<if condition="$now_bind['update_error_tip']">
							&nbsp;&nbsp;&nbsp;<button class="btn btn-success" onclick="look_update_error()" style="padding:0px 10px;">查看上次审核失败原因</button>
						</if>
						&nbsp;&nbsp;&nbsp;<button class="btn btn-success" onclick="wxapp_setting()" style="padding:0px 10px;">小程序配置</button>
						&nbsp;&nbsp;&nbsp;<button class="btn btn-success" onclick="re_authorizer()" style="padding:0px 10px;">重新授权</button>
					</div>
					
					<div><span style="font-size:16px;line-height:30px;">体验者列表</span>&nbsp;&nbsp;<button class="btn btn-success" onclick="CreateTester()" style="padding:0px 15px;">绑定</button></div>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="120">序号</th>
									<th>微信昵称</th>
									<th>微信号</th>
									<th>添加时间</th>
									<th width="200" style="text-align:center;">操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$tester_list">
									<volist name="tester_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.tester_id}</td>
											<td>{pigcms{$vo.wechatname}</td>
											<td>{pigcms{$vo.wechatid}</td>
											<td>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</td>
											<td style="text-align:center;"><a style="width:60px;" class="label label-sm label-info handle_btn" title="解绑" href="{pigcms{:U('unbind_tester',array('id'=>$vo['tester_id']))}">解绑</a></td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="5" >您还未添加体验者</td></tr>
								</if>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="applet_box" style="display:none;">
	<div style="width:380px;margin:20px auto;" class="applet_box">
		<form class="applet" action="">
			<ul>
				<li><span>微信昵称</span>: <input name="wechatname" type="text" value="" placeholder="微信昵称" class="px" style="width:220px"/></li>
				<li><span>微信号</span>: <input name="wechatid" type="text" value="" placeholder="微信号" class="px" style="width:220px"/></li>
			</ul>
		</form>
	</div>
</div>
<div id="commit_box" style="display:none;">
	<div style="width:380px;margin:20px auto;" class="commit_box">
		<form class="applet" action="">
			<div>
				<span>选择类目：</span>
				<select id="commit_category"></select>
			</div>
			<div style="margin-top:20px;">
				<span>填写标签：</span>
				<input id="commit_tag" />
				<span>多个标签以空格分开</span>
			</div>
		</form>
	</div>
</div>
<div id="setting_box" style="display:none;">
	<div class="setting_box">
		<form class="applet" action="">
			<div>
				<span style="width:120px;display:inline-block;text-align:right;">选择自定义页面：</span>
				<select id="mer_index_page"></select>
			</div>
			<div style="margin-top:20px;">
				<span style="width:120px;display:inline-block;text-align:right;">Appid：</span>
				<span id="setting_appid"></span>
			</div>
			<div style="margin-top:20px;">
				<span style="width:120px;display:inline-block;text-align:right;">小程序密钥：</span>
				<input id="setting_appsecret" style="width:260px;"/>
			</div>
			<div style="margin-top:20px;">
				<span style="width:120px;display:inline-block;text-align:right;">商户号(mch_id)：</span>
				<input id="setting_wxpay_merid" style="width:260px;"/>
			</div>
			<div style="margin-top:20px;">
				<span style="width:120px;display:inline-block;text-align:right;">商户密钥：</span>
				<input id="setting_wxpay_key" style="width:260px;"/>
			</div>
		</form>
	</div>
</div>
<style>
.applet_box ul li {
	margin-top: 10px;
	list-style: none;
}
.applet_box ul li span {
    width: 80px;
    text-align: right;
    display: inline-block;
    margin-right: 5px;
}

</style>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<if condition="$audit_result['status'] eq 1">
	<script>
		art.dialog({
			title: '提交小程序，审核失败',
			content: '审核结果：{pigcms{$audit_result['reason']}<br/><br/>请及时提交新的版本审核',
			opacity:'0.4',
			lock: true,
			fixed: true,
			width:400
		});
	</script>
</if>
<script>
	function wxapp_setting(){
		var createTesterDialogTip = art.dialog({
			icon: 'face-smile',
			time: 100,
			title: '提醒',
			opacity:'0.4',
			lock: true,
			fixed: true,
			resize: false,
			content: '加载中...'
		});
		$.post("{pigcms{:U('get_setting')}",{},function(result){
			var html = '';
			for(var i in result.info.diypage_list){
				html += '<option value="'+result.info.diypage_list[i].page_id+'" '+(result.info.diypage_list[i].page_id == result.info.mer_index_page ? 'selected="selected"' : '')+'>'+result.info.diypage_list[i].page_name+'</option>';
			}
			$('#mer_index_page').html(html);
			$('#setting_appid').html(result.info.appid);
			$('#setting_appsecret').val(result.info.appsecret);
			$('#setting_wxpay_merid').val(result.info.wxpay_merid);
			$('#setting_wxpay_key').val(result.info.wxpay_key);
			createTesterDialogTip.close();
			if(result.status == 1){
				art.dialog({
					title: '小程序配置',
					content: document.getElementById('setting_box'),
					opacity:'0.4',
					lock: true,
					fixed: true,
					resize: false,
					width:500,
					ok:function(){
						var createTesterDialogTip = art.dialog({
							icon: 'face-smile',
							time: 100,
							title: '提醒',
							opacity:'0.4',
							lock: true,
							fixed: true,
							resize: false,
							content: '加载中...'
						});
						$.post("{pigcms{:U('set_setting')}",{mer_index_page:$('#mer_index_page').val(),appsecret:$('#setting_appsecret').val(),wxpay_merid:$('#setting_wxpay_merid').val(),wxpay_key:$('#setting_wxpay_key').val()},function(result){
							createTesterDialogTip.close();
							alert(result.info);
							if(result.status == 1){
								window.location.reload();
							}
						});
						return false;
					}
				});
			}
		});
		return false;
	}
	function CreateTester(){
		var createTesterDialog = art.dialog({
			title: '绑定体验者',
			content: '<div id="applet_box_fixed">'+$('#applet_box').html()+'</div>',
			ok: function(){
				var wechatname = $.trim($('#applet_box_fixed input[name="wechatname"]').val());
				var wechatid = $.trim($('#applet_box_fixed input[name="wechatid"]').val());
				if(wechatname == ''){
					alert('请填写微信昵称');
					$('#applet_box_fixed input[name="wechatname"]').focus();
					return false;
				}
				if(wechatid == ''){
					alert('请填写微信号');
					$('#applet_box_fixed input[name="wechatid"]').focus();
					return false;
				}
				var createTesterDialogTip = art.dialog({
					icon: 'face-smile',
					time: 100,
					title: '提醒',
					opacity:'0.4',
					lock: true,
					fixed: true,
					resize: false,
					content: '加载中...'
				});
				$.post("{pigcms{:U('add_tester')}",{wechatname:wechatname,wechatid:wechatid},function(result){
					alert(result.info);
					if(result.status == 1){
						window.location.reload();
					}else{
						createTesterDialogTip.close();
					}
				});
				return false;
			}
		});
	}
	function get_qrcode(){
		var createTesterDialogTip = art.dialog({
			icon: 'face-smile',
			time: 100,
			title: '提醒',
			opacity:'0.4',
			lock: true,
			fixed: true,
			resize: false,
			content: '加载中...'
		});
		$.post("{pigcms{:U('get_qrcode')}",{},function(result){
			createTesterDialogTip.close();
			if(result.status == 1){
				art.dialog({
					title: '体验二维码',
					opacity:'0.4',
					lock: true,
					fixed: true,
					content: '<div id="applet_box_img"><img src="'+result.info+'" style="width:300px;height:300px;"/></div>',
				});
			}
		});
		return false;
	}
	function look_update_error(){
		art.dialog({
			title: '查看上次审核失败原因',
			content: '{pigcms{$now_bind['update_error_tip']}',
			opacity:'0.4',
			lock: true,
			fixed: true,
			width:400
		});
	}
	function commit_verify(){
		var createTesterDialogTip = art.dialog({
			icon: 'face-smile',
			time: 100,
			title: '提醒',
			opacity:'0.4',
			lock: true,
			fixed: true,
			resize: false,
			content: '加载中...'
		});
		$.post("{pigcms{:U('get_category')}",{},function(result){
			var html = '';
			for(var i in result.info){
				html += '<option value="'+result.info[i].first_id+'||'+result.info[i].second_id+'||'+result.info[i].third_id+'||'+result.info[i].first_class+'||'+result.info[i].second_class+'||'+result.info[i].third_class+'">'+result.info[i].first_class+'-'+result.info[i].second_class+(result.info[i].third_class ? '-'+result.info[i].third_class : '')+'</option>';
			}
			$('#commit_category').html(html);
			createTesterDialogTip.close();
			if(result.status == 1){
				art.dialog({
					title: '提交小程序审核',
					content: '<div id="commit_box_fixed">'+$('#commit_box').html()+'</div>',
					opacity:'0.4',
					lock: true,
					fixed: true,
					resize: false,
					ok:function(){
						var createTesterDialogTip = art.dialog({
							icon: 'face-smile',
							time: 100,
							title: '提醒',
							opacity:'0.4',
							lock: true,
							fixed: true,
							resize: false,
							content: '加载中...'
						});
						$.post("{pigcms{:U('submit_audit')}",{category:$('#commit_box_fixed #commit_category').val(),tag:$('#commit_box_fixed #commit_tag').val(),new_version:'{pigcms{$new_bind.now_version}',new_version_id:'{pigcms{$new_bind.now_version_id}'},function(result){
							createTesterDialogTip.close();
							alert(result.info);
							if(result.status == 1){
								window.location.reload();
							}
						});
					}
				});
			}
		});
		return false;
	}
	function commitVersion(){
		location.href = "{pigcms{:U('commit_version')}";
	}
	function releaseVersion(){
		location.href = "{pigcms{:U('release_version')}";
	}
	function re_authorizer(){
		location.href = "{pigcms{$go_url}";
	}
</script>
<include file="Public:footer"/>
