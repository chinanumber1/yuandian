<include file="Public:header"/>		<div class="mainbox">			<div id="nav" class="mainnav_title">				<ul>					<a href="{pigcms{:U('Service/offer_list')}" class="on">订单列表</a>				</ul>			</div>			<table class="search_table" width="100%">				<tr>					<td>						<form action="{pigcms{:U('Service/offer_list')}" method="get">							<input type="hidden" name="c" value="Service"/>							<input type="hidden" name="a" value="offer_list"/>							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>							<select name="searchtype"><!--                                <option value="offer_id" <if condition="$_GET['searchtype'] eq 'offer_id'">selected="selected"</if>>编号</option>-->                                <option value="order_sn" <if condition="$_GET['searchtype'] eq 'order_sn'">selected="selected"</if>>订单编号</option>								<option value="nickname" <if condition="$_GET['searchtype'] eq 'nickname'">selected="selected"</if>>用户昵称</option>								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>用户手机</option>							</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;							<font color="#000">日期筛选：</font>							<input type="text" class="input-text" name="start_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.start_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   							<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>														状态:							<select name="status">								<option value="" <if condition="$_GET['status'] eq ' '">selected="selected"</if>>全部</option>								<option value="2" <if condition="$_GET['status'] eq '2'">selected="selected"</if>>待服务</option>								<option value="3" <if condition="$_GET['status'] eq '3'">selected="selected"</if>>待确认</option>								<option value="4" <if condition="$_GET['status'] eq '4'">selected="selected"</if>>订单完成</option>								<option value="5" <if condition="$_GET['status'] eq '5'">selected="selected"</if>>用户退款</option>								<option value="6" <if condition="$_GET['status'] eq '6'">selected="selected"</if>>退款成功</option>								<option value="7" <if condition="$_GET['status'] eq '7'">selected="selected"</if>>评价成功</option>								<option value="8" <if condition="$_GET['status'] eq '8'">selected="selected"</if>>等待取货</option>								<option value="9" <if condition="$_GET['status'] eq '9'">selected="selected"</if>>配送中</option>								<option value="10" <if condition="$_GET['status'] eq '10'">selected="selected"</if>>用户取消</option>								<option value="11" <if condition="$_GET['status'] eq '11'">selected="selected"</if>>服务过期</option>                                <option value="12" <if condition="$_GET['status'] eq '12'">selected="selected"</if>>过期退款</option>                                <option value="13" <if condition="$_GET['status'] eq '13'">selected="selected"</if>>退款失败</option>							</select>							<input type="submit" style="margin-right:20px;" value="查询" class="button"/>						</form>											</td>				</tr>			</table>			<form name="myform" id="myform" action="" method="post">				<div class="table-list">					<table width="100%" cellspacing="0">						<colgroup>							<col/>							<col/>							<col/>							<col/>							<col/>						</colgroup>						<thead>							<tr><!--                                <th>编号</th>-->                                <th>订单编号</th>								<th>用户</th>								<th>服务商</th>								<th>分类</th>								<th>价格</th>								<th>时间</th>								<th>状态</th>								<th class="textcenter">订单详情</th>								<th class="textcenter">留言记录</th>								<th class="textcenter">操作</th>							</tr>						</thead>						<tbody>							<if condition="is_array($serviceOfferList)">								<volist name="serviceOfferList" id="vo">									<tr><!--                                        <td>{pigcms{$vo.offer_id}</td>-->                                        <td>{pigcms{$vo.order_sn}</td>										<td>{pigcms{$vo.nickname}</td>										<td>{pigcms{$vo.provider_name}</td>										<td>{pigcms{$vo.cat_name}</td>										<td>{pigcms{$vo.price}</td>										<td>{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}</td>										<td>                                            <if condition="$vo.offer_status eq 5">用户申请退款                                                <elseif condition="$vo.status eq 2"/>已支付待服务                                                <elseif condition="$vo.status eq 3"/>已服务待确认                                                <elseif condition="$vo.status eq 4"/>订单完成                                                <elseif condition="$vo.status eq 5"/>用户申请退款                                                <elseif condition="$vo.status eq 6"/>退款成功                                                <elseif condition="$vo.status eq 7"/>订单完成                                                <elseif condition="$vo.status eq 8"/>等待取货                                                <elseif condition="$vo.status eq 9"/>配送中                                                <elseif condition="$vo.status eq 10"/>用户已取消                                                <elseif condition="$vo.status eq 11"/>服务过期                                                <elseif condition="$vo.status eq 12"/>过期退款                                                <elseif condition="$vo.status eq 13"/>退款失败                                            </if>										</td>										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Service/offer_info',array('offer_id'=>$vo['offer_id'],'frame_show'=>true))}','查看订单信息',600,600,true,false,false,false,'detail',true);">查看</a></td>										<td class="textcenter">											<if condition="$vo['catgory_type'] eq 1">												<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Service/offer_message',array('offer_id'=>$vo['offer_id'],'frame_show'=>true))}','查看留言记录',600,600,true,false,false,false,'detail',true);">查看</a>											<else/>												--											</if>																					</td>										<td class="textcenter">                                            <if condition="$vo.offer_status eq 5">                                                <a href="javascript:void(0);" onclick='refundReply("{pigcms{$vo.offer_id}")'>服务退款</a> |                                                <a href="javascript:void(0);" onclick='offerConfirm("{pigcms{$vo.offer_id}")'>完成服务</a>                                                <elseif condition="$vo.status eq 2"/>                                                <a href="javascript:void(0);" onclick='refundReply("{pigcms{$vo.offer_id}")'>服务退款</a>                                                <elseif condition="$vo.status eq 3"/>                                                <a href="javascript:void(0);" onclick='refundReply("{pigcms{$vo.offer_id}")'>服务退款</a> |                                                <a href="javascript:void(0);" onclick='offerConfirm("{pigcms{$vo.offer_id}")'>完成服务</a>                                                <elseif condition="$vo.status eq 4"/>                                                订单完成                                                <elseif condition="$vo.status eq 5"/>                                                <a href="javascript:void(0);" onclick='refundReply("{pigcms{$vo.offer_id}")'>服务退款</a> |                                                <a href="javascript:void(0);" onclick='offerConfirm("{pigcms{$vo.offer_id}")'>完成服务</a>                                                <elseif condition="$vo.status eq 6"/>                                                退款成功                                                <elseif condition="$vo.status eq 7"/>                                                订单完成                                                <elseif condition="$vo.status neq 1 && $vo.status neq 10 && $vo.status neq 12"/>                                                <a href="javascript:void(0);" onclick='refundReply("{pigcms{$vo.offer_id}")'>服务退款</a>                                            </if>										</td>									</tr>								</volist>								<tr><td class="textcenter pagebar" colspan="10">{pigcms{$pagebar}</td></tr>							<else/>								<tr><td class="textcenter red" colspan="10">列表为空！</td></tr>							</if>						</tbody>					</table>				</div>			</form>		</div>				<script>            function refundReply(offer_id){                var refund_reply_url = "{pigcms{:U('Service/refund_reply')}";                var status = 6;                $.post(refund_reply_url,{'offer_id':offer_id,'status':status},function(data){                    if(data.error == 1){                        alert(data.msg);                        location.reload();                    }else{                        alert(data.msg);                    }                },'json')            }            function offerConfirm(offer_id){		        var user_confirm_service_url = "{pigcms{:U('Service/confirm_service')}";		        $.post(user_confirm_service_url,{'offer_id':offer_id},function(data){		            if(data.error == 1){		                alert(data.msg);		                location.reload();		            }else{		                alert(data.msg);		            }		        },'json');		    }        </script><include file="Public:footer"/>