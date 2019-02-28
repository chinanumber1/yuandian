$(function(){
	$(".listForm input").val("");
	$(".listForm").on("mouseover",function(){
             	 $(".listForm").css("width",'400px');
             	  $(".listForm input").show();
             	  $(".listForm button").css('width','17%');
             })
             $(".listForm").on("mouseout",function(){
                 $(".listForm").css("width",'40px');
             	  $(".listForm button").css('width','40px');
             	  $(".listForm input").hide();
             	
             })
             $(".listForm input").on("mouseover",function(){
                    $(".listForm").css("width",'400px');
             	  $(".listForm input").show();
             	  $(".listForm button").css('width','17%');
             })
             $(".listForm input").on("mouseout",function(){
                   $(".listForm").css("width",'40px');
             	  $(".listForm button").css('width','40px');
             	  $(".listForm input").hide();
             })
	$(".elevator").pin({padding:{top:-45,bottom:0},containerSelector:".container",minWidth:1e3,activeClass:"hover"});
	var t={};
	$(".elevator .menuItem").find("a").each(function(e,o){t[e]=$(o).attr("href").slice(1)});
	$(".elevator").stickUp({parts:t,itemClass:"menuItem",itemHover:"active"});
	$("#upward").click(function(){$("html,body").animate({scrollTop:0},300)});
	var e=window.location.hash;
	$.scrollTo(e,500,{});
	if(e == ''){
		$('#elevator .menuItem:first').addClass('active');
	}
	
	$.each($('.baoj-service li'),function(i,item){
		if(i != 0 && i%4 == 3){
			$(item).addClass('last');
		}
	});
});