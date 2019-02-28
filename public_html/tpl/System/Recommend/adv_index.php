<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Recommend/index')}">推荐楼盘列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Recommend/recommend_add')}','添加推荐',600,300,true,false,false,addbtn,'add',true);">添加推荐</a>|
					<a href="{pigcms{:U('Recommend/adv_index')}" class="on">首页广告</a>
					<a href="javascript:;" onclick="window.top.artiframe('{pigcms{:U('Recommend/adv_add')}','添加广告',600,300,true,false,false,addbtn,'add',true);" >添加广告</a>
				</ul>
			</div>
			<style type="text/css">
				#_showimg{width:300px;border: solid 1px #ccc;position: fixed;background-color:#fff; }
				#_showimg img{width:98%;padding:3px;}
			</style>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup>
							<col/>
							<col/>
							<col/>
							<col/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>编号</th>
								<th>预览</th>
								<th>楼盘名称</th>
								<th>推荐描述</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="$adv_list">
								<volist name="adv_list" key="k" id="vo">
									<tr id="tr_{pigcms{$vo.id}">
										<td>{pigcms{$vo.id}</td>
										<td><span onmouseover="showimg(this)" onmouseleave="hideimg(this)">{pigcms{$vo.img}</span></td>
										<td>{pigcms{$vo.building_name}</td>
										<td>{pigcms{$vo.desc}</td>
										<td class="textcenter">
											<a href="javascript:;" onclick="del({pigcms{$vo.id})">删除</a>
									  	</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="7">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="4">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<script type="text/javascript">
// 删除
function del(id){
	if(!confirm('确定要删除吗？')){
		return;
	}
	$.post("{pigcms{:U('Recommend/del_adv')}",{'id':id},function(response){
		if(response.err_code>0){
			alert(response.err_msg);
		}else{
			$('#tr_'+id).remove();
		}
	},'json');
}

// 鼠标坐标
var mouseX = 0;
var mouseY = 0;


// 查看图片
function showimg(obj){
	var img_url = $(obj).text();
	var strArr = img_url.split('/');
	var img_name = strArr[strArr.length-1];
	img_url = img_url.replace(img_name,'s_'+img_name);	// 使用缩略图
	var img_html = '<div id="_showimg" style="display:none;"><img src="'+img_url+'" /></div>';
	$('body').append(img_html);
	$('#_showimg').show(150);
	$('#_showimg').css('left',mouseX);
	$('#_showimg').css('top',mouseY);;
}
function hideimg(obj){
	$('#_showimg').remove();
}
document.onmousemove=function(e){e=e? e:window.event;mouseX = e.clientX; mouseY = e.clientY;}
</script>
<include file="Public:footer"/>