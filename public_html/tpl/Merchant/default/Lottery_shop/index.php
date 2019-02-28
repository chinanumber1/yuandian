<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-credit-card"></i>
                <a href="{pigcms{:U('Lottery_shop/index')}">{pigcms{$config.shop_alias_name}抽奖配置</a>
            </li>
        </ul>
    </div>
	<div class="page-content form-horizontal ">
        <div class="page-content-area">
            <div class="row">
                <div class="col-xs-12">
					<form class="form" method="post" action="" target="_top" enctype="multipart/form-data">
						<label for="tab1" class="select_tab select" >基本信息</label>
						<label for="tab2" class="select_tab " >中奖列表</label>
					
						<div class="tab-content card_new" id="tab1">
							<div class="headings gengduoxian">商家自定义配置：</div>
							
							<div class="form-group">
								<label class="tiplabel"><label>{pigcms{$config.shop_alias_name}详情顶部文字：</label></label>
								<input type="text" name="detail_msg" id="detail_msg" class="px" value="{pigcms{$lottery.detail_msg}" style="width:210px;"/><span class="tip"></span>
							</div>
							
							<div class="form-group">
								<label class="tiplabel"><label>抽奖页自定义标题：</label></label>
								<input type="text" name="lottery_msg" id="lottery_msg" class="px" value="{pigcms{$lottery.lottery_msg}" style="width:210px;"/><span class="tip"></span>
							</div>
							
							<div class="form-group">
								<label class="tiplabel"  style="vertical-align:top;"><label>抽奖规则：</label></label>
								<textarea name="lottery_rule" id="lottery_rule" class="px" style="width:410px;height:120px;">{pigcms{$lottery.lottery_rule}</textarea>
							</div>
							
							
							<div class="headings gengduoxian">系统抽奖信息配置：</div>
							
							<div class="form-group other">
								<if condition="!empty($lottery['sys_content'])">
									<table cellpadding="0" cellspacing="0" class="px" width="80%" >
									<volist name="lottery.sys_content" id="vv">
										<tr class="plus textIamge" >
											<td width="100"><label class="tiplabel">【抽奖项<label>{pigcms{$i}</label>】</label></td>
											
											<td>
												
												<table style="width:100%;">
												<if condition="$i eq 1">
													<tr >
														<td width="10%" sytle="font-weight:bold">奖品类型</td>
														<td width="30%" sytle="font-weight:bold">图片</td>
														<td width="30%" sytle="font-weight:bold">标题<font color="#ada9a9">(不要超过五个字)</font></td>
														<td width="10%" sytle="font-weight:bold">概率值</td>
														<td width="20%" sytle="font-weight:blod">概率</td>
														
													</tr>
												</if>
												<tr class="textIamge">
												
													<td width="10%"><if condition="$vv.type eq 0">红包<elseif condition="$vv.type eq 1" />优惠券<else />自定义</if></td>
													<td width="30%"><if condition="$vv.image_url neq ''"><img class="mini_imgs" src="{pigcms{$vv.image_url}"><else />无</if></td>
													
													<td width="30%">{pigcms{$vv.title}</td>
													
													<td width="10%">{pigcms{$vv.probability}</td>
													<td width="20%" >中奖概率{pigcms{$vv['probability']/$lottery['probability_all']*100|round=###,2}%</td>
												</tr>
											</table>
											</td>
										</tr>
									</volist>
									
									</table>
									
								</if>
							</div>
							<div class="headings gengduoxian">抽奖信息配置：</div>
							
							<div class="form-group other">	
								<if condition="!empty($lottery['content'])">
									<table cellpadding="0" cellspacing="0" class="px" width="80%" >
									<volist name="lottery.content" id="vo">
										<tr class="plus textIamge" >
											<td width="100"><label class="tiplabel">【抽奖项<label>{pigcms{$i}</label>】</label></td>
											
											<td>
												
												<table style="width:100%;">
												<if condition="$i eq 1">
													<tr >
														<td width="20">是否中奖</td>
														<td width="20">奖品类型</td>
														<td width="36" sytle="font-weight:bold">图片<font color="blue">(可传,不传只显示文字)</font></td>
														<td width="30" sytle="font-weight:bold">标题<font color="#ada9a9">(不要超过五个字)</font></td>
														<td width="35" sytle="font-weight:bold">概率值</td>
														<if condition="$lottery.status eq 1"><td width="35" sytle="font-weight:blod">概率</td></if>
													</tr>
												</if>
												<tr class="textIamge">
													<td width="20">
														<select name="is_win[]">
															<option value="1" <if condition="$vo.is_win eq 1">selected="selected"</if>>是</option>
															<option value="0" <if condition="$vo.is_win eq 0">selected="selected"</if>>否</option>
														</select>
													</td>
													<td width="20">
														<select name="type[]">
															<option value="0" <if condition="$vo.type eq 0">selected="selected"</if>>优惠券</option>
															<option value="1" <if condition="$vo.type eq 1">selected="selected"</if>>自定义</option>
															<!--<option value="2"></option>-->
														</select>
													</td>
													<td width="180">
													<input type="text"   name="image_url[]"  class="px input-image" value="{pigcms{$vo.image_url}"   readonly>&nbsp;&nbsp;
													<if condition="$vo.image_url neq ''"><img class="mini_img" src="{pigcms{$vo.image_url}"></if>
													<a href="javascript:void(0)" class="fileupload-exists btn btn-ccc J_selectImage" >上传图片</a>
													
													</td>
													
													<td width="50">
													<input  type="text" class="px "  name="title[]"  value="{pigcms{$vo.title}" id="url{pigcms{$i}" >
													<input type="hidden" name="coupon_id[]" id="url{pigcms{$i}_code" value="{pigcms{$vo.coupon_id}" />
													<a href="#modal-table" <if condition="$vo.type eq 1">style="display:none"</if> class="addLink btn btn-ccc" onclick="addLinks('url{pigcms{$i}',0)" data-toggle="modal">选优惠券</a>
													
													</td>
													
													<td width="80"><input type="text"  class="px" name="probability[]"   value="{pigcms{$vo.probability}" ></td>
													<if condition="$lottery.status eq 1"><td width="35" >{pigcms{$vo['probability']/$lottery['probability_all']*100|round=###,2}%</td></if>
												</tr>
											</table>
											</td>
										</tr>
									</volist>
									
									</table>
									
								<else />
								<table cellpadding="0" cellspacing="0" class="px" width="80%" >
									<for start="1" end="7">
									<tr class="plus textIamge" >
										<td width="100"><label class="tiplabel">【抽奖项<label>{pigcms{$i}</label>】</label></td>
										<td>
											<table style="width:100%;">
												<if condition="$i eq 1">
													<tr >
														<td width="20">是否中奖</td>
														<td width="20">奖品类型</td>
														<td width="36" sytle="font-weight:bold">图片<font color="blue">(可传,不传只显示文字)</font></td>
														<td width="30" sytle="font-weight:bold">标题<font color="#ada9a9">(不要超过五个字)</font></td>
														<td width="35" sytle="font-weight:bold">概率值</td>
													</tr>
												</if>
													<tr class="textIamge">
													<td width="20">
														<select name="is_win[]">
															<option value="1" selected="selected">是</option>
															<option value="0" >否</option>
													
														</select>
													</td>
													<td width="20">
														<select name="type[]">
															<option value="0" selected="selected">优惠券</option>
															<option value="1">自定义</option>
															<!--<option value="2"></option>-->
														</select>
													</td>
													<td width="180"><input type="text"   name="image_url[]"  class="px input-image" value=""   readonly>&nbsp;&nbsp;<a href="javascript:void(0)" class="fileupload-exists btn btn-ccc J_selectImage" >上传图片</a></td>
													
													<td width="80">
													<input  type="text" class="px "  name="title[]"  id="url{pigcms{$i}" >
													<input type="hidden" name="coupon_id[]" id="url{pigcms{$i}_code" value="" />
													<a href="#modal-table"  class="addLink btn btn-ccc " onclick="addLinks('url{pigcms{$i}',0)" data-toggle="modal">选优惠券</a>
													</td>
													
													<td width="80"><input type="text"  class="px" name="probability[]" value="0"></td>
												<tr/>
												
											</table>
										</td>
									</tr>
									</for>
									
									
								</table>
								</if>
							</div>
							
							
							
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

<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="./static/js/upyun.js"></script>
<script src="./static/js/cart/jscolor.js" type="text/javascript"></script>
<link rel="stylesheet" href="./static/kindeditor/themes/default/default.css"/>
<link rel="stylesheet" href="./static/kindeditor/plugins/code/prettify.css"/>
<style>
	.select_tab{
		width:100px;
		height:36px;
		color: #555;
		border: 1px solid #c5d0dc;
		font-size:16px;
		z-index:9;
		line-height: 36px;
    text-align: center;
		position: relative;
	}
	label .select_tab{
		display: inline-block;
		margin: 0 0 -1px;
		padding: 15px 25px;
		font-weight: 600;
		text-align: center;
		color: #bbb;
		border: 1px solid transparent;
	}
	
	.select{
		border-top: 1px solid orange;
		border-bottom: 1px solid #fff;
	}
	.card_new{
		margin-top:-6px;
	}
	.other label,table{
		color:#a0a0a0;
	}
	.mini_img{
		width:60px;
		height:30px;
	}
	.mini_imgs{
		width:30%;
		height:30px;
	}
</style>
<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>

<script type="text/javascript">
	KindEditor.ready(function(K){
			var site_url = "{pigcms{$config.site_url}";
			var editor = K.editor({
				allowFileManager : true
			});
			$('.J_selectImage').click(function(){
				var upload_file_btn = $(this);
				editor.uploadJson = "{pigcms{:U('Config/ajax_upload_pic')}";
				editor.loadPlugin('image', function(){
					editor.plugin.imageDialog({
						showRemote : false,
						clickFn : function(url, title, width, height, border, align) {
							upload_file_btn.siblings('.input-image').val(site_url+url);
							editor.hideDialog();
						}
					});
				});
			});

		});
</script>
<script type="text/javascript">
    $(document).ready(function() {
		$('#sys_card_bg').change(function(){
			if($.trim($('#bgs').val()) == ''){
				$('#cardbg').attr('src', $(this).val());
			}
		});
		
		$('select[name="type[]"]').change(function(event) {
			var lottery_type = $(this).val();
			var addLink  = $(this).parent().parent().find('.addLink');
			var title  = $(this).parent().parent().find('input[name="title[]"]');
			var is_win  = $(this).parent().parent().find('input[name="is_win[]"]');
			
			if(lottery_type==0){
				addLink.show();
				title.val('');
				title.attr('readonly',true);
				is_win.find("option[value='1']").attr('selected',false);
				is_win.val(1);
			}else{
				addLink.hide();
				title.val('');
				title.attr('readonly',false);
				is_win.attr('selected',false);
				is_win.val(0);
			}
		});	
		
		if($('.support_score_select:checked').val()==0){
            $('.support_score').css('display','none');
		}else{
            $('.support_score').css('display','block');
		}

		$('#support_recharge').change(function(event) {
			if($('#support_recharge').val()==0){
                $('.support_recharge').css('display','none');

			}else{
                $('.support_recharge').css('display','block');
			}
		});

		$('.support_score_select').change(function(event) {
            if($('.support_score_select:checked').val()==0){
                $('.support_score').css('display','none');
			}else{
                $('.support_score').css('display','block');
			}
		});
	   $('#tab2').hide();
		$('.select_tab').click(function(){
			$('.select_tab').removeClass('select');
			$(this).addClass('select');
			var id_for = $(this).attr('for');
			if(id_for=='tab1'){
				
				window.location.href="{pigcms{:U('index')}"
				
			}else{
				window.location.href="{pigcms{:U('had_pull')}"
				
			}
		
			$('#'+id_for).show();
			
		});
		
		//$('select[name="wx_color"]').css('background-color','#63b359');	
			$('select[name="wx_color"]').change(function(event) {
				$('#wx_color').css('background-color',$('select[name="wx_color"]').find('option:selected').html());
				$(this).css('background-color',$('select[name="wx_color"]').find('option:selected').html());
			});		
		if($('.plus').length<2){
			$('.delete').children().hide();
		}
    });
	function upload_func(){
		$('#cardbg').attr('src',$('#bgs').val());
	}
	
	function plus(){
			var item = $('.plus:last');
			var newitem = $(item).clone(true);
			var No = parseInt(item.find(".tiplabel label").html())+1;
			$('.delete').children().show();
			if(No>4){
				alert('不能超过4条信息');
			}else{
				$(item).after(newitem);
				newitem.find('input').attr('value','');
				newitem.find('textarea').attr('value','');
				newitem.find("#addLink").attr('onclick',"addLink('url"+No+"',0)");
				newitem.find(".tiplabel label").html(No);
				newitem.find('input[name="url[]"]').attr('id','url'+No);
				newitem.find('.delete').children().show();
			}
		}
		function del(obj){
			if($('.plus').length<=1){
				$('input[name="wx_image_url[]"]').val('');
				$('textarea[name="wx_text[]"]').val('');
				$('.delete').children().hide();
			}else{
				if($('.plus').length==2){
					$('.delete').children().hide();
				}
				$(obj).parents('.plus').remove();
				$.each($('.plus'), function(index, val) {
					var No =index+1;
					$(val).find(".tiplabel label").html(No);
					$(val).find('input[name="url[]"]').attr('id','url'+No);
					$(val).find("#addLink").attr('onclick',"addLink('url"+No+"',0)");
				});
			}
		}
		
		function sysc(){
			$.ajax({
				url: '{pigcms{:U('sysc_wxcard')}',
				type: 'POST',
				dataType: 'json',
				data: {param1: 'value1'},
				beforeSend:function(){
					var index = layer.load(1, {
					  shade: [0.3,'#000'] //0.1透明度的白色背景
					});
				},
				success:function(data){
					layer.closeAll()
					layer.alert(data.info)
				}
			});
		}
		
		function addLinks(domid,iskeyword){
			art.dialog.data('domid', domid);
			art.dialog.open('?g=Merchant&c=Link&a=Coupon_list&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
		}
</script>
<link rel="stylesheet" href="{pigcms{$static_path}css/card_new.css"/>
<include file="Public:footer"/>