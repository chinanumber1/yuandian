<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}meal/css/datePicker.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}meal/css/common1.css?t=594897597511" media="all">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}meal/css/color1.css" media="all">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}meal/css/nav.css" media="all">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}meal/css/mobiscroll_min.css" media="all">
<script type="text/javascript" src="{pigcms{$static_path}meal/js/jquery_min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}meal/js/mobiscroll_min.js"></script>
<script src="{pigcms{$static_path}layer/layer.m.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}meal/js/info.js?t=1"></script>
<title>{pigcms{$store['name']}</title>
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
<meta name="Keywords" content="">
<meta name="Description" content="">
<!-- Mobile Devices Support @begin -->
<meta content="telephone=no, address=no" name="format-detection">
<meta name="apple-mobile-web-app-capable" content="yes"> <!-- apple devices fullscreen -->
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<!-- Mobile Devices Support @end -->
<style>
.bookinfo .info .line{padding:0px;}
.bookinfo .info .line:before, .bookinfo .info .line:after {
    display: table;
    content: " ";
}
.bookinfo .info .line:after {
	clear:both;
}
</style>
</head>
<body onselectstart="return true;" ondragstart="return false;">
<script>
    var config = { 
        table_fee:0,//包房服务费 0 不需要  大于是需要包房费 
        dishes_status:{pigcms{$is_reserve},//0新增预订 1先点菜后预订位置下一步操作 2修改预订  
        order_sn:'',
        utype:1,
        businessHours:[{"stime":"00:00","etime":"23:59"}],//营业时间  
        editInfo:{"date":"{pigcms{$date}", "time":"{pigcms{$time}", "tel":"{pigcms{$user_info['phone']}", "name":"{pigcms{$user_info['name']}", "sex":null, "seattype":'{pigcms{$tableid}', "mark":""},//修改预订信息
        postURL: "{pigcms{:U('Food/saveorder', array('mer_id' => $mer_id, 'store_id' => $store_id))}",//ajax发送路径
        GetTableURL: "{pigcms{:U('Food/get_table', array('mer_id' => $mer_id, 'store_id' => $store_id))}",//ajax发送路径
        max_seat_num:50,
        seat_num_default:2
    }; 
</script>
<div data-role="container" class="container bookinfo">
    <header data-role="header">	
    </header>
    <section data-role="body">
        <div class="info">
			<if condition="$is_reserve">
			   <div class="line icons">
	                <span>预交定金</span>
	                <label style="text-align:right;"><strong style="color:#FF4907">{pigcms{$reserve_money}</strong>元</label>
				</div>
	         </if>
			<if condition="!empty($total_money) AND $totalnum gt 0">
			   <div class="line icons">
	                <span>购买情况</span>
	                <label>{pigcms{$totalnum}份商品，共<strong style="color:#FF4907">{pigcms{$total_money}</strong>元</label>
				</div>
	         </if>
         	
			<if condition="!empty($sorts_discout) AND empty($is_reserve)">
				<div class="line icons">
                <span>店铺折扣后的价格： <strong style="color:#FF4907">{pigcms{$store_discount_money}</strong></span>
				</div>
			</if>
         	
			<if condition="!empty($level_off)">
				<div class="line icons">
                <span>【{pigcms{$level_off['lname']}】优惠后价格： <strong style="color:#FF4907">{pigcms{$total_discount_money}</strong></span>
				</div>
			</if>
			
            <div class="line icons" <if condition="$is_reserve eq 0">style="display: none"</if>>
                <i></i>
				<span>预约日期</span>	
                <label><input class="select date" name="date" type="text" placeholder="请选择就餐日期" readonly="" id="mobiscroll1432347110712" value=""></label>							
            </div>
            <div class="line icons time" <if condition="$is_reserve eq 0">style="display: none"</if>>
                <i></i>
				<span>预约时间</span>
                <label id="select_time"><input class="select datetime" name="time" type="text" placeholder="请选择就餐时间" readonly="" id="mobiscroll1432347110713" value="10:11"></label>
				
			</div>
            <div class="line icons num">
                <i></i>
				<span>使用人数</span>
                <label>
                    <div class="ui_select">
                        <span id="input_num">2</span>
                          <select name="num" id="select_num" class="">
                          <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option></select>
                    </div>                        
				</label>		
				
            </div>
            <div class="line icons status">
				<i></i>
				<span>选择位置</span>
                 <label>
                    <div class="ui_select">
                        <span id="input_type">不限</span>
                         <select name="seat" class="type" id="select_type">
                            <option value="0">不限</option>
                         	<volist name="tables" id="table">
                            <option value="{pigcms{$table['pigcms_id']}" <if condition="$table['pigcms_id'] eq $tableid">selected</if>>{pigcms{$table['name']}({pigcms{$table['num']}人座)</option>
                            </volist>
                        </select>
                    </div>
                </label>		
				
            </div>
            <if condition="$store['deposit'] gt 0 AND false">
            <div class="line name">
            	<div class="paytypediv">
                    <label><input type="radio" name="isdeposit" value="1" <if condition="$is_reserve eq 1"> checked="checked" </if> class="">支付定金【{pigcms{$store['deposit']}元】</label>
                    <if condition="$is_reserve eq 0">	
                    <label><input type="radio" name="isdeposit" value="0" checked="checked" class="">全额支付</label>
                    </if>
                </div>
            </div>
            </if>
        </div>
        
        <div class="info">
        	<if condition="$is_reserve eq 1">
            <div class="line">
                <input type="tel" name="tel" id="tel" placeholder="请输入您的手机号码" value="{pigcms{$user_info['phone']}">
            </div>
            <div class="line">
                <input type="text" name="address" id="address" placeholder="请输入您的地址" value="{pigcms{$user_info['adress']}">
            </div>
            <div class="line name">
                <input type="text" placeholder="请输入您的姓名" name="name" id="name" value="{pigcms{$user_info['name']}">
                <div class="sexdiv">							
                    <label><input type="radio" name="sex" value="0" id="sex" class="">女士</label>	
                    <label><input type="radio" name="sex" value="1" checked="checked" class="">先生</label>
                </div>						
            </div>
            </if>
            <div class="line textarea">
                <textarea name="remark" placeholder="您的其他需求说明" id="mark"></textarea>					
            </div>
        </div>
    </section>
    <footer data-role="footer">	
        <nav class="g_nav">
            <div>
            	<if condition="$deposit eq 0">
            	<a class="green left" id="pre" href="{pigcms{:U('Food/shop', array('mer_id' => $mer_id, 'store_id' => $store_id))}"><label>上一步</label></a>
            	<else />
                <a class="green left" id="pre" href="{pigcms{:U('Food/cart', array('mer_id' => $mer_id, 'store_id' => $store_id))}"><label>上一步</label></a>
                </if>
                <a class="orange right" onclick="submit_F()" id="next"><label>下一步</label></a>
            </div>				
        </nav>
    </footer>
</div>
<include file="kefu" />
{pigcms{$hideScript}
</body>
</html>