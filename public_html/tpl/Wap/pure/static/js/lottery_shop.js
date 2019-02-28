$(function(){
	
	var _viewport=$(window).width();
    _viewport=_viewport>750?750:_viewport;
    var per = _viewport>750?1:_viewport/750;
    var fontSize=_viewport/7.5;
    window.screenWidth_ = _viewport;
    $("html").css('font-size',fontSize+'px');

    $(".mask,.popup .buton").click(function(){
    	$(".mask,.popup").hide();
    })
	
	$('.buton').click(function(){
		window.location.href="./wap.php?g=Wap&c=My&a=lottery_shop_list"; 
	});
    

    var lottery={
	index:0,	//当前转动到哪个位置
	count:0,	//总共有多少个位置
	timer:0,	//setTimeout的ID，用clearTimeout清除
	speed:200,	//初始转动速度
	times:0,	//转动次数
	cycle:60,	//转动基本次数：即至少需要转动多少次再进入抽奖环节
	prize:-1,	//中奖位置
	init:function(id){
		if ($("#"+id).find(".lottery-unit").length>0) {
			$lottery = $("#"+id);
			$units = $lottery.find(".lottery-unit");
			this.obj = $lottery;
			this.count = $units.length;
			$lottery.find(".lottery-unit-"+this.index).addClass("active");
		};
	},
	roll:function(){
		
		var index = this.index;
		var count = this.count;
		var lottery = this.obj;
		$(lottery).find(".lottery-unit-"+index).removeClass("active");
		index += 1;
		if (index>count-1) {
			index = 0;
		};
		$(lottery).find(".lottery-unit-"+index).addClass("active");
		this.index=index;
		return false;
	},
	stop:function(index){
		this.prize=index;
		return false;
	}
};

function roll(){
	lottery.times += 1;
	lottery.roll();

	if (lottery.times > lottery.cycle+10 && lottery.prize==lottery.index) {
		clearTimeout(lottery.timer);
		lottery.prize=-1;
		lottery.times=0;
		click=false;
		setTimeout("$('.popup,.mask').show()",200)
		
	}else{
		if (lottery.times<lottery.cycle) {
			lottery.speed -= 10;
		}else if(lottery.times==lottery.cycle) {
			var index = Math.random()*(lottery.count)|0;
			lottery.prize = award;
			var is_win = $('.lottery-unit-'+award).data('is_win');
			var award_desc = $('.lottery-unit-'+award).find('p').html();
			if(is_win){
				
				$('.popup h2').html('恭喜您，中奖啦');
				$('.popup p').html('恭喜您抽中了【'+award_desc+'】， 已存入您的账户');
			}else{
				$('.popup h2').html('很抱歉，未中奖');
				$('.popup p').html(award_desc);
			}
			$.post(ajax_check_lottery, {order_id: order_id,award:1,type:type}, function(data, textStatus, xhr) {
				if(!data.status){
					$('.popup h2').html('很遗憾');
				}
				$('.popup p').html(data.info);
			},'json');
		}else{
			if (lottery.times > lottery.cycle+10 && ((lottery.prize==0 && lottery.index==7) || lottery.prize==lottery.index+1)) {
				lottery.speed += 110;
			}else{
				lottery.speed += 20;
			}
		}
		if (lottery.speed<40) {
			lottery.speed=40;
		};
		//console.log(lottery.times+'^^^^^^'+lottery.speed+'^^^^^^^'+lottery.prize);
		lottery.timer = setTimeout(roll,lottery.speed);
	}
	return false;
}

var click=false;//是否已进入转动抽奖

window.onload=function(){
	lottery.init('lottery');
	$(".click").click(function(){
		$.post(ajax_check_lottery, {order_id: order_id}, function(data, textStatus, xhr) {
			if(data.status){
				if (click) {
					return false;
				}else{
					lottery.speed=100;
					roll();
					click=true;
					return false;
				}
			}else{
				$('.popup h2').html('提示');
				$('.popup p').html(data.info);
				$('.popup,.mask').show()
			}
		},'json');
		
	});
};


	


})