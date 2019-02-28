<include file="Public:header" />
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
    			<i class="ace-icon fa fa-wechat"></i>
    			微信模板消息模板
			</li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
				    <if condition="isset($weixin['code']) AND $weixin['code'] eq 3">
					<div class="tabbable">
						<div class="tab-content">
							<div>
								<a href="{pigcms{:U('Weixin/tpladd')}" class="btn btn-success">新建模板</a>
							</div>
							<div id="yw0" class="grid-view">
								<table class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th>模板编号</th>
											<th>模板名</th>
											<th>回复内容</th>
											<th>头部颜色</th>
											<th>文字颜色</th>
											<th>状态</th>
											<th>模板ID</th>
											<th>操作</th>
										</tr>
									</thead>
									<tbody id="shopList">
										<if condition="$list"> <volist name="list" id="t">
										<tr>
											<td>{pigcms{$t.tempkey}</td>
											<td>{pigcms{$t.name}</td>
											<td><pre>{pigcms{$t.content}</pre></td>
											<td><input type="text" name="topcolor[]" value="{pigcms{$t.topcolor}" class="px color" style="background: {pigcms{$t.topcolor}" /></td>
											<td><input type="text" name="textcolor[]" value="{pigcms{$t.textcolor}" class="px color" style="background: {pigcms{$t.textcolor}" /></td>
											<td><if condition="$t['status'] eq 0">关闭<else />开启</if></td>
											<td>{pigcms{$t.tempid}</td>
											<td>
												<a title="修改" class="green" style="padding-right:8px;" href="{pigcms{:U('Weixin/tpledit', array('id' => $t['id']))}">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>　　
												<a title="删除" class="red" style="padding-right:8px;" href="{pigcms{:U('Weixin/tpldel', array('id' => $t['id']))}">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</td>
										</tr>
										</volist> <else />
										<tr class="odd">
											<td class="button-column" colspan="8">无内容</td>
										</tr>
										</if>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<else />
					<div>
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert">
								<i class="ace-icon fa fa-times"></i>
							</button>
							您当前的账号是{pigcms{$weixin['errmsg']},没有模板消息的权限！
						</div>
					</div>
					</if>
					
				</div>
			</div>
		</div>
	</div>
	<include file="Public:footer" />