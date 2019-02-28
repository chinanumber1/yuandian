<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Wap_around/index')}" class="on">首页模块列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Wap_around/add')}','添加导航',500,240,true,false,false,addbtn,'add',true);">添加模块</a>
			
				</ul>
			</div>
			<form name="myform" id="myform" action="" method="post">
		
					<span style="    float: left;margin: 11px 0px 8px 8px;">WAP首页附近显示状态：</span>
					<span class="cb-enable" style="margin: 8px 0px 8px 8px;"><label class="cb-enable <php>if($config['wap_around_show_type']==1){</php>selected<php>}</php>"><span>显示</span><input type="radio" name="show_status" value="1" checked="checked" /></label></span>
					<span class="cb-disable  " style="margin: 8px 8px 8px 0px;"><label class="cb-disable <php>if($config['wap_around_show_type']==0){</php>selected<php>}</php>"><span>隐藏</span><input type="radio" name="show_status" value="0" /></label>
					
					</span>
					<em tips="开启则前台显示，反之则不显示" class="notice_tips" ></em>
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col width="180" align="center"/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>排序</th>
								<th>编号</th>
								<th>名称</th>
								<th>描述</th>
								<th>链接地址</th>
								<th>图片(以下为强制小图，点击图片查看大图)</th>
								<th class="textcenter">最后操作时间</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($list)">
								<volist name="list" id="vo">
									<tr>
										<td>{pigcms{$vo.sort}</td>
										<td>{pigcms{$vo.id}</td>
										<td>附近{pigcms{$alias_name[$vo['name']]}</td>
										<td>{pigcms{$vo.des}</td>
										<td><a href="{pigcms{$vo.url}" target="_blank">访问链接</a></td>
										<td>
											<if condition="$vo['pic']">
												<img src="{pigcms{$config.site_url}/upload/wap/{pigcms{$vo.pic}" style="width:50px;height:50px;" class="view_msg"/>
											<else/>
												没有图片
											</if>
										</td>
										<td class="textcenter">{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}</td>
										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Wap_around/edit',array('id'=>$vo['id'],'frame_show'=>true))}','查看信息',480,330,true,false,false,false,'add',true);">查看</a> | <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Wap_around/edit',array('id'=>$vo['id']))}','编辑信息',480,330,true,false,false,editbtn,'add',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.id}" url="{pigcms{:U('Wap_around/del')}">删除</a></td>
									</tr>
								</volist>
							<else/>
								<tr><td class="textcenter red" colspan="8">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
		<style>
			.tips_img{
				margin-top: 9px !important;
    float: left;

			}
		</style>
		
		<script>
			$(function(){
				$('input[name="show_status"]').click(function(){
					$.post('{pigcms{:U('hide')}',{hide:$(this).val()},function(){
						
					});
				});
			});
		</script>
<include file="Public:footer"/>