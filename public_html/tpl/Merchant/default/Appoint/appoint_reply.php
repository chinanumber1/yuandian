<include file="Public:header"/>
<div class="main-content">
    <style type="text/css">
.page-content-area .form-group .col-sm-1:first-child{box-sizing:content-box}
    </style>
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-comments-o comments-o-icon"></i>
			<li class="active">预约管理</li>
			<li><a href="{pigcms{:U('appoint_reply')}">{pigcms{$config.appoint_alias_name}评论</a></li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="form-group">
									<label class="col-sm-1">选择项目：</label>
									<select id="choose_catfid" name="cat_fid" class="col-sm-1" style="margin-right:10px;">
										<option value="">请选择</option>
										<volist name="store_list" id="vo">
											<option value="{pigcms{$vo.store_id}" <if condition="$_GET['store_id'] eq $vo['store_id']">selected="selected"</if>>{pigcms{$vo.name}</option>
										</volist>
									</select>
									<select id="choose_catid" name="cat_id" class="col-sm-1" style="margin-right:10px;">
										<option value="">请选择</option>
										<volist name="appoint_list" id="vo">
											<option value="{pigcms{$vo.appoint_id}" <if condition="$_GET['appoint_id'] eq $vo['appoint_id']">selected="selected"</if>>{pigcms{$vo.appoint_name}</option>
										</volist>
									</select>
									<input type="hidden" name="cat_id" id="cat_id" value=""/>
								</div>
				<div class="col-xs-12" style="padding-left:0px;padding-right:0px;">
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="10%">顾客ID</th>
									<th width="10%">订单号</th>
									<th>评论内容</th>
									<th width="10%">评论时间</th>
									<th width="10%">评论打分</th>
									<th width="10%">是否回复</th>
                                    <th width="10%">审核状态</th>
									<th class="button-column" width="10%">操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$reply_list">
									<volist name="reply_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.uid}</td>
											<td>{pigcms{$vo.order_id}</td>
											<td>{pigcms{$vo.comment|msubstr=###,0,50}</td>
											<td>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</td>
											<td>{pigcms{$vo.score}</td>
											<td><if condition="$vo['merchant_reply_time']">已回复<else/>未回复</if></td>
                                            <td><if condition="$vo['status'] == 0"><span class="red">未审核</span><elseif condition="$vo['status'] == 1" /><span class="green">审核通过</span><else/><span class="red">审核不通过</span></if></td>
											<td><a style="width: 60px;" class="label label-sm label-info" title="评论列表" href="{pigcms{:U('Appoint/appoint_reply_detail',array('id'=>$vo['pigcms_id']))}">评论详情</a></td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="7" >暂无评论！</td></tr>
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
<script type="text/javascript">
	$('#choose_catfid').change(function(){
		$.getJSON("{pigcms{:U('Appoint/ajax_get_appoint')}",{store_id:$(this).val()},function(result){
			var html = '';
			html += '<option value="">请选择</option>';  
			if(result.error == 0){
				for ( var i=0; i<result.appoint_list.length; i++){
                    html += '<option value="'+ result.appoint_list[i].appoint_id +'">' + result.appoint_list[i].appoint_name + '</option>';  
                }  
                $('#choose_catid').html(html);
				
		
            } else {  
                $("#choose_catid").html(html);
            }  
		});
	});
	
	$('#choose_catid').change(function(){
		var store_id = $('#choose_catfid').val();
		var Url="{pigcms{:U('appoint_reply')}"+"&appoint_id="+($(this).val())+'&store_id='+store_id;
		location.href=Url;
	});
</script>
<include file="Public:footer"/>
