<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="" class="on">{pigcms{$_GET['name']}图片列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Village/img_add',array('village_id'=>$_GET['village_id']))}','添加小区',600,300,true,false,false,addbtn,'store_add',true);">添加图片</a>|
					<a href="{pigcms{:U('Village/index')}">小区列表</a>
					
				</ul>
			</div>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup>
							<col/>
							<col/>
							<col/>
							<col/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>排序</th>
								<th>标题</th>
								<th>地址</th>
								<th>类型</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="!empty($imgList)">
								<volist name="imgList" id="imgList">
									<tr>
										<td>{pigcms{$imgList.sort}</td>
										<td>{pigcms{$imgList.title}</td>
										<td>{pigcms{$imgList.url}</td>
										<td>{pigcms{$img_type[$imgList['img_type']]}</td>
										<td class="textcenter">
											<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Village/img_save',array('img_id'=>$imgList['img_id']))}','添加小区',600,300,true,false,false,addbtn,'store_add',true);">编辑</a>|
											<a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$imgList.img_id}" url="{pigcms{:U('Village/img_del')}">删除</a>
									  	</td>
									</tr>
								</volist>
							<else/>
								<tr><td class="textcenter red" colspan="5">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>