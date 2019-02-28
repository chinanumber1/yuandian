<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('express_service_list')}">功能库</a>
			</li>
			<li class="active"><a href="{pigcms{:U('express_analysis')}">快递统计</a></li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
        <div class="page-content-area">
            <div class="row">
                <div class="col-xs-12">
              
                	<form action="{pigcms{:U('Library/express_analysis')}" method="get">
						<input type="hidden" name="c" value="Library"/>
						<input type="hidden" name="a" value="express_analysis"/>
						
						
						快递公司:
						<select name="express_id">
							<option value="0" <if condition="!isset($_GET['express_id'])">selected="selected"</if>>所有快递</option>
							<volist name="express_list" id="vo">
								<option value="{pigcms{$vo.id}" <if condition="$_GET['express_id'] eq $vo['id']">selected="selected"</if>>{pigcms{$vo.name}</option>
							</volist>
						
						</select>
						<font color="#000">日期筛选：</font>
						<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>&nbsp;&nbsp;			   
						<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>&nbsp;&nbsp;	
					
						<input type="submit" value="查询" class="btn btn-success"/>　　
						
						<if condition="in_array(213,$house_session['menus'])">
						<a href="{pigcms{:U('Library/express_analysis',$_GET)}&export=1" class="btn btn-success" style="float:right;margin-right: 10px;">导出快递订单记录</a>
						<else/>
						<button class="btn btn-success" disabled="disabled">导出快递订单记录</button>
						</if>
					</form>
					 <div class="alert alert-info" style="margin:10px;">
					 快递数量:{pigcms{:count($list['list'])}
					</div>
                
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="10%">快递类型</th>
                                    <th width="10%">快递单号</th>
                                    <th width="10%">收件人手机号</th>
                                    <th width="15%">收件人地址</th>
									 <th width="5%">送件费用</th>
                                    <th width="10%">状态</th>
                                    <th width="10%">预约代送时间</th>
                                    <th width="10%">添加时间</th>
                                    <th class="button-column" width="15%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$list['list']">
                                    <volist name="list['list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.id}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.express_name}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.express_no}</div></td>
                                           <td><div class="tagDiv">{pigcms{$vo.phone}</div></td>
                                           <td><div class="tagDiv"><if condition="$vo.address neq '' AND $vo.floor_name neq ''">{pigcms{$vo.address}<else />{pigcms{$vo.floor_name}</if></div></td>
										   <td><div class="tagDiv">
										   <if condition="$vo.money gt 0">
                                            {pigcms{$vo['money']}元
											
											</if>
                                            </div></td>
                                            <td><div class="tagDiv">
                                            <if condition='$vo["status"] eq 0'>
                                            	<span class="red">未取件</span>
												<if condition="in_array(210,$house_session['menus'])">
										   		<a href="javascript:void(0)" class="chk_express" data-id="{pigcms{$vo['id']}">确认取件</a>
										   		</if>
                                            <elseif condition='$vo["status"] eq 1'/>
                                            	<span class="green">已取件（业主）</span>
                                            <else />
                                            	<span class="green">已取件（社区）</span>
                                            </if>
                                            </div></td>
											<td>
											 <if condition='$vo["send_time"] gt 0'>
												<div class="tagDiv">{pigcms{$vo.send_time|date="Y-m-d H:i",###}</div>
												 </if>
											</td>
                                            <td>
												<div class="tagDiv">{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}</div>
											</td>
                                            <td class="button-column">
                                                <a style="width: 60px;" class="label label-sm label-info handle_btn" title="详情" href="{pigcms{:U('express_detail',array('id'=>$vo['id']))}">详情</a>&nbsp;&nbsp;&nbsp;&nbsp;
												<if condition="in_array(211,$house_session['menus'])">
                                                <a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onClick="if(confirm('确认删除该条信息？')){location.href='{pigcms{:U('express_del',array('id'=>$vo['id']))}'}">删除</a>
                                            	</if>
                                           </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="10" >没有任何快递。</td></tr>
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
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
function express_add(){
	window.location.href = "{pigcms{:U('express_add')}";
}


$('#search').live('click',function(){
	var search_url = "{pigcms{:U('express_search')}"
			art.dialog.open(search_url,{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'搜索快递',
				padding: 0,
				width: 720,
				height: 400,
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
		
		
		$('.handle_btn').live('click',function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'',
				padding: 0,
				width: 720,
				height: 520,
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
		
		
		$('.chk_express').click(function(){
			var express_edit_url = "{pigcms{:U('express_edit')}";
			var id = $(this).data('id');
			var status = 2;
			$.post(express_edit_url,{'id':id,'status':status},function(data){
					if(data['status']){
						alert(data['msg']);
						location.reload();
					}else{
						alert(data['msg']);
					}
			},'json')
		});
</script>

<include file="Public:footer"/>