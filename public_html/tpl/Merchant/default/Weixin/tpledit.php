<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
    			<i class="ace-icon fa fa-wechat"></i>
    			<a href="{pigcms{:U('template')}">模板消息列表</a>
			</li>
			<li class="active">编辑模板</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
				
								<div class="form-group">
									<label class="col-sm-1">模板编号</label>
                                    <select name="tempkey">
                                    <volist name="tplList" id="row">
                                    <option value="{pigcms{$key}" data-content="{pigcms{$row['content']}" data-name="{pigcms{$row['name']}" <if condition="$tempmsg['tempkey'] eq $row['key']">selected</if>>{pigcms{$key}</option>
                                    </volist>
                                    </select>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1">模板名</label>
									<input class="col-sm-2" name="name" id="name" type="text" value="{pigcms{$tempmsg['name']}" />
								</div>
								<div class="form-group">
									<label class="col-sm-1">回复内容</label>
									<textarea class="col-sm-3" rows="6" name="content" id="content" readonly>{pigcms{$tempmsg['content']}</textarea>
								</div>
								<div class="form-group">
									<label class="col-sm-1">模板ID</label>
									<input class="col-sm-4" name="tempid" type="text" value="{pigcms{$tempmsg['tempid']}" />
								</div>
								<div class="form-group">
									<label class="col-sm-1">头部颜色</label>
									<input class="col-sm-1 color" name="topcolor" type="text" value="{pigcms{$tempmsg['topcolor']}" />
								</div>
								<div class="form-group">
									<label class="col-sm-1">文字颜色</label>
									<input class="col-sm-1 color" name="textcolor" type="text" value="{pigcms{$tempmsg['textcolor']}" />
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>状态</label></label>
									<label><span><label><input name="status" <if condition="$tempmsg['status'] eq 0 ">checked="checked"</if> value="0" type="radio"></label>&nbsp;<span>关闭</span>&nbsp;</span></label>
									<label><span><label><input name="status" <if condition="$tempmsg['status'] eq 1 ">checked="checked"</if> value="1" type="radio" ></label>&nbsp;<span>开启</span></span></label>
								</div>
							</div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="/static/js/cart/jscolor.js" type="text/javascript"></script>
<script type="text/javascript">
$('select[name=tempkey]').change(function(){
    $('#name').val($(this).find("option:selected").data('name'));
    $('#content').val($(this).find("option:selected").data('content'));
});
</script>
<include file="Public:footer"/>
