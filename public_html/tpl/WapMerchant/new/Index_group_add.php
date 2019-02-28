<!--头部-->
<include file="Public:top"/>
<!--头部结束-->
<link rel="stylesheet" href="{pigcms{$static_path}css/shop_item.css">
<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js"></script>
<style>
	.pigcms-container{background: none;padding: 0;}
	.form_tips{color:red;}
	.radio{margin: 0 3%;padding: 5px 0; width: 92%;line-height: 20px!important;background-color: #FFF;}
	.pigcms-textarea{margin-bottom:10px;}
</style>
<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
<body>
		<header class="pigcms-header mm-slideout">
			<a href="javascript:history.go(-1);" id="pigcms-header-left"><i class="iconfont icon-left"></i></a>
			<p id="pigcms-header-title">添加{pigcms{$config.group_alias_name}商品</p>
		</header>
	<div class="container container-fill" style='padding-top:50px'>

		<form class="pigcms-form" method="post" action="{pigcms{:U('Index/group_add')}">
			<div class="pigcms-container">
				<p class='pigcms-form-title'>商品标题：</p>
				<input type="text" class="pigcms-input-block" name="name" placeholder="商品的介绍标题，100字段以内,首页和列表页将显示" value="">
			</div>
			<div class="pigcms-container">
				<p class='pigcms-form-title'>商品名称：</p>
				<input type="text" class="pigcms-input-block" name="s_name" placeholder="必填。在订单页显示此名称" value="">
			</div>
			<div class="pigcms-container">
				<p class='pigcms-form-title'>商品简介：</p>
				<textarea class="pigcms-textarea" name='intro' rows=5 placeholder="商品的简短介绍，建议为100字以下"></textarea>
			</div>
			<div class="pigcms-container">
				<p class='pigcms-form-title'>关键词：</p>
				<input type="text" class="pigcms-input-block" name="keywords" placeholder="选填,用空格分隔不同的关键词，最多5个，用户在微信将按此值搜索" value="">
			</div>
			<div class="pigcms-container">
				<p class='pigcms-form-title'>原价：</p>
				<input type="text" class="pigcms-input-block" name="old_price" placeholder="必填。最多支持1位小数" value="">
			</div>
			<div class="pigcms-container">
				<p class='pigcms-form-title'>{pigcms{$config.group_alias_name}价：</p>
				<input type="text" class="pigcms-input-block" name="price" placeholder="必填。最多支持1位小数" value="">
			</div>
			<div class="pigcms-container">
				<p class='pigcms-form-title'>微信优惠：</p>
				<input type="text" class="pigcms-input-block" name="wx_cheap" placeholder="单位元，最多支持1位小数，不填则不显示微信优惠！实际购买价=（{pigcms{$config.group_alias_name}价-微信优惠）" value="">
			</div>
			<div class="pigcms-container">
				<p class='pigcms-form-title'>{pigcms{$config.group_alias_name}开始时间：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="form_tips">到了{pigcms{$config.group_alias_name}开始时间，商品才会显示！</span></p>
				<input class="pigcms-input-block Wdate" readonly="readonly" type="text"  style="height:30px;" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日 HH时mm分ss秒',startDate:'{pigcms{:date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME'])}',vel:'begin_time'})" value="{pigcms{:date('Y年m月d日 H时i分s秒',$_SERVER['REQUEST_TIME'])}"/>
				<input name="begin_time" id="begin_time" type="hidden" value="{pigcms{:date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME'])}"/>	
			</div>
			<div class="pigcms-container">
				<p class='pigcms-form-title'>{pigcms{$config.group_alias_name}结束时间：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="form_tips">超过{pigcms{$config.group_alias_name}结束时间，会结束{pigcms{$config.group_alias_name}！</span></p>
				<input class="pigcms-input-block Wdate" type="text" readonly="readonly" style="height:30px;" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日 HH时mm分ss秒',startDate:'{pigcms{:date('Y-m-d H:i:s',strtotime('+1 day'))}',vel:'end_time'})" value="{pigcms{:date('Y年m月d日 H时i分s秒',strtotime('+1 day'))}"/>
				<input name="end_time" id="end_time" type="hidden" value="{pigcms{:date('Y-m-d H:i:s',strtotime('+1 day'))}"/>							
			</div>
			<div class="pigcms-container">
				<p class='pigcms-form-title'>{pigcms{$config.group_alias_name}券有效期：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="form_tips">必填</span></p>
				<input class="pigcms-input-block Wdate" type="text" readonly="readonly" style="height:30px;" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日 HH时mm分ss秒',startDate:'{pigcms{:date('Y-m-d H:i:s',strtotime('+7 day'))}',vel:'deadline_time'})" value="{pigcms{:date('Y年m月d日 H时i分s秒',strtotime('+7 day'))}"/>
				<input name="deadline_time" id="deadline_time" type="hidden" value="{pigcms{:date('Y-m-d H:i:s',strtotime('+7 day'))}"/>					
			</div>
			<div class="pigcms-container">
				<p class='pigcms-form-title'>使用时间限制：</p>
				  <select name="is_general" class="pigcms-input-block">
				     <option value="0">周末、法定节假日通用</option>
					<option value="1">周末不能使用</option>
					<option value="2">法定节假日不能使用</option>
				  </select>
			</div>
			<div class="pigcms-container" style="margin-bottom: 15px;">
			<p class='pigcms-form-title'>选择店铺：</p>
			<volist name="store_list" id="vo">
					<div class="radio">
						<input class="paycheck ace" type="checkbox" name="store[]" value="{pigcms{$vo.store_id}" id="store_{pigcms{$vo.store_id}" checked="checked"/>
						<span class="lbl"><label for="store_{pigcms{$vo.store_id}">{pigcms{$vo.name} - {pigcms{$vo.area_name}-{pigcms{$vo.adress}</label></span>
					</div>
			</volist>

			</div>

			<div class="pigcms-container">
				<p class='pigcms-form-title'>{pigcms{$config.group_alias_name}类型：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="form_tips">如果是{pigcms{$config.group_alias_name}券或代金券，则会生成券密码；如果是实物，则需要填写快递单号</span></p>
					<select name="tuan_type" class="pigcms-input-block">
						<option value="0">{pigcms{$config.group_alias_name}券</option>
						<option value="1">代金券</option>
						<option value="2">实物</option>
					</select>
			</div>
			<div class="pigcms-container">
				<p class='pigcms-form-title'>选择分类：</p>
				<select id="choose_catfid" name="cat_fid" class="pigcms-input-block" style="margin-right:10px;">
					<volist name="f_category_list" id="vo">
						<option value="{pigcms{$vo.cat_id}">{pigcms{$vo.cat_name}</option>
					</volist>
				</select>
				<select id="choose_catid" name="cat_id" class="pigcms-input-block" style="margin-right:10px;">
					<volist name="s_category_list" id="vo">
						<option value="{pigcms{$vo.cat_id}">{pigcms{$vo.cat_name}</option>
					</volist>
				</select>
			</div>
			<div style="border:1px solid #c5d0dc;padding:0px 0px 10px 10px;" id="custom_html_tips">
				<div class="form-group" style="margin-top:10px;color:red;">以下为主分类设定的特殊字段，不同分类字段不同，请选择。</div><br/>
				<div id="custom_html">{pigcms{$custom_html}</div>
			</div>
			<div style="border:1px solid #c5d0dc;padding:0px 0px 10px 10px;margin-bottom:10px;" id="cue_html_tips">
				<div class="form-group" style="margin-top:20px;color:red;">以下为主分类设定的 购买须知填写项，请填写。</div><br/>
				<div id="cue_html">{pigcms{$cue_html}</div>
			</div>
			<div class="pigcms-container">
				<p class='pigcms-form-title'>本单详情：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="form_tips">必填</span></p>
				<textarea class="pigcms-textarea" name='content' rows=7 placeholder="本单详情介绍，必填"></textarea>
			</div>
			<div class="pigcms-container">
				<p class='pigcms-form-title'>商品图片</p>
				<div class="pic-detail-container">
					<div class="detail-img" id='detail-img-add' onclick="upLoadDetailImg()">
						<i class="iconfont icon-upload"></i>
						<p>添加商品图片</p>
					</div>
					<input type="hidden" name='pic_detail'>
					<div class="clearfix"></div>
				</div>
			</div>
			<if condition="!empty($levelarr)">
			<div id="levelcoupon" style="border:1px solid #c5d0dc;padding:0px 0px 10px 10px;margin-bottom:10px;">
				<div class="pigcms-container">
					<p class="col-sm-1" style="color:red;width:95%;">说明：必须设置一个会员等级优惠类型和优惠类型对应的数值，我们将结合优惠类型和所填的数值来计算该商品会员等级的优惠的幅度！</p>
				</div>
				<volist name="levelarr" id="vv">
				  <div class="pigcms-container">
					<input  name="leveloff[{pigcms{$vv['level']}][lid]" type="hidden" value="{pigcms{$vv['id']}"/>
					<input  name="leveloff[{pigcms{$vv['level']}][lname]" type="hidden" value="{pigcms{$vv['lname']}"/>
					<p class="pigcms-form-title">{pigcms{$vv['lname']}：优惠类型：&nbsp;</p>
					<select name="leveloff[{pigcms{$vv['level']}][type]" style="margin-left: 15px;width: 160px;">
						<option value="0">无优惠</option>
						<option value="1">百分比（%）</option>
						<option value="2">立减</option>
					</select>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="leveloff[{pigcms{$vv['level']}][vv]" type="text" placeholder="请填写一个优惠值数字" onkeyup="value=value.replace(/[^1234567890]+/g,'')" style="width: 160px;"/>
				</div>
				</volist>
			</div>
			</if>

	      <div id="relpackages" style="border:1px solid #c5d0dc;padding:0px 0px 10px 10px;margin-bottom:10px;border-top:none;">
				<div class="pigcms-container">
					<p class="pigcms-form-title" style="color:red;width:95%;">说明：一个团购商品只能参与一个套餐！</p>
				</div>
			  <div class="pigcms-container">
					<p class="pigcms-form-title">本{pigcms{$config.group_alias_name}套餐标签：</p>
					<input class="pigcms-input-block" name="tagname" type="text" value="" placeholder="必须填写（例如：3-4人）" />
					<if condition="!empty($mpackagelist)">
					<p class="pigcms-form-title" style="margin-left:20px;">选择加入套餐：</p>
					<select name="packageid" style="width:300px;" class="pigcms-input-block">
					<option value="0">不加入任何套餐</option>
					<volist name="mpackagelist" id="vo">
					  <option value="{pigcms{$vo['id']}">{pigcms{$vo['title']}</option>
					</volist>
					</select>
					<else />
					<p class="pigcms-form-title" style="color:red;">您还没有套餐可选，<a href="{pigcms{:U('Index/mpackageadd')}" style="color: green;">请点击这里去新建吧</a></p>
					</if>
			   </div>
			</div>

			<div id="txtnum" class="tab-pane" style="display:none;">
				<div class="pigcms-container">
					<p class="pigcms-form-title">成功{pigcms{$config.group_alias_name}人数要求：</p>
					<input class="pigcms-input-block" maxlength="20" name="success_num" type="text" value="1" /><span class="form_tips">最少需要多少人购买才算{pigcms{$config.group_alias_name}成功。</span>
				</div>
				<div class="pigcms-container" style="display:none;">
					<p class="pigcms-form-title">虚拟已购买人数：</p>
					<input class="pigcms-input-block" maxlength="20" name="virtual_num" type="text" value="0" /><span class="form_tips">前台购买人数会显示[ 虚拟购买人数+真实购买人数 ]</span>
				</div>
				<div class="pigcms-container">
					<p class="pigcms-form-title">商品总数量：</p>
					<input class="pigcms-input-block" maxlength="20" name="count_num" type="text" value="0" /><span class="form_tips">0表示不限制，否则产品会出现“已卖光”状态</span>
				</div>
				<div class="pigcms-container">
					<p class="pigcms-form-title">ID最多购买数量：</p>
					<input class="pigcms-input-block" maxlength="20" name="once_max" type="text" value="0" /><span class="form_tips">一个ID最多购买数量，0表示不限制</span>
				</div>
				<div class="pigcms-container">
					<p class="pigcms-form-title">一次最少购买数量：</p>
					<input class="pigcms-input-block" maxlength="20" name="once_min" type="text" value="1" /><span class="form_tips">购买数量低于此设定的不允许参团</span>
				</div>
			</div>
			<!--<div class="pigcms-container">
				<div class="top-img-container">
					<div class="up-load-img" onclick="upLoadImg(this)">
						<i class="iconfont icon-upload"></i>
							<p>添加商品图片</p>
							<div class="clearfix"></div>
							<input type="hidden" name="pic_url" value="">
					</div>
				</div>			
			</div>--->
			<button type="submit" class="pigcms-btn-block pigcms-btn-block-info" name="submit" value="添加">添加</button>
			<input type="hidden" name="action" value="edit_item" />
		</form>
		
	
		</div>

	
	
</body>
		<script type="text/javascript">
		    var picarr = [];
			var attachurl = "{pigcms{$site_URl}",
			    localIds,
				pic_detail = [],
				Img_Classify = 'group',
				upload_url = "{pigcms{:U('Index/img_uplode')}";
			$("input[name='pic_detail']").val(pic_detail);

		$('#choose_catfid').change(function(){
		$.getJSON("{pigcms{:U('Index/ajax_get_category')}",{cat_fid:$(this).val()},function(result){
			if(result.error == 0){
				var catid_html = '';
				$.each(result.cat_list,function(i,item){
					catid_html += '<option value="'+item.cat_id+'">'+item.cat_name+'</option>';
				});
				$('#choose_catid').html(catid_html);
				if(result.custom_html == ''){
					$('#custom_html_tips').hide();
				}else{
					$('#custom_html_tips').show();
				}
				if(result.cue_html == ''){
					$('#cue_html_tips').hide();
				}else{
					$('#cue_html_tips').show();
				}
				$('#custom_html').html(result.custom_html);
				$('#cue_html').html(result.cue_html);
			}else{
				// $('#choose_catid').html('<option value="0">请选择其他分类</option>');
				
				alert(result.msg);
				$('#choose_catfid option').eq(0).prop('selected',true);
				//$('#choose_catfid').trigger('change');
			}
		});
	});	
	$('#add_form').submit(function(){
		$('#save_btn').prop('disabled',true);
		$.post("{pigcms{:U('Index/group_add')}",$('#add_form').serialize(),function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.href = "{pigcms{:U('Index/gpro')}";
			}else{
				alert(result.info);
			}
			$('#save_btn').prop('disabled',false);
		})
		return false;
	});
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/shop_item_edit.js?ver=<php>echo time();</php>"></script>
	<include file="Public:footer"/>
</html>