<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-empire"></i>
				<a href="{pigcms{:U('Index/active_store_list')}">推荐快店管理</a>
			</li>
			<li class="active">添加快店</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" action="{pigcms{:U('Index/active_store_edit',array('id'=>$now_acitive['pigcms_id']))}" onsubmit="return doSub();">
						<input type="hidden" name="store_id" id="store_id"/>
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="title">快店网址</label></label>
									<input class="col-sm-2" size="80" name="url" id="store_url" type="text" value="{pigcms{$config.site_url}/wap.php?c=Shop&a=classic_shop&shop_id={pigcms{$now_acitive.store_id}"/>&nbsp;&nbsp;
									<button style="width:50px;height:35px" onclick="return checkGroup();">检测</button>
									<span style='color:red;display:none;' class='js-show-red'>暂无匹配的快店信息</span>
								</div>
								<div class="form-group js-show-red-no">
									<label class="col-sm-1"><label for="title">快店名称</label></label>
									<input class="col-sm-2" size="20" type="text" style="border:none;background:white!important;" readonly="readonly" id="store_name"/>
								</div>
								<div class="form-group js-show-red-no">
									<label class="col-sm-1"><label for="title">所属商家</label></label>
									<input class="col-sm-2" size="20" type="text" style="border:none;background:white!important;" readonly="readonly" id="meal_name"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="sort">排序</label></label>
									<input class="col-sm-2" size="80" name="sort" id="sort" type="text" value="{pigcms{$now_acitive.sort}"/>
								</div>
								<div class="space"></div><div class="space"></div><div class="space"></div>
								<div class="clearfix form-actions">
									<div class="col-md-offset-3 col-md-9">
										<button class="btn btn-info" type="submit" <if condition="!in_array(152,$house_session['menus'])">disabled="disabled"</if>>
											<i class="ace-icon fa fa-check bigger-110"></i>
											保存
										</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
.ke-dialog-body .ke-input-text{height: 30px;}
</style>
<<script type="text/javascript">
$(function(){
	$('.js-show-red').html('暂无匹配的快店信息');
	checkGroup();
	hide();
})
function preview1(input){
	if (input.files && input.files[0]){
		var reader = new FileReader();
		reader.onload = function (e) { $('#img').attr('src', e.target.result);$('#imgBox').show();}
		reader.readAsDataURL(input.files[0]);
	}
}

function checkGroup(){
	var check_url = $('#store_url').val();
	if(check_url.length <1){
		alert('请输入快店地址，才能做匹配');
	}
	
	if(check_url.indexOf('wap.php')>0){
		var a = parseURL(check_url);
		var store_id = a.params.shop_id;
		// alert(store_id);
	}else{
		var spstr = check_url.split("/");
		var subhtml = spstr[spstr.length-1].split(".");
		var store_id = subhtml[subhtml.length-2];
	}
	if(!store_id){
		showRed();
	}
	$('#store_id').val(store_id);
	$.post('{pigcms{:U("Index/check_store")}', {store_id:store_id}, function(result){
		if(result.error == 0){
			$('.js-show-red').html('暂无匹配的快店信息');
			hideRed();
			$('#store_name').val(result.store_name);
			$('#meal_name').val(result.merchant_name);
		}else if(result.error == 1){
			$('#store_name').val('');
			$('#meal_name').val('');
			$('.js-show-red').html(result.msg);
			showRed();
		}else{
			alert('保存失败，请稍后重试');
		}
		
		return false;
	});
	return false;
}

function parseURL(url) {
   var a =  document.createElement('a');
   a.href = url;
   return {
       source: url,
       protocol: a.protocol.replace(':',''),
       host: a.hostname,
       port: a.port,
       query: a.search,
       params: (function(){
           var ret = {},
               seg = a.search.replace(/^\?/,'').split('&'),
               len = seg.length, i = 0, s;
           for (;i<len;i++) {
               if (!seg[i]) { continue; }
               s = seg[i].split('=');
               ret[s[0]] = s[1];
           }
           return ret;
       })(),
       file: (a.pathname.match(/\/([^\/?#]+)$/i) || [,''])[1],
       hash: a.hash.replace('#',''),
       path: a.pathname.replace(/^([^\/])/,'/$1'),
       relative: (a.href.match(/tps?:\/\/[^\/]+(.+)/) || [,''])[1],
       segments: a.pathname.replace(/^\//,'').split('/')
   };
}

function showRed(){
	$('.js-show-red').show();
	$('.js-show-red-no').hide();
}
function hideRed(){
	$('.js-show-red').hide();
	$('.js-show-red-no').show();
}
function hide(){
	$('.js-show-red').hide();
	$('.js-show-red-no').hide();
}
function doSub(){
	var store_id = $('#store_id').val();
	var sort = $('#sort').val();
	if(!store_id){
		alert('检测通过之后才能保存哦');
		return false;
	}
}
</script>
<include file="Public:footer"/>