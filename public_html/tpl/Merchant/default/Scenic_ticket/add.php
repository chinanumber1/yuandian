<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-home"></i>
				<a href="{pigcms{:U('index')}">景区管理</a>
			</li>
			<li class="active">新增门票</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">
							<li class="active">
								<a data-toggle="tab" href="#basicinfo">添加门票</a>
							</li>
							<li>
								<a data-toggle="tab" href="#pro">项目选择</a>
							</li>
							<!--<li>
								<a data-toggle="tab" href="#rili">门票日历</a>
							</li>-->
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label>门票名称</label></label>
									<input type="text" name="ticket_title" value="" />
									<span class="form_tips">必填。在订单页显示此名称！</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">预定须知</label>
									<textarea id="ticket_explain" name="ticket_explain"  placeholder="写上一些想要发布的内容"></textarea>
									<span class="form_tips">必填。预定须知，在订单填写页面显示。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>入园提示</label></label>
									<textarea id="park_intr" name="park_intr"  placeholder="写上一些想要发布的内容"></textarea>
									<span class="form_tips">必填。订单完成后显示，100字以内。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>入园方式</label></label>
									<textarea id="ticket_cue" name="ticket_cue"  placeholder="写上一些想要发布的内容"></textarea>
									<span class="form_tips">必填。订单完成后显示，具体的入园方式，公司车之类。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>原价</label></label>
									<input type="text" name="old_price" value="" />
									<span class="form_tips">不填默认为现价</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>现价</label></label>
									<input type="text" name="ticket_price" value="" />
									<span class="form_tips">必填。最多支持1位小数</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>进园时间</label></label>
									<input class="col-sm-1 Wdate" name="start_time" type="time" value="{pigcms{$ticket['start_time']}"/>
									<span class="form_tips">几点可以进入景区，方便有夜场的景区</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>出园时间</label></label>
									<!--<input class="col-sm-2 Wdate" type="text" readonly="readonly" style="height:30px;" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日 HH时mm分ss秒',startDate:'{pigcms{:date('Y-m-d H:i:s',strtotime('+1 day'))}',vel:'end_time'})" value="{pigcms{:date('Y-m-d H:i:s',$ticket['end_time'])}"/>
									<input name="end_time" id="end_time" type="hidden" value="{pigcms{:date('Y-m-d H:i:s',strtotime('+1 day'))}"/>-->
									<input class="col-sm-1 Wdate" name="end_time" type="time" value="{pigcms{$ticket['end_time']}"/>
									<span class="form_tips">几点必须离开景区，方便有夜场的景区</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>门票数量</label></label>
									<input type="number" name="count_num" value="-1" />
									<span class="form_tips">必填。-1代表无限制</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>排序</label></label>
									<input type="number" name="ticket_sort" value="0" />
									<span class="form_tips">值越大越靠前</span>
								</div>
								<!--<div class="form-group">
									<label class="col-sm-1">随时退：</label>
									<select name="is_refund" class="col-sm-3">
										<option value="1">可以随时退</option>
										<option value="2">不可以随时退</option>
									</select>
								</div>-->
								<div class="form-group">
									<label class="col-sm-1">门票类型：</label>
									<select name="is_children" class="col-sm-3">
										<option value="2">成人票</option>
										<option value="1">儿童票</option>
									</select>
								</div>
								<div class="form-group">
									<label class="col-sm-1">使用时间：</label>
									<select name="is_general" class="col-sm-3">
										<option value="0">周末、法定节假日通用</option>
										<option value="1">周末不能使用</option>
										<option value="2">法定节假日不能使用</option>
										<option value="3">周末、法定节假日不能通用</option>
									</select>
								</div>
							</div>
							<div id="pro" class="tab-pane">
								<div class="form-group">
									<div class="m-l-md">可以单选票价，也可以多选组合为：套票</div>
									<volist name="project" id="vo">
										<div class="radio col-xs-6">
											<label>
												<input class="paycheck ace" type="checkbox" name="project[]" value="{pigcms{$vo.project_id}" id="store_{pigcms{$vo.project_id}" checked="checked"/>
												<span class="lbl"><label for="store_{pigcms{$vo.project_id}">{pigcms{$vo.project_title} - {pigcms{$vo.project_price}元 - (<if condition="$vo.project_status eq 1">开启<else/>关闭</if>)</label></span>
											</label>
										</div>
									</volist>
									<div class="radio col-xs-6">
										<label>
											<input class="paycheck ace" type="checkbox" name="all_select" value="1" id="all_select" checked="checked"/>
											<span class="lbl"><label for="all_select">全选</label></span>
										</label>
									</div>
								</div>
								<input type="text" id="calendar" name="calendar" readonly="readonly" onclick="selectTime();" placeholder="点击选择时间"/>
							</div>
						<!--<div id="rili" class="tab-pane">
							<div class="container body">
									<div class="custom-calendar-wrap custom-calendar-full">
										<div class="custom-header clearfix">
											<h2>Calendar</h2>
											<h3 class="custom-month-year">
												<span id="custom-month" class="custom-month">July</span>
												<span id="custom-year" class="custom-year">2016</span>
												<nav>
													<span id="custom-prev" class="custom-prev"></span>
													<span id="custom-next" class="custom-next"></span>
													<span id="custom-current" class="custom-current" title="Got to current date"></span>
												</nav>
											</h3>
										</div>
										<div id="calendar" class="fc-calendar-container"><div class="fc-calendar fc-five-rows"><div class="fc-head"><div>Monday</div><div>Tuesday</div><div>Wednesday</div><div>Thursday</div><div>Friday</div><div>Saturday</div><div>Sunday</div></div><div class="fc-body"><div class="fc-row"><div></div><div></div><div></div><div></div><div><span class="fc-date">1</span><span class="fc-weekday">Fri</span></div><div><span class="fc-date">2</span><span class="fc-weekday">Sat</span></div><div><span class="fc-date">3</span><span class="fc-weekday">Sun</span></div></div><div class="fc-row"><div><span class="fc-date">4</span><span class="fc-weekday">Mon</span></div><div><span class="fc-date">5</span><span class="fc-weekday">Tue</span></div><div><span class="fc-date">6</span><span class="fc-weekday">Wed</span></div><div><span class="fc-date">7</span><span class="fc-weekday">Thu</span></div><div><span class="fc-date">8</span><span class="fc-weekday">Fri</span></div><div><span class="fc-date">9</span><span class="fc-weekday">Sat</span></div><div><span class="fc-date">10</span><span class="fc-weekday">Sun</span></div></div><div class="fc-row"><div><span class="fc-date">11</span><span class="fc-weekday">Mon</span></div><div><span class="fc-date">12</span><span class="fc-weekday">Tue</span></div><div><span class="fc-date">13</span><span class="fc-weekday">Wed</span></div><div class="fc-today "><span class="fc-date">14</span><span class="fc-weekday">Thu</span></div><div><span class="fc-date">15</span><span class="fc-weekday">Fri</span></div><div><span class="fc-date">16</span><span class="fc-weekday">Sat</span></div><div><span class="fc-date">17</span><span class="fc-weekday">Sun</span></div></div><div class="fc-row"><div><span class="fc-date">18</span><span class="fc-weekday">Mon</span></div><div><span class="fc-date">19</span><span class="fc-weekday">Tue</span></div><div><span class="fc-date">20</span><span class="fc-weekday">Wed</span></div><div><span class="fc-date">21</span><span class="fc-weekday">Thu</span></div><div><span class="fc-date">22</span><span class="fc-weekday">Fri</span></div><div><span class="fc-date">23</span><span class="fc-weekday">Sat</span></div><div><span class="fc-date">24</span><span class="fc-weekday">Sun</span></div></div><div class="fc-row"><div><span class="fc-date">25</span><span class="fc-weekday">Mon</span></div><div><span class="fc-date">26</span><span class="fc-weekday">Tue</span></div><div><span class="fc-date">27</span><span class="fc-weekday">Wed</span></div><div><span class="fc-date">28</span><span class="fc-weekday">Thu</span></div><div><span class="fc-date">29</span><span class="fc-weekday">Fri</span></div><div><span class="fc-date">30</span><span class="fc-weekday">Sat</span></div><div><span class="fc-date">31</span><span class="fc-weekday">Sun</span></div></div></div></div></div>
									</div>
								</div>
								<div class="clearfix"></div>
					    </div>-->
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
input.ke-input-text {
background-color: #FFFFFF;
background-color: #FFFFFF!important;
font-family: "sans serif",tahoma,verdana,helvetica;
font-size: 12px;
line-height: 24px;
height: 24px;
padding: 2px 4px;
border-color: #848484 #E0E0E0 #E0E0E0 #848484;
border-style: solid;
border-width: 1px;
display: -moz-inline-stack;
display: inline-block;
vertical-align: middle;
zoom: 1;
}
.form-group>label{font-size:12px;line-height:24px;}
#upload_pic_box{margin-top:20px;height:150px;}
#upload_pic_box .upload_pic_li{width:130px;float:left;list-style:none;}
#upload_pic_box img{width:100px;height:70px;}
</style>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">

KindEditor.ready(function(K){
	var editor = K.editor({
		allowFileManager : true
	});
	kind_editor = K.create("#ticket_explain",{
		width:'200px',
		height:'200px',
		resizeType : 1,
		allowPreviewEmoticons:false,
		allowImageUpload : true,
		filterMode: true,
		items : [
			'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist'
		],
//		emoticonsPath : './static/emoticons/',
//		uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=merchant/news" , 'link' 'image', , '|', 'emoticons'
	});
	kind_editor = K.create("#park_intr",{
		width:'200px',
		height:'200px',
		resizeType : 1,
		allowPreviewEmoticons:false,
		allowImageUpload : true,
		filterMode: true,
		items : [
			'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist'
		],
//		emoticonsPath : './static/emoticons/',
//		uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=merchant/news" , 'link' 'image', , '|', 'emoticons'
	});
	kind_editor = K.create("#ticket_cue",{
		width:'200px',
		height:'200px',
		resizeType : 1,
		allowPreviewEmoticons:false,
		allowImageUpload : true,
		filterMode: true,
		items : [
			'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist'
		],
//		emoticonsPath : './static/emoticons/',
//		uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=merchant/news" , 'link' 'image', , '|', 'emoticons'
	});
	$('#edit_form').submit(function(){
		$('#edit_form button[type="submit"]').prop('disabled',true).html('保存中...');
		var postObj = $('#edit_form').serialize();
		postObj = postObj + "&jsonPrice="+JSON.stringify(jsonTimeArr);
		//console.log(postObj);return false;
		$.post("{pigcms{:U('add')}",postObj,function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.href = "{pigcms{:U('index')}";
			}else{
				$('#edit_form button[type="submit"]').prop('disabled',false).html('<i class="ace-icon fa fa-check bigger-110"></i>保存');
				alert(result.info);
			}
		})
		return false;
	});
	$('#all_select').click(function(){
		if ($(this).attr('checked')){
			$('.paycheck').attr('checked', true);
		} else {
			$('.paycheck').attr('checked', false);
		}
	});
});
</script>
<include file="Public:footer"/>
	<script>
	//var jsonTimeStr = new array();
	//var jsonTimeArr = $.parseJSON(jsonTimeStr);
	var jsonTimeArr = [];
	function selectTime(){
		console.log(jsonTimeArr);
		pickerEvent.setPriceArr(jsonTimeArr);
		pickerEvent.Init("calendar");
	}
	</script>