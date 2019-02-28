//star
$(document).ready(function(){
    var stepW = 18;
    var description = new Array("非常差，很难吃","真的是差，都不忍心说你了","一般，还过得去吧","很好，是我想要的东西","太完美了，此物只得天上有，人间哪得几回闻!");
    var stars = $("#star > li");
    var descriptionTemp;
    $("#showb").css("width",0);
    stars.each(function(i){
        $(stars[i]).click(function(e){
            var n = i+1;
            $("#showb").css({"width":stepW*n});
            descriptionTemp = description[i];
            $(this).find('a').blur();
            return stopDefault(e);
            return descriptionTemp;
        });
    });
    stars.each(function(i){
        $(stars[i]).hover(
            function(){
                $(".description").text(description[i]);
            },
            function(){
                if(descriptionTemp != null)
                    $(".description").text("当前您的评价为："+descriptionTemp);
                else 
                    $(".description").text(" ");
            }
        );
    });
});

function stopDefault(e) {
    if(e && e.preventDefault) {
    	e.preventDefault();
    } else {
    	window.event.returnValue = false;
    }
    return false;
}

var degree = ['','很差','差','中','良','优','未评分'];

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
	$("i[cjmark]").click(function(){
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
	
	
	$(".peisong:lt(2)").click(function(){
		$(".good-comment").show();
	});
	$(".peisong:gt(2)").click(function(){
		$(".good-comment").hide();
	});
});