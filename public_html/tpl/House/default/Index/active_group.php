<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-empire"></i>
				<a href="{pigcms{:U('Index/active_group_list')}">推荐{pigcms{$config.group_alias_name}管理</a>
			</li>
			<li class="active">添加{pigcms{$config.group_alias_name}</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					
				   <input name="group_id" type="hidden" value="" id="group_id"/>
					<div class="tab-content">
						<div id="basicinfo" class="tab-pane active">
						 
							<div class="form-group">
								<label class="col-sm-1"><label for="title">{pigcms{$config.group_alias_name}网址</label></label>
								<input class="col-sm-2" size="80" name="group_url" id="group_url" type="text" value=""/>&nbsp;&nbsp;
								<button style="width:50px;height:35px" onclick="return checkGroup();">检测</button>
								<span style='color:red;display:none;' class='js-show-red'>暂无匹配的团购信息</span>
							</div>
							<div class="form-group js-show-red-no">
								<label class="col-sm-1"><label for="title">{pigcms{$config.group_alias_name}名称</label></label>
								<span id="group_name"></span>
								&nbsp;&nbsp;
							</div>
							<div class="form-group js-show-red-no">
								<label class="col-sm-1"><label for="title">{pigcms{$config.group_alias_name}所属商家</label></label>
								<span id="meal_name"></span>&nbsp;&nbsp;
							</div>
						
							<div class="form-group">
								<label class="col-sm-1"><label for="sort">排序</label></label>
								<input class="col-sm-2" size="80" name="sort" id="sort" type="text" value="0"/>
							</div>
							<div class="space"></div><div class="space"></div><div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit" onclick="doSub();">
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
								</div>
							</div>
						</div>
					</div>
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
	$('.js-show-red').html('暂无匹配的{pigcms{$config.group_alias_name}信息');
	hide();
})
function checkGroup(){
	var check_url = $('#group_url').val();
	if(check_url.length <1){
		alert('请输入{pigcms{$config.group_alias_name}地址，才能做匹配');
	}
	if(check_url.indexOf('wap.php')>0){
		var a = parseURL(check_url);
		var group_id = a.params.group_id;
	}else{
		var spstr = check_url.split("/");
		var subhtml = spstr[spstr.length-1].split(".");
		var group_id = subhtml[subhtml.length-2];
	}
	
	if(!group_id){
		showRed();
	}
	$('#group_id').val(group_id);
	$.post('{pigcms{:U("Index/check_group")}', {group_id:group_id}, function(result){
		
		if(result.error == 0){
			$('.js-show-red').html('暂无匹配的{pigcms{$config.group_alias_name}信息');
			hideRed();
			$('#group_name').html(result.group_name);
			$('#meal_name').html(result.merchant_name);
		}else if(result.error == 1){
			$('#group_name').html();
			$('#meal_name').html();
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
	var check_url = $('#group_url').val();
	if(check_url.length <1){
		alert('请输入{pigcms{$config.group_alias_name}地址，才能做匹配');
	}
	var groupId = $('#group_id').val();
	var sort = $('#sort').val();
	if(!groupId){
		alert('检测通过之后才能保存哦');
		return false;
	}
	$.post('{pigcms{:U("Index/active_group")}', {group_id:groupId,sort:sort,'url':check_url}, function(result){ 
		if(result){
			if(result.error == 0){
				window.location.href = "{pigcms{:U('Index/active_group_list')}";
			}else if(result.error == 1){
				alert(result.msg);
			}else{
				alert('保存失败，请稍后重试');
			}
		}else{
			alert('保存失败，请稍后重试');
		}
		return false;
	});
}
</script>
<include file="Public:footer"/>