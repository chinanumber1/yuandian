var isClick =0;
$(document).ready(function(){
	/*//showbtn();//下一步按钮显示
	var menu_ul_lis =$("#usermenu li");
	for(var i=0;i<menu_ul_lis.length;i++){
		var li =menu_ul_lis[i];
		var num=$("input",$(li)).val();
		if(num==""){
			$("input",$(li)).val(0);
			num=0;
		}
		if(num==0){
			$(".minus",$(li)).hide();
			$("input",$(li)).hide();
		}
	}
	$("#usermenu .all ul li .plus").click(function(){
		plus(this)										   
	});
	$("#usermenu .all ul li .minus").click(function(){
		minus(this);										   
	});
	*/

	$('#menuList .plus').each(function(){
		$(this).amount(0, $.amountCb());
		for(var i = 0, num = parseInt($(this).data('num')); i < num; i++){
			$(this).click();
		}
	});

	
	var _wraper = $('#menuDetail');

	var dialogTarget;
	var num = 0;
	var saleunit= "";
	
	$('#menuList .showPop').click(function(e){
		num = 0;
		var _this = $(this).parent('.licontent'),
			F = function(str){return _this.find(str);},
			title = F('h3').text(),
			imgUrl = F('img').attr('url'),
			price = F('.unit_price').text(),
			sales = F('.sales strong').attr('class'),
			saleNum = F('.sale_num').text(),
			info = F('.info').text(),			
//			original_price = F('.unit_price').attr('original-price'),
			max_mun = F('.fr').attr('max'),
			p_in = F('p').html(),
			_detailImg = _wraper.find('img');
			num = $(".num input", _this).val();
			saleunit = F('.theunit').text();
			_wraper.find('.price').text(price).end()
			//.find('.sales strong').attr('class', sales).end()
			//.find('.sale_num').text(saleNum).end()
			//.find('.original_price').text(original_price).end()
			.find('p').html(p_in).end()
			.find('.sale_unit').text(saleunit).end()
			.find('#detailBtn').attr('max',max_mun).end()			
			.find('.info').text(info);

			_wraper.parents('.dialog').find('.dialog_tt').text(title);
//		if (typeof(original_price) == "undefined") {
//		  	$(".o_price").hide();
//		} else{
//			$(".o_price").show();
//		}
		
		if(parseInt(num)>0){
			
			if($(".num",$('#detailBtn').parent()).length==0)
			{
				$('#detailBtn').before('<span ><a class="btn del active"></a><span class="num">'+ num +'</span></span>');
				$('#menuDetail .del').click(function(){
					dia_del(this);
				});
			}
			$('#detailBtn').removeClass("comm_btn").removeClass("disabled").addClass("btn").addClass("add").addClass("active").html("");
			$(".num",$('#detailBtn').parent()).text(num);
		} else {
			if($('.btndiv1 >span').length>0){
				$('.btndiv1').get(0).removeChild($('.btndiv1 >span').get(0));
				$('#detailBtn').removeClass("btn").removeClass("add").removeClass("active").addClass("comm_btn");
			}
			if(F('.plus').length){
				$('#detailBtn').removeClass('disabled').html('来一<span class="sale_unit" >'+saleunit+'</span>');
			}else{
				$('#detailBtn').addClass('disabled').text('已售完');
			}
		}
		
		if(imgUrl){
			_detailImg.attr('src', imgUrl).show().next().hide();
		}else{
			_detailImg.hide().next().show();
		}
		var showfixed_top=0;
		dialogTarget = _this;
		
		var _id = this.id
		_wraper.dialog({title: title, closeBtn: true,updatePosition:function(y){	
				
			//if(y<c_y-100){
				if(pageIndex<=pagecount){
					if(isajax==true){
						renderlist(_id,title,showfixed_top,0);
						c_y =y;	
					}
				 	
				}
			//}
			
			if(-y>showfixed_top){
				$(".showfixd").addClass("fixed");
				$(".showfixd").css("top",""+-y+"px")
			}
			else{
				$(".showfixd").removeClass("fixed");
			}
			console.log(y+","+showfixed_top)
		}});
		$(".showfixd").removeClass("fixed");
		showfixed_top=$(".showfixd").offset().top-$(".menu_detail").offset().top ;
		$("#scoreList").html("<div class='loading'>加载中…</div>")
		//renderlist(_id,title,showfixed_top,1);
	});

	$('#menuList .mylovedish').click(function(e){
		e.stopPropagation();
	});
	
	$('#detailBtn').click(function(e){
		// alert(dialogTarget.find('.unit_price').text());
		var max = parseInt($(this).attr("max"));
		if((isNaN(max))) max=-1
		if(!$(this).hasClass('disabled')){
			if(num==0){
				$(this).before('<span ><a class="btn del active"></a><span class="num">'+ num +'</span></span>');
				$(this).removeClass("comm_btn").removeClass("disabled").addClass("btn").addClass("add").addClass("active").html("");
				$('#menuDetail .del').get(0).onclick=function(){
					dia_del(this,max);
				};
			}
			if(num<max||max<0){
				num=parseInt(num)+1;
				$(".num",$(this).parent()).text(num);	
				
				dialogTarget.find('.plus ').click();
			}
		}
	});
	function dia_del(elm,max){
		dialogTarget.find('.minus ').click();
		num=parseInt(num)-1;	
		$(".num",$(elm).parent()).text(num);
		if(num==0){
			$('.btndiv1').get(0).removeChild($('.btndiv1 >span').get(0));
			$('#detailBtn').removeClass("btn").removeClass("add").removeClass("active").addClass("comm_btn");
			$('#detailBtn').html('来一<span class="sale_unit" >'+saleunit+'</span>');
		}	
	} 

	
	$("#ILike").click(function(){
		$("#menuList li").hide();
		$("#menuList li.like").show();
		$("#typeList li").removeClass("on");
		$(".hartblckgray",$(this)).addClass("on");
		/*
		var menu_ul_lis =$(".all li.like");
		//alert(menu_ul_lis.length)
		var like_ul=$(".likediv ul");
		like_ul.html("");
		for(var i=0;i<menu_ul_lis.length;i++){
			var li  =  document.createElement("li");
			li.id= menu_ul_lis[i].id;
			li.className= menu_ul_lis[i].className;
			li.innerHTML= menu_ul_lis[i].innerHTML;
			like_ul.append(li);
			$("input",$(li)).val($("input",$(menu_ul_lis[i])).val())
		}
		
		$(".likediv").show();
		$(".all").hide();
		$("#typeList li").removeClass("on");
		$(".hartblckgray",$(this)).addClass("on");
		
		$("#usermenu .likediv ul .hart").click(function(){
			var li = $(this).parent().parent().parent();
			var id =li.get(0).id.replace("li","");
			check_i_like(id);
			$("#usermenu .all ul #"+li.get(0).id+"").removeClass("like");
			li.remove();			
		});
		$("#usermenu .likediv ul li .plus").click(function(){
			var li = $(this).parent().parent().parent().parent();			
			var li_id=li.get(0).id
			plus(this,li_id);			
			
		});
		$("#usermenu .likediv ul li .minus").click(function(){
			var li = $(this).parent().parent().parent().parent();
			var li_id=li.get(0).id												
			minus(this,li_id);										   
		});
		$("#usermenu  ul li img").click(function(){
			showDetails();
		});*/
	});

	$("#menuList li .hart").click(function(){
		var li = $(this).parent().parent().parent().parent();										
		li.toggleClass("like");
		var id =li.get(0).id.replace("dish_li","");
		islove=li.hasClass("like") ? 1 :0;
		//check_i_like(id,islove);
		
	});
	
	/*$("#usermenu  ul li img").click(function(){
		showDetails(this);
	});
	
	$(".details  .content a").click(function(){
		hideDetails();
	});*/
	/*****原来的逻辑*******/
	/*$("#typeList li").click(function(){
		$("#menuList li").show();
		var idStr = this.id;
		var id =idStr.replace("li_type","");
		$("#typeList li").removeClass("on");
		$(this).addClass("on")
		$(".likediv").hide();
		$("#ILike .hartblckgray").removeClass("on");
		//$(".all").show();
		var top =$("#ul_type"+id+"").get(0).offsetTop;
		//alert(top)
		var topM=$("#usermenu").get(0).scrollHeight-$("#usermenu").get(0).offsetHeight;
		var st =$("#usermenu").scrollTop();
		if(st<=topM){
			if(top<=topM){
				$("#usermenu").scrollTop(top);
			}
			else{
				$("#usermenu").scrollTop(topM);
			}
		}
		isClick=1;
		
		
	});*/
 $("#typeList li").click(function(){
		$("#menuList li").hide();
		var idStr = this.id;
		var id =idStr.replace("li_type","");
		$("#ul_type"+id+" li").show();
		$("#typeList li").removeClass("on");
		$(this).addClass("on")
		$(".likediv").hide();
		$("#ILike .hartblckgray").removeClass("on");
		//$(".all").show();
		/*var top =$("#ul_type"+id+"").get(0).offsetTop;
		//alert(top)
		var topM=$("#usermenu").get(0).scrollHeight-$("#usermenu").get(0).offsetHeight;
		var st =$("#usermenu").scrollTop();
		if(st<=topM){
			if(top<=topM){
				$("#usermenu").scrollTop(top);
			}
			else{
				$("#usermenu").scrollTop(topM);
			}
		}
		isClick=1;
		*/
		
	});
   $("#all_dish").click(function(){
		$("#menuList li").show();
		$("#typeList li").removeClass("on");
		$(this).addClass("onall");
		$(".likediv").hide();
		$("#ILike .hartblckgray").removeClass("on");
		//$(".all").show();
		/*var top =$("#ul_type"+id+"").get(0).offsetTop;
		//alert(top)
		var topM=$("#usermenu").get(0).scrollHeight-$("#usermenu").get(0).offsetHeight;
		var st =$("#usermenu").scrollTop();
		if(st<=topM){
			if(top<=topM){
				$("#usermenu").scrollTop(top);
			}
			else{
				$("#usermenu").scrollTop(topM);
			}
		}
		isClick=1;
		*/
		
	});
	/*$("#usermenu").get(0).onscroll=function(){
		if(isClick==1){
			isClick=0;
			return false;
		}
		var st =$("#usermenu").scrollTop();
		var uls = $("#usermenu .all ul");		
		var h= 0;
		if(uls.length>0){
			h=$(uls[0]).height();
		}
		if(st>0){
			for(i=1;i<uls.length;i++){			
				var h_ul = $(uls[i]).height()+h;
				if(h<st&st<h_ul){
					var u_id =uls[i].id;
					id = u_id.replace("ul_type","");
					$("#typeList li").removeClass("on");
					$("#li_type"+id+"").addClass("on");
					return;
				}
				h=h_ul;
				
			}
		}
		else{
			$("#typeList li").removeClass("on");
			$($("#typeList li")[0]).addClass("on");
		}
	}*/
});

/*function showDetails(elm){
	$(".details").show();
	showDetailInfo(elm);
}

function showDetailInfo(elm){
	var li = $(elm).parent().parent();
	var des=elm.alt;
	var name =$(">label>span",li).html();
	var money = $(">label>label .price_n",li).html();
	
	$(".details .name").html(name);
	$(".details .money").html(money);
	$(".details p").html(des);
	$(".details img").get(0).src=elm.src;
	var p_ts = $('.p_ts',li).html();
	if(p_ts==undefined) 
	{
		$(".details .p_ts").hide();
	}
	else{
		$(".details .p_ts").show();
		$(".details .p_ts").html(p_ts);
	}
}

function hideDetails(){
	$(".details").hide();
}

function plus(elm,id){	
	var L_elm= $(elm).parent();
	var m_elm=$(".minus",L_elm);
	var num=$("input",L_elm);
	var nstr =num.val();
	if(nstr==""){nstr=0}
	var n =parseInt(nstr)+1;
	num.val(n);
	if(n>0){
		m_elm.show();
		num.show();
	}
	if(id!=undefined){
		var allListNum_elm = $("#usermenu .all ul #"+id+" input")
		allListNum_elm.val(n);
		if(n>0){
			 $("#usermenu .all ul #"+id+" .minus").show();
			 allListNum_elm.show();
		}
	}
	
	var p =parseFloat($(".price_n",L_elm.parent()).html());
	var allmoney_elm = $("#allmoney");
	var allmoney=parseFloat(allmoney_elm.html());
	var c_allmoney =parseFloat(allmoney+p).toFixed(2)
	allmoney_elm.html( parseFloat(c_allmoney));
	
	var menucount_elm = $("#menucount");
	var menucount=parseInt(menucount_elm.html());
	var mc =menucount+1;
	menucount_elm.html(mc);
	//showbtn();
	
}
function minus(elm,id){
	var L_elm= $(elm).parent();
	var m_elm=$(elm);
	var num=$("input",L_elm);
	var nstr =num.val();
	if(nstr==""){nstr=0}
	var n =parseInt(nstr)-1;
	if(n>=0){num.val(n);}
	
	if(n==0){
		m_elm.hide();
		num.hide();
	}
	if(id!=undefined){
		var allListNum_elm = $("#usermenu .all ul #"+id+" input")
		allListNum_elm.val(n);
		if(n==0){
			 $("#usermenu .all ul #"+id+" .minus").hide();
			 allListNum_elm.hide();
		}
	}

	var p =parseFloat($(".price_n",L_elm.parent()).html())
	var allmoney_elm = $("#allmoney");
	var allmoney=parseFloat(allmoney_elm.html())
	var c_allmoney =parseFloat(allmoney-p).toFixed(2)
	allmoney_elm.html( parseFloat(c_allmoney));
	
	var menucount_elm = $("#menucount");
	var menucount=parseInt(menucount_elm.html());
	var mc =menucount-1;
	menucount_elm.html(mc);
	//showbtn();
	
}

function showbtn(){
	var mc =parseInt($("#menucount").html())
	if(mc==0){
		$(".btn.show").hide()
		$(".btn.disabled").show()
	}else{
		$(".btn.show").show()
		$(".btn.disabled").hide()
	}
}
*/
function getMenuChecklist(){
	var lis =$("#menuList li");
	var list = [];
	$("#menuList li").each(function(){
		//var count = $(".num >input",lis[i]).get(0).value;
		var count = $(this).find('.number').val();
		count = parseInt(count);
		if(count>0){
			//var id = lis[i].id.replace("dish_li","");
			var id = $(this).find('.thisdid').val();
			var info = {'id':id,'count':count}
			list.push(info);
		}
		
	});
	
	return list;
}
