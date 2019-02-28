<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-home home-icon"></i>
			<li class="active">首页</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-sm-12 infobox-container" style="margin-top: 100px;">
					<!-- <div style="background:#f2dede;padding:5px;margin-bottom:30px;">
						<div style="background:#f2dede;padding:5px;">
							<div class="" style="font-size:14px;" id="scrollText">
								<volist name="news_list" id="vo">
									<if condition="$vo['is_top']">
										<div style="float:left;">
											<span style="padding-right:30px;color:#a94442;">
												<i class="ice-icon fa fa-volume-up bigger-130"></i>
												<a href="{pigcms{:U('Index/news',array('id'=>$vo['id']))}">{pigcms{$vo.title}</a>
											</span>
										</div>
									</if>
								</volist>
							</div>
						</div>
					</div>
					 -->

					<if condition="in_array(37,$house_session['menus'])">
					<a href="{pigcms{:U('Unit/import_village')}">
					<else/>
					<a href="javascript:layer.alert('您没有权限查看，请联系管理员！');">
					</if>
						<div class="infobox" style="background:#81d2cf; ">
							<div class="infobox-data" style="padding-left:0px;width:100%;text-align:center;">
								<span class="infobox-data-number" style="color: white;">{pigcms{$room_count}</span>
								<div class="infobox-content" style="color: white;">房间数量</div>
							</div>
						</div>
					</a>

					<if condition="in_array(90,$house_session['menus'])">
					<a href="{pigcms{:U('User/index')}">
					<else/>
					<a href="javascript:layer.alert('您没有权限查看，请联系管理员！');">
					</if>
					<div class="infobox" style="background:#7cbae5;">
						<div class="infobox-data" style="padding-left:0px;width:100%;text-align:center;">
							<span class="infobox-data-number">{pigcms{$user_count}</span>
							<div class="infobox-content">业主数量</div>
						</div>
					</div>
					</a>

					<if condition="in_array(55,$house_session['menus'])">
					<a href="{pigcms{:U('Unit/vehicle_management')}">
					<else/>
					<a href="javascript:layer.alert('您没有权限查看，请联系管理员！');">
					</if>
					<div class="infobox" style="background:#cec0f4;">
						<div class="infobox-data" style="padding-left:0px;width:100%;text-align:center;">
							<span class="infobox-data-number">{pigcms{$car_count}</span>
							<div class="infobox-content">车辆数量</div>
						</div>
					</div>
					</a>

					<if condition="in_array(45,$house_session['menus'])">
					<a href="{pigcms{:U('Unit/parking_management')}">
					<else/>
					<a href="javascript:layer.alert('您没有权限查看，请联系管理员！');">
					</if>
					<div class="infobox" style="background:#92bf77;">
						<div class="infobox-data" style="padding-left:0px;width:100%;text-align:center;">
							<span class="infobox-data-number">{pigcms{$position_count}</span>
							<div class="infobox-content">车位数量</div>
						</div>
					</div>
					</a>
					<div class="space-18"></div>
				</div>
				<div class="col-sm-12" style="height: 60px;"></div>

				<div class="col-sm-9 " style="margin-left: 13%">
					<div class="widget-box">
						<div class="widget-header widget-header-flat">
							<h4 class="lighter smaller" style="    margin-top: 10px;">
								<i class="ace-icon fa fa-star blue"></i>
								数据总览
							</h4>
						</div>

						<div class="tab-pane active" id="txtstore">
                            <table class="table table-striped table-bordered table-hover" style="text-align: center;margin-bottom: 0px;" width="80%">
                            	<?php if (!array_intersect(array(101,103,219,224,222,207,107),$house_session['menus'])){?>
                            		
                            		<tr>
                            			<td style="text-align: center;">暂无数据</td>                            		
                            		</tr>
                            	<?php } else { ?>
                                <thead>
                                    <tr>
										<if condition="in_array(101,$house_session['menus'])">
                                        <th width="5%">待审核业主</th> 
                                    	</if>
										<if condition="in_array(103,$house_session['menus'])">
                                        <th width="5%">待审核家属</th>
										<if condition="in_array(219,$house_session['menus'])">
                                        <th width="5%">待处理报修</th>
										<if condition="in_array(224,$house_session['menus'])">
                                        <th width="10%">待处理投诉建议</th>
										<if condition="in_array(222,$house_session['menus'])">
                                        <th width="8%">待处理水电煤气上报</th>
										<if condition="in_array(207,$house_session['menus'])">
                                        <th width="5%">待处理快递</th>
										<if condition="in_array(107,$house_session['menus'])">
                                        <th width="5%">待申请解绑</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
										<if condition="in_array(101,$house_session['menus'])">
                                       	<td onclick="javascript:window.location.href='{pigcms{:U('User/audit_index',array('status'=>2))}'" style="cursor: pointer;">{pigcms{$audit_user_count}</td>
                                    	</if>

										<if condition="in_array(103,$house_session['menus'])">
                                        <td onclick="javascript:window.location.href='{pigcms{:U('User/bind_audit_list',array('status'=>2))}'" style="cursor: pointer;">{pigcms{$audit_child_count}</td>
                                    	</if>
										<if condition="in_array(219,$house_session['menus'])">
                                        <td onclick="javascript:window.location.href='{pigcms{:U('Repair/index',array('status'=>1))}'" style="cursor: pointer;">{pigcms{$baoxiu_count}</td>
                                    	</if>
										<if condition="in_array(224,$house_session['menus'])">
                                        <td onclick="javascript:window.location.href='{pigcms{:U('Repair/village_suggest',array('status'=>1))}'" style="cursor: pointer;">{pigcms{$suggest_count}</td>
                                    	</if>
										<if condition="in_array(222,$house_session['menus'])">
                                        <td onclick="javascript:window.location.href='{pigcms{:U('Repair/water')}'" style="cursor: pointer;">{pigcms{$water_count}</td>
                                    	</if>
										<if condition="in_array(207,$house_session['menus'])">
                                       <td onclick="javascript:window.location.href='{pigcms{:U('Library/express_service_list')}'" style="cursor: pointer;">{pigcms{$express_count}</td>
                                    	</if>
										<if condition="in_array(107,$house_session['menus'])">
                                       <td onclick="javascript:window.location.href='{pigcms{:U('User/audit_unbind',array('status'=>1))}'" style="cursor: pointer;">{pigcms{$unbind_count}</td>
                                    	</if>
                                    </tr>
                                </tbody>
                            	<?php } ?>
                            </table>
                        </div>
					</div>
				</div>
				<!-- <div class="col-sm-6">
					<div class="alert alert-block alert-success">
						<button type="button" class="close" data-dismiss="alert">
							<i class="ace-icon fa fa-times"></i>
						</button>
						<p>欢迎大家联系系统管理员咨询或反馈。</p>
						<p>
							<a class="btn btn-sm btn-success" href="http://wpa.qq.com/msgrd?v=3&uin={pigcms{$config.site_qq}&site=qq&menu=yes" target="_blank">联系管理员QQ</a>
						</p>
					</div>
				</div> -->
			</div>
		</div>
	</div>
</div>


<style>
#scrollText div a{ color: #a94442;}
</style>
<include file="Public:footer"/>
