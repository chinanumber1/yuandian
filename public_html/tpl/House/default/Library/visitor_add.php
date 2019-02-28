<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-tablet"></i>
				<a href="{pigcms{:U('visitor_list')}">功能库列表</a>
			</li>
			<li class="active">添加访客</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<form  class="form-horizontal" method="post" onSubmit="return check_submit()" action="__SELF__">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
                            	<div class="form-group">
									<label class="col-sm-1"><label for="visitor_type">选择访客分类：</label></label>
									<select name="visitor_type" id="visitor_type">
                                        <volist name='visitor_type' id='vo'>
                                    	<option value="{pigcms{$key}">{pigcms{$vo}</option>
                                        </volist>
                                    </select>
								</div>
                                
                                <div class="form-group">
									<label class="col-sm-1"><label for="owner_phone">业主手机号码：</label></label>
									<input class="col-sm-2" size="20" name="owner_phone" id="owner_phone" type="text" value=""/>
                                    <label class="col-sm-3"><span class="red" id="owner_info"></span></label>
								</div>
                                
                                <div class="form-group owner-name" style=" display:none">
									<label class="col-sm-1"><label for="owner_name">业主姓名：</label></label>
									<input class="col-sm-2" size="20" name="owner_name" id="owner_name" type="text" value="" readOnly="true"/>
								</div>
                                
                                
                                <div class="form-group owner-address" style=" display:none">
									<label class="col-sm-1"><label for="owner_address">业主住址：</label></label>
									<input class="col-sm-2" size="20" name="owner_address" id="owner_address" type="text" value="" readOnly="true"/>
								</div>
  
								<div class="form-group">
									<label class="col-sm-1"><label for="visitor_name">访客姓名：</label></label>
									<input class="col-sm-2" size="20" name="visitor_name" id="visitor_name" type="text" value=""/>
                                    <label class="col-sm-3"><span class="red">*&nbsp;&nbsp;可不填写</span></label>
								</div>
                                <div class="form-group">
									<label class="col-sm-1"><label for="visitor_phone">访客手机号码：</label></label>
									<input class="col-sm-2" size="20" name="visitor_phone" id="visitor_phone" type="text" value=""/>
								</div>
                                
                                <div class="form-group">
									<label class="col-sm-1">是否放行</label>
									
										<label style="padding-left:0px;padding-right:20px;"><input type="radio" class="ace" value="2" name="status"><span style="z-index: 1" class="lbl">开启</span></label>
										<label style="padding-left:0px;"><input type="radio" class="ace" checked="checked" value="0" name="status"><span style="z-index: 1" class="lbl">关闭</span></label>
								</div>
                                
                                <div class="form-group">
                                	<label class="col-sm-1"><label for="memo">备　　注：</label></label>
									<textarea id="memo" name="memo"style="width:281px; height:100px"></textarea>
								</div>
							</div>

						
						<div class="space"></div>
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
<script type="text/javascript">
	var ajax_get_owner_info_url = "{pigcms{:U('ajax_get_owner_info')}";
	$('#owner_phone').blur(function(){
		$.post(ajax_get_owner_info_url,{'owner_phone':$('#owner_phone').val()},function(data){
			var html='';
			if(!data){
				html += '<span class="red">无业主信息</span>';
				$('#owner_address,#owner_name').val('');
				$('.owner-address,.owner-name').hide();
				$('#owner_info').empty().html(html);
			}else{
				if(data['status']){
					$('.owner-address,.owner-name').show();
					$('#owner_address').val(data['info']['address']);
					$('#owner_name').val(data['info']['name']);
					$('#owner_info').empty();
				}else{
					html += '<span class="red">无业主信息</span>';
					$('#owner_address,#owner_name').val('');
					$('.owner-address,.owner-name').hide();
					$('#owner_info').empty().html(html);
				}
			}
		},'json')
	})
	


	function check_submit(){
		if($('#visitor_type').val()==0){
			alert('访客类型不能为空！');
			return false;
		}
		
		if($('#visitor_phone').val()==''){
			alert('访客手机号码不能为空！');
			return false;
		}
		
		
		if($('#owner_phone').val()==''){
			alert('业主手机号不能为空！');
			return false;
		}
		
		if($('#owner_name').val()==''){
			alert('业主不存在！');
			return false;
		}
		
		
		
		
		if(confirm('确认保存？')){
			return true;
		}else{
			return false;
		}
	}
</script>

<include file="Public:footer"/>