<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('service_slide')}">便民服务</a>
			</li>
			<li class="active">便民页面幻灯片</li>
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
                
                <if condition='!$has_service_slide'>
                    <div style="margin-top:10px; cursor:pointer" class="alert alert-danger" onClick="window.open('{pigcms{:U('Index/index')}')">
                        <button data-dismiss="alert" class="close" type="button"><i class="ace-icon fa fa-times"></i></button>
                        还未开启&nbsp;&nbsp;<span style="font-weight:bold">便民页面幻灯片</span>&nbsp;&nbsp;功能，请先到&nbsp;&nbsp;<span style="font-weight:bold">基本信息管理 - 基本信息设置 - 便民配置&nbsp;&nbsp;</span>开启相应配置。
                    </div>
                <else />
                	<button class="btn btn-success" onclick="slide_add()" <if condition="!in_array(245,$house_session['menus'])">disabled="disabled"</if>>添加导航</button>
            	</if>
                
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="10%">名称</th>
                                    <th width="20%">链接地址</th>
                                    <th width="25%">图片</th>
                                    <th width="5%">状态</th>
                                    <th width="5%">排序</th>
                                    <th width="10%">最后操作时间</th>
                                    <th class="button-column" width="20%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$list">
                                    <volist name="list['list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.id}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.name}</div></td>
                                            <td><div class="tagDiv"><a href="{pigcms{$vo.url}" target="_blank">查看链接</a></div></td>
                                            <td><div class="tagDiv"><img src="{pigcms{$config.site_url}/upload/service/{pigcms{$vo.pic}" width="140px" height="58px" /></div></td>
                                            <td><div class="tagDiv">
                                            <if condition='$vo["status"] eq 0'>
                                            	<span class="red">禁止</span>
                                            <else />
                                            	<span class="green">开启</span>
                                            </if>
                                            </div></td>
                                            
                                            <td>
												<div class="tagDiv">{pigcms{$vo.sort}</div>
											</td>
                                            
                                            <td>
												<div class="tagDiv">{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}</div>
											</td>
                                            
                                            <td class="button-column">
                                                <a style="width: 60px;" class="label label-sm label-info" title="编辑" href="{pigcms{:U('service_slide_edit',array('id'=>$vo['id']))}">编辑</a>
                                                <if condition="in_array(247,$house_session['menus'])">
                                                <a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onClick="if(confirm('确认删除该条信息？')){location.href='{pigcms{:U('slider_del',array('id'=>$vo['id']))}'}">删除</a>
                                                </if>
                                           </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="8" >没有任何幻灯片。</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$list.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function slide_add(){
	window.location.href = "/shequ.php?g=House&c=Service&a=service_slide_add";
}
</script>
<include file="Public:footer"/>