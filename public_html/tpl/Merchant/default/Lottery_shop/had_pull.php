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
						<label for="tab1" class="select_tab " id="tab1" >基本信息</label>
						<label for="tab2" class="select_tab select" >中奖列表</label>
					
						<div class="tab-content card_new" id="tab1">
							<table width="100%" cellspacing="0" class="table table-striped table-bordered table-hover">
								<colgroup>
									
									<col/>
									<col/>
									<col/>
									<col/>
									<col/>
									<col/>
									<col width="180" align="center"/>
								</colgroup>
								<thead>
									<tr>
										<th>ID</th>
										<th>商家ID</th>
										<th>用户ID</th>
										<th>用户手机</th>
										<th>分享时间</th>
										<th>中奖时间</th>
										<th>描述</th>
										
									</tr>
								</thead>
								<tbody>
									<if condition="is_array($lottery_list)">
										<volist name="lottery_list" id="vo">
											<tr>
												<td>{pigcms{$vo.id}</td>
												<td>{pigcms{$vo.mer_id}</td>
												<td>{pigcms{$vo.uid}</td>
												<td>{pigcms{$vo.phone}</td>
												<td>{pigcms{$vo.lottery_time|date='Y-m-d',###} </td>
												<td><if condition="$vo.award_time gt 0">{pigcms{$vo.award_time|date='Y-m-d',###} </if></td>
												<php>$tmp = unserialize($vo['return']);</php>
												<td>{pigcms{$tmp.msg} </td>
												
											</tr>
										</volist>
										
									<else/>
										<tr><td class="textcenter red" colspan="7">列表为空！</td></tr>
									</if>
								</tbody>
							</table>
							{pigcms{$pagebar}
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

	.mini_img{
		width:60px;
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