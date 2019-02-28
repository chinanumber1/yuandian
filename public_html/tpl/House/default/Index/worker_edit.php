<include file="Public:header"/>
<div class="main-content">
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear"></i>
				<a href="{pigcms{:U('Index/worker')}">工作人员管理</a>
			</li>
			<li class="active">编辑工作人员</li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<form  class="form-horizontal" method="post" action="{pigcms{:U('Index/worker_edit', array('wid' => $worker['wid']))}">
						<div class="tab-content">
							<div class="form-group">
								<label class="col-sm-1"><label for="name">姓名</label></label>
								<input class="col-sm-2" size="20" name="name" id="name" type="text" value="{pigcms{$worker['name']}"/>
							</div>
							<div class="form-group">
								<label class="col-sm-1"><label for="phone">手机号码</label></label>
								<input class="col-sm-2" size="20" name="phone" id="phone" type="text" value="{pigcms{$worker['phone']}"/>
								<span class="col-sm-5" style="padding-top: 3px">此手机号需与注册时的手机号一致，才能开门成功。</span>
							</div>
                          
							 <div class="form-group">
								<label class="col-sm-1">职务类型</label>
								<select name="type">
								<volist name="worker_name" id="vo">
									<option value="{pigcms{$key}"  <if condition="$worker['type'] eq $key">selected</if>>{pigcms{$vo}</option>
								</volist>
								</select>
						
								
								</label>
							</div> 
							<div class="form-group">
								<label class="col-sm-1">是否可以开门</label>
								
								<label style="padding-left:0px;padding-right:20px;">
									<input type="radio"  class="ace" value="1" name="open_door"  <if condition="$worker.open_door eq '1'">checked</if>>
									
									<span style="z-index: 1" class="lbl">可以</span>
									
								</label>
								<label style="padding-left:0px;padding-right:20px;">
									<input type="radio"  class="ace" value="0" name="open_door" <if condition="$worker.open_door eq '0'">checked</if>>
									
									<span style="z-index: 1" class="lbl">不可以</span>
									
								</label>
								
								</label>
							</div>
							<if condition="$worker['openid']">
	                            <div class="form-group">
									<label class="col-sm-1">状态</label>
									<label style="padding-left:0px;padding-right:20px;"><input type="radio" class="ace" value="1" name="status" <if condition="$worker['status'] eq 1">checked</if>><span style="z-index: 1" class="lbl">正常</span></label>
									<label style="padding-left:0px;"><input type="radio" class="ace" name="status" <if condition="$worker['status'] eq 0">checked</if>><span style="z-index: 1" class="lbl">关闭</span></label>
								</div>
							</if>
						</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<if condition="in_array(6,$house_session['menus'])">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
									<else/>
									<button class="btn btn-info" type="submit" disabled="disabled">
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
									</if>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<include file="Public:footer"/>