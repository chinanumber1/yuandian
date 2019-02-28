 $(function() {
     $(".profile-btn").click(function() {
         $(".common-widget-profile").slideToggle();
         $(".global-mask").fadeToggle(1000);
     })

     $(".youhui").click(function() {
         $(this).parents("li").find(".warefare-details-card").toggle();
     })

     $(".tool-item").click(function() {
         $(this).siblings(".filter-container").slideToggle(1000).parent().siblings().find(".filter-container").slideUp(1000);
         $(this).parent("li").toggleClass("on").siblings().removeClass("on");
         //  $(".global-mask").fadeToggle(1000);
     })

     $(".pay-list span").click(function() {
         $(this).addClass("checkmark").removeClass("pradio").parents("li").siblings().find("span").removeClass("checkmark").addClass("pradio");

     })
     $(".radio-item").click(function() {
         $(this).addClass("yes").find("i").addClass("icon-radio-checked").removeClass(" icon-radio-unchecked ").parents("span").siblings().removeClass("yes").find("i").removeClass("icon-radio-checked").addClass(" icon-radio-unchecked ");

     })
     $(".addre_on li").click(function() {
         $(this).addClass("red").siblings().removeClass("red");
     })
     $(".normal-btn").click(function() {
         if ($(".normal-btn").hasClass("address-edit")) {
             $(this).toggleClass("address-edit");
             $(this).text("完成");
             $(".address-list").toggleClass("editlist");
         } else {

             $(this).toggleClass("address-edit");
             $(this).text("编辑");
             $(".address-list").toggleClass("editlist");
         }
     })
 })

 $(function() {
     var h = document.body.scrollTop; //网页被卷去的高度
     if (h > 50) {
         $(".sticky-a").css("top", "0")
     } else if (h <= 50) {
         $(".sticky-a").css("top", "80");
     }

 })

 $(function() {
     var t = $(".wa-label").text();
     var s = $(".wa-label").size();
     var a = new Array;
     var i = 0;

     $(".wa-label").click(function() {
         var t = $(this).text();
         a[i] = t;
         i++;
         $("textarea").val(a);
     })


 })

 $(function() {
     tab(".coupon-filter li", ".coupon_list > li", "selected")
     $(".coupon-filter li").click(function() {
         $("#page-loader").fadeToggle(500);
         setTimeout(function() {
             $("#page-loader").fadeToggle(500);

         })
     })
 })

 function tab(a, b, c) { //a 是点击的目标,,b 是所要切换的目标,c 是点击目标的当前样式
     var len = $(a);
     len.bind("click", function() {
         var index = 0;
         $(this).addClass(c).siblings().removeClass(c);
         index = len.index(this); //获取当前的索引
         $(b).eq(index).show().siblings().hide();
         return false;
     }).eq(0).trigger("click"); //浏览器模拟第一个点击
 }



 $(function() {
     $(".list1").click(function() {
         aaa('table2', 'table1');

     });
     $(".list2").click(function() {
         aaa('table1', 'table2');

     });

     $(".comment_list").click(function() {
         $(this).addClass("select").siblings().removeClass("select");
         $("#page-loader").fadeToggle(500);
         setTimeout(function() {
             $("#page-loader").fadeToggle(500);
         })
     });

     function aaa(sClass1, sClass2) {
         $('.' + sClass1).hide();
         $('.' + sClass2).show();
     }
 });


 $(function() {
     var zong = 0;
     var ddz = 0;
     var de = parseFloat($(".row-status i").text());
     var dee = de;
     $(".-plus").click(function() {
         var dd = $(this).siblings("input").val();
         $(this).parent(".item-add").addClass("show-all");
         dd++;
         ddz++;
         $(this).siblings("input").val(dd);
         var pr = $(this).parent(".item-add").siblings(".wrap").find(".price i").text();
         zong = parseFloat(zong) + parseFloat(pr);
         $(".cart-price i").text(zong);
         $(".cart-count").text(ddz);
         var zde = $(".cart-price i").text();
         if (zde >= de) {
             $(".row-status").removeClass("cb-disable");
             $(".row-status").text("选好了");
         }
         if (zde < de) {
             $(".row-status").addClass("cb-disable");
             $(".row-status").html("差¥<i>" + dee + "</i>起送");
         }

     })

     $(".-minus").click(function() {
         var dd = $(this).siblings("input").val();
         var pr = $(this).parent(".item-add").siblings(".wrap").find(".price i").text();
         ddz--;
         if (dd >= 0) {
             dd--;
             $(this).siblings("input").val(dd);
             zong = parseFloat(zong) - parseFloat(pr);
             $(".cart-price i").text(zong);
             $(".cart-count").text(ddz);
             if (dd < 1) {
                 $(this).parent(".item-add").removeClass("show-all");
             }
         }
         var zde = $(".cart-price i").text();
         if (zde >= de) {
             $(".row-status").removeClass("cb-disable");
             $(".row-status").text("选好了");
         }
         if (zde < de) {
             $(".row-status").addClass("cb-disable");
             $(".row-status").html("差¥<i>" + dee + "</i>起送");
         }
     })

 })
 
 
 $(function(){
	 var degree = ['','很差','差','中','良','优','未评分'];
//重新点评
function addComment2(e,inid,opt,id){
	$.ajax({
		url:'/siteMessage/content',
		type:'post',
		data:'id='+id,
		dataType:'json',
		success:function(data){
			if(data.status==1){
				var list = $('#Addnewskill_119');
				list.eq(0).html(data.talent+'(人才ID：'+data.talentId+')');
				list.eq(1).html(data.job);
				list.eq(2).html(data.ms);
				
				var arr = [data.total,data.expAuth,data.killAuth,data.followTime,data.formality,data.appReact];
				var list2 = $('span.level','#Addnewskill_119');
				$('input[name="InterviewCommentInfoSub[opt]"]').val(opt+1);
				list2.each(function(i,v){
						var a = '';
						
						if(i>0){
							a = 'cjmark';
							$(v).parents('li').find('input').val(arr[i]);
						}
						var str = '';
						if(arr[i]==6){
							for(var n=0;n<=4;n++){
								str += '<i '+a+' class="level_hollow"></i>';
							}
							$(v).parents('li').find('input').prop('disabled',true)
						}else{
							$(v).parents('li').find('input').prop('checked',true)
							for(var n=0;n<arr[i];n++){
								str += '<i '+a+' class="level_solid"></i>';
							}
							for(var n=0;n<(5-arr[i]);n++){
								str += '<i '+a+' class="level_hollow"></i>';
							}
						}
						$(v).html(str);
						$(v).next().html(degree[arr[i]]);
					
				})
				
				
				create_show(119);
			}else{
				ui.error(data.msg,2000);
			}
		}
	})	
}
//提交点评
function addComment3(){
	$.ajax({
		url:'/siteMessage/commentinterview',
		type:'post',
		data:$('form[name="comment"]').serialize(),
		dataType:'json',
		success:function(data){

		}

	})
}

$(function(){
	//点星星
	$(document).on('mouseover','i[cjmark]',function(){
		var num = $(this).index();
		var pmark = $(this).parents('.revinp');
		var mark = pmark.prevAll('input');
	
		if(mark.prop('checked')) return false;
		
		var list = $(this).parent().find('i');
		for(var i=0;i<=num;i++){
			list.eq(i).attr('class','level_solid');
		}
		for(var i=num+1,len=list.length-1;i<=len;i++){
			list.eq(i).attr('class','level_hollow');
		}
		$(this).parent().next().html(degree[num+1]);

	})
	//点击星星
	$(document).on('click','i[cjmark]',function(){
		var num = $(this).index();
		var pmark = $(this).parents('.revinp');
		var mark = pmark.prevAll('input');
		
		if(mark.prop('checked')){
			mark.val('');
			mark.prop('checked',false);mark.prop('disabled',true);	
		}else{
			mark.val(num);
			mark.prop('checked',true);mark.prop('disabled',false);	
		}
	})
	//选框
	$('#Addnewskill_119 input[type="checkbox"]').change(function(){
		if($(this).not(':checked')){//!($(this).prop('checked'))
			$(this).prop('checked',false);$(this).prop('disabled',true)
			var smark = $(this).nextAll('.revinp');
			smark.find('span.revgrade').html('未评分');
			smark.find('i').attr('class','level_hollow');
			smark.val(6);
		}
	})
	

})

      $(".add-list span").click(function(){
	    $(this).toggleClass("select");
	
	})
	
	$(".peisong:lt(2)").click(function(){
		$(".good-comment").show();
		}) 
	 	$(".peisong:gt(2)").click(function(){
		$(".good-comment").hide();
	}) 
 
		var dd=$('#wrapper').width();
	    var ade=$(".couponinfo").width();
	      $(".couponinfo").css("left",(dd-ade)/2)
 	
		
		
	 })