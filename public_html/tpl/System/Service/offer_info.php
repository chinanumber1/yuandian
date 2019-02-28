<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Yuedan/examine_edit')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<if condition="$publishInfo eq ''">
				<div style="text-align: center; color: red; font-size: 20px; margin-top: 50px;">暂无订单信息</div>
			</if>
			<if condition="$publishInfo.catgory_type eq 1">
    			<volist name="publishInfo['cat_field']" id="vo">
                    <if condition="$vo['type'] eq 6">
                    	<tr>
							<td width="150">{pigcms{$vo.alias_name}：</td>
							<td>
								<label>{pigcms{$vo.value.address}</label>
							</td>
						</tr>
                    <elseif condition="$vo['type'] eq 3"/>
                    	<tr>
							<td width="150">{pigcms{$vo.alias_name}：</td>
							<td>
								<label><volist name="vo.value" id="vvo">
                                    <span style="display:inline; padding-right: 15px;" class="">{pigcms{$vvo}</span>
                                </volist></label>
							</td>
						</tr>
                    <elseif condition="$vo['type'] eq 2"/>
                    	<tr>
							<td width="150">{pigcms{$vo.alias_name}：</td>
							<td>
								<if condition="$vo['value'] eq 'inputdesc'">
	                                <label>{pigcms{$vo.desc}</label>
	                            <elseif condition="$vo['value'] eq 'time'"/>
	                                <label>{pigcms{$vo.date}{pigcms{$vo.minute}</label>
	                            <else/>
	                                <label>{pigcms{$vo.value}</label>
	                            </if>
							</td>
						</tr>
                        
                    <elseif condition="$vo['type'] eq 4"/>
                    	<tr>
							<td width="150">{pigcms{$vo.alias_name}：</td>
							<td>
								<label>{pigcms{$vo.value.time_start} {pigcms{$vo.value.time_end}</label>
							</td>
						</tr>
                    <elseif condition="$vo['type'] eq 7"/>
                    	<tr>
							<td width="150">{pigcms{$vo.alias_name}：</td>
							<td>
								<label>起点：<span class="orange js_need_address address_start">{pigcms{$vo['value']['address_start']}</span>
                                &nbsp;&nbsp;&nbsp;终点：<span class="orange js_need_address address_end">{pigcms{$vo['value']['address_end']}</span></label>
							</td>
						</tr>
                    <else/>
                        <tr>
							<td width="150">{pigcms{$vo.alias_name}：</td>
							<td>
								<label>{pigcms{$vo.value}</label>
							</td>
						</tr>
                    </if>
                </volist>


        <elseif condition="$publishInfo.catgory_type eq 2"/>


        	<tr>
				<td width="150">商品要求：</td>
				<td>
					<label>{pigcms{$cat_field_info.goods_remarks}</label>
				</td>
			</tr>
			<tr>
				<td width="150">购买类型：</td>
				<td>
					<label><if condition="$cat_field_info.buy_type eq 1">就近购买<else/>指定地址</if></label>
				</td>
			</tr>

			<if condition="$cat_field_info.buy_type eq 2">
                <tr>
					<td width="150">指定地址：</td>
					<td>
						<label>{pigcms{$cat_field_info.address}</label>
					</td>
				</tr>
            </if>

			
			<tr>
				<td width="150">送达地址：</td>
				<td>
					<label>{pigcms{$cat_field_info.end_adress_name}</label>
				</td>
			</tr>
			<tr>
				<td width="150">送达时间：</td>
				<td>
					<label>预计在 {pigcms{$cat_field_info.arrival_time}（分钟） 内送达</label>
				</td>
			</tr>
			<tr>
				<td width="150">预估商品费用：</td>
				<td>
					<label>{pigcms{$cat_field_info.estimate_goods_price}</label>
				</td>
			</tr>

			<tr>
				<td width="150">基础配送费：</td>
				<td>
					<label>{pigcms{$cat_field_info.basic_distance_price}</label>
				</td>
			</tr>
			<tr>
				<td width="150">超出基础配送距离费用：</td>
				<td>
					<label>{pigcms{$cat_field_info.distance_price}</label>
				</td>
			</tr>
			<tr>
				<td width="150">小费：</td>
				<td>
					<label>{pigcms{$cat_field_info.tip_price}</label>
				</td>
			</tr>
			<tr>
				<td width="150">总价：</td>
				<td>
					<label>{pigcms{$cat_field_info.total_price}</label>
				</td>
			</tr>

			<if condition="$cat_field_info['img']">
                <tr>
					<td width="150">商品图片：</td>
					<td>
						<label><volist name="cat_field_info['img']" id="vo">
                            <!-- <a href="javascript:void(0);" onclick='showImg("{pigcms{$vo}")'><img src="{pigcms{$vo}" style="float:left; padding-left: 2px; width: 70px; height: 70px;" alt=""></a> -->
                            <img src="{pigcms{$vo}" style="width:70px; height: 70px;" class="view_msg"/>&nbsp;&nbsp;
                        </volist></label>
					</td>
				</tr>

            </if>

        <elseif condition="$publishInfo.catgory_type eq 3"/>
        	<tr>
				<td width="150">商品分类：</td>
				<td>
					<label>{pigcms{$cat_field_info.goods_catgory}</label>
				</td>
			</tr>
			<tr>
				<td width="150">物品重量：</td>
				<td>
					<label>{pigcms{$cat_field_info.weight}（KG）</label>
				</td>
			</tr>
			<tr>
				<td width="150">物品价值：</td>
				<td>
					<label>{pigcms{$cat_field_info.price}</label>
				</td>
			</tr>
			<tr>
				<td width="150">取件地址：</td>
				<td>
					<label>{pigcms{$cat_field_info.start_adress_name}</label>
				</td>
			</tr>
			<tr>
				<td width="150">收货地址：</td>
				<td>
					<label>{pigcms{$cat_field_info.end_adress_name}</label>
				</td>
			</tr>
			<tr>
				<td width="150">取件时间：</td>
				<td>
					<label><if condition="$cat_field_info['fetch_time']">{pigcms{$cat_field_info.fetch_time}<else/>立即取件</if></label>
				</td>
			</tr>
			<tr>
				<td width="150">备注：</td>
				<td>
					<label>{pigcms{$cat_field_info.remarks}</label>
				</td>
			</tr>
			<tr>
				<td width="150">重量费用：</td>
				<td>
					<label>{pigcms{$cat_field_info.weight_price}</label>
				</td>
			</tr>
			<tr>
				<td width="150">基础配送费：</td>
				<td>
					<label>{pigcms{$cat_field_info.basic_distance_price}</label>
				</td>
			</tr>
			<tr>
				<td width="150">超出基础配送费：</td>
				<td>
					<label>{pigcms{$cat_field_info.distance_price}</label>
				</td>
			</tr>
			<tr>
				<td width="150">小费：</td>
				<td>
					<label>{pigcms{$cat_field_info.tip_price}</label>
				</td>
			</tr>
			<tr>
				<td width="150">总价：</td>
				<td>
					<label>{pigcms{$cat_field_info.total_price}</label>
				</td>
			</tr>
			<if condition="$cat_field_info['img']">
				<tr>
					<td width="150">商品图片：</td>
					<td>
						<label>
							<volist name="cat_field_info['img']" id="vo">
								<img src="{pigcms{$vo}" style="width:70px; height: 70px;" class="view_msg"/>&nbsp;&nbsp;
	                        </volist>
                        </label>
					</td>
				</tr>
			</if>
        </if>

		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="审核" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
	<script>
    $(document).ready(function(){
        $('.show_bigimage').click(function(){
            window.top.art.dialog({
    			padding: 0,
    			title: '大图',
    			content: '<img src="'+$(this).attr('src')+'" />',
    			lock: true
    		});
		});
    });
	</script>
<include file="Public:footer"/>
