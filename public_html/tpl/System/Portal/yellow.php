<include file="Public:header"/>
<script type="text/javascript" src="//apps.bdimg.com/libs/layer/2.1/layer.js"></script>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Portal/yellow')}" class="on">黄页列表</a>|
					<a href="{pigcms{:U('Portal/yellow_add')}">添加黄页</a>
				</ul>
			</div>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<thead>
							<tr>
								<th class="textcenter">公司名称</th>
								<th class="textcenter">公司电话</th>
								<th class="textcenter">邮箱</th>
								<th class="textcenter">地址</th>
								<th class="textcenter">一级分类</th>
								<th class="textcenter">二级分类</th>
								<th class="textcenter">公司Logo</th>
								<th class="textcenter">公司二维码</th>
								<th class="textcenter">申请时间</th>
								<th class="textcenter">状态</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="$yellow_list">
							<volist name="yellow_list" id="vo">
							<tr>
								<td class="textcenter">{pigcms{$vo.title}</td>
								<td class="textcenter">{pigcms{$vo.tel}</td>
								<td class="textcenter">{pigcms{$vo.email}</td>
								<td class="textcenter">{pigcms{$vo.address}</td>
								<td class="textcenter">{pigcms{$vo.parent_cat_name}</td>
								<td class="textcenter">{pigcms{$vo.child_cat_name}</td>
								<td class="textcenter"><if condition="$vo['logo'] neq ''">有<else/>无</if></td>
								<td class="textcenter"><if condition="$vo['qrcode'] neq ''">有<else/>无</if></td>
								<td class="textcenter">{pigcms{$vo.dateline|date="Y-m-d H:i:s",###}</td>
								<?php $status=array(0=>'申请中',1=>'通过审核',2=>'已拒绝');?>
								<td class="textcenter"><?php echo $status[$vo['status']]?></td>
								<td class="textcenter">
								<if condition="$vo['top_time']">
								<a href="javascript:;" onclick="untop({pigcms{$vo['id']})"><span style="color:red;">取消</span></a>
								<else/>
								<a href="javascript:;" onclick="dotop({pigcms{$vo['id']})">置顶</a>
								</if>
								<a href="{pigcms{:U('Portal/yellow_add',array('yid'=>$vo['id']))}">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="yid={pigcms{$vo.id}" url="{pigcms{:U('Portal/yellow_del')}">删除</a> | <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Portal/yellow_recomment',array('yellow_id'=>$vo['id'],'frame_show'=>true))}','评论列表',1000,500,true,false,false,false,'detail',true);">评论管理</a>
								</td>
							</tr>
							</volist>
								<tr><td class="textcenter pagebar" colspan="12">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="12">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>

<script type="text/javascript">
	// 特别推荐
	function isrecommend(obj){
		var aid = $(obj).attr('aid');
		var ischecked = $(obj).is(':checked') ? 1 : 0;
		$.post("{pigcms{:U('Portal/article_recommend')}",{'aid':aid,'isrecommend':ischecked},function(response){
			if(response.code > 0){
				layer.alert(response.msg);
			}else{
				layer.msg(response.msg);
			}
		},'json');
	}

	// 置顶
	function dotop(yid){

		$.post("{pigcms{:U('Portal/yellow_dotop')}",{'yid':yid},function(response){
			if(response.code>0){
				layer.alert(response.msg);
				return;
			}
			layer.msg(response.msg);
			setTimeout(function(){window.location.reload();},1000);
		},'json');
	}

	// 取消置顶
	function untop(yid){
		$.post("{pigcms{:U('Portal/yellow_untop')}",{'yid':yid},function(response){
			if(response.code>0){
				layer.alert(response.msg);
				return;
			}
			layer.msg(response.msg);
			setTimeout(function(){window.location.reload();},1000);
		},'json');
	}
</script>