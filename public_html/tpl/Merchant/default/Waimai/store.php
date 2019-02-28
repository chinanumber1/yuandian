<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-gear gear-icon"></i>
                <a href="{pigcms{:U('Waimai/index')}">店铺管理</a>
            </li>
            <li class="active">店铺相关设置</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <div class="row">
                <div class="col-xs-12">
                    <div class="tabbable">
                        <ul class="nav nav-tabs" id="myTab">                
                            <li class="active">
                                <a data-toggle="tab" href="#basicinfo">基本设置</a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#txtstore">店铺分类</a>
                            </li>
                        </ul>
                    </div>
                    <form enctype="multipart/form-data" class="form-horizontal" method="post">
                    <div class="tab-content">               
                        <div id="basicinfo" class="tab-pane active">
                            <input type='hidden' value='{pigcms{$store_id}' name='store_id'>
                                <div class="tab-content">
                                    <div id="basicinfo" class="tab-pane active">
                                    	<div class="form-group">
                                                <label class="col-sm-1"><label>营业状态</label></label>
                                                <span><label><input id='close_waimai' name="close_waimai" <if condition="$store['close'] eq '0' ">checked="checked"</if> value="0" type="radio"></label>&nbsp;<span>休息</span></span>&nbsp;
                                                <span><label><input id='close_waimai' name="close_waimai" <if condition="$store['close'] eq '1'" >checked="checked"</if> value="1" type="radio" ></label>&nbsp;<span>可预约</span></span>&nbsp;
                                                <span><label><input id='close_waimai' name="close_waimai" <if condition="$store['close'] eq '2' || !isset($store['close'])" >checked="checked"</if> value="2" type="radio" ></label>&nbsp;<span>正常营业</span></span>
                                        </div>
                                    	<div class="form-group">
                                                <label class="col-sm-1"><label>餐具费</label></label>
                                                <span><label><input id='tools_money_have' name="tools_money_have" <if condition="$store['tools_money_have'] eq '1' || !$store['tools_money_have']">checked="checked"</if> value="1" type="radio"></label>&nbsp;<span>收取</span>&nbsp;</span>
                                                <span><label><input id='tools_money_have' name="tools_money_have" <if condition="$store['tools_money_have'] eq '0'">checked="checked"</if> value="0" type="radio" ></label>&nbsp;<span>不收</span></span>
                                        </div>
                                        <div class="form-group">
                                                <label class="col-sm-1"><label>配送方式</label></label>
                                                <span><label><input id='deliver_type' name="deliver_type" <if condition="$store['deliver_type'] eq 0 ">checked="checked"</if> value="0" type="radio"></label>&nbsp;<span>{pigcms{$config['deliver_name']}</span>&nbsp;</span>
                                                <span><label><input id='deliver_type' name="deliver_type" <?php if($store['deliver_type']==1){?>checked="checked"<?php }?> value="1" type="radio" ></label>&nbsp;<span>自己配送</span></span>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-1">起送费</label>
                                            <input class="col-sm-2" size="30" name="start_send_money" id="js-start_send_money" value="<if condition="$store['start_send_money']">{pigcms{$store['start_send_money']}<else />0</if>" type="text" jstips='请输入起送费'/>
                                            <label class="col-sm-3">(元)</label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-1">配送费</label>
                                            <input class="col-sm-2" size="30" name="send_money" id="js-send_money" value="<if condition="$store['send_money']">{pigcms{$store['send_money']}<else />0</if>" type="text" jstips='请输入配送费'/>
                                            <label class="col-sm-3">(元)</label>
                                        </div>
                                         <div class="form-group">
                                            <label class="col-sm-1">配送范围</label>
                                            <input class="col-sm-2" size="30" name="send_range" id="js-send_range" value="<if condition="$store['send_range']">{pigcms{$store['send_range']}<else />5</if>" type="text" jstips='请输入配送范围'/>
                                            <label class="col-sm-3">(公里)</label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-1">免运送费</label>
                                            <input class="col-sm-2" size="30" name="total_money" id="js-total_money" value="<if condition="$store['total_money']">{pigcms{$store['total_money']}<else />0</if>" type="text" jstips='请输入免运送费'/>
                                            <label class="col-sm-3">满足多少金额免运送费(元)</label>
                                        </div>
                                        <div class="form-group">
                                                <label class="col-sm-1">开始接单时间</label>
                                                <input class="" size="30" name="start_time_1" id="start_time_1" value="<if condition="$store['start_time_1']">{pigcms{$store['start_time_1']}</if>" type="text" jstips='开始接单时间'/>
                                                至 <input class="" size="30" name="end_time_1" id="end_time_1" value="<if condition="$store['end_time_1']">{pigcms{$store['end_time_1']}</if>" type="text" jstips='接单结束时间'/>
                                        </div>
                                        <div class="form-group">
                                                <label class="col-sm-1">接受预约时间</label>
                                                <input class="" size="30" name="start_time_2" id="start_time_2" value="<if condition="$store['start_time_2']">{pigcms{$store['start_time_2']}</if>" type="text" jstips='接受预约开始时间'/>
                                                至 <input class="" size="30" name="end_time_2" id="end_time_2" value="<if condition="$store['end_time_2']">{pigcms{$store['end_time_2']}</if>" type="text" jstips='接受预约结束时间'/>
                                        </div>
                                        <div class="form-group">
                                                <label class="col-sm-1">营业时间</label>
                                                <input class="" size="30" name="start_time_3" id="start_time_3" value="<if condition="$store['start_time_3']">{pigcms{$store['start_time_3']}</if>" type="text" jstips='营业开始时间'/>
                                                至 <input class="" size="30" name="end_time_3" id="end_time_3" value="<if condition="$store['end_time_3']">{pigcms{$store['end_time_3']}</if>" type="text" jstips='营业结束时间'/>
                                        </div>
                                        <div class="form-group">
                                                <label class="col-sm-1">平均配送时间</label>
                                                <input class="col-sm-2" size="30" name="send_time" id="js-send_time" value="<if condition="$store['send_time']">{pigcms{$store['send_time']}<else />30</if>" type="text" jstips='请输入平均配送时间'/>
                                                <label class="col-sm-3">单位：分钟</label>
                                        </div>
                                        <div class="form-group">
                                                <label class="col-sm-1">商家公告</label>
                                                <textarea class="col-sm-5" rows="5" name="txt_info"><if condition="$store['tips']">{pigcms{$store['tips']}<else />本店欢迎您下单，用餐高峰请提前下单，谢谢！</if></textarea>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div id="txtstore" class="tab-pane">
                        <div class="tab-content">
                            <div class="form-group">
                                <label class="col-sm-1">选择分类：</label>
                            </div>
                            <volist name="category_list" id="vo">
                                <div class="form-group">
                                    <div class="radio">
                                        <label class='col-sm-1'>
                                            <span class="lbl"><label style="color: red">{pigcms{$vo.cat_name}：</label></span>
                                        </label>
                                        <volist name="vo['list']" id="child">
                                            <label>
                                                <input class="cat_class" type="checkbox" name="store_category[]" value="{pigcms{$vo.cat_id}-{pigcms{$child.cat_id}" id="Config_store_category_{pigcms{$child.cat_id}" <if condition="in_array($child['cat_id'],$relation_array)">checked="checked"</if>/>
                                                <span class="lbl"><label for="Config_store_category_{pigcms{$child.cat_id}">{pigcms{$child.cat_name}</label></span>
                                            </label>
                                        </volist>
                                    </div>
                                </div>
                            </volist>
                        </div>
                        </div>
                        <div class="clearfix form-actions">
                        	<div class="col-md-offset-3 col-md-9">
                            	<button class="btn btn-info" onclick="$(form).submit();return false;">
                            	<i class="ace-icon fa fa-check bigger-110"></i>
                                	保存
                         		</button>
                    		</div>
                     	</div>
                    	</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$('#start_time_1').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm:ss','hour':'9','minute':'00','second':'00'}));
$('#end_time_1').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm:ss','hour':'12','minute':'00','second':'00'}));
$('#start_time_2').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm:ss','hour':'16','minute':'00','second':'00'}));
$('#end_time_2').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm:ss','hour':'18','minute':'00','second':'00'}));
$('#start_time_3').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm:ss','hour':'19','minute':'00','second':'00'}));
$('#end_time_3').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm:ss','hour':'21','minute':'00','second':'00'}));

$("form").submit(function(){
    return checkForm();
});
function checkForm(){
    
    var startMoneyObj = $('#js-start_send_money');
    var sendMoneyObj = $('#js-send_money');
    var totalMoneyObj = $('#js-total_money');
    var sendTimeObj = $('#js-send_time');
    var start_time_1 = $.trim($('#start_time_1').val());
    var end_time_1 = $.trim($('#end_time_1').val());
    
    if(!checkLength(startMoneyObj) || !checkLength(sendMoneyObj) || !checkLength(totalMoneyObj) || !checkLength(sendTimeObj) ){
        return false;
    }
    if(start_time_1 == '00:00:00' || end_time_1 == '00:00:00'){
    	alert('请填写开始接单时间');
    	return false;
    }
    return  true;
}
function checkLength(obj){
    var objValue = $.trim(obj.val());
    if(objValue.length == 0){
        alert(obj.attr('jstips'))
        return false;
    }
    return true;
}
</script>
<include file="Public:footer"/>