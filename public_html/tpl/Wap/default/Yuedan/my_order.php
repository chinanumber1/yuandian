<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>服务订单</title>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/my_fabu.css"/>
    <script src="{pigcms{$static_path}yuedan/js/jquery-1.9.1.min.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
</head>
<body>
	<header>
		<a href="{pigcms{:U('my_index')}" class="ft"></a>
		<span>服务订单</span>
	</header>
	<section class="list_change">
		<div>
			<a class="<if condition="$_GET['type'] neq 1">active</if>" href="{pigcms{:U('my_order')}"><span>我购买的</span></a>
			<a class="<if condition="$_GET['type'] eq 1">active</if>" href="{pigcms{:U('my_order',array('type'=>1))}"><span >我出售的</span></a>
		</div>
	</section>
	<div class="personality fuwu">
		<if condition="$_GET['type'] eq 1">
			<volist name="orderList" id="vo">
				<div class="sevice after">
					<a href="{pigcms{:U('order_details',array('order_id'=>$vo['order_id']))}">
					<div class="top">
						<img src="{pigcms{$vo.listimg}"/>
						<ul>
							<li>{pigcms{$vo.title}</li>
							<li>￥{pigcms{$vo.price}/{pigcms{$vo.unit}</li>
						</ul>
						<!-- <p class="sitShi">剩<span>00:00:00</span>后自动接受服务</p> -->
					</div>
					</a>
					<!-- 订单状态 1正常，2已支付，3以服务，4订单完成，5完成已评价，6已关闭 -->
					<div class="bottom rg" style="width: 85%;">
					<if condition="$authentication_status eq 2">
						<if condition="$vo['status'] eq 4">
							<span>待评价</span>
						<elseif condition="$vo['status'] eq 3"/>
							<span>待确认</span>
						<elseif condition="$vo['status'] eq 1"/>
							<span >待支付</span>
						<elseif condition="$vo['status'] eq 2"/>
							<!-- <button style="margin-right: 0px;" type="button" onclick="confirmService('{pigcms{$vo.order_id}',7)">开始服务</button> -->

							<select name="" onchange="sell_changeStatus(this.value,this,{pigcms{$vo.order_id})" id="changeStatus">
								<option value="">接受or拒绝</option>
								<option value="9">接受</option>
								<option value="10">拒绝</option>
							</select>
						<elseif condition="$vo['status'] eq 9"/>
							<button style="margin-right: 0px;" type="button" onclick="confirmService('{pigcms{$vo.order_id}',7)">开始服务</button>
						<elseif condition="$vo['status'] eq 2"/>
							<button style="margin-right: 0px;" type="button" onclick="confirmService('{pigcms{$vo.order_id}',7)">开始服务</button>

						<elseif condition="$vo['status'] eq 5"/>
							<span>已评价</span>
						<elseif condition="$vo['status'] eq 6"/>
							<span>订单已取消</span>
						<elseif condition="$vo['status'] eq 7"/>
							<button style="margin-right: 0px;" type="button" onclick="confirmService('{pigcms{$vo.order_id}',3)">确认服务</button>
						</if>
							<button style="margin-right: 0px;" type="button" onclick="message({pigcms{$vo['order_id']})">留言</button>
					<else/>
						<a href="{pigcms{:U('Yuedan/authentication')}"><button style="color: red;">请先认证</button></a>
					</if>

						
					</div>
				</div>
			</volist>

			<script>
				function sell_changeStatus(val,obj,order_id){
					if(val == ''){
						return false;
					}
			        var confirm_service_url = "{pigcms{:U('Yuedan/confirm_service')}";
	                $.post(confirm_service_url,{'order_id':order_id,status:val},function(data){
	                    if(data.error == 1){
	                        layer.open({
	                            content: data.msg
	                            ,btn: ['确定']
	                            ,yes: function(index){
	                                location.href = location.href;
	                                layer.close(index);
	                            }
	                        });
	                    }else{
	                        layer.open({
	                            content: data.msg
	                            ,btn: ['确定']
	                        });
	                    }
	                },'json')


				}
			</script>
		<else/>
			<volist name="orderList" id="vo">
				<div class="sevice after">
					<a href="{pigcms{:U('order_details',array('order_id'=>$vo['order_id']))}">
					<div class="top">
						<img src="{pigcms{$vo.listimg}"/>
						<ul>
							<li>{pigcms{$vo.title}</li>
							<li>￥{pigcms{$vo.price}/{pigcms{$vo.unit}</li>
						</ul>
						<p class="sitTime">剩<span >200</span>秒后自动接受服务</p>
					</div>
					</a>
					<div class="bottom rg" style="width: 85%;">
						<if condition="$vo['status'] eq 4">
							<a href="{pigcms{:U('comment',array('order_id'=>$vo['order_id']))}"><button type="button" style="margin-right: 0px;">评价</button></a>
						<elseif condition="$vo['status'] eq 3"/>
							<button style="margin-right: 0px;" type="button" onclick="confirmService('{pigcms{$vo.order_id}',4)">确认服务</button>
						<elseif condition="$vo['status'] eq 1"/>
							<button style="margin-right: 0px;" type="button">待支付</button>
						<elseif condition="$vo['status'] eq 2"/>
							<span >待服务</span>
							<!-- <button style="margin-right: 0px;" onclick="cancelOrder({pigcms{$vo.order_id},{pigcms{$vo.is_free},{pigcms{$vo.cancel_proportion},{pigcms{$vo.cancel_time})" type="button">接受or拒绝</button> -->
							<select name="" onchange="alert(1)" id="changeStatus">
								<option value="">接受or拒绝</option>
								<option value="">接受</option>
								<option value="">拒绝</option>
							</select>
						<elseif condition="$vo['status'] eq 5"/>
							<span>已评价</span>
						<elseif condition="$vo['status'] eq 6"/>
							<span>订单已取消</span>
						<elseif condition="$vo['status'] eq 7"/>
							<span>服务中</span>
						</if>
						<button style="margin-right: 0px;" type="button" onclick="message({pigcms{$vo['order_id']})">留言</button>
					</div>
				</div>
			</volist>
		</if>
		
		
	</div>
<script>
	function cancelOrder(order_id,is_free,cancel_proportion,cancel_time){
		if(is_free == 1){
			var content = '下单时间如果超过'+cancel_time+'小时系统会扣除'+cancel_proportion+'%的手续费,';
		}else{
			var content = '免费取消，';
		}
		layer.open({
            content: content+'确定要取消服务吗？'
            ,btn: ['确定', '取消']
            ,yes: function(index){
                var cancel_order_url = "{pigcms{:U('Yuedan/cancel_order')}";
                $.post(cancel_order_url,{'order_id':order_id},function(data){
                    if(data.error == 1){
                        layer.open({
                            content: data.msg
                            ,btn: ['确定']
                            ,yes: function(index){
                                location.href = location.href;
                                layer.close(index);
                            }
                        });
                    }else{
                        layer.open({
                            content: data.msg
                            ,btn: ['确定']
                        });
                    }
                },'json')
            }
        });
	}

	function message(order_id){
		location.href = "{pigcms{:U('message')}&order_id="+order_id;
	}

	function confirmService(order_id,status){

		if(status == 7){
			var content = "确认要开始服务吗？";
		}else{
			var content = "确定已完成服务内容？";
		}
        layer.open({
            content: content
            ,btn: ['确定', '取消']
            ,yes: function(index){
                var confirm_service_url = "{pigcms{:U('Yuedan/confirm_service')}";
                $.post(confirm_service_url,{'order_id':order_id,status:status},function(data){
                    if(data.error == 1){
                        layer.open({
                            content: data.msg
                            ,btn: ['确定']
                            ,yes: function(index){
                                location.href = location.href;
                                layer.close(index);
                            }
                        });
                    }else{
                        layer.open({
                            content: data.msg
                            ,btn: ['确定']
                        });
                    }
                },'json')
            }
        });
    }
    //转化为秒
    var valTime=$('.sitTime span').text();
    settime(valTime);
   
    function settime(val) { 
		 setTimeout(function() { 
			if (val <= 0) {  //结束后触发事件
			
			} else { 
				val--; 
				$('.sitTime span').text(val);
				 setTimeout(function() { 
					settime(val);
				},1000) 
			} 
		},1000);
		
	} 

	//转化为时分秒
	var rs=10;
	setShi(rs);
	function setShi(val) { 
		 setTimeout(function() { 
			if (val <= 0) { //结束后触发事件
				// alert(1);
			} else { 
				val--; 
				secondToDate(val);
				setTimeout(function() { 
					setShi(val);
				},1000) 
			} 
		},1000);
		
	} 

	function secondToDate(result) {
        var h = Math.floor(result / 3600);
        var m = Math.floor((result / 60 % 60));
        var s = Math.floor((result % 60));
        var  results='';
        if(m<10){
        	m='0'+m;
        }
        if(s<10){
        	s='0'+s;
        }
       results= h + ":" + m + ":" + s ;
        $('.sitShi span').text(results);
    }
</script>
</body>
</html>