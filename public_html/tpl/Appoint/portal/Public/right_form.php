<div class="right fr">
  <div class="pin-wrapper">
    <div class="panel" style="width: 290px;">
      <div class="form">
        <h2 class="title">微信下单</h2>
        <div class="body" style="text-align:center;font-size:14px;"> <img src="{pigcms{$config.config_site_url}{pigcms{:U('Index/Recognition/see_qrcode',array('type'=>'appoint','id'=>$now_group['appoint_id']))}" style="width:160px;height:160px;"/> <br/>
          微信扫一扫轻松下单 </div>
      </div>
      <form action="{pigcms{:U('order')}" id="deal-buy-form" method="post">
      <div class="form" style="margin-top: 12px;">
        <h2 class="title">在线预约</h2>
        <div class="body" id="form_com">
        <if condition='$now_group["is_store"]'>
           <div class="drop-down-com" style="z-index: 13;"> <span name="store_id">选择店铺</span>
              <ul class="store_name">
                <volist name='now_group["store_list"]' id='vo'>
                  <li data-store-id='{pigcms{$vo.store_id}' onClick="get_worker({pigcms{$vo.store_id},{pigcms{$_GET['appoint_id']})">{pigcms{$vo.name}</li>
                </volist>
              </ul>
              <input type="hidden" name="is_store" value="" />
              <input type="hidden" name="store_id" value="" />
            </div>
        </if>
		<if condition='$now_group["is_store"]'>
            <if condition='$worker_list'>
                 <div class="drop-down-com" style="z-index: 12;"> <span name="merchant_worker_id">选择技师</span>
                  <ul class="merchant_workers">
                    <li data-merchant-worker-id='0' onClick="get_drop_down_com($(this))">请选择</li>
                  </ul>
                  <input type="hidden" name="merchant_worker_id" value="" />
                </div>
            </if>
		</if>
          <div class="drop-down clearfix">
        <if condition='$appoint_product'><!--div class="drop-down-com  service-type-select" style="z-index: 10;"> <span name="product_id">选择服务</span> </div>
        <input type="hidden" name="product_id" value="" /-->
        </if>
        <div class="drop-down-com" style="z-index: 10;" id="serviceJobTime"> <span class="appoint_time" name="appoint_time">预期服务日期</span>
        <input type="hidden" name="appoint_time" value="" />
        </div>
        <if condition="$now_group['appoint_type']">
            <div  class="form-field"  data-name="服务位置">
                <input type="hidden" name="custom_field[0][name]" value="服务位置"/>
                <input type="hidden" name="custom_field[0][type]" value="2"/>
                <input type="hidden" name="custom_field[0][long]" data-type="long"/>
                <input type="hidden" name="custom_field[0][lat]" data-type="lat"/>
                <input type="hidden" name="custom_field[0][address]" data-type="address"/>
                <p class="cover">
                    <input data-role="position" class="ipt-attr" type="text" name="custom_field[0][value]" placeholder="请选择服务位置" readonly data-required="required"/>
                    <p class="cover" style="margin-top:10px;"><input data-role="position-desc" class="ipt-attr" type="text" name="custom_field[1][value-desc]" data-role="text" style="color:#999;" value="请标注地图后填写详细地址"/></p>
                </p>
            </div>
    	</if>
   
        
        <if condition='$formData'>
        <volist name="formData" id="vo">
                <div class="form-field" data-name="{pigcms{$vo.name}">
                  <label for="address-detail">
                    <!--<if condition="$vo['iswrite']"><em>*</em></if>-->
                    &nbsp;{pigcms{$vo.name}：</label>
                  <switch name="vo['type']">
                    <case value="0">
                      <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
                      <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
                      <p class="cover"><input class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value]" data-role="text" 
                        <if condition="$vo['iswrite']">data-required="required"</if>
                        /></p>
                    </case>
                    <case value="1">
                      <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
                      <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
                      <p class="cover"><textarea class="ipt-attr" name="custom_field[{pigcms{$i}][value]" data-role="textarea" 
                        <if condition="$vo['iswrite']">data-required="required"</if>
                        >
                        </textarea>
                      </p>
                    </case>
                    <case value="2">
                      <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
                      <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
                      <input type="hidden" name="custom_field[{pigcms{$i}][long]" data-type="long"/>
                      <input type="hidden" name="custom_field[{pigcms{$i}][lat]" data-type="lat"/>
                      <input type="hidden" name="custom_field[{pigcms{$i}][address]" data-type="address"/>
                      <p class="cover"> <input data-role="position" class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value]" readonly style="cursor:pointer;color:#666;" value="请点击标注地图" 
                        <if condition="$vo['iswrite']">data-required="required"</if>
                        /> </p>
                      <p class="cover" style="margin-top:10px;">
                        <input data-role="position-desc" class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value-desc]" data-role="text" style="color:#999;" value="请标注地图后填写详细地址"/>
                      </p>
                    </case>
                    <case value="3">
                      <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
                      <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
                      <p class="cover select"> <select name="custom_field[{pigcms{$i}][value]" class="dropdown--small" data-role="select" 
                        <if condition="$vo['iswrite']">data-required="required"</if>
                        >
                        <volist name="vo['use_field']" id="voo">
                          <option value="{pigcms{$voo}">{pigcms{$voo}</option>
                        </volist>
                        </select>
                      </p>
                    </case>
                    <case value="4">
                      <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
                      <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
                      <p class="cover"><input class="ipt-attr" type="tel" name="custom_field[{pigcms{$i}][value]" data-role="number" 
                        <if condition="$vo['iswrite']">data-required="required"</if>
                        /></p>
                    </case>
                    <case value="5">
                      <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
                      <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
                      <p class="cover"><input class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value]" data-role="email" 
                        <if condition="$vo['iswrite']">data-required="required"</if>
                        /></p>
                    </case>
                    <case value="6">
                      <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
                      <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
                      <p class="cover"><input class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value]" data-role="date" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日'})" style="cursor:pointer;color:#666;" value="请点击选择日期" 
                        <if condition="$vo['iswrite']">data-required="required"</if>
                        /></p>
                    </case>
                    <case value="7">
                      <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
                      <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
                      <p class="cover"><input class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value]" data-role="time" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'HH时mm分'})" style="cursor:pointer;color:#666;" value="请点击选择时间" 
                        <if condition="$vo['iswrite']">data-required="required"</if>
                        /></p>
                    </case>
                    <case value="8">
                      <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
                      <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
                      <p class="cover"><input class="ipt-attr" type="tel" name="custom_field[{pigcms{$i}][value]" data-role="phone" 
                        <if condition="$vo['iswrite']">data-required="required"</if>
                        /></p>
                    </case>
                    <case value="9">
                      <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
                      <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
                      <p class="cover"><input class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value]" data-role="datetime" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日 HH时mm分ss秒'})" style="cursor:pointer;color:#666;" value="请点击选择时间" 
                        <if condition="$vo['iswrite']">data-required="required"</if>
                        /></p>
                    </case>
                  </switch>
                </div>
              </volist>
        </if>
        <input type="hidden" name="appoint_id" value="{pigcms{$_GET['appoint_id']}" />
        <if condition='$now_group["payment_status"]'><div style=" padding-left:10px;line-height:38px;float: left;height: 38px;margin: 0 9px 9px 0;position: relative;width: 250px; font-weight:bold; font-size:16px"> <span>订金：</span><span style="font-weight:bold;  color:red">{pigcms{$now_group['payment_money']}元</span>&nbsp;&nbsp;( <span style="color:gray;  ">全价：{pigcms{$now_group['appoint_price']}元</span> )</div><else /><div style=" padding-left:10px;line-height:38px;float: left;height: 38px;margin: 0 9px 9px 0;position: relative;width: 250px; font-weight:bold; font-size:16px"> <span style="color:gray; ">全价：{pigcms{$now_group['appoint_price']}元</span> </if>
          </div>
         <!-- <div class="submit-btn styfff">立即预约</div>-->
         <input type="submit" id="confirmOrder" value="提交订单" class="submit-btn styfff" style="height:50px; border:none"/>
        </div>
      </div>
      </form>
      
      <!-- 编辑模板时去除   以下号码显示商家号码 -->
      <div class="phone-search">
        <div class="company-tel" href="javascript:;"> <i class="phone-icon"></i> <strong class="tel-name">电话咨询
          :</strong><!-- <strong>{pigcms{$config.appoint_site_phone}</strong> --> <strong>{pigcms{$now_group.phone}</strong> </div>
      </div>
    </div>
  </div>
</div>

<div id="service-date" style="display:none">
  <div class="yxc-pay-main yxc-payment-bg pad-bot-comm">
    <div class="yxc-time-con number-{pigcms{:count($timeOrder)}">
      <volist name="timeOrder" id="timeOrderInfo"> <dl 
        <if condition="$i eq count($timeOrder)">class="last"</if>
        > <dt 
        <if condition="$i eq 1">class="active"</if>
        data-role="date" data-text="
        <if condition="$key eq date('Y-m-d')" > 今天
          <elseif condition="$key eq date('Y-m-d',strtotime('+1 day'))" />
          明天
          <elseif condition="$key eq date('Y-m-d',strtotime('+2 day'))" />
          后天
          <else />
          {pigcms{$key}</if>
        " data-date="{pigcms{$key}">
        <if condition="$key eq date('Y-m-d')" > 今天
          <elseif condition="$key eq date('Y-m-d',strtotime('+1 day'))" />
          明天
          <elseif condition="$key eq date('Y-m-d',strtotime('+2 day'))" />
          后天
          <else />
        </if>
        <span>{pigcms{$key}</span>
        </dt>
        </dl>
      </volist>
    </div>
    <div class="yxc-time-con" data-role="timeline">
      <volist name="timeOrder" id="timeOrderInfo">
      <div class="date-{pigcms{$key} timeline" 
      <if condition="$i neq 1">style='display:none'</if>
      >
      <volist name="timeOrderInfo" id="vo">
        <dl>
          <dd data-role="item"  <if condition="$vo['order'] eq 'yes' ">title="现可预约人数：{pigcms{$vo['remain_num']}人"</if> data-peroid="{pigcms{$vo['time']}" 
          <if condition="$vo['order'] eq 'no' || $vo['order'] eq 'all' ">class="disable"</if>
          >{pigcms{$vo['time']}<br>
          <if condition="$vo['order'] eq 'no' ">不可预约
            <elseif condition="$vo['order'] eq 'all' " />
            已约满
            <else />
            可预约</if>
          </dd>
        </dl>
      </volist>
    </div>
    </volist>
  </div>
</div>
</div>

<if condition='$appoint_product'>
    <div id="service-type-box" style="display:none;">
      <ul class="delivery-type service-list">
        <volist name="appoint_product" id="vo"> <li 
          <if condition="$i eq 1">class="active"</if>
          data-id="{pigcms{$vo['id']}">
          <label class="pay-type" for="pay-type-{pigcms{$vo['id']}">
          <span class="service-price"><em>¥</em><span data-role="payAmount">{pigcms{$vo['price']}</span></span>
          <div class="service-intro">
            <h3 data-role="title">{pigcms{$vo['name']}</h3>
            <span data-role="content">{pigcms{$vo['content']}</span> </div>
          <span class="bt-interior"> <input name="pay-type" id="pay-type-{pigcms{$vo['id']}" type="radio" 
          <if condition="$i eq 1">checked="checked"</if>
          /> </span>
          </label>
          </li>
        </volist>
      </ul>
    </div>
</if>

<script type="text/javascript">
	var ajaxWorkUrl = "{pigcms{:U('ajaxWorker')}";
	var ajaxWorkerTimeUrl = "{pigcms{:U('ajaxWorkerTime')}";
	var ajaxAppointTimeUrl = "{pigcms{:U('ajaxAppointTime')}";
	var appoint_id = "{pigcms{$_GET['appoint_id']}";
	var url = "{pigcms{:U('order')}";
	var map_url="{pigcms{:U('Map/frame_select')}";
	var setPointDom = {};
	yxc_time_con_tab()
function setPoint(randNum,lng,lat,address){
	var objDom = setPointDom[randNum];
	objDom.closest('div').find('input[data-type="long"]').val(lng);
	objDom.closest('div').find('input[data-type="lat"]').val(lat);
	objDom.closest('div').find('input[data-type="address"]').val(address);
	objDom.data({'long':lng,'lat':lat,'address':address}).val(address);
}
	var setPointDom = {};
    $('.inp-del').each(function(){
		$(this).click(function(){
			$(this).prev().val('');
		});
	});
	
	$('.drop-down-com').each(function(){
		$(this).toggle(function(){
			drop_down_com_tab();
			if($(this).find('div').hasClass('calendar')){
				$('#calendar').show();
			}else{
				$(this).children('ul').show();
			}
		},function(){
			drop_down_com_tab();
			if($(this).find('div').hasClass('calendar')){
				$('#calendar').hide();
			}else{
				$(this).children('ul').hide();
			}
		});
	});
	
	function drop_down_com_tab(){
		$('.drop-down-com ul').each(function(){
				$(this).hide();
		});
	}
	

	function get_drop_down_com(obj){
			$('.appoint_time').html('预期服务日期');
			var shtml = obj.html();
			var merchant_worker_id = obj.data('merchant-worker-id');
			if(merchant_worker_id){
				obj.parents('.drop-down-com').find('span').data('merchant-worker-id',merchant_worker_id);
				getWorkerTime(merchant_worker_id,appoint_id);
			}else{
				obj.parents('.drop-down-com').find('span').data('merchant-worker-id',0);
				getAppointTime(appoint_id);
			}
			obj.parents('.drop-down-com').find('span').html(shtml);
	}

	$('.store_name li').click(function(){
		$('.appoint_time').html('预期服务日期');
		$('span[name="merchant_worker_id"]').html('请选择技师');
		$('span[name="merchant_worker_id"]').data('merchant-worker-id',0);
		var shtml = $(this).html();
		var store_id = $(this).data('store-id');
		if(store_id){
			$(this).parents('.drop-down-com').find('span').data('store-id',store_id);
			$(this).parents('.drop-down-com').find('span').html(shtml);
		}else{
			$(this).parents('.drop-down-com').find('span').data('store-id',0);
		}
	})
	
	$('#deal-buy-form').submit(function(){
		var product_id = $('span[name="product_id"]').data('product_id');
		var appoint_time =$('span[name="appoint_time"]').html();
		var store_id = $('span[name="store_id"]').data('store-id');
		var merchant_worker_id = $('span[name="merchant_worker_id"]').data('merchant-worker-id');
		var is_store = "{pigcms{$now_group['is_store']}";
		
		$('input[name="product_id"]').val(product_id);
		$('input[name="appoint_time"]').val(appoint_time);
		$('input[name="store_id"]').val(store_id);
		$('input[name="merchant_worker_id"]').val(merchant_worker_id);
		$('input[name="is_store"]').val(is_store);
		
		if(is_store!=0){
			if(!store_id){
				alert('店铺不能为空！');
				return false;
			}
		}
		
		if(appoint_time=='预期服务日期'){
			alert('预期服务日期不能为空！');
			return false;
		}
		
		
		
		var slA = $('#deal-buy-form').serializeArray();
		for(var i in slA){
			var tmpDom = $("[name='"+slA[i].name+"']");
			if(tmpDom.data('role')){
				if(tmpDom.data('required')){
					if(tmpDom.data('role') == 'phone' && !/^0?1[3|4|5|7|8][0-9]\d{8}$/.test(slA[i].value)){
						formError(tmpDom);
						return false;
					}else if(tmpDom.data('role') == 'text' && slA[i].value == ''){
						formError(tmpDom);
						return false;
					}else if(tmpDom.data('role') == 'position' && !tmpDom.data('long')){
						formError(tmpDom);
						return false;
					}else if(tmpDom.data('role') == 'textarea' && slA[i].value == ''){
						formError(tmpDom);
						return false;
					}else if(tmpDom.data('role') == 'number' && !/^[0-9]*$/.test(slA[i].value)){
						formError(tmpDom);
						return false;
					}else if(tmpDom.data('role') == 'date' && (slA[i].value == '' || slA[i].value == '请点击选择日期')){
						formError(tmpDom);
						return false;
					}else if(tmpDom.data('role') == 'time' && (slA[i].value == '' || slA[i].value == '请点击选择时间')){
						formError(tmpDom);
						return false;
					}else if(tmpDom.data('role') == 'select' && slA[i].value == ''){
						formError(tmpDom);
						return false;
					}else if(tmpDom.data('role') == 'datetime' && (slA[i].value == '' || slA[i].value == '请点击选择时间')){
						formError(tmpDom);
						return false;
					}
				}
			}
		}
		
		$('#confirmOrder').attr('disabled',true).css('background','gray');
		$.post($('#deal-buy-form').attr('action'),$('#deal-buy-form').serialize(),function(result){
			if(result.status){
				location.href ="{pigcms{$config.config_site_url}/" + result.url;
			}else{
				motify.log(result.info);
				if(result.url){
					window.location.href=result.url
				}else{
					window.location.reload();
				}
			}
		},'json');
		return false;
	});
	
function formError(tmpDom){
	$('.form_error').removeClass('form_error');
	motify.log('请正确填写该项：'+tmpDom.closest('.form-field').data('name'));
	$(window).scrollTop(tmpDom.offset().top-20);
	tmpDom.addClass('form_error');
}

var motify = {
	timer:null,
	log:function(msg){
		alert(msg);
	/* 	$('.motify').hide();
		if(motify.timer) clearTimeout(motify.timer);
		if($('.motify').size() > 0){
			$('.motify').show().find('.motify-inner').html(msg);
		}else{
			$('body').append('<div class="motify" style="display:block;"><div class="motify-inner">'+msg+'</div></div>');
		}
		motify.timer = setTimeout(function(){
			$('.motify').hide();
		},3000); */
	}
};
	
function setPoint(randNum,lng,lat,address){
	var objDom = setPointDom[randNum];
	objDom.closest('div').find('input[data-type="long"]').val(lng);
	objDom.closest('div').find('input[data-type="lat"]').val(lat);
	objDom.closest('div').find('input[data-type="address"]').val(address);
	objDom.data({'long':lng,'lat':lat,'address':address}).val(address);
}

function get_worker(store_id,appoint_id){
			$.post(ajaxWorkUrl,{'merchant_store_id':store_id,'appoint_id':appoint_id},function(data){
			var str='<li data-merchant-worker-id="0" onClick="get_drop_down_com($(this))">请选择技师</li>';
			if(data.status){
				for(var i in data.worker_list){
					str+='<li data-merchant-worker-id="'+data.worker_list[i]["merchant_worker_id"]+'" onClick="get_drop_down_com($(this))">'+data.worker_list[i]["name"]+'</li>';
				}
				$('.merchant_workers').empty().html(str);
			}else{
				$('.merchant_workers').empty().html(str);
				getAppointTime(appoint_id);
			}
		},'json')
	}
	
function getWorkerTime(worker_id,appoint_id){
	$.post(ajaxWorkerTimeUrl,{'worker_id':worker_id,'appoint_id':appoint_id},function(data){
		var html='';
		if(data.status){
			function show(){
			   var mydate = new Date();
			   var str = "" + mydate.getFullYear() + "-";
			   str += ((mydate.getMonth() + 1)>10 ? (mydate.getMonth() + 1) : '0'+(mydate.getMonth() + 1)) + "-";
			   str += parseInt(mydate.getDate())>10 ? mydate.getDate() : '0' + mydate.getDate();
			   return str;
			 }
			  
			  function DateDiff(sDate1, sDate2) {  //sDate1和sDate2是yyyy-MM-dd格式
				var aDate, oDate1, oDate2, iDays;
				aDate = sDate1.split("-");
				oDate1 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0]);  //转换为yyyy-MM-dd格式
				aDate = sDate2.split("-");
				oDate2 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0]);
				iDays = parseInt(Math.abs(oDate1 - oDate2) / 1000 / 60 / 60 / 24); //把相差的毫秒数转换为天数
				return iDays;  //返回相差天数
			}
			  var currentDate=show();
			html+='<div class="yxc-pay-main yxc-payment-bg pad-bot-comm"><div class="yxc-time-con number-4">';
			for(var i in data.timeOrder){
				html+='<dl><dt data-role="date"';
				if(currentDate==i){
					html+='data-text="今天" class="active"';
				}else if(DateDiff(i,currentDate)==1){
					html+='data-text="明天"';
				}else if(DateDiff(i,currentDate)==2){
					html+='data-text="后天"';
				}else{
					html+='data-text="'+i+'"';
				}
				html+=' data-date="'+i+'">';
				if(currentDate==i){
					html+='今天';
				}else if(DateDiff(i,currentDate)==1){
					html+='明天';
				}else if(DateDiff(i,currentDate)==2){
					html+='后天';
				}else{
					html+=i;
				}
				html+='<span>'+i+'</span></dt></dl>'
			}
			html+='</div><div class="yxc-time-con" data-role="timeline">';
			
			for(var i in data.timeOrder){
				html+='<div class="date-'+i+' timeline"';
				if(currentDate!=i){
					html+='style="display:none"';
				}
				html+='>';
				for(var j in data.timeOrder[i]){
					html+='<dl><dd data-role="item" data-peroid="'+data.timeOrder[i][j]["start"]+'"';
					if(data.timeOrder[i][j]["order"]=='no' || data.timeOrder[i][j]["order"]=='all'){
						html+='class="disable"';
					}else{
						html+=' title="现可预约人数：'+data.timeOrder[i][j]["remain_num"]+'人"';
					}
					html+='>'+data.timeOrder[i][j]["start"]+'<br>';
					
					if(data.timeOrder[i][j]["order"]=='no'){
						html+='不可预约';
					}else if(data.timeOrder[i][j]["order"]=='all'){
						html+='已约满';
					}else{
						html+='可预约';
					}
					
					html+='</dd></dl>';
				}
				html+='</div>';
			}

			$('#service-date').empty().append(html);
			yxc_time_con_tab();
		}else{
			getAppointTime(appoint_id);
		}
	},'json');
}


function getAppointTime(appoint_id){
	$.post(ajaxAppointTimeUrl,{'appoint_id':appoint_id},function(data){
		var html='';
		if(data.status){
			function show(){
			   var mydate = new Date();
			   var str = "" + mydate.getFullYear() + "-";
			   str += ((mydate.getMonth() + 1)>10 ? (mydate.getMonth() + 1) : '0'+(mydate.getMonth() + 1)) + "-";
			   str += parseInt(mydate.getDate())>10 ? mydate.getDate() : '0' + mydate.getDate();
			   return str;
			 }
			  
			  function DateDiff(sDate1, sDate2) {  //sDate1和sDate2是yyyy-MM-dd格式
				var aDate, oDate1, oDate2, iDays;
				aDate = sDate1.split("-");
				oDate1 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0]);  //转换为yyyy-MM-dd格式
				aDate = sDate2.split("-");
				oDate2 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0]);
				iDays = parseInt(Math.abs(oDate1 - oDate2) / 1000 / 60 / 60 / 24); //把相差的毫秒数转换为天数
				return iDays;  //返回相差天数
			}
			  var currentDate=show();
			html+='<div class="yxc-pay-main yxc-payment-bg pad-bot-comm"><div class="yxc-time-con number-4">';
			for(var i in data.timeOrder){
				html+='<dl><dt data-role="date"';
				if(currentDate==i){
					html+='data-text="今天" class="active"';
				}else if(DateDiff(i,currentDate)==1){
					html+='data-text="明天"';
				}else if(DateDiff(i,currentDate)==2){
					html+='data-text="后天"';
				}else{
					html+='data-text="'+i+'"';
				}
				html+=' data-date="'+i+'">';
				if(currentDate==i){
					html+='今天';
				}else if(DateDiff(i,currentDate)==1){
					html+='明天';
				}else if(DateDiff(i,currentDate)==2){
					html+='后天';
				}else{
					html+=i;
				}
				html+='<span>'+i+'</span></dt></dl>'
			}
			html+='</div><div class="yxc-time-con" data-role="timeline">';
			
			for(var i in data.timeOrder){
				html+='<div class="date-'+i+' timeline"';
				if(currentDate!=i){
					html+='style="display:none"';
				}
				html+='>';
				for(var j in data.timeOrder[i]){
					html+='<dl><dd data-role="item" data-peroid="'+data.timeOrder[i][j]["start"]+'"';
					if(data.timeOrder[i][j]["order"]=='no' || data.timeOrder[i][j]["order"]=='all'){
						html+='class="disable" ';
					}else{
						html+=' title="现可预约人数：'+data.timeOrder[i][j]["remain_num"]+'人"';
					}
					html+='>'+data.timeOrder[i][j]["start"]+'<br>';
					
					if(data.timeOrder[i][j]["order"]=='no'){
						html+='不可预约';
					}else if(data.timeOrder[i][j]["order"]=='all'){
						html+='已约满';
					}else{
						html+='可预约';
					}
					
					html+='</dd></dl>';
				}
				html+='</div>';
			}

			$('#service-date').empty().append(html);
			yxc_time_con_tab();
		}else{
			$('.appoint-time').hide();
		}
	},'json')
}
var time_gap= Number('{pigcms{$now_group.time_gap}');

function yxc_time_con_tab(){
		console.log(333)
		$('.yxc-time-con dt[data-role="date"]').click(function(){
			console.log(time_gap)
			if(time_gap>0){
				$('.date-'+$(this).data('date')).show().siblings('div').hide();
			}else{
				if(!$(this).hasClass('disable')){
				$('.yxc-time-con dt[data-role="date"]').removeClass('active');
				$(this).addClass('active');
					var sDate = $('.yxc-time-con dt[data-role="date"].active').data('date');
					console.log(sDate)
					var sDay = $(this).data('peroid');
					
					$(this).addClass('active');
					
					$('#service_date').val(sDate);
					$('#serviceJobTime').find('span').html(sDate);
					art.dialog({id: 'service-time-handle'}).close();
				}
			}
		});
		if(time_gap>0){
			$('.yxc-time-con dd[data-role="item"]').click(function(){
				if(!$(this).hasClass('disable')){
					var sDate = $('.yxc-time-con dt[data-role="date"].active').data('date');
					var sDay = $(this).data('peroid');
					$('.yxc-time-con dd[data-role="item"]').removeClass('active');
					$(this).addClass('active');
					$('#serviceJobTime').val($('.yxc-time-con dt[data-role="date"].active').data('text') + ' ' +sDay).css({'color':'black','font-size':'14px'});
					$('#service_date').val(sDate);
					$('#serviceJobTime').find('span').html(sDate + ' ' +sDay);
					art.dialog({id: 'service-time-handle'}).close();
				}
			});
		}
}


</script>