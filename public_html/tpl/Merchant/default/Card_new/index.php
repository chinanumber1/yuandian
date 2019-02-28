<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-credit-card"></i>
				<a href="{pigcms{:U('Card_new/index')}">会员卡</a>
			</li>
			<li class="active">会员卡用户列表</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<a class="btn btn-success" href="{pigcms{:U('Card_new/card_edit')}" >编辑会员卡</a>
					<button class="btn btn-success handle_btn" data-title="新增会员" href="{pigcms{:U('Card_new/add_user')}" >新增会员</button>
					<a class="btn btn-success" href="{pigcms{:U('Card_new/card_new_coupon')}" >优惠券列表</a>
					<a class="btn btn-success" href="{pigcms{:U('Card_new/card_group')}" >会员卡分组</a>
					<a class="btn btn-success" href="{pigcms{:U('Card_new/recharge_list')}" >会员卡消费记录</a>
					<a class="btn btn-success"  href="javascript:void(0)" onclick="exports()"  style="float:right;">导出会员</a>
					<form action="{pigcms{:U('Card_new/index')}" method="get" style="float:left;margin-left:10px">
						<input type="hidden" name="c" value="Card_new"/>
						<input type="hidden" name="a" value="index"/>
					筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
						<select name="searchtype">
							<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>手机号码</option>
							<option value="card_id" <if condition="$_GET['searchtype'] eq 'card_id'">selected="selected"</if>>会员卡号</option>
							<option value="physical_id" <if condition="$_GET['searchtype'] eq 'physical_id'">selected="selected"</if>>实体卡号</option>
							<option value="nickname" <if condition="$_GET['searchtype'] eq 'nickname'">selected="selected"</if>>用户姓名</option>
						</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

						<button class="btn btn-success" >查询</button>&nbsp;&nbsp;&nbsp;&nbsp;
					</form>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th id="shopList_c1" width="100">会员卡号</th>
									<th id="shopList_c1" width="100">微信卡号</th>
									<th id="shopList_c1" width="100">用户姓名</th>
									<th id="shopList_c1" width="100">用户生日</th>
									<th id="shopList_c1" width="100">用户手机</th>
									<th id="shopList_c1" width="100">会员卡余额</th>
									<th id="shopList_c1" width="100">会员卡{pigcms{$config['score_name']}</th>
									<th id="shopList_c1" width="100">实体卡号</th>
									<th id="shopList_c1" width="100">领卡时间</th>
									<th id="shopList_c1" width="100">会员卡状态</th>
									<th id="shopList_c1" width="120" style="text-align:center">操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$card_user_list">
									<volist name="card_user_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.id}</td>
											<td><if condition="$vo.wx_card_code neq ''">{pigcms{$vo.wx_card_code}<else /></if></td>
											<td><if condition="$vo['uid'] neq ''">{pigcms{$vo.nickname}<else /><a href="{pigcms{:U('Card_new/see_qrcode',array('id'=>$vo['id']))}" data-id = "{pigcms{$vo.id}" class="see_qrcode">查看二维码(用户微信扫描后绑定)</a></if></td>
											<td><if condition="$vo['birthday'] neq '0000-00-00'">{pigcms{$vo.birthday}</if></td>
											<td>{pigcms{$vo.phone}</td>
											<td>{pigcms{$vo['card_money']+$vo['card_money_give']}</td>
											<td>{pigcms{$vo.card_score}</td>
											<td>{pigcms{$vo.physical_id}</td>
											<td><if condition="$vo.add_time neq 0">{pigcms{$vo['card_add_time']|date="Y-m-d H:i:s",###}</if></td>
											<td><if condition="$vo.card_status eq '1'"><font color="green">正常</font><else /><font color="red">禁止</font></if></td>
											<td class="button-column" nowrap="nowrap">

											<a title="查看详情" class="green handle_btn" data-title="查看详情" style="padding-right:8px;" href="{pigcms{:U('Card_new/card_detail',array('id'=>$vo['id'],'uid'=>$vo['uid']))}">
												<i class="ace-icon fa fa-search bigger-110">查看详情</i>
											</a>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="11" >无内容</td></tr>
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
$(function(){
	jQuery(document).on('click','#shopList a.red',function(){
		if(!confirm('确定要删除这条数据吗?不可恢复。')) return false;
	});
});

function drop_confirm(msg, url)
{
	if (confirm(msg)) {
		window.location.href = url;
	}
}
</script>

<script>
var cardid='';
	$(function(){
		$('.handle_btn').live('click',function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:$(this).data('title'),
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

		$('.see_qrcode').click(function(){
			cardid=$(this).attr('data-id');
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'查看渠道二维码',
				padding: 0,
				width: 430,
				height: 433,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: null,
				left: '50%',
				top: '38.2%',
				opacity:'0.4',
				cancel: function () {
					cardid = '';
				}
			});
			return false;
		});

		 var time = setInterval(function(){
			if(cardid!=''){
				$.post("{pigcms{:U('Card_new/ajax_get_bind_status')}", {cardid:cardid }, function(data, textStatus, xhr) {
					data = eval('('+data+')');
					if(data.error_code==0){
						cardid='';
						window.location.reload();
					}
				});

			}
        },1000);


		$('#group_id').change(function(){
			$('#frmselect').submit();
		});
	});
  var url = "{pigcms{$config.site_url}"
    var export_url = "{pigcms{:U('Card_new/export')}"
</script>
<script type="text/javascript" src="{pigcms{$static_public}js/export.js"> </script>




<include file="Public:footer"/>
