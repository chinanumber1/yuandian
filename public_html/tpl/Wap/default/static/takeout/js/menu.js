
var menu = {
	offsetAry: [0],
	_is_left_menu_addclass:true,
	init: function(id){
		var winH = $(window).height(),
			_this = this,			
			//_icoMenu = $('#icoMenu'),
			_sideNav = $('#sideNav'),
			maxH = winH - 45;

		this.el =  $(id);
		//alert($('.shopping_cart').height())
		$('#mymenu_lists').height(winH - $('.nav').height() - $('.shopping_cart').height());
		_sideNav.height(maxH);

		//if(_sideNav.find('ul').height() > maxH)  new IScroll('#sideNav', { probeType: 3, mouseWheel: true ,click:true});
		//new Scroller('#sideNav', {scrollX: false});

//		$(window).bind('scroll', function(){
//			//_this.scroll.call(_this);
//		});

//		$('#icoMenu').click(function(){
//			_sideNav.toggle();
//			if(_sideNav.find('ul').height() > maxH)  new IScroll('#sideNav', { probeType: 3, mouseWheel: true ,click:true});
//		});

		$('.menu_tt h2').each(function(){
			_this.offsetAry.push($(this).offset().top);
		});

		this.el.find('a').click(function(){
			$(this).addClass('on').parent().siblings().find('a').removeClass('on');
			_this._is_left_menu_addclass = false;
			var t = $(window).scrollTop();
			var t1 = _this.offsetAry[_this.el.find('a').index(this) + 1];
			
			var _t = Math.abs(t1-t);
			var _time = parseInt(Math.round(_t / 3));
			t1 = t1 - 50;
			$('#mymenu_lists').animate({scrollTop: t1}, _time,"linear",function(){_this._is_left_menu_addclass=true;});
		});

		//_this.offsetT = this.el.offset().top;	
//	},
//	getIndex: function(ary, value){
//		var i = 0;
//		for(; i < ary.length; i++){
//			if(value >= ary[i] && value < ary[i + 1]){
//				return i;
//			}
//		}
//		return ary.length -1;
//	},
//	scroll: function(){
//		var st = $(document).scrollTop(),
//			index = this.getIndex(this.offsetAry, st),
//			i = index - 1;
//
//		if(this.curIndex !== index){ // 判断分类是否切换
//			
//			//$('.menu_tt h2').removeClass('menu_fixed');
//			if(this._is_left_menu_addclass==true)
//				this.el.find('a').removeClass('on');
//			if(i >= 0){
//				//this.el.addClass('menu_fixed');
//				//$('.menu_tt').eq(i).find('h2').addClass('menu_fixed');
//				if(this._is_left_menu_addclass==true)
//					this.el.find('a').eq(i).addClass('on');	
//			}else{
//				//this.el.removeClass('menu_fixed');
//			}
//			this.curIndex = index;
//		}
	}
}

$(function(){
	menu.init('#menuNav');

	// $('#menuWrap .add').amount(0, $.amountCb());
	$('#menuWrap .add').each(function(){
		$(this).amount(0, $.amountCb());
		for(var i = 0, num = parseInt($(this).data('num')); i < num; i++){
			init=0;
			$(this).click();
		}
	});

	
	var _wraper = $('#menuDetail');

	var dialogTarget;
	var num=0;
	var saleunit= "";
	$('.menu_list li').click(function(e){
		num=0;
		var _this = $(this),
			F = function(str){return _this.find(str);},
			title = F('h3').text(),
			imgUrl = F('img').attr('url'),
			price = F('.unit_price').text(),
			sales = F('.sales strong').attr('class'),
			saleNum = F('.sale_num').text(),
			info = F('.info').text(),			
			original_price = F('.unit_price').attr('original-price'),
			max_mun = F('.fr').attr('max'),
			p_in= F('p').html(),
			_detailImg = _wraper.find('img');
		num=$(".num",$(this)).text();
		saleunit=F('.sale_unit').text();
		_wraper.find('.price').text(price).end()
			.find('.sales strong').attr('class', sales).end()
			//.find('.sale_num').text(saleNum).end()
			.find('.original_price').text(original_price).end()
			.find('p').html(p_in).end()
			.find('.sale_unit').text(saleunit).end()
			.find('.name').text(title).end()
			.find('#detailBtn').attr('max',max_mun).end()			
			.find('.info').text(info);

//		_wraper.parents('.dialog').find('.dialog_tt').text(title);
		if (typeof(original_price) == "undefined") {
		  	$(".o_price").hide();
		}   
		else{
			$(".o_price").show();
		}
		
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
		}
		else{
			if($('.btndiv1 >span').length>0){
				$('.btndiv1').get(0).removeChild($('.btndiv1 >span').get(0));
				$('#detailBtn').removeClass("btn").removeClass("add").removeClass("active").addClass("comm_btn");
			}
			if(F('.add').length){
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
		_wraper.dialog({title: '商品详情', closeBtn: true,updatePosition:function(y){	
				
			//if(y<c_y-100){
				if(pageIndex<=pagecount){
					if(isajax==true){
						//renderlist(_id,title,showfixed_top,0);
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

	$('#menuWrap .price_wrap').click(function(e){
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
				
				dialogTarget.find('.add ').click();
			}
		}
	});
	function dia_del(elm,max){
		dialogTarget.find('.del ').click();
		num=parseInt(num)-1;	
		$(".num",$(elm).parent()).text(num);
		if(num==0){
			$('.btndiv1').get(0).removeChild($('.btndiv1 >span').get(0));
			$('#detailBtn').removeClass("btn").removeClass("add").removeClass("active").addClass("comm_btn");
			$('#detailBtn').html('来一<span class="sale_unit" >'+saleunit+'</span>');
		}	
	} 
	
	/*评论翻页*/
	var pageIndex =1; 
	var pageSize=10;
	var c_y =0; 
	var pagecount = 0;
	var isajax =true
	function renderlist(id,title,showfixed_top,init){
		var ul = $("#scoreList");
		if(init==1){
			ul.html("");
			pageIndex=1;
			pagecount=0;
			c_y=0
		}
		isajax=false;
		$.ajax({
			type: "POST",
			url:APP.urls.getCommentsList,
			data: {
				id:id,
				pageIndex:pageIndex,
				pageSize:pageSize
			},
			async:true,
			success: function(res){
				//var res={status:0,message:"",count:90,list:[{username:"Avin",time:"2015-04-27 13:33",score:2,des:"味道真心好"}]}
				
				pagecount =  Math.ceil(res.count/pageSize);
				if(res.status==0){
					var list = res.list;
					for(var i=0;i<list.length;i++){
						var li = document.createElement("li");
						li.innerHTML='<div><strong>'+list[i]["username"]+'</strong><span>'+list[i]["time"]+'</span></div><p class="ico_scored" ><strong class="score_'+list[i]["score"]+'"></strong></p><label class="des">'+list[i]["des"]+'</label>';
						ul.append(li);
					}
					
					pageIndex++;
					isajax=true;
					
				_wraper.dialog({title: '商品详情', closeBtn: true,updatePosition:function(y){
						if(-y>showfixed_top){
							$(".showfixd").addClass("fixed");
							$(".showfixd").css("top",""+-y+"px")
						}
						else{
							$(".showfixd").removeClass("fixed");
						}
						//console.log(y+","+showfixed_top)
					}});
					
				}
			
			},
			dataType: "json"
		});	
	}
	
});