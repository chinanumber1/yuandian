<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="format-detection"content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <title>预约</title>
    <link href="{pigcms{$static_path}css/appoint_form.css?07" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/datePicker.css">
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mobiscroll_min.css" media="all">
    <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE')}"></script>
    <script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/mobiscroll_min.js"></script>
    <script type="text/javascript">
        var ajaxWorkUrl="{pigcms{:U('ajaxWorker')}";
        var ajaxWorkerTimeUrl="{pigcms{:U('ajaxWorkerTime')}";
        var ajaxAppointTimeUrl="{pigcms{:U('ajaxAppointTime')}";
        var appoint_id="{pigcms{$_GET['appoint_id']}";
        var merchant_workers_id="{pigcms{$_GET['merchantWorkerId']}";
        var is_store = "{pigcms{$now_group['is_store']}";
        var jqueryUrl="{pigcms{:C('JQUERY_FILE')}";
        var layUrl="{pigcms{$static_path}layer/layer.m.js";
        var appointFormLoadUrl="{pigcms{$static_path}js/appoint_form_load.js";
        var static_path = "{pigcms{$static_path}";
        var workerUrl = "{pigcms{:U('worker_list')}";
        var time_gap = "{pigcms{$now_group.time_gap}";
        var change_select_store = "{pigcms{$_GET['appoint_store_id']}";
        workerUrl += '&appoint_id=' + {pigcms{$_GET['appoint_id']};
		
		
    </script>
    <style>
        .detail{ height: 8rem; padding-left: 0.4rem;padding-top: 0.4rem;}
        .detail div{ padding-left:10px;  float: left; width: 100px; margin-top:10px}
        .detail-tou{
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }
        .con-service-inner{ height:60px; font-family:"微软雅黑"}
        .detail-intro p{ border-bottom: none; line-height: 24px; min-height: 0;text-overflow:ellipsis; overflow:hidden; white-space:nowrap;}
        .detail-intro p:first-child{ color:#32c8a2}
        .detail-intro p:nth-child(2){ color:#666}
        .detail-intro p:nth-child(3){ color:#999; font-size: 12px;}
        .detail div.detail-right{ float: right; border-left: 1px dashed #80e1d5; margin-top: 1.5rem; width: 75px;}
        .detail-right img{ width:45px; height:60px; margin-top:1rem}
        .ico_arrow {
            vertical-align: -2px;
            margin-left: 10px;
        }
        .ico_arrow {
            width: 8px;
            height: 13px;
            background-position: -14px -25px;
        }
        .ico_arrow{
            display: inline-block;
            background: url({pigcms{$static_path}/images/s.png) no-repeat;
            background-size: 150px auto;
        }
        .detail div.detail-msg {
            margin-top: 5px;
        }
        .detail div.tip_reward{
            display: block;
            width: 80%;
            height: 85%;
            position: absolute;
            bottom: 18%;
            left: 5%;
            border-radius: 50%;
            background-color: rgba(76, 76, 65, 0.98);
            content: attr(data-text);
            transition: all 1s ease;
            color: white;
        }
        .title_info {
            text-align: center;
            font-weight: bolder;
            font-size: 18px;
            padding: 20px 0 0;
        }

        .title_msg {
            text-align: center;
            font-size: 18px;
            padding: 20px 0;
        }

        .layermcont {
            padding: 0;
        }

        .layermchild {
            width: 66%;
        }

        .tip_info_list {
            border-top: 1px solid #f0f0f0;
            padding: 10px 20px;
        }

        .money_info {
            float: right;
        }
    </style>
</head>

<body>
<section id="main">
    <div class="yxc-body-bg index-section">
        <form action="" method="post" id="main_form">
            <if condition="count($appoint_product) gt 1">
                <div class="yxc-space"></div>
                <div class="tit-select-service">选择服务</div>
                <input type="hidden" name="service_type" id="service_type" value="{pigcms{$defaultAppointProduct['id']}"/>
                <div class="comm-service more">
                    <span><img src="{pigcms{$now_group['all_pic'][0]['s_image']}" width="50px" height="50px" /></span>

                    <div class="con-service">
                        <span><em>¥</em><span>{pigcms{$defaultAppointProduct['price']}</span></span>
                        <div class="con-service-inner" data-role="packageDescription">{pigcms{$defaultAppointProduct['name']}：{pigcms{$defaultAppointProduct['content']}
                            &nbsp;&nbsp;<em style="color:red; display:block">【用时&nbsp;:&nbsp;{pigcms{$defaultAppointProduct['use_time']}分钟】</em></div>
                    </div>
                </div>
                <else />
                <div class="yxc-space"></div>
                <div class="comm-service">
                    <span><img src="{pigcms{$now_group['all_pic'][0]['s_image']}" width="50px" height="50px" /></span>
                    <div class="con-service">
                        <div class="con-service-inner" data-role="packageDescription">
                            <p>{pigcms{$now_group['appoint_name']}</p>
                            <p style=" color:#666;">耗时：{pigcms{$now_group['expend_time']} 分钟</p>
                            <p style=" color:#32c8a2;"><if condition="$now_group['appoint_price'] eq 0"
                                >面议<else />全价：{pigcms{$now_group['appoint_price']}</if></p>
                        </div>
                    </div>
                </div>
            </if>
            <div class="yxc-space space-six border-t-no"></div>

            <if condition='$now_group["is_store"]'>
                <ul class="yxc-attr-list">
                    <li data-role="chooseStore">
                        <i class="icon-store"></i>
                        <p class="cover select">
                            <php>if($_GET['appoint_store_id']){$default_store_id  = $_GET['appoint_store_id'];} </php>
                            <select name="store_id" id="store_id" class="ipt-attr" <if condition='$default_store_id'>style="color: black;"</if>>
                            <option value="0">选择预约店铺</option>
                            <volist name="now_group['store_list']" id="vo">
                                <option value="{pigcms{$vo.store_id}" <if condition="$vo['store_id'] eq $default_store_id">selected="selected"</if>>{pigcms{$vo.name} <if condition='$vo["range_txt"]'>[距您约{pigcms{$vo.range_txt}]</if></option>
                            </volist>
                            </select>
                        </p>
                    </li>
                </ul>
                <div class="yxc-space" id="work_select"></div>
            </if>

            <if condition="$default_workers_info">
                <div class="yxc-attr-list detail" <if condition="$default_workers_info['is_reward'] eq 2">style="padding-bottom: 0.6rem;"</if>>
                <div style="position: relative;">
                    <img <if condition="$default_workers_info['is_reward'] eq 1">style="margin-top: 9px;"</if> src="/upload/appoint/{pigcms{$default_workers_info['avatar_path']}" class="detail-tou" />

                    <if condition="$default_workers_info['is_reward'] eq 2">
                        <div class="tip_reward" onclick="worker_reward_pay('{pigcms{$default_workers_info.merchant_worker_id}')">
                            <div style="margin: 30% auto;font-size: 0.2rem;width: 62%;text-align: center;">打赏后查看清晰图</div>
                        </div>
                        <div style="width: 94%;font-size: 0.8rem;background-color: #46d2e6;color: white;text-align: center;border-radius: 5%;float: none;padding: 0;" onclick="worker_reward_pay('{pigcms{$default_workers_info.merchant_worker_id}')">去打赏</div>
                    </if>
                </div>
                <div class="detail-msg">
                    <p>{pigcms{$default_workers_info['name']}</p>

                    <p>共服务{pigcms{$default_workers_info['finish_count']}次</p>
                </div>

                <div class="detail-right">
                    <a href="{pigcms{:U('worker_list',array('appoint_id'=>$_GET['appoint_id']))}<if condition="$_GET['merchantWorkerId']">&merchantWorkerId={pigcms{$_GET['merchantWorkerId']}</if>"><img src="{pigcms{$static_path}images/detail_gh.png" /></a>
    </div>

    </div>
    <div class="yxc-space"></div>
    </if>
    <input type="hidden" name="merchant_workers_id" value="{pigcms{$_GET['merchantWorkerId']}"/>
    <if condition="$now_group.product_type eq 0">
        <ul class="yxc-attr-list appoint-time" >
            <li data-role="chooseTime">
                <i class="icon-time"></i>
                <p class="cover no-arrow">
                    <input type="hidden" name="service_date" id="service_date" value="<php>echo $_GET['now_date']?$_GET['now_date']:$_GET['service_date'];</php>"/>
                    <input type="hidden" name="service_time" id="service_time" value="<php>echo $_GET['now_time']?$_GET['now_time']:$_GET['service_time'];</php>"/>
                    <input class="ipt-attr" type="text" id="serviceJobTime" placeholder="选择预约时间" readonly="readonly" value="<php>$time_txt =  $_GET['now_date']?$_GET['now_date']:$_GET['service_date']; if($time_txt==date('Y-m-d')){ echo '今天';}else{ echo $time_txt;}</php> <php>echo $_GET['now_time']?$_GET['now_time']:$_GET['service_time'];</php>" />
                </p>
            </li>
        </ul>
        <div class="yxc-space"></div>
    </if>
    <if condition="$now_group['payment_status'] eq 1">
        <div class="yxc-paymentMoney"><img src="{pigcms{$static_path}images/icon_deposit.png" style="width:15px;margin-right:5px;"/><span>预约定金</span><img src="{pigcms{$static_path}images/icon_rmb.png" style="width:8px;margin-left:15px;"/>&nbsp;
            <if condition='$defaultAppointProduct["payment_price"]'>
                <span id="appoint_price" style="font-size:20px;color:#ff8a00;margin-bottom:0;">{pigcms{$defaultAppointProduct["payment_price"]}</span>
                <else />
                <span id="appoint_price" style="font-size:20px;color:#ff8a00;margin-bottom:0;">{pigcms{$now_group.payment_money}</span>
            </if>
        </div>
        <div class="yxc-space"></div>
    </if>
    <input type="hidden" name="product_id" value="{pigcms{$defaultAppointProduct['id']}" />
    <if condition="$formData || $now_group['appoint_type']">
        <ul class="yxc-attr-list">
            <if condition="$now_group['appoint_type']">
                <!--<li>
                    <i class="icon-position"></i>
                    <input type="hidden" name="custom_field[0][name]" value="服务位置"/>
                    <input type="hidden" name="custom_field[0][type]" value="2"/>
                    <input type="hidden" name="custom_field[0][long]" data-type="long"/>
                    <input type="hidden" name="custom_field[0][lat]" data-type="lat"/>
                    <input type="hidden" name="custom_field[0][address]" data-type="address"/>
                    <p class="cover">
                        <input data-role="position" class="ipt-attr" type="text" name="custom_field[0][value]" placeholder="请选择服务位置" readonly data-required="required"/>
                    </p>
                    <p class="cover">
                        <input data-role="position-desc" class="ipt-attr" type="text" name="custom_field[0][value-desc]" placeholder="请标注地图后填写详细地址" data-required="required"/>
                    </p>
                </li>-->
                <li >
                    <a href="javascript:void(0)" id="edit_address" data-href="{pigcms{:U('My/adress',array('buy_type' => 'appoint', 'appoint_id'=>$now_group['appoint_id'],  'mer_id' => $now_group['mer_id'], 'frm' => $_GET['frm'], 'current_id'=>$user_adress['adress_id'], 'order_id' => $order_id, 'cartid' => $cartid))}" style="    text-decoration: none;">
                        <input type="hidden" name="custom_field[0][name]" value="服务位置"/>
                        <input type="hidden" name="custom_field[0][type]" value="2"/>
                        <input type="hidden" name="custom_field[0][long]" data-type="long" value="{pigcms{$user_adress.longitude}"/>
                        <input type="hidden" name="custom_field[0][lat]" data-type="lat" value="{pigcms{$user_adress.latitude}"/>
                        <input data-role="position"  type="hidden" name="custom_field[0][value]"  data-required="required" value="{pigcms{$user_adress['province_txt']} {pigcms{$user_adress['city_txt']} {pigcms{$user_adress['area_txt']} {pigcms{$user_adress['adress']} {pigcms{$user_adress['detail']}" data-long="{pigcms{$user_adress.longitude}" placeholder="请点击添加预约地址"/>


                        <input type="hidden" name="custom_field[0][address]" data-type="address" value="{pigcms{$user_adress['province_txt']} {pigcms{$user_adress['city_txt']} {pigcms{$user_adress['area_txt']} {pigcms{$user_adress['adress']} {pigcms{$user_adress['detail']}" />
                        <p style="color:#000;    display: inline-block;min-height: 50px;width: 100%;<php>if($user_adress['adress_id']){</php>font:inherit;<php>}</php>">

                            <span id="showAddres"><php>if($user_adress['adress_id']){</php>{pigcms{$user_adress['province_txt']} {pigcms{$user_adress['city_txt']} {pigcms{$user_adress['area_txt']} {pigcms{$user_adress['adress']} {pigcms{$user_adress['detail']}<php>}else{</php>请点击添加预约地址<php>}</php></span>
                            <span id="showTel"><php>if($user_adress){</php>电话：{pigcms{$user_adress['phone']}<php>}</php></span>

                        </p>
                        <div><i class="icon-position"></i></div>
                    </a>
                </li>
            </if>
            <volist name="formData" id="vo">
                <li>
                    <switch name="vo['type']">
                        <case value="0">
                            <i class="icon-txt"></i>
                            <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
                            <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
                            <p class="cover"><input class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value]" placeholder="请输入{pigcms{$vo.name}<if condition="!$vo['iswrite']">（可选）</if>" data-role="text" <if condition="$vo['iswrite']">data-required="required"</if>/></p>
    </case>
    <case value="1">
        <i class="icon-txt"></i>
        <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
        <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
        <p class="cover"><textarea class="ipt-attr" name="custom_field[{pigcms{$i}][value]" placeholder="请输入{pigcms{$vo.name}<if condition="!$vo['iswrite']">（可选）</if>" data-role="textarea" <if condition="$vo['iswrite']">data-required="required"</if>></textarea></p>
    </case>
    <case value="2">
        <i class="icon-position"></i>
        <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
        <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
        <input type="hidden" name="custom_field[{pigcms{$i}][long]" data-type="long"/>
        <input type="hidden" name="custom_field[{pigcms{$i}][lat]" data-type="lat"/>
        <input type="hidden" name="custom_field[{pigcms{$i}][address]" data-type="address"/>
        <p class="cover">
            <input data-role="position" class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value]" placeholder="请标注{pigcms{$vo.name}<if condition="!$vo['iswrite']">（可选）</if>" readonly="readonly" <if condition="$vo['iswrite']">data-required="required"</if>/>
        </p>
        <p class="cover">
            <input data-role="position-desc" class="ipt-attr" type="text" name="custom_field[0][value-desc]" placeholder="请标注地图后填写详细地址" data-required="required"/>
        </p>
    </case>
    <case value="3">
        <i class="icon-txt"></i>
        <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
        <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
        <p class="cover select">
            <select name="custom_field[{pigcms{$i}][value]" class="ipt-attr" data-role="select"  placeholder="请选择{pigcms{$vo.name}" <if condition="$vo['iswrite']">data-required="required"</if>>
            <option value="">请选择{pigcms{$vo.name}</option>
            <volist name="vo['use_field']" id="voo">
                <option value="{pigcms{$voo}">{pigcms{$voo}</option>
            </volist>
            </select>
        </p>
    </case>
    <case value="4">
        <i class="icon-txt"></i>
        <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
        <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
        <p class="cover"><input class="ipt-attr" type="tel" name="custom_field[{pigcms{$i}][value]" placeholder="请输入{pigcms{$vo.name}<if condition="!$vo['iswrite']">（可选）</if>" data-role="number" <if condition="$vo['iswrite']">data-required="required"</if>/></p>
    </case>
    <case value="5">
        <i class="icon-txt"></i>
        <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
        <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
        <p class="cover"><input class="ipt-attr" type="email" name="custom_field[{pigcms{$i}][value]" placeholder="请输入正确的{pigcms{$vo.name}<if condition="!$vo['iswrite']">（可选）</if>" data-role="email" <if condition="$vo['iswrite']">data-required="required"</if>/></p>
    </case>
    <case value="6">
        <i class="icon-txt"></i>
        <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
        <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
        <p class="cover"><input class="ipt-attr date" name="custom_field[{pigcms{$i}][value]" placeholder="请输入{pigcms{$vo.name}<if condition="!$vo['iswrite']">（可选）</if>" data-role="date" <if condition="$vo['iswrite']">data-required="required"</if>/></p>
    </case>
    <case value="7">
        <i class="icon-txt"></i>
        <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
        <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
        <p class="cover"><input class="ipt-attr time" name="custom_field[{pigcms{$i}][value]" placeholder="请输入{pigcms{$vo.name}<if condition="!$vo['iswrite']">（可选）</if>" data-role="time" <if condition="$vo['iswrite']">data-required="required"</if>/></p>
    </case>
    <case value="8">
        <i class="icon-phone"></i>
        <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
        <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
        <p class="cover"><input class="ipt-attr" type="tel" name="custom_field[{pigcms{$i}][value]" placeholder="请输入{pigcms{$vo.name}<if condition="!$vo['iswrite']">（可选）</if>" data-role="phone" <if condition="$vo['iswrite']">data-required="required"</if>/></p>
    </case>
    <case value="9">
        <i class="icon-txt"></i>
        <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
        <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
        <p class="cover"><input class="ipt-attr datetime" name="custom_field[{pigcms{$i}][value]" placeholder="请输入{pigcms{$vo.name}<if condition="!$vo['iswrite']">（可选）</if>" data-role="datetime" <if condition="$vo['iswrite']">data-required="required"</if>/></p>
    </case>
    </switch>
    </li>
    </volist>
    </ul>
    <div class="yxc-space space-six border-t-no"></div>
    </if>
    <em class="tip-add-money">
        <div class="foot-index">
            <a class="bt-sub-order" data-role="submit">
                立即下单
            </a>
        </div>
    </em>
    </form>
    </div>
</section>
<section id="service-type" style="display:none;">
    <div class="yxc-pay-main yxc-payment-bg pad-bot-comm">
        <header class="yxc-brand">
            <a class="arrow-wrapper" data-role="cancel">
                <i class="bt-brand-back"></i>
            </a>
            <span>选择服务</span>
        </header>
        <ul class="yxc-service-list yxc-package boder-top service-list">
            <volist name="appoint_product" id="vo">
                <li <if condition="$vo['id'] eq $defaultAppointProduct['id']">class="active"</if> data-id="{pigcms{$vo['id']}" data-payment-price="{pigcms{$vo['payment_price']}">
                <label class="pay-type" for="pay-type-{pigcms{$vo['id']}">
                    <span class="service-price"><em>¥</em><span data-role="payAmount">{pigcms{$vo['price']}</span></span>
                    <div class="service-intro">
                        <h3 data-role="title">{pigcms{$vo['name']}</h3>
                        <span data-role="content">{pigcms{$vo['content']}&nbsp;&nbsp;<em style="color:red; display:block">【用时&nbsp;:&nbsp;{pigcms{$vo['use_time']}分钟】</em></span>
                    </div>
                    <input name="pay-type" id="pay-type-{pigcms{$vo['id']}" type="radio" value="" style="opacity:0;position:absolute;top:0;" <if condition="$vo['id'] eq $defaultAppointProduct['id']">checked="checked"</if>/>
                    <span class="bt-interior"></span>
                </label>
                </li>
            </volist>
        </ul>
    </div>
</section>

<section id="service-date" style="display:none;">
    <div class="yxc-pay-main yxc-payment-bg pad-bot-comm">
        <header class="yxc-brand">
            <a class="arrow-wrapper" data-role="cancel">
                <i class="bt-brand-back"></i>
            </a>
            <span>选择预约时间</span>
        </header>
        <div class="yxc-time-con number-{pigcms{:count($timeOrder)}">
            <volist name="timeOrder" id="timeOrderInfo">
                <dl <if condition="$i eq count($timeOrder)">class="last"</if>>
                <dt <if condition="$i eq 1">class="active"</if> data-role="date" data-text="<if condition="$key eq date('Y-m-d')" > 今天<elseif condition="$key eq date('Y-m-d',strtotime('+1 day'))" />明天
                    <elseif condition="$key eq date('Y-m-d',strtotime('+2 day'))" />后天<else />{pigcms{$key}
                </if>" data-date="{pigcms{$key}">
                <if condition="$key eq date('Y-m-d')" > 今天
                    <elseif condition="$key eq date('Y-m-d',strtotime('+1 day'))" />明天
                    <elseif condition="$key eq date('Y-m-d',strtotime('+2 day'))" />后天
                    <else />
                </if>
                <span>{pigcms{$key}</span>
                </dt>
                </dl>
            </volist>
        </div>

        <div class="yxc-time-con number-{pigcms{:count($timeOrder)}">

        </div>
        <div class="yxc-time-con" data-role="timeline" id="worker_time">
            <volist name="timeOrder" id="timeOrderInfo">
                <div class="date-{pigcms{$key} timeline" <if condition="$i neq 1">style='display:none'</if> >
                <volist name="timeOrderInfo" id="vo">
                    <dl>
                        <dd data-role="item" data-peroid="{pigcms{$vo['start']}" <if condition="$vo['order'] eq 'no' || $vo['order'] eq 'all' ">class="disable"</if>>{pigcms{$vo['start']}<br>
                        <if condition="$vo['order'] eq 'no' ">不可预约<elseif condition="$vo['order'] eq 'all' " />已约满<else />可预约</if></dd>
                    </dl>
                </volist>
        </div>
        </volist>
    </div>
    </div>
</section>
<section id="service-position" style="display:none;">
    <div class="yxc-pay-main yxc-payment-bg pad-bot-comm">
        <header class="yxc-brand">
            <a class="arrow-wrapper" data-role="cancel">
                <i class="bt-brand-back"></i>
            </a>
            <span>选择位置</span>
        </header>
        <div class="selectInput">
            <input type="text" placeholder="直接输入定位您的地址" id="se-input-wd" autocomplete="off"/>
        </div>
        <div class="mapBox">
            <div id="allmap"></div>
            <div class="dot"></div>
        </div>
        <div class="mapaddress">
            <ul id="addressShow"></ul>
        </div>
    </div>
</section>

<script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
<script src="{pigcms{$static_path}/js/common_wap.js"></script>
<script type="text/javascript">
    var user_long = '0',user_lat  = '0';
</script>
<!--<script>

if(user_long == '0' || user_lat == '0'){
//检查浏览器是否支持地理位置获取
if (navigator.geolocation){
    //若支持地理位置获取,成功调用showPosition(),失败调用showError
    var config = {enableHighAccuracy:true};
    navigator.geolocation.getCurrentPosition(showPosition,showError,config);
}else{
    alert("定位失败,用户浏览器不支持或已禁用位置获取权限");
}
}

/**
* 获取地址位置成功
*/
function showPosition(position){
//获得经度纬度
user_lat  = position.coords.latitude;
user_long = position.coords.longitude;

$.getJSON('http://api.map.baidu.com/geoconv/v1/?coords='+user_long+','+user_lat+'&ak=4c1bb2055e24296bbaef36574877b4e2&from=1&to=5&callback=funName&jsoncallback=?');
}



function funName(result){
            user_lat  = result.result[0].y;
            user_long = result.result[0].x;
    }

/**
* 获取地址位置失败[暂不处理]
*/
function showError(error){
$('#near_dom').remove();
switch (error.code){
    case error.PERMISSION_DENIED:
        alert("定位失败,用户拒绝请求地理定位");
        break;
    case error.POSITION_UNAVAILABLE:
        alert("定位失败,位置信息不可用");
        break;
    case error.TIMEOUT:
        alert("定位失败,请求获取用户位置超时");
        break;
    case error.UNKNOWN_ERROR:
        alert("定位失败,定位系统失效");
        break;
}
}
</script>-->

<script type="text/javascript">
    <if condition="$long_lat">
        var user_long={pigcms{$long_lat.long},user_lat={pigcms{$long_lat.lat},user_city='{pigcms{$city_name}';
    <else/>
    var user_long=0,user_city='{pigcms{$city_name}';
    </if>


    var click = false;

    function worker_reward_pay(merchant_worker_id) {
        if (click) return false;
        click = true;
        setTimeout(function () {
            if (click) {
                console.log('change')
                click = false;
            }
        }, 2000);
        var worker_reward_pay_order = "{pigcms{:U('Appoint/worker_reward_pay_order')}";
        var worker_reward_pay = "{pigcms{:U('Appoint/worker_reward_pay')}";

        $.post(worker_reward_pay_order, {'merchant_worker_id': merchant_worker_id}, function (data) {
            console.log('支付信息-》  ', data)
            click = false;
            if (data.error == 3) {
                layer.open({
                    content: '<div  class="title_info">' + data.msg + '</div><br>' +
                    '<div class="tip_info_list">账户余额（元）：<div class="money_info">￥' + data.info.now_money + '</div></div>' +
                    '<div class="tip_info_list" style="color: #06c1ae;">打赏金额（元）：<div class="money_info">￥' + data.info.reward_money + '</div></div>' +
                    '<div class="tip_info_list" style="color: #FF658E;">还需充值（元）：<div class="money_info">￥' + data.info.difference + '</div></div>'
                    , btn: ['确定', '取消']
                    , yes: function (index) {
                        location.href = "{pigcms{:U('My/recharge',array('label'=>'wap_portal_article_'))}{pigcms{$article['aid']}";
                    }
                });
            } else if (data.error == 1 || data.error == 2) {
                layer.open({
                    content: '<div  class="title_msg">' + data.msg + '</div>'
                    , btn: ['确定']
                    , yes: function (index) {
                        window.location.href = window.location.href;
                    }
                });
            } else if (data.error == 5) {
                layer.open({
                    content: '<div  class="title_info">打赏</div><br>' +
                    '<div class="tip_info_list">账户余额（元）：<div class="money_info">￥' + data.info.now_money + '</div></div>' +
                    '<div class="tip_info_list" style="color: #06c1ae;">打赏金额（元）：<div class="money_info">￥' + data.info.reward_money + '</div></div>'
                    , btn: ['立即支付', '取消']
                    , yes: function (index) {
                        $.post(worker_reward_pay, {'merchant_worker_id': merchant_worker_id}, function (data) {
                            if (data.error == 3) {
                                layer.open({
                                    content: '<div  class="title_info">' + data.msg + '</div><br>' +
                                    '<div class="tip_info_list">账户余额（元）：<div class="money_info">￥' + data.info.now_money + '</div></div>' +
                                    '<div class="tip_info_list" style="color: #06c1ae;">打赏金额（元）：<div class="money_info">￥' + data.info.reward_money + '</div></div>' +
                                    '<div class="tip_info_list" style="color: #FF658E;">还需充值（元）：<div class="money_info">￥' + data.info.difference + '</div></div>'
                                    , btn: ['确定', '取消']
                                    , yes: function (index) {
                                        location.href = "{pigcms{:U('My/recharge',array('label'=>'wap_portal_article_'))}{pigcms{$article['aid']}";
                                    }
                                });
                            } else if (data.error == 1 || data.error == 2) {
                                layer.open({
                                    content: '<div  class="title_msg">' + data.msg + '</div>'
                                    , btn: ['确定']
                                    , yes: function (index) {
                                        window.location.href = window.location.href;
                                    }
                                });
                            } else {
                                layer.open({
                                    content: '<div  class="title_msg">' + data.msg + '</div>'
                                    , btn: ['确定']
                                });
                            }
                        }, 'json');
                    }
                });
            } else {
                if (data.code == 2) {
                    layer.open({
                        content: '<div  class="title_msg">请先登录</div>'
                        , btn: ['去登录']
                        , yes: function (index) {
                            location.href = "{pigcms{:U('Login/index')}";
                        }
                    });
                } else {
                    layer.open({
                        content: '<div  class="title_msg">' + data.msg + '</div>'
                        , btn: ['确定']
                    });
                }
            }
        }, 'json');
    }

</script>

<script>
    $(function(){
        $('#edit_address').click(function(){
            var edit_address_url = $(this).data('href');
            if($('#service_date').val()!=''){
                edit_address_url +='&service_date='+$('#service_date').val()+'&service_time='+$('#service_time').val()+'&appoint_store_id='+$('#store_id').val();
            }
            window.location.href=edit_address_url
        })

        $('.date').mobiscroll()["date"]({
            lang: 'zh',
            display: 'bottom',
            minWidth: 64,
        });

        $('.datetime').mobiscroll()["datetime"]({
            lang: 'zh',
            display: 'bottom',
            minWidth: 64,
        });

        $('.time').mobiscroll()["time"]({
            lang: 'zh',
            display: 'bottom',
            minWidth: 64,
        });

    })

</script>
<script type="text/javascript" src="{pigcms{$static_path}js/appoint_form.js?20180830"></script>
</body>
</html>