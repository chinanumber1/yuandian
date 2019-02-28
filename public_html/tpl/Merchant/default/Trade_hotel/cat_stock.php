<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('index')}">酒店管理</a>
			</li>
			<li class="active"><a href="{pigcms{:U('son_cat_list',array('cat_id'=>$now_cat['cat_id']))}">{pigcms{$now_cat.cat_name} - 子类别列表</a></li>
			<li class="active">{pigcms{$now_son_cat.cat_name} - 价格库存管理</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div id="J_Calendar" class="calendar" style="z-index:-1"></div>
				<div class="clearfix form-actions">
					<div class="col-md-offset-3 col-md-9">
						<button class="btn btn-info" type="button" id="save_stock_btn">
							<i class="ace-icon fa fa-check bigger-110"></i>
							保存
						</button>
						<button class="btn btn-info handle_btn" type="button" id="save_stock_btn" href="{pigcms{:U('cat_stock_mutil',array('cat_id'=>$_GET['cat_id']))}">
							<i class="ace-icon fa fa-check bigger-110"></i>
							批量添加库存
						</button>
						<button class="btn btn-info handle_btn" type="button" id="save_stock_btn" href="{pigcms{:U('cat_stock_mutil_edit',array('cat_id'=>$_GET['cat_id']))}">
							<i class="ace-icon fa fa-check bigger-110"></i>
							批量修改库存
						</button>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
.calendar-bounding-box{
	z-index:12;
}
.calendar-bounding-box .container {
	background: #F6F6F6;
    padding: 0;
}
.calendar-bounding-box .container .date-box{
	margin-right: 20px;
	margin-left: 20px;
}
.calendar-bounding-box .container .date-box .inner{
	width:100%!important;
    padding: 0;
}
.calendar-bounding-box .container .date-box .inner h4{
	width:100%!important;
	font-weight: 700;
    background-color: #EAEAEA;
    box-shadow: 0px -2px 4px rgba(0, 0, 0, 0.1);
	height: 50px;
    line-height: 50px;
    font-size: 14px;
}
.calendar-bounding-box .content-box .inner table{
	width:100%;
	box-shadow: 0px -2px 4px rgba(0, 0, 0, 0.1);
}
.calendar-bounding-box .content-box .inner table th{
    text-align: center;
    background-color: #EAEAEA;
    height: 40px;
    color: #999;
    line-height: 40px;
}
.calendar-bounding-box .content-box .inner table td{
	width: 87px;
    height: 132px;
    vertical-align: top;
}
.calendar-bounding-box .content-box .inner table td.disabled{
    background-color: #F6F6F6;
}

.calendar-bounding-box td a{
    margin-top: 8px;
    margin-left: 8px;
    font-size: 16px;
}
.calendar-bounding-box td.disabled a {
    color: #999!important;
}
.calendar-input {
    color: #A9A9A9;
    display: block;
    height: 24px;
    line-height: 24px;
    text-align: right;
    padding: 0 3px 0 0;
    background-color: #FFF;
    overflow: hidden;
    border: 1px solid #A7A7A7;
    box-shadow: 1px 2px 5px rgba(0, 0, 0, 0.2) inset;
    width: 80%;
    margin-left: 8px;
    font-weight: normal;
    margin-top: 6px;
}
.disabled .calendar-input{
    background: #f5f5f5!important;
	box-shadow:none;
}
.calendar-input input {
    float: left;
    border: 0;
    width: 98px;
    height: 24px;
    font-size: 12px;
    padding-left: 3px;
    font-family: Arial;
    background-color: transparent;
    outline: none;
    background-image: none;
    color: black;
}
.calendar-input input:focus{
	background-color: transparent;
}
</style>
<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
<script src="https://cdn.bootcss.com/yui/3.18.1/yui/yui.js"></script>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
var priceArr = $.parseJSON('{pigcms{:json_encode($stock_list)}');
function calendar_render(){
	$.each($('.calendar-bounding-box .content-box .inner table td'),function(i,item){
		if($(item).data('date') != ''){
			if(priceArr[$(item).data('date')]){
				var tmpPrice = priceArr[$(item).data('date')].price;
				var tmpDisPrice = priceArr[$(item).data('date')].discount_price;
				var tmpStock = priceArr[$(item).data('date')].stock;
			}else{
				tmpPrice = '';
				tmpDisPrice = '';
				tmpStock = '';
			}
			
			$(item).append('<div class="calendar-input"><input type="text" class="price-input" value="'+tmpPrice+'" placeholder="价格" '+($(item).hasClass('disabled') ? 'disabled="disabled"' : '')+'/>元</div><div class="calendar-input"><input type="text" class="discount-price-input" value="'+tmpDisPrice+'" placeholder="优惠价格" '+($(item).hasClass('disabled') ? 'disabled="disabled"' : '')+'/>元</div><div class="calendar-input"><input type="text" class="stock-input" value="'+tmpStock+'" placeholder="库存" '+($(item).hasClass('disabled') ? 'disabled="disabled"' : '')+'/>间</div>');
		}
	});
}
$(function(){
	$('.handle_btn').live('click',function(){
		art.dialog.open($(this).attr('href'),{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('iframe_handle',iframe);
			},
			id: 'handle',
			title:'批量添加库存',
			padding: 0,
			width: 720,
			height: 520,
			lock: true,
			resize: false,
			background:'black',
			button: null,
			fixed: false,
			close: null,
			left: '50%',
			top: '38.2%',
			opacity:'0.4',
			close:function(){
			
			    window.location.reload(true);
			}
		});
		return false;
	});
	$('.price-input').live('blur',function(e){
		$(this).val($.trim($(this).val()));
		
		var date = $(this).closest('td').data('date');
		var dateObj = date.split('-');
		if(!priceArr[date]){
			priceArr[date] = {};
		}
		priceArr[date].price = Number($(this).val());
		priceArr[date].date = dateObj[0]+'年'+dateObj[1]+'月'+dateObj[2]+'日';
		
		//console.log(priceArr);
	});
	$('.discount-price-input').live('blur',function(e){
		$(this).val($.trim($(this).val()));
		
		var date = $(this).closest('td').data('date');
		var dateObj = date.split('-');
		if(!priceArr[date]){
			priceArr[date] = {};
		}
		priceArr[date].discount_price = Number($(this).val());
		priceArr[date].date = dateObj[0]+'年'+dateObj[1]+'月'+dateObj[2]+'日';
		
		//console.log(priceArr);
	});
	$('.stock-input').live('blur',function(e){
		$(this).val($.trim($(this).val()));

		var date = $(this).closest('td').data('date');
		console.log(date);
		var dateObj = date.split('-');
		if(!priceArr[date]){
			priceArr[date] = {};
		}
		priceArr[date].stock = Number($(this).val());
		priceArr[date].date = dateObj[0]+'年'+dateObj[1]+'月'+dateObj[2]+'日';
		
		console.log(priceArr);
	});
	$('#save_stock_btn').click(function(){
		var postStockArr = [];
		var myDate = new Date();      
		if(myDate.getMonth()+1<10){
			month = '0'+(myDate.getMonth()+1);
		}else{
			month = (myDate.getMonth()+1);
		}
		
		if(myDate.getDate()+1<10){
			now_day = '0'+myDate.getDate();
		}else{
			now_day = myDate.getDate();
		}
		var now_date=myDate.getFullYear()+'-'+month+'-'+now_day; 
	
		for(var i in priceArr){
			 var d = new Date(i.replace(/-/g,"/")).getTime();   
			var d1 = new Date(now_date.replace(/-/g,"/")).getTime(); 
			
			if(d<d1){
				continue;
			}else if(!priceArr[i].price && !priceArr[i].discount_price && !priceArr[i].stock){
				continue;
			}else if(!priceArr[i].price || !priceArr[i].discount_price){
				alert('数据没有设置完整（注，如果不优惠,优惠价格请跟价格保持一致）');
				return false;
			}
			
			
			var tmpStock = priceArr[i];
			
			var dateObj = i.split('-');
			
			tmpStock.date_num = dateObj[0]+dateObj[1]+dateObj[2];
			postStockArr.push(tmpStock);
		}
		
		var tipIndex = layer.load(0, {shade: [0.5,'#fff']});
		
		$.post("{pigcms{:U('stock_save')}",{stock:postStockArr,cat_id:{pigcms{$now_son_cat.cat_id}},function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.reload();
			}else{
				layer.close(tipIndex);
				alert(result.info);
			}
		});
	});
});
YUI({
    modules: {
        'trip-calendar': {
            fullpath: '{pigcms{$static_public}trip-calendar/trip-calendar.js',
            type    : 'js',
            requires: ['trip-calendar-css']
        },
        'trip-calendar-css': {
            fullpath: '{pigcms{$static_public}trip-calendar/trip-calendar.css',
            type    : 'css'
        }
    }
}).use('trip-calendar', function(Y) {

    /**
     * 非弹出式日历实例
     * 直接将日历插入到页面指定容器内
     */
    var oCal = new Y.TripCalendar({
        container   : '#J_Calendar' //非弹出式日历时指定的容器（必选）
        // ,selectedDate: new Date       //指定日历选择的日期
		,count		: 1
		,afterDays	: 180
    });
	oCal.render();
    //日期点击事件
    oCal.on('dateclick', function(e) {
        var selectedDate = this.get('selectedDate');
        // alert(selectedDate + '\u3010' + this.getDateInfo(selectedDate) + '\u3011');
		// $('td[data-date="'+selectedDate+'"] .price-input').focus();
    });
});
</script>

<include file="Public:footer"/>
