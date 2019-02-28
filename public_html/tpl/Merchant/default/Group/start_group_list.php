<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Group/start_group_list')}">团购小组列表</a>
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
				
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="120"><input type="checkbox" id="all" />编号</th>
									<th>团购名称</th>
									<th>团购类型</th>
									<th>发起人</th>
									<th>成团人数</th>
									<th>当前参加人数</th>
									<th>是否成团</th>
									<th>发起时间</th>
									<th>最后更新时间</th>
									<th>操作 <a href="javascript:void(0)"  onclick="all_group_right_now(this)" style="color:red">批量成团</a></th>
					

								</tr>
							</thead>
							<tbody>
								<if condition="$start_group_list">
									<volist name="start_group_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>" data-id="{pigcms{$vo.id}">
											<td class="start_id">  <if condition="$vo.status eq 0"><input type="checkbox"  name="start[]" value="{pigcms{$vo.id}"/></if> {pigcms{$vo.id}</td>
											<td>{pigcms{$vo['group_name']}</td>
											<td><if condition="$vo.tuan_type eq 0">团购券<elseif condition="$vo.tuan_type eq 1" />代金券<elseif condition="$vo.tuan_type eq 2" />实物</if></td>
											<td>{pigcms{$vo['nickname']}</td>
											<td>{pigcms{$vo['complete_num']}</td>
											<td>{pigcms{$vo.num}</td>
											<td><if condition="$vo.status eq 1"><font color="green">已经成团</font><elseif condition="$vo.status eq 2" /><font color="orange">拼团超时</font><elseif condition="$vo.status eq 3" /><font color="blue">商家设置成团</font><else /><font color="red">还未成团</font></if></td>
											<td>{pigcms{$vo.start_time|date="Y-m-d H:i:s",###}</td>
											<if condition="$vo['status'] eq '2'">
											<td>{pigcms{$vo['start_time']+$vo['pin_effective_time']*3600|date="Y-m-d H:i:s",###}</td>
											<else />
											<td>{pigcms{$vo.last_time|date="Y-m-d H:i:s",###}</td>
											</if>
											<td><if condition="$vo.num lt $vo.complete_num AND $vo.status eq 0"><a href="javascript:void(0)"  onclick="group_right_now(this)" style="color:red">立即成团</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</if><a href="{pigcms{:U('Group/start_group_info',array('id'=>$vo['id']))}" >查看详情</a></td>
											
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="11" >没有团购小组</td></tr>
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
<script type="text/javascript">
	function CreateShop(){
		window.location.href = "{pigcms{:U('Group/pick_address_add')}";
	}
	
	function group_right_now(obj){
		
			
			var r=confirm("该团购还未成团，您确定要立即成团吗？");
			if (r==true){
				$.post("{pigcms{:U('Group/update_start_status')}", {status: 3,id:$(obj).parents('tr').data('id')}, function(data, textStatus, xhr) {
					if(data){
						window.location.href = window.location.href;
					}else{
						alert(data.msg);
					}
				});
			}
		

	}
	
	function all_group_right_now(obj){
		var list = $('input[name="start[]"]:checked');
		
		if(list.length<1){
			alert('没有勾选待成团的拼团小组');
		}else{
			var ids='';
			$.each(list,function(i,k){
				ids+=','+$(k).val();
			})
		
			var r=confirm("这些团购还未成团，您确定要立即成团吗？");
			if (r==true){
				$.post("{pigcms{:U('Group/update_start_status')}", {status: 3,id:ids.substring(1)}, function(data, textStatus, xhr) {
					if(data){
						window.location.href = window.location.href;
					}else{
						alert(data.msg);
					}
				});
			}
		}

	}
	$(function(){
		$('.group_name').hover(function(){
			var top = $(this).offset().top;
			var left = $(this).offset().left+$(this).width()+10;
			$('body').append('<div id="group_name_div" style="position:absolute;z-index:5555;background:white;top:'+top+'px;left:'+left+'px;border:1px solid #ccc;padding:10px;"><div style="margin-bottom:10px;"><b>商品标题：</b>'+$(this).data('title')+'</div><div><b>商品图片：</b><img src="'+$(this).data('pic')+'" style="width:180px;"/></div></div>');
		},function(){
			$('#group_name_div').remove();
		});	
		$('.see_qrcode').click(function(){
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
				opacity:'0.4'
			});
			return false;
		});
		var all = false;
		$('#all').click(function(){
			if(!all){
				$('input[name="start[]"]').attr('checked',true);
				all = true;
			}else{
				$('input[name="start[]"]').attr('checked',false);
				all = false;
			}
		})
	});
</script>
<include file="Public:footer"/>
