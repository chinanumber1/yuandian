<include file="Public:header"/>		<div class="mainbox">			<div id="nav" class="mainnav_title">				<ul>					<a href="{pigcms{:U('Goods/index')}" class="on">【{pigcms{$category['name']}】分类下的轮播图列表</a>|					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Goods/banner_add', array('cat_id' => $category['id']))}','添加轮播图',480,260,true,false,false,addbtn,'add',true);">添加轮播图</a>				</ul>			</div>			<form name="myform" id="myform" action="" method="post">				<div class="table-list">					<table width="100%" cellspacing="0">						<colgroup>							<col/>							<col/>							<col width="180" align="center"/>						</colgroup>						<thead>							<tr>                                <th>图片(以下为强制小图，点击图片查看大图)</th>								<th>最后修改时间</th>								<th class="textcenter">操作</th>							</tr>						</thead>						<tbody>							<if condition="is_array($banners)">								<volist name="banners" id="vo">									<tr>                                        <td>                                            <img src="{pigcms{$config.site_url}/upload/goodsbanner/{pigcms{$vo.image}" style="width:300px;height:80px;" class="view_msg"/>                                        </td>                                        <td>{pigcms{$vo['dateline']|date="Y-m-d H:i:s",###}</td>                                        <td class="textcenter">                                        <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Goods/banner_edit',array('id' => $vo['id'], 'cat_id' => $vo['cat_id'], 'parentid' => $vo['fid']))}','编辑轮播图',480,260,true,false,false,editbtn,'edit',true);">编辑</a> |                                         <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.id}" url="{pigcms{:U('Goods/bannerDel')}">删除</a>                                        </td>									</tr>								</volist>							<else/>								<tr><td class="textcenter red" colspan="3">列表为空！</td></tr>							</if>						</tbody>					</table>				</div>			</form>		</div><include file="Public:footer"/>