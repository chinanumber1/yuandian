<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('Unit/garage_management')}">车库管理</a>
            </li>
            <li class="active">修改车库信息</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<form  class="form-horizontal" method="post">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">

								<div class="form-group">
									<label class="col-sm-1"><label for="garage_num">车库名称</label></label>
									<input class="col-sm-2" size="20" name="garage_num" id="garage_num" type="text"  value="{pigcms{$info_list.garage_num}" />
								</div>
                                
								<div class="form-group">
									<label class="col-sm-1"><label for="garage_position">车库地址</label></label>
									<input class="col-sm-2" size="20" name="garage_position" id="garage_position" type="text"  value="{pigcms{$info_list.garage_position}" />
									
								</div>
								
								<div class="form-group" s>
									<label class="col-sm-1"><label>备注</label></label>
									<label><textarea name="garage_remark" id="garage_remark" maxlength="255" style="width:286px;height:90px;resize:none" placeholder="最多输入255个字">{pigcms{$info_list.garage_remark}</textarea></label>
								</div>
						</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info submit_info" type="button" <if condition="!in_array(53,$house_session['menus'])">disabled="disabled"</if>>
										<i class="ace-icon fa fa-check bigger-110"></i>
										修改
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript">
$('.submit_info').click(function(){
	
	var garage_num = $('#garage_num').val();
	if(!garage_num){
		layer.msg('车库名称不能为空!',{icon:2});
		return false;
	}

	var garage_position = $('#garage_position').val();
	if(!garage_position){
		layer.msg('车库地址不能为空!',{icon:2});
		return false;
	}
	var garage_remark = $('#garage_remark').val();//车库备注
	$.post("{pigcms{:U('garage_edit')}",{'garage_id':{pigcms{$info_list.garage_id},'garage_num':garage_num,'garage_position':garage_position,'garage_remark':garage_remark},function(data){
                if(data.code == 1){
                    layer.msg(data.msg,{icon: 1},function(){
	                   	location.href='{pigcms{:U('garage_management')}';
	                });
                }
                if(data.code == 2){
                    layer.msg(data.msg,{icon: 2});
                }
    },'json');
})
</script>

<include file="Public:footer"/>