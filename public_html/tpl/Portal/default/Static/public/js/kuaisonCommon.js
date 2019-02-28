function isIE6(){return getIEVersion() === '6'}
function getIEVersion(){
	var a=document;
	if(a.body.style.scrollbar3dLightColor!=undefined){
		if(a.body.style.opacity!=undefined){
			return "9"
		}else if(a.body.style.msBlockProgression!=undefined){
			return "8"
		}else if(a.body.style.msInterpolationMode!=undefined){
			return "7"
		}else if(a.body.style.textOverflow!=undefined){
			return "6"
		}else{
			return "IE5.5"
		}
	}
	return false;
}
function getYYkill(){//判断店铺营业状态
	var state_node = $('#state_node');
	if(isopen === '2'){
		state_node.addClass('state_2').html(noopenmark);
		return;
	}
	var iDate = new Date();
	var theYear = iDate.getFullYear();
	var theMonth = iDate.getMonth()+1;
	theMonth = theMonth>9?theMonth.toString():'0' + theMonth;
	var theDay = iDate.getDate();
	var today = theMonth + '/' + theDay + '/' + theYear + ' ';
	var thestartdate = Date.parse(today + startdate + ':00');
	var theenddate = Date.parse(today + enddate + ':00');
	var thestartdate2 = Date.parse(today + startdate2 + ':00');
	var theenddate2 = Date.parse(today + enddate2 + ':00');
	
	function showkill(allow){
		if(!!allow){
			state_node.addClass('state_1').html('营业中');
			window['ALLOWBUY'] = !0;
		}else{
			shipkill === '0'?(state_node.addClass('state_3').html('打烊了'),window['ALLOWBUY'] = !0):(state_node.addClass('state_2').html('打烊了'),window['ALLOWBUY'] = !1);
		}
	}
	if(theenddate<thestartdate){
		theenddate = theenddate + 86400000;
	}
	if(theenddate2<thestartdate2){
		theenddate2 = theenddate2 + 86400000;
	}
	if(startkill === '0' && start2kill === '0' ){
		showkill(!0);
		return false;
	}
	if((startkill === '1' && thestartdate < sys_time && sys_time<theenddate)||(start2kill === '1' && thestartdate2 < sys_time && sys_time<theenddate2)){
		showkill(!0);
	}else{
		showkill(!1);
	}
	
}
//保留小数点后两位
function changeTwoDecimal_f(x){
    var f_x = parseFloat(x);
    if (isNaN(f_x)) {
        alert('function:changeTwoDecimal->parameter error');
        return false;
    }
    var f_x = Math.round(x * 100) / 100;
    var s_x = f_x.toString();
    var pos_decimal = s_x.indexOf('.');
    if (pos_decimal < 0) {
        pos_decimal = s_x.length;
        s_x += '.';
    }
    while (s_x.length <= pos_decimal + 2) {
        s_x += '0';
    }
    return s_x;
}
function addFav(o,data){
	if($('#isLogin').val() === '0'){
		var url = siteUrl+'member/login.html?from='+encodeURIComponent(window.location.href);
		MSGwindowShow('revert','1','对不起，请登录后再进行收藏！',url,'');
		return false;
	}
	var url = siteUrl+'request.ashx?action=addshoucang&shopid='+data.shopid+'&id='+data.productid+'&styleid='+data.styleid+'&jsoncallback=?&timer='+Math.random();
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			$(o).addClass('favok').html('收藏成功');
		}else{
			MSGwindowShow('shopping','0',data[0].error,'','');
		}
	});
}
function delFav(data){
	var url = siteUrl+'request.ashx?action=delshoucang&shopid='+data.shopid+'&id='+data.productid+'&jsoncallback=?&timer='+Math.random();
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			MSGwindowShow('shopping','1','删除收藏成功！',window.location.href,'');
		}else{
			MSGwindowShow('shopping','0',data[0].error,'','');
		}
	});
}
function addeditAddress(chrname,chraddress,mobile,sid,styleid,ismoren,s_typeid,shop_x,shop_y,shop_z,ggmap,streetnumber){
	var url=siteUrl+'request.ashx?action=addmyaddress&typeid='+s_typeid+'&styleid='+styleid+'&id='+sid+'&ismoren='+ismoren+'&ishtml=1&chrname='+chrname+'&chraddress='+chraddress+'&mobile='+mobile+'&streetnumber='+streetnumber+'&shop_x='+shop_x+'&shop_y='+shop_y+'&shop_z='+shop_z+'&ggmap='+ggmap+'&jsoncallback=?&timer='+Math.random();
	
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			$('#addeditNode').hide();
			$('#addeditMask').hide();
			$('#addressList').find('.item').remove()
			$('#addressList').prepend(data[0].MSG);
			$('#addressid').val($('#addressList').find('.cur1').attr('data-id'));
		}else{
			MSGwindowShow('shopping','0',data[0].error,'','');
		}
	});
}
function setmorenMyAddress(typeid,sid,ismoren,node){
	var url=siteUrl+'request.ashx?action=addmyaddress&typeid='+typeid+'&styleid=2&id='+sid+'&ismoren='+ismoren+'&jsoncallback=?&timer='+Math.random();
	
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			node.parent().siblings('li').removeClass('ismoren1').end().parent().find('.edit').attr({'data-ismoren':'0'});
			node.parent().addClass('ismoren1').find('.edit').attr({'data-ismoren':'1'});
			node.hide();
		}else{
			MSGwindowShow('shopping','0',data[0].error,'','');
		}
	});
}
function delMyAddress(sid,node){
	var url=siteUrl+'request.ashx?action=getmyaddress&delid='+sid+'&jsoncallback=?&timer='+Math.random();
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			node.parent().remove();
		}else{
			MSGwindowShow('shopping','0',data[0].error,'','');
		}
	});
}
function setShoppingCart(sid,gid,num,delid,typeid,customids){
	if(!window['ALLOWBUY']){
		MSGwindowShow('shopping','0','打烊了，暂时不能下单购买！','','');
		return false;
	}
	var i_customids = '&customids=',arr=[];
	
	if(typeof customids !== 'undefined'){
		i_customids += customids;
	}else{
		arr = JSON.parse(window['arr_customids_'+gid]);
		if(arr.length>0){
			i_customids += arr.join(',');
		}
	}
	var url=siteUrl+'request.ashx?action=addmyshopping&id='+sid+'&gid='+gid+'&styleid='+window['GOODSTYLEID']+'&num='+num+'&shopid='+window['SHOPID']+i_customids+'&ishtml='+window['ISHTML']+'&delid='+delid+'&jsoncallback=?&timer='+Math.random();
	
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			showShoppingCart(!0,data[0]);
			showShoppingList(data[0].JSONMSG);
			
		}else{
			if(typeof typeid !== 'undefined'){
				if('increase' === typeid){
					$('#gouwuche'+sid).val(parseInt($('#gouwuche'+sid).val())-1);
				}
			}
			MSGwindowShow('shopping','0',data[0].error,'','');
		}
	});
}
function getShoppingCart(ishide,delid){
	var Delid = delid || '';
	var url=siteUrl+'request.ashx?action=getmyshopping&shopid='+window['SHOPID']+'&ishtml='+window['ISHTML']+'&delid='+Delid+'&jsoncallback=?';
	$.getJSON(url,function(data){
		if(data[0].islogin === '1'){
			showShoppingCart(ishide,data[0]);
			showShoppingList(data[0].JSONMSG);
			
		}else{
			MSGwindowShow('shopping','0',data[0].error,'','');
		}
	});
}
function showShoppingCart(ishide,data){
	
	if($('#header_cart').css('display') === 'none'){
		$(window).scrollTo( '+=80',0,function(){});
	}
	
	$('#h_cart_inner').find('.list').html(data.MSG);
	$('#h_cart_num').html(data['CHRMONEY']['numAll']);
	$('#ShoppingCartNumAll').attr('data-numall',data['CHRMONEY']['numAll']);
	$('#chrmoneyAll').html(data['CHRMONEY']['chrmoneyAll']);
	if(parseInt(data['CHRMONEY']['numAll'])>0){
		$('#submitGo').removeClass('disabled').unbind('click');
	}else{
		$('#submitGo').addClass('disabled').bind('click',function(e){e.preventDefault();});
	}
	setTimeout(function(){
		showQison(parseFloat($('#shipmoney1').html()),parseFloat($('#chrmoneyAll').html()));ishide&&$('#h_cart').trigger('mouseenter');
		$('#h_cart_inner .item').customid_data_txt();
	},500);
	
}
function showShoppingList(jsonMSG){
	if(window['ISBL'] === '0'){
		return false;
	}else if(window['ISBL'] === '1'){
		var i=0,len = jsonMSG.length;
		$('#prolist').find('.buycar:hidden').show().end().find('.buycar2:visible').hide().end().find('.link').attr('data-showBuy','1');
		for( ;i<len;i++){
			$('#item_'+jsonMSG[i]['goodid']).find('.buycar2').show().end().find('.buycar').hide().end().find('.link').attr('data-showBuy','0');
		}
	}else if(window['ISBL'] === '2'){
		var i=0,len = jsonMSG.length;
		$('#prolist').find('.buycar').css({'display':''}).end().find('.buycar2').hide().end().find('.link').attr('data-showBuy','1');
		for( ;i<len;i++){
			$('.item_'+jsonMSG[i]['goodid']).find('.buycar2').show().end().find('.buycar').hide().end().find('.link').attr('data-showBuy','0');
		}
	}else{
		
	}
}
function showQison(val_1,val_2){
	var val1 = changeTwoDecimal(val_1);
	var val2 = changeTwoDecimal(val_2);
	
	if(val2 === 0){$('#submitGo').addClass('disabled').bind('click',function(e){e.preventDefault();});$('#distanceNode').hide();return false;}
	if((val1 > 0) && (val1 > val2)){
		if(!$('#distanceNode')[0]){
			$('<div id="distanceNode" class="distanceNode"></div>').insertAfter("#submitGo");
		}
		$('#distanceNode').html('该店最小起送金额为'+val1+'元，还差' + changeTwoDecimal(val1 - val2) + '元<span class="arrow"></span>').show();
		$('#submitGo').addClass('disabled').bind('click',function(e){e.preventDefault();});
	}else{
		$('#distanceNode').hide();
		$('#submitGo').removeClass('disabled').unbind('click');
	}
}
function showHide(e,objname){     
    var obj = $('#'+objname),
		inner = $('#list_nav_2013'),
		uls = inner.find('.po:visible');
	obj.toggle();
	$(e).parent().parent().toggleClass('open');
	uls.css({'display':'none'});
	uls.parent().removeClass('open');
}


(function(){
	$.fn.addressList = function(){
		var $t = $(this),addressid = $('#addressid'),addeditNode = $('#addeditNode'),addeditMask = $('#addeditMask'),s_id=$('#s_id'),s_styleid = $('#s_styleid'),s_ismoren = $('#s_ismoren'),shop_x = $('#shop_x'),shop_y = $('#shop_y'),shop_z = $('#shop_z'),ggmap = $('#ggmap'),$streetnumber = $('#streetnumber');
		
		var showAddEditAddress = function(){
			var d_h = $(document).height(),
				d_w = $(window).width(),
				w_h = $(window).height(),
				t_h=addeditNode.height(),
				r_h = parseInt((w_h-t_h)/2);
			addeditMask.css({'height':d_h+'px','width':d_w+'px'});
			addeditNode.css({'top':r_h+'px'});
			$(window).bind("resize",function(){
				w_h = $(window).height();
				r_h = parseInt((w_h-t_h)/2);
				if(!isIE6()){
					addeditNode.css({'top':r_h+'px'});
				}else{
					var d = $(document).scrollTop();
					addeditNode.css({'top':d+r_h+'px'});
				}
			});
			$(window).bind("scroll",function(){
				if(!isIE6()) return;
				showWin();
			});
			function showWin(){
				var d = $(document).scrollTop();
				addeditNode.css({'top':d+r_h+'px'});
			}
		};
		setTimeout(function(){showAddEditAddress();},50);
		
		addressid.val($('input[name="sleAdress"]:checked').val());
		$t.on('mouseenter','.item:not(".ismoren1")',function(){
			$(this).find('.moren').show();
		}).on('mouseleave','.item:not(".ismoren1")',function(){
			$(this).find('.moren').hide();		 
		}).on('click','.item',function(){
			$t.find('.item').removeClass('cur1');
			$(this).addClass('cur1');
			addressid.val($(this).attr('data-id'));
		}).on('click','.moren',function(event){
			event.preventDefault();
			event.stopPropagation();
			setmorenMyAddress($(this).parent().attr('data-typeid'),$(this).attr('data-id'),'1',$(this));
		}).on('click','.edit',function(event){
			event.preventDefault();
			event.stopPropagation();
			s_id.val($(this).attr('data-id'));
			s_styleid.val('1');
			
			shop_x.val($(this).attr('data-x'));
			shop_y.val($(this).attr('data-y'));
			shop_z.val($(this).attr('data-z'));
			ggmap.val($(this).attr('data-ggmap'));
			$streetnumber.val($(this).parent().find('.chraddress2').html());
			$('#s_address').val($(this).parent().find('.chraddress1').html());
			$('#s_realname').val($(this).parent().find('.chrname').html());
			
			$('#s_phone').val($(this).siblings('.tel').html());
			addeditNode.show();
			addeditMask.show();
		}).on('click','.del',function(e){
			e.preventDefault();
			delMyAddress($(this).attr('data-id'),$(this));
		}).on('click','#addAddress_btn',function(e){
			e.preventDefault();
			s_id.val('');
			s_styleid.val('');
			//s_ismoren.val('');
			$('#myformAddress').trigger('reset');
			addeditNode.show();
			addeditMask.css({'height':$(document).height()+'px'}).show();
			
		});
		
		addeditNode.on('click','.close',function(e){
			e.preventDefault();
			$('#myformAddress').trigger('reset');
			addeditNode.hide();
			addeditMask.hide();
		});
	}
	$.fn.superIMG = function(){
		var node=$(this),dialog_pro = $('#dialog_pro'),img = $('#dialog_img'),mask = $('#img_mask'),po = $('#po_bigView'),superIMG = $('#superPIC');
		var w_w = $(window).width(),dialog_pro_w = dialog_pro.width(),offset_x = (w_w - dialog_pro_w)/2;
		
		node.mousemove(function(event){
			var offset = dialog_pro.offset(),offset_y = offset.top;
			mask.css('display','block');
			po.show();
			var mousex = event.pageX - offset_x -21;
			var mousey = event.pageY - offset_y -21;
			var mask_x = mousex-75;
			var mask_y = mousey -75;
			if(mask_x<0)mask_x = 0;
			if(mask_y<0)mask_y = 0;
			if(mask_x+150 > 360)mask_x = 210;
		if(mask_y+150 > 360)mask_y = 210;
			mask.css({'left':mask_x,'top':mask_y});
			superIMG.css({'left':-mask_x*2.6,'top':-mask_y*2.6});
		});
		mask.mouseout(function(){
			mask.css('display','');
			po.hide();
		});
	}
	$.fn.superIMG2 = function(){
		var node=$(this),dialog_pro = $('#dialog_pro'),img = $('#dialog_img'),mask = $('#img_mask'),po = $('#po_bigView'),superIMG = $('#superPIC');
		//var w_w = $(window).width(),dialog_pro_w = dialog_pro.width(),offset_x = (w_w - dialog_pro_w)/2;
		
		node.mousemove(function(event){
			var offset = dialog_pro.offset(),offset_y = offset.top,offset_x = offset.left;
			mask.css('display','block');
			po.show();
			var mousex = event.pageX - offset_x -15;
			var mousey = event.pageY - offset_y -15;
			var mask_x = mousex-75;
			var mask_y = mousey -75;
			if(mask_x<0)mask_x = 0;
			if(mask_y<0)mask_y = 0;
			if(mask_x+150 > 360)mask_x = 210;
		if(mask_y+150 > 360)mask_y = 210;
			mask.css({'left':mask_x,'top':mask_y});
			superIMG.css({'left':-mask_x*2.6,'top':-mask_y*2.6});
		});
		mask.mouseout(function(){
			mask.css('display','');
			po.hide();
		});
	}
	function getCustomData(sid){
		var url=siteUrl+'api/mallapi.ashx?action=getcustom&styleid=1&id='+sid+'&jsoncallback=?&date='+new Date();
		$.getJSON(url,function(data){
			if(data[0].islogin !== '1'){return false;}
			$('#i_buy_inner').html(data[0].MSG).show();
			setTimeout(function(){
				if($('.select_data')[0]){
					$('.select_data').makePriceData();
					window['hasCC'] = true;
				}
			},20);
		}).error(function(){alert("对不起，操作失败！");});
	}
	$.fn.getiscustomList = function(){
		var t = $(this),list = t.find('.link'),arrid=[],txtid='';
		if(list.length === 0){
			$('#prolist').pagelist2();
			return false;
		}
		list.each(function(){
			arrid.push($(this).attr('data-id'));
		});
		txtid = arrid.join(',');
		var url = siteUrl+'api/mallapi.ashx?action=getiscustom&styleid=1&id='+txtid+'&jsoncallback=?&date='+new Date();
		$.getJSON(url,function(data){
			if(data[0].islogin !== '1'){return false;}
			for(var i=0; i<list.length;i++){
				t.find('.link[data-id="'+data[0]['MSG'][i]['id']+'"]').attr('data-iscustom',data[0]['MSG'][i]['is']);
			}
			$('#prolist').pagelist2();
		});
	}
	$.fn.pagelist2 = function(){
		var node = $(this),
			list = node.find('.link'),
			dialog = $('#dialog_pro'),
			dialog_tit = $('#dialog_tit'),
			dialog_price = $('#dialog_price'),
			dialog_price1 = $('#dialog_price1'),
			dialog_kcnum = $('#dialog_kcnum'),
			dialog_proid = $('#dialog_proid'),
			dialog_num = $('#dialog_num');
		list.each(function(){
			if($(this).attr('data-styleid') === '0' && $(this).attr('data-kcnum') === '0'){
				$(this).find('.maiguang').css('display','block');
				$(this).find('.buycar').remove();
				return false;
			}
			if($(this).attr('data-iscustom') === '1'){
				$(this).find('.buycar').html('可选规格');
			}
		});
		function resetTop(){
			var d = $(document).scrollTop(),
	   			e = $(window).height(),
				f=dialog.height();
				dialog.css({'top':parseInt((e-f)/2+d)+'px'}).show();	
		}
		list.bind('click',function(e){
			e.preventDefault();
			if($(this).attr('data-iscustom') === '1'){
				var iscustom = $(this).attr('data-iscustom');
				if(iscustom === '1'){//是否有可选配置
					var sid=$(this).attr('data-id');
					getCustomData(sid);//ajax载入配置项
				}else{
					$('#i_buy_inner').empty().hide();
					window['hasCC'] = false;
				}
				dialog_tit.html($(this).attr('data-title'));
				dialog_price1.html($(this).attr('data-price1'));
				dialog_price.html($(this).attr('data-price')).attr('data-price',$(this).attr('data-price'));
				dialog_kcnum.html($(this).attr('data-kcnum'));
				dialog_proid.val($(this).attr('data-id'));
				resetTop();
				return false;
			}
			if($(this).attr('data-showBuy')=='1'){
				setShoppingCart($(this).attr('data-id'),'','1','',null,'');
			}else{
				$('#h_cart').trigger('mouseenter');
			}
		});
		//购物车 开始
		$('#addto').click(function(e){
			e.preventDefault();
			if(!window['hasCC']){
				shopping();
			}else{
				if(!!checkBuyForm(true)){
					shopping();
				}
			}
		});
		$('#buyok').click(function(e){
			e.preventDefault();
			$('#i_buy').removeClass('i_buy_open');
			$('#buyok').parent().removeClass('visible');
			shopping();
		});
		//购物车结束
		dialog.reduce_increase();
	}
	
	$.fn.pagelist = function(){
		var node = $(this),
			list = node.find('.item'),
			dialog = $('#dialog_pro'),
			dialog_tit = $('#dialog_tit'),
			dialog_img = $('#dialog_img'),
			dialog_imgList = $('#dialog_imgList'),
			dialog_goodnum = $('#dialog_goodnum'),
			dialog_brand = $('#dialog_brand'),
			dialog_guige = $('#dialog_guige'),
			dialog_price = $('#dialog_price'),
			dialog_price1 = $('#dialog_price1'),
			dialog_kcnum = $('#dialog_kcnum'),
			dialog_proid = $('#dialog_proid'),
			dialog_num = $('#dialog_num'),
			dialog_num_node = $('#dialog_num_node'),
			dialog_submit = $('#dialog_submit'),
			dialog_button = $('#dialog_button'),
			dialog_shopLink = $('#dialog_shopLink'),
			dialog_shopLink_details = $('#dialog_shopLink_details');
			if_tuwen = !1;
		
		function showPicList(txt){
			var data = JSON.parse(txt);
			var arr = data['smallimg'];
			var arr_b = data['bigimg'];
			if(!arr || arr.length==0){
				dialog_imgList.html('<li class="cur"><a href="'+window['tplPath']+'images/kuaison_nofind_product2.gif" class="item"><img src="'+window['tplPath']+'images/kuaison_nofind_product2.gif" alt="" /></a><s class="arrow"></s></li>');
				dialog_img.attr('src',window['tplPath']+'images/kuaison_nofind_product2.gif');
				$('#superPIC').attr('src',window['tplPath']+'images/kuaison_nofind_product2.gif');
				return;
			}
			var len=arr.length,txt='',fristClass='',tPrev=$('#dialog_img_prev'),tNext=$('#dialog_img_next'),cellW=82,tIndex=0;
			
			dialog_img.attr('src',arr_b[0]);
			$('#superPIC').attr('src',arr_b[0]);
			tPrev.addClass('btn_disabled').unbind('click');
			tNext.removeClass('btn_disabled').unbind('click');
			dialog_imgList.css({'left':'0'});
			
			for(var i=0;i<len;i++){
				if(i === 0){fristClass="cur";}else{fristClass='';}
				txt += '<li class="'+fristClass+'"><a href="'+arr_b[i]+'" data-super="'+arr_b[i]+'" class="item"><img src="'+arr[i]+'" alt="" /></a><s class="arrow"></s></li>';
			}
			dialog_imgList.html(txt);
			
			if(len<4){
				tNext.addClass('btn_disabled');
				return;
			}
			dialog_imgList.css({'width':cellW*len+'px'});
			
			tPrev.click(function(e){
				if(tIndex-1>-1){
					tIndex--;
					if(tIndex === 0){tPrev.addClass('btn_disabled');}
					tNext.removeClass('btn_disabled');
					dialog_imgList.animate({left:'+='+cellW},300,function(){});
				}else{
					tIndex = 0;
				}
				e.preventDefault();
			});
			tNext.click(function(e){
				if(tIndex+1<len-3){
					tIndex++;
					if(tIndex === len-4){tNext.addClass('btn_disabled');}
					tPrev.removeClass('btn_disabled');
					dialog_imgList.animate({left:'-='+cellW},300,function(){});
				}else{
					tIndex=len-4;
					
				}
				e.preventDefault();
			});
		}
		function resetTop(){
			var d = $(document).scrollTop(),
	   			e = $(window).height(),
				f=dialog.height();
				dialog.css({'top':parseInt((e-f)/2+d)+'px'}).show();	
		}
		dialog_imgList.on('click','.item',function(e){
			e.preventDefault();
			dialog_imgList.find('.item').parent().removeClass('cur');
			$(this).parent().addClass('cur');
			dialog_img.attr('src',$(this).attr('href'));
			$('#superPIC').attr('src',$(this).attr('data-super'));
		});
		dialog_shopLink.bind('click',function(e){
			e.preventDefault();
			if(!!if_tuwen){
				if_tuwen = !1;
				dialog.removeClass('dialog_tuwen');
				$('#dialog_shopLink').html('查看商品详情');
				$('#dialog_shopLink_tit').html('商品属性');
				$('#dialog_shopLink_change').show();
			}else{
				if_tuwen = !0;
				dialog.addClass('dialog_tuwen');
				$('#dialog_shopLink').html('返回');
				$('#dialog_shopLink_tit').html('商品详情');
				$('#dialog_shopLink_change').hide();
				dialog_shopLink_details.attr('src',siteUrl+'k'+dialog_shopLink_details.attr('data-shopid')+'_g'+dialog_shopLink_details.attr('data-goodid')+'.html');
			}
			resetTop();
		});
		list.each(function(){
			if($(this).find('.buycar').attr('data-styleid') === '1'){
				$(this).find('.buycar').html('<em class="em"></em><span class="sp">点击进入购买</span>');
			}
			if($(this).find('.link').attr('data-styleid') === '0' && $(this).find('.link').attr('data-kcnum') === '0'){
				$(this).find('.maiguang').css('display','block');
			}
			if($(this).find('.link').attr('data-iscustom') === '1'){
				$(this).find('.buycar').addClass('buycar3').html('<em class="em"></em><span class="sp">可以选择规格</span>');
			}
			$(this).find('.link').bind('click',function(e){
				e.preventDefault();
				var iscustom = $(this).attr('data-iscustom');
				if(iscustom === '1'){//是否有可选配置
					//ajax载入配置项
					var sid=$(this).attr('data-id');
					getCustomData(sid);
					
					
				}else{
					$('#i_buy_inner').empty().hide();
					window['hasCC'] = false;
				}
				if(!!if_tuwen){
					if_tuwen = !1;
					dialog.removeClass('dialog_tuwen');
					$('#dialog_shopLink').html('查看商品详情');
					$('#dialog_shopLink_tit').html('商品属性');
					$('#dialog_shopLink_change').show();
					dialog_shopLink_details.attr('src','about:blank');
				}
				
				dialog_img.attr('src',window['tplPath']+'images/kuaison_nofind_product2.gif');
				showPicList($(this).attr('data-piclist'));
				dialog_tit.html($(this).attr('data-title'));
				dialog_goodnum.html($(this).attr('data-goodnum'));
				dialog_brand.html($(this).attr('data-brand'));
				dialog_guige.html($(this).attr('data-xinghao'));
				dialog_price1.html($(this).attr('data-price1'));
				dialog_price.html($(this).attr('data-price')).attr('data-price',$(this).attr('data-price'));
				dialog_kcnum.html($(this).attr('data-kcnum'));
				dialog_proid.val($(this).attr('data-id'));
				dialog_submit.parent().attr({'data-styleid':$(this).attr('data-styleid'),'data-httpurl':$(this).attr('data-httpurl')});
				dialog_shopLink_details.attr({'data-shopid':$(this).attr('data-shopid'),'data-goodid':$(this).attr('data-id')});
				if($(this).attr('data-styleid') === '1'){
					dialog_submit.html('点击进入购买');
				}else{
					dialog_submit.html('添加到购物车');
				}
				resetTop();
			});
			$(this).find('.buycar').bind('click',function(e){
				e.preventDefault();
				
				var styleid = $(this).attr('data-styleid');
				if(styleid === '1'){
					window.open($(this).attr('data-httpurl'),'_blank');
					return false;
				}
				var i_link = $(this).parent().find('.link');
				if(i_link.attr('data-iscustom') === '1'){
					i_link.trigger('click');
					return false;
				}
				setShoppingCart($(this).attr('data-id'),'','1','',null,'');
			});
		});
		
		//购物车 开始
		$('#addto').click(function(e){
			e.preventDefault();
			if(!window['hasCC']){
				shopping();
			}else{
				if(!!checkBuyForm(true)){
					shopping();
				}
			}
		});
		$('#buyok').click(function(e){
			e.preventDefault();
			$('#i_buy').removeClass('i_buy_open');
			$('#buyok').parent().removeClass('visible');
			shopping();
		});
		//购物车结束
		dialog.reduce_increase();
	}
	$.fn.reduce_increase = function(){
		var dialog = $(this);
		dialog.find('.reduce').click(function(e){
			e.preventDefault();
			var now_node = $(this).siblings('.n_ipt'),
				now_val = parseInt(now_node.val());
			if(now_val===1){return false;}
			--now_val === 1?($(this).addClass('disabled'),now_val=1):($(this).removeClass('disabled'));
			now_node.val(now_val);
			$(this).siblings('.increase').removeClass('disabled');
		});
		dialog.find('.increase').click(function(e){
			e.preventDefault();
			var now_node = $(this).siblings('.n_ipt'),
				now_val = parseInt(now_node.val());
			if(now_val===100){return false;}
			++now_val === 100?($(this).addClass('disabled'),now_val=100):($(this).removeClass('disabled'));
			now_node.val(now_val);
			$(this).siblings('.reduce').removeClass('disabled');
		});
		dialog.find('.close').bind('click',function(e){
			e.preventDefault();
			dialog.hide();
		});
	}
	function shopping(){
		var t_customids = '';
		$('.formnumber_ipt').each(function(){
			t_customids+=$(this).val()+',';
		});
		if($('#addto').attr('data-styleid') === '1'){
			window.open($('#addto').attr('data-httpurl'),'_blank');
			return false;
		}
		$('#dialog_pro').hide();
		setShoppingCart($('#dialog_proid').val(),'',$('#dialog_num').val(),'',null,t_customids);
	}
	function checkBuyForm(isShowLayer){
		var isok = true;
		$('#i_buy_inner').find('.formnumber_ipt').each(function(){
			if($(this).val() === ''){
				isok = false;
				return false;
			}
		});
		if(!isok){
			isShowLayer && $('#i_buy').addClass('i_buy_open');
			return false;
		}
		$('#buyok').parent().addClass('visible');
		return isok;
	}
	function calculateResult(){
		var i_buy = $('#i_buy'),
			items = i_buy.find('.item_btn').filter('.current'),
			mallPrice = $('#dialog_price'),priceResult=parseFloat(mallPrice.attr('data-price'));
		items.each(function(i,item){
			var formtype = $(item).attr('data-formtype'),
				moneyf = $(item).attr('data-moneyf');
			if(formtype === '0'){
				priceResult = parseFloat(moneyf);
			}else{
				priceResult = priceResult+parseFloat(moneyf);
			}
		});
		mallPrice.html(changeTwoDecimal(priceResult));
	}
	$.fn.makePriceData = function(){
		var t = $(this);
		return this.each(function(){
			var t = $(this),items = t.find('.item_btn');
			items.bind('click',function(e){
				e.preventDefault();
				items.removeClass('current');
				$(this).addClass('current').find('.radio').prop('checked',true);
				$('#i_buy_inner #formnumber_'+$(this).attr('data-formnumber')).val($(this).attr('data-id'));
				checkBuyForm();
				setTimeout(function(){calculateResult();},100);
			});
		});
	}
	$.fn.header_cart = function(){
		//跟随滚动
		var t = $(this),
			ie6 = isIE6(),
			h = $('#header'),
			c_inner = 'h_cart_inner',
			_curInst = !1;
		$(window).bind("scroll",function(){
			var d = $(document).scrollTop();
			close_box();
			if(h.height() < d){
				if(t.is(":hidden")){t.show();} 
				if(!!ie6){
					t.css({'position':'absolute','top':d+'px'});
				}else{
					t.css({'position':'fixed'});
				}
			}else{
				if(t.is(":visible")){t.hide();} 
				t.css({'position':'relative'});
			}
		});
		
		//显示隐藏下拉
		$('#h_cart').bind('mouseenter',function(){
			_curInst = !0;
			$('#'+c_inner).slideDown('fast');
		});
		$(document).mousedown(function(event){
			_checkExternalClick(event);
		});
		function close_box(){
			_curInst = !1;
			$('#'+c_inner).slideUp('fast');
		}
		function _checkExternalClick(event){
			if(!_curInst) return;
			var $target = $(event.target);
			if(($target.parents('#' + c_inner).length == 0)){
				 close_box();
			}
		}
		//购物车操作
		$('#'+c_inner).on('click','.del', function(e){
			var sid = $(this).attr('data-id');
				getShoppingCart(!1,sid);
				e.preventDefault();
		});
		$('#'+c_inner).on('click','.reduce', function(e){
			e.preventDefault();
			var now_node = $(this).siblings('.n_ipt'),
				now_val = parseInt(now_node.val()),
				sid = $(this).attr('data-id');
			if(now_val===1){return false;}
			--now_val === 1?($(this).addClass('disabled'),now_val=1):($(this).removeClass('disabled'));
			now_node.val(now_val);
			$(this).siblings('.increase').removeClass('disabled');
			setShoppingCart(sid,now_node.attr('data-id'),now_val,'','reduce');
		});
		$('#'+c_inner).on('click','.increase', function(e){
			e.preventDefault();
			var now_node = $(this).siblings('.n_ipt'),
				now_val = parseInt(now_node.val()),
				sid = $(this).attr('data-id');
			if(now_val===100){return false;}
			++now_val === 100?($(this).addClass('disabled'),now_val=100):($(this).removeClass('disabled'));
			now_node.val(now_val);
			$(this).siblings('.reduce').removeClass('disabled');
			setShoppingCart(sid,now_node.attr('data-id'),now_val,'','increase');
		});
	}
	$.fn.keyword = function(formId,iptId,oldValue){
		var t = $(this),form = $('#'+formId),ipt = $('#'+iptId);
		ipt.focus(function(e){
			t.addClass('focus');
		});
		ipt.blur(function(e){
			t.removeClass('focus');
		});
		form.submit(function(e){
			if(ipt.val() === oldValue){
				MSGwindowShow('shopping','0','请输入关键字','','');
				ipt.focus();
				return false;
			}
		});
	}
	$.fn.slideText = function(){
		var t = $(this),
			list = t.find('.inner'),
			len = list.length-1,
			next_btn = t.find('#next'),
			prev_btn = t.find('#prev'),
			c_index = 0;
		function showIndex(increasing){
			!!increasing?(c_index<len&&(c_index++,prev_btn.removeClass('disable'))):(c_index>0&&(c_index--,next_btn.removeClass('disable')));
			list.css('display','none');
			if(c_index === len){next_btn.addClass('disable')}
			if(c_index === 0){prev_btn.addClass('disable')}
			list.eq(c_index).css('display','block');
		}
		next_btn.click(function(e){
			showIndex(!0);
			e.preventDefault();
		});
		prev_btn.click(function(e){
			showIndex(!1);
			e.preventDefault();
		});
		showIndex(c_index);
	}
	$.fn.fixed = function(can,posi){
		if(isIE6()){return false;}
		var b = $(this),h = b.height(),offset = b.offset(),top = offset.top,bottom = $('#footer').outerHeight(true),d_h = $(document).height(),w_h = $(window).height();
		if(can.height()<h){return;}
		$(window).bind("scroll",function(){
			var d = $(document).scrollTop(),h = b.height(),s_h = d_h-bottom-h,s_b = $('#footer').offset().top-h-32;
			
			if(top < d){
				if((s_h - d - posi)<0){
					b.css({'position':'absolute','top':s_b+'px'});
				}else{
					b.css({'position':'fixed','top':posi+'px'});
				}
			}else{
				b.css({'position':'relative','top':'0'});
			}
		});
	}
	/*$.fn.selStar = function(){
		var t = $(this),list = t.find('.s_star'),score_1 = $('#score_1');
		list.click(function(e){
			e.preventDefault();
			var val = $(this).attr('data-index');
			list.parent().removeClass().addClass('i_star_'+val);
			score_1.val(val);
		});
	};*/
	$.fn.selStar = function(){
		var t = $(this),list = t.find('.s_star'),score_1 = $('#score_1'),total_score = $('#total_score'),pj_txt = $('#pj_txt');
		list.click(function(e){
			e.preventDefault();
			var val = $(this).attr('data-index');
			list.parent().removeClass().addClass('i_star_'+val);
			score_1.val(val);
			if(val==1){
				total_score.val(3);
				pj_txt.removeClass().addClass('t3').text('差评');
				}
			else if(val > 1 && val < 4){
				total_score.val(2);
				pj_txt.removeClass().addClass('t2').text('中评');
				}
			else{
				total_score.val(1);
				pj_txt.removeClass().addClass('t1').text('好评');
				}	
		});
	};
	$.fn.selScore = function(){
		var t = $(this),list = t.find('.btn'),total_score = $('#total_score');
		list.click(function(e){
			e.preventDefault();
			var val = $(this).attr('data-index');
			list.removeClass('cur');
			$(this).addClass('cur');
			total_score.val(val);
		});
	};
	$.fn.iRenLing = function(){
		var renLingNode = $(this),renLingMask = $('#renLingMask'),renLingBtn = $('#renLingBtn');
		
		var d_h = $(document).height(),
			d_w = $(window).width(),
			w_h = $(window).height(),
			t_h=renLingNode.height(),
			r_h = parseInt((w_h-t_h)/2);
		renLingMask.css({'height':d_h+'px','width':d_w+'px'});
		renLingNode.css({'top':r_h+'px'});
		$(window).bind("resize",function(){
			w_h = $(window).height();
			r_h = parseInt((w_h-t_h)/2);
			if(!isIE6()){
				renLingNode.css({'top':r_h+'px'});
			}else{
				var d = $(document).scrollTop();
				renLingNode.css({'top':d+r_h+'px'});
			}
		});
		$(window).bind("scroll",function(){
			if(!isIE6()) return;
			showWin();
		});
		function showWin(){
			var d = $(document).scrollTop();
			renLingNode.css({'top':d+r_h+'px'});
		}
		
		renLingBtn.click(function(e){
			e.preventDefault();
			renLingNode.show();
			renLingMask.css({'height':$(document).height()+'px'}).show();
		});
		renLingNode.on('click','.close',function(e){
			e.preventDefault();
			renLingNode.hide();
			renLingMask.hide();
		});
		renLingMask.bind('click',function(){
			renLingNode.hide();
			renLingMask.hide();
		});
	}
	$.fn.customid_data_txt = function(){
		return this.each(function(){
			var t = $(this),list = t.find('.select_data'),txt='';
			list.each(function(index,item){
				txt+=$(item).attr('data-chrkey')+'：';
				if(!$(item).find('.radio:checked')[0]){
					txt+='无　';
				}else{
					txt+=$(item).find('.radio:checked').attr('data-chrvalue')+'　';	
				}
			});
			t.find('.custom').html(txt);
		});
	};
})(jQuery);