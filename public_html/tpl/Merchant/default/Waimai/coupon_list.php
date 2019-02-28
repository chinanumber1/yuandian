<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-gear gear-icon"></i>
                <a href="{pigcms{:U('Waimai/index')}">外卖管理</a>
            </li>
            <li class="active">优惠券</li>
        </ul>
    </div>
	<div class="alert alert-info" style="margin:10px;">
		<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>1.平台代金券和店铺代金券无需设置金额，只要用户有这两类的券不管是线上和线下都能使用，新老用户都能使用<br/>
		2.满减类型区分线上和线下支付，以及新老用户的使用<br/>
		3.新用户立减只适合新用户使用，不管线上线下都可以使用的<br/>
	</div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <div class="row">
                <div class="col-xs-12">
                    <div class="tabbable">
                        <ul class="nav nav-tabs" id="myTab">                
                            <li class="active">
                                <a data-toggle="tab" href="#basicinfo">列表</a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#txtstore">添加</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content col-xs-12">               
                        <div id="basicinfo" class="tab-pane active">
                            <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">编号</th>
                                    <th width="10%">店铺名称</th>
                                    <th width="10%">优惠券名称</th>
                                    <th width="10%">优惠券描述</th>
                                    <th width="8%">优惠券金额</th>
                                    <th width="8%">订单金额</th>
                                    <th width="8%">开始时间</th>
                                    <th width="8%">结束时间</th>
                                    <th width="8%">创建时间</th>
                                    <th class="button-column" width="10%">设置</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$coupon_list">
                                    <volist name="coupon_list" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.coupon_id}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.store_name}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.name}</div></td>
                                            <td>{pigcms{$vo.desc}</td>
                                            <td>{pigcms{$vo.max_money}</td>
                                            <td>{pigcms{$vo.order_money}</td>
                                            <td>{pigcms{$vo.start_time|date='Y-m-d H:i:s',###}</td>
                                            <td>{pigcms{$vo.end_time|date='Y-m-d H:i:s',###}</td>
                                            <td>{pigcms{$vo.create_time|date='Y-m-d H:i:s',###}</td>
                                            <td class="button-column">
                                                <a style="width:80px;" class="label label-sm label-info" title="{pigcms{$config.meal_alias_name}优惠设置" href="{pigcms{:U('Waimai/coupon_del',array('coupon_id'=>$vo['coupon_id']))}">删除</a>
                                            </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="9" >您没有添加优惠方式，或优惠方式没开启{pigcms{$config.waimai_alias_name}功能，或优惠方式正在审核中。</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$pagebar}
                        </div>
                        <div id="txtstore" class="tab-pane">
                            <div class="col-xs-4">
                            <div style="width:320px;text-align:center;-webkit-tap-highlight-color: transparent;position: relative;height: 330px;background: -webkit-gradient(linear, 0 0, 0 100%, from(#fe314e), to(#fe6263));color: #fff;font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">
                                <h1 style="padding: 18px 60px 0 60px;height: 46px;line-height: 46px;font-size: 16px;color: #fff;font-weight: bold;text-align: center;background: url('/static/images/coupon_titlebar.png') no-repeat;display: inline-block;height: 64px;width: 320px;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;margin: 0;padding: 0;border: 0;font: inherit;font-size: 100%;vertical-align: baseline;">
                                    <span style="display: inline-block;height: 46px;width: 200px;text-overflow: ellipsis;white-space: nowrap;overflow: hidden;font-size: 15px;font-weight: bold;margin-top: 32px;">优惠券</span>
                                </h1>
                                <div style="margin: 0;padding: 0;border: 0;font: inherit;font-size: 100%;vertical-align: baseline;display: block;cursor: pointer;-webkit-user-select: none;">
                                    <div style="margin: 0;padding: 0;border: 0;font: inherit;font-size: 100%;vertical-align: baseline;display: block;cursor: pointer;-webkit-user-select: none;">
                                        <div style="margin-top:-5px;-webkit-tap-highlight-color: transparent;position: relative;height: 60px;color: #fff;">
                                            <h2 style="font-size: 18px;line-height: 60px;padding-left: 10px;float: left;display: block;margin: 0;padding: 0;border: 0;vertical-align: baseline;margin-left: 10px;font-weight:bold;" id="_title">优惠券标题</h2>
                                        </div>
                                        <p style="margin:0;padding:0;height:40px;vertical-align: baseline;display: block;text-align: center;color: #fff;">
                                            <span style="font-size: 30px;line-height: 40px;margin: 0;padding: 0;border: 0;font: inherit;font-size: 100%;vertical-align: baseline;">
                                                <span style="cursor: pointer;display: inline;font-family: Helvetica, STHeiti, 'Microsoft YaHei', Verdana, Arial, Tahoma, sans-serif;font-size: 30px;font-stretch: normal;font-style: normal;font-variant: normal;font-weight: normal;height: auto;line-height: 40px;margin-bottom: 0px;margin-left: 0px;margin-right: 0px;margin-top: 0px;padding-bottom: 0px;padding-left: 0px;padding-right: 0px;padding-top: 0px;text-align: center;vertical-align: baseline;width: auto;zoom: 1;">￥</span>
                                                <i style="cursor: pointer;display: inline;font-family: Helvetica, STHeiti, 'Microsoft YaHei', Verdana, Arial, Tahoma, sans-serif;font-size: 30px;font-stretch: normal;font-style: normal;font-variant: normal;font-weight: normal;height: auto;line-height: 40px;margin-bottom: 0px;margin-left: 0px;margin-right: 0px;margin-top: 0px;padding-bottom: 0px;padding-left: 0px;padding-right: 0px;padding-top: 0px;text-align: center;vertical-align: baseline;width: auto;zoom: 1;" id="_money">0.10</i>
                                            </span>
                                        </p>
                                        <p style="line-height: 40px;margin: 0;padding: 0;border: 0;font: inherit;font-size: 100%;vertical-align: baseline;display: block;-webkit-margin-before: 1em;-webkit-margin-after: 1em;-webkit-margin-start: 0px;-webkit-margin-end: 0px;text-align: center;color: #fff;font-weight:bold;" id="_limit">订单满 0.00 元可使用</p>
                                        <p style="line-height: 40px;margin: 0;padding: 0;border: 0;font: inherit;font-size: 100%;vertical-align: baseline;display: block;text-align: center;color: #fff;font-weight:bold;" id="_time">有效期：{pigcms{:date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME'])} 至 {pigcms{:date('Y-m-d H:i:s',strtotime('+7 day'))}</p>
                                        <div style="position: absolute;left: 0;bottom: 0;width: 100%;height: 5px;background: url('/static/images/coupon_dot.png') repeat-x left bottom;background-size: auto 5px;"></div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div class="col-xs-8">
                                <form enctype="multipart/form-data" action="{pigcms{:U('Waimai/coupon_add')}" class="form-horizontal" method="post" id="add_form">
                                <div class="form-group">
                                    <label class="col-sm-2">选择店铺:</label>
                                    <select name="store_id" id="store_id" class="col-sm-2">
                                    <volist name="store_list" id="vo">
                                        <option value="{pigcms{$vo.store_id}">{pigcms{$vo.name}</option>
                                    </volist>
                                    </select>
                                    <span class="form_tips">选择店铺名称！</span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2">优惠券名称:</label>
                                    <input class="col-sm-3" maxlength="10" id="name" name="name" type="text" value="" />
                                    <span class="form_tips">优惠券名称！</span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2">优惠券描述:</label>
                                    <input class="col-sm-3" maxlength="10" id="desc" name="desc" type="text" value="" />
                                    <span class="form_tips">优惠券备注描述！</span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2">发放总量:</label>
                                    <input class="col-sm-3" maxlength="10" id="num" name="num" type="text" value="100" />
                                    <span class="form_tips">该优惠券发放总量！</span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2">每人最多领取:</label>
                                    <input class="col-sm-3" maxlength="10" id="limit" name="limit" type="text" value="1" />
                                    <span class="form_tips">同一账号最多领取数量！</span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2">优惠券金额:</label>
                                    <input class="col-sm-3" maxlength="10" id="money" name="money" type="text" value="0.1" />
                                    <span class="form_tips">优惠价面值！</span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2">订单金额:</label>
                                    <input class="col-sm-3" maxlength="10" id="order_money" name="order_money" type="text" value="0" />
                                    <span class="form_tips">优惠券使用最低消费！</span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2">开始时间:</label>
                                    <input class="col-sm-3" maxlength="10" id="start_time" name="start_time" onfocus="WdatePicker({minDate:'{pigcms{:date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME'])}',isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss',startDate:'{pigcms{:date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME'])}',vel:'start_time'})" type="text" value="{pigcms{:date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME'])}" />
                                    <span class="form_tips">优惠券开始使用时间！</span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2">结束时间:</label>
                                    <input class="col-sm-3" maxlength="10" id="end_time" name="end_time" onfocus="WdatePicker({minDate:'{pigcms{:date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME'])}',isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss',startDate:'{pigcms{:date('Y-m-d H:i:s',strtotime('+7 day'))}',vel:'end_time'})" type="text" value="{pigcms{:date('Y-m-d H:i:s',strtotime('+7 day'))}" />
                                    <span class="form_tips">优惠券结束使用时间！</span>
                                </div>
                                <div class="clearfix form-actions">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button class="btn btn-info" type="submit" id="save_btn">
                                                <i class="ace-icon fa fa-check bigger-110"></i>
                                                保存
                                        </button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
<script type="text/javascript">
    $("form").change(function(){
        var name = $("#name").val();
        var money = $("#money").val();
        var order_money = $("#order_money").val();
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        if (name) {
            $("#_title").html(name);
        }
        if (money) {
            var temp = parseFloat(money);
            $("#_money").html(temp.toFixed(2));
        }
        if (order_money) {
            var temp = parseFloat(order_money);
            $("#_limit").html("订单满 "+temp.toFixed(2)+"元可使用");
        }
        if (start_time && end_time) {
            $("#_time").html("有效期："+start_time+" 至 "+end_time);
        }
    });
    $("form").submit(function(){
        var name = $("#name").val();
        var store_id = $("#store_id").val();
        if (!name) {
            alert("名称不能为空！");
            return false;
        }
        if (!store_id) {
            alert("店铺不能为空！");
            return false;
        }
        return true;
    });
</script>
<include file="Public:footer"/>