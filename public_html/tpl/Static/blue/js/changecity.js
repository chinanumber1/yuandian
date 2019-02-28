$(function(){
	// 城市列表
	$('.city_set li').hover(function(){
		$(this).addClass('hover');
	},function(){
		$(this).removeClass('hover');
	});
	
	
	$('#site_sel_pro').change(function(){
		var $pid=$(this).val();
		if($pid == 0){
			$('#site_sel_city').html('<option value="">请选择</option>');
			return false;
		}
		$.getJSON(city_action,{'pid':$pid},function($data){
			if($data||$data!=null){
				var $html='';
				$.each($data,function($i,$v){
					$html+='<option value="'+$v.encity+'">'+$v.cityname+'</option>';
				});
				$('#site_sel_city').html($html);
			}else{
				$('#site_sel_city').html('<option value="">请选择</option>');
			}
		}).error(function(){
			$('#site_sel_city').html('<option value="">获取城市失败</option>');
		});
	});
	
	$('.hd_left form').submit(function(){
		var $city=$('#site_sel_city').val();
		if($city==''){
			alert('请选择城市！');
		}else{
			window.open('http://'+$city+'.'+top_domain+request);
		}
		return false;
	});
});
