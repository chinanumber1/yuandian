<include file="Public:header"/>
<script type="text/javascript" src="{pigcms{$static_path}js/area_pca.js"></script>
<style>
.alert {
    font-size: 14px;
    border-radius: 0;
}

.alert-info {
    background-color: #d9edf7;
    /* border-color: #bce8f1; */
    color: #31708f;
}
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 4px;
}
</style>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Deliver/user')}">配送员管理</a>|
					<a href="{pigcms{:U('Deliver/reply')}">配送员评论列表</a>|
                    <a href="javascript:void(0);" class="on">配送员扔回订单列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('user_add')}','添加配送员',800,560,true,false,false,editbtn,'edit',true);">添加配送员</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
                        <form action="{pigcms{:U('Deliver/cancel')}" method="get" id="searchSubmit">
                            <input type="hidden" name="c" value="Deliver"/>
                            <input type="hidden" name="a" value="reply"/>
                                <if condition="$admin_area neq 3">筛选:
                                <div id="choose_pca" province_idss="{pigcms{$_GET.province_idss}" city_idss="{pigcms{$_GET.city_idss}" area_id="{pigcms{$_GET.area_id}" style="display:inline"></div>
                                </if>
                                <span>日期筛选：</span>
                                <div style="display:inline-block;">
                                    <select class='custom-date' id="time_value" name='select'>
                                        <option value='1' <if condition="$day eq 1">selected</if>>今天</option>
                                        <option value='7' <if condition="$day eq 7">selected</if>>7天</option>
                                        <option value='30' <if condition="$day eq 30">selected</if>>30天</option>
                                        <option value='180' <if condition="$day eq 180">selected</if>>180天</option>
                                        <option value='365' <if condition="$day eq 365">selected</if>>365天</option>
                                        <option value='custom' <if condition="$period">selected</if>>{pigcms{$period|default='自定义'}</option>
                                    </select>
                                </div>
                                <select name="nameType">
                                    <option value="0" <if condition="$_GET['nameType'] eq 0">selected</if> >配送员名称</option>
                                    <!--option value="1" <if condition="$_GET['nameType'] eq 1">selected</if> >用户名称</option-->
                                </select>
    							<input type="text" class="input-text" name="keyword" value="{pigcms{$_GET.keyword}"/>
    							
    							<input type="button" value="查询" class="button" id="searchButton"/>
                                
    							<a href="{pigcms{:U('Deliver/exportCancel', array('day' => $day, 'province_idss'=> $_GET['province_idss'], 'city_idss'=> $_GET['city_idss'], 'area_id'=> $_GET['area_id'], 'keyword'=> $keyword, 'period' => $period, 'nameType' => $_GET['nameType']))}" class="button" style="float:right;margin-right: 10px;">导出订单</a>
                        </form>
                        <!-- div class="alert alert-info" style="margin:10px 0;">配送订单总数：{pigcms{$count}&nbsp;&nbsp;&nbsp;综合评分：{pigcms{$meanScore}&nbsp;&nbsp;&nbsp;好评率：{pigcms{$goodPrecent}&nbsp;&nbsp;&nbsp;好评总数：{pigcms{$commentScore3}&nbsp;&nbsp;&nbsp;中评总数：{pigcms{$commentScore2}&nbsp;&nbsp;&nbsp;差评总数：{pigcms{$commentScore1}&nbsp;&nbsp;&nbsp;未评总数：{pigcms{$commentScore0}</div-->
					</td>
				</tr>
			</table>
				<div class="table-list">
					<table width="100%" cellspacing="0">
			
						<thead>
							<tr>
								<th>配送ID</th>
								<th>店铺名称</th>
								<th>配送员昵称</th>
								<th>配送员手机号</th>
								<th>扔回时间</th>
								<th>查看订单详情</th>
								
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($supply_info)">
								<volist name="supply_info"  id="vo">
									<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
										<td width="30">{pigcms{$vo.supply_id}</td>
										<td width="80">{pigcms{$vo.storename}</td>
                                        <td width="50">{pigcms{$vo.name}</td>
                                        <td width="80">{pigcms{$vo.phone}</td>
                                        <td width="50">{pigcms{$vo.dateline|date='Y-m-d H:i:s',###}</td>
										<td width="80">
										<if condition="$vo['item'] eq 3">
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Service/offer_info',array('publish_id' => $vo['order_id'],'frame_show'=>true))}','查看订单信息',600,600,true,false,false,false,'detail',true);">查看详情</a>
										<elseif condition="$vo['item'] eq 2" />
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/order_detail',array('order_id'=>$vo['order_id'],'frame_show'=>true))}','查看{pigcms{$config.shop_alias_name}订单详情',720,520,true,false,false,false,'detail',true);">查看详情</a>
										</if>
										</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="19">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="19">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
		</div>
<style>
.drp-popup{top:90px !important}
.deliver_search input{height: 20px;}
.deliver_search select{height: 20px;}
.deliver_search .mar_l_10{margin-left: 10px;}
.deliver_search .btn{height: 23px;line-height: 16px; padding: 0px 12px;}
</style>
<script type="text/javascript" src="{pigcms{$static_public}js/date-picker/index.js"></script>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/date-picker/index.css" />
<script>
$(document).ready(function(){
    $('#searchButton').click(function(){
        if($('#time_value option:selected').attr('value')=='custom'){
            $('#time_value option:selected').val($('#time_value option:selected').html());
        }
        $('form').submit();
    });
});
</script>
<include file="Public:footer"/>