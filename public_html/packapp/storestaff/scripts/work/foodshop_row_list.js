var queue_data = null, now_tid = 0;
$(document).ready(function(){
	$('.Mask').height($(window).height())
	common.http('Storestaff&a=queue_list', null, function(data){
		console.log(data)
		$('.cut_top').html('<div class="lockup"><h2>排号已开启</h2><p>开启时间：'+ data.foodshop.queue_open_time +'</p><div class="close">关闭排号</div></div><div class="open"><p>排号已关闭,点击开启排号</p><div class="open_num">开启排号</div></div>');
		if (data.foodshop.queue_is_open == 0) {
			$(".lockup").hide().siblings(".open").show();
			$(".cut_tab,.take").hide();
		} else {
			queue_data = data.list;
			if(queue_data[0]){
				now_tid = queue_data[0].id;
			}
			queue_detail(queue_data);
		}
	});

	//关闭排号
	$(document).on('click', '.lockup .close', function() {
		common.http('Storestaff&a=change_queue', null, function(data){
			now_tid = 0;
			$(".lockup").hide().siblings(".open").show();
			$(".cut_tab,.take").hide();
		});
	});
	
	//开启排号
	$(document).on('click', '.open_num', function() {
		common.http('Storestaff&a=change_queue', null, function(data){
			$('.cut_top').html('<div class="lockup"><h2>排号已开启</h2><p>开启时间：'+ data.foodshop.queue_open_time +'</p><div class="close">关闭排号</div></div><div class="open"><p>排号已关闭,点击开启排号</p><div class="open_num">开启排号</div></div>');
			$(".cut_tab,.take").show();
			queue_data = data.list;
			now_tid = queue_data[0].id;
			queue_detail(queue_data);
		});
	});
	
	//跳号
	var isCancelQueue = false;
	$(document).on('click', '.clickbot .jump', function(){
		if (isCancelQueue) return false;
		isCancelQueue = true;
		common.http('Storestaff&a=queue_cancel', {'tid':now_tid, noTip:true}, function(data){
			isCancelQueue = false;
			$(".cut_tab, .take").show();
			queue_data = data.list;
			queue_detail(queue_data);
			motify.log('过号成功');
		});
	});
	
	//叫号
	$(document).on('click', '.clickbot .call', function(){
		common.http('Storestaff&a=queue_call', {'tid':now_tid, noTip:true}, function(data){
			motify.log(data);
		});
	});

	//现场取号
	$(document).on('click', '.yell_n', function() {
		var select = '<select>';
		common.http('Storestaff&a=queue_create', {'tid':now_tid, noTip:true}, function(data){
			$.each(data, function(i, item){
				if (item.id == now_tid) {
					select += '<option value="' + item.id + '" selected>' + item.name + '（' + item.min_people + '-' + item.max_people + '人）</option>';
				} else {
					select += '<option value="' + item.id + '">' + item.name + '（' + item.min_people + '-' + item.max_people + '人）</option>';
				}
			});
			select += '</select>';
			$('.creator .select').html(select);
			$('.creator, .Mask').show();
		});
	});
	
	$(document).on('click', '#queue_save', function() {
		var tid = $(this).parents('.setup_n').find('select').val(), num = parseInt($(this).parents('.setup_n').find('#num').val());
		if (num < 1 || isNaN(num)) {
			motify.log('请输入正确的用餐人数');
			return false;
		}
		common.http('Storestaff&a=queue_save', {'table_type':tid, 'num':num, noTip:true}, function(data){
//			$('.creator, .Mask').hide();
//			motify.log(data);
			setTimeout(location.reload(), 7000);
		});
	});
	$('.Mask, .del').click(function() {
		$('.creator, .Mask').hide();
	});
	
	//餐台类型切换
	$(document).on('click', '.cut_tab li', function() {
		now_tid = $(this).data('id');
		for (var i in queue_data) {
			if (now_tid == queue_data[i].id) {
				laytpl($('#queueDetailTpl').html()).render(queue_data[i], function(html){
					$('.take').html(html);
					$('.howl li').each(function() {
						$(this).height($(this).width() * (32 / 24))
					});
				});
			}
		}
		$(this).addClass('on').siblings().removeClass('on');
	});
	
	
//	setInterval(function(){
//		common.http('Storestaff&a=queue_list', {noTip:true}, function(data){
//			$('.cut_top').html('<div class="lockup"><h2>排号已开启</h2><p>开启时间：'+ data.foodshop.queue_open_time +'</p><div class="close">关闭排号</div></div><div class="open"><p>排号已关闭,点击开启排号</p><div class="open_num">开启排号</div></div>');
//			if (data.foodshop.queue_is_open == 0) {
//				$(".lockup").hide().siblings(".open").show();
//				$(".cut_tab,.take").hide();
//			} else {
//				queue_data = data.list;
//				queue_detail(queue_data);
//			}
//		});
//	}, 3000);
});


function queue_detail(data)
{
	laytpl($('#topSwiperTpl').html()).render(data, function(html){
		$('.cut_tab .swiper-wrapper').html(html);
		new Swiper('.swiper-container', {
			direction : 'horizontal',
			freeMode : true,
			slidesPerView : 'auto'
		});
	});
	
	var queueData = null, index = 0;
	if (now_tid == 0) {
		queueData = data[0];
		index = 0;
	} else {
		for (var i in queue_data) {
			if (now_tid == queue_data[i].id) {
				queueData = queue_data[i];
				index = i;
			}
		}
	}
	$('.cut_tab .swiper-wrapper').find('li').eq(index).addClass('on').siblings().removeClass('on');
	
	if(data && data.length > 0){
		laytpl($('#queueDetailTpl').html()).render(queueData, function(html){
			$('.take').html(html);
			$('.howl li').each(function() {
				$(this).height($(this).width() * (32 / 24))
			});
		});
	}
}