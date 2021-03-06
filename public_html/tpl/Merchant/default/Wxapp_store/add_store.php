<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Dizwifi/store')}">微信链接WIFI</a>
			</li>
			<li class="active">添加门店</li>
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
								<a data-toggle="tab" href="#basicinfo">基本设置</a>
							</li>
							
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="name" style="color:red">*号必填</label></label>
									
								<input type="hidden" name="store_id" value="{pigcms{$now_store.store_id}">
								</div>
								<if condition="$now_store.wxapp_status gt 0">
									<div class="form-group">
									  <if condition="$now_store.wxapp_status eq 1">
									  <label class="col-sm-1"><label for="name" style="color:red;">审核中，请勿重复提交</label></label>
									  <elseif condition="$now_store.wxapp_status eq 2" />
									  <label class="col-sm-1"><label for="name" style="color:red;">审核成功</label></label>
									  <elseif condition="$now_store.wxapp_status eq 3" />
									  <label class="col-sm-1"><label for="name" style="color:red;">审核失败</label></label>
									  {pigcms{$now_store.wxapp_map_errmsg}
									  </if>

									</div>
								</if>
								<div class="form-group">
									<label class="col-sm-1"><label for="name">店铺名称</label></label>
									<input class="col-sm-2" size="20" name="name" value="{pigcms{$now_store.name}" type="text"   readonly />
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label>根据所在地搜索门店</label></label>
									<fieldset id="choose_cityarea">
										<select id="province" name="province_id" class="col-sm-2 area_list" style="margin-right:10px;"></select>
										<select id="city" name="city_id" class=
										"col-sm-2 area_list" style="margin-right:10px;"></select>
										<select id="district" name="district" class="col-sm-2 area_list" style="margin-right:10px;"></select>
										<input class="col-sm-2" size="20" name="store_name" id="store_name" type="text" value=""/>
										<input type="hidden" id="province_name" name="province_name">
										<input type="hidden" id="city_name" name="city_name">
										<input type="hidden" id="district_name" name="district_name">
										<input type="hidden" name="districtid">
										<a class="fileupload-exists btn search_store_name" style="margin-left:20px;font-size:12px;" >搜索</a>
										
										
									</fieldset>
										<span class="form_tips">搜索不到请到腾讯地图添加</span>
										<a target="_blank" href="https://ugc.map.qq.com/AppBox/Landlord/login.html">点我去添加</a>
									
							
								</div>
								
								<div class="form-group search_result" >
									<label class="col-sm-1"><label for="phone"><font color="red">*</font>搜索结果</label></label>
									<select id="map_poi_id" name="map_poi_id" class="col-sm-2 area_list" style="margin-right:10px;">
										<option value="" >无</option>
									</select>
									
								</div>
							
							
								<div class="form-group">
									<label class="col-sm-1"><label for="phone"><font color="red">*</font>电话</label></label>
									<input class="col-sm-2" size="20" name="contract_phone" id="phone" type="text" value="{pigcms{$now_store.wxapp_param.contract_phone}"/>
									
								</div>
							
								<div class="form-group">
									<label class="col-sm-1"><label for="phone"><font color="red">*</font>营业时间</label></label>
								
									<input id="hour_start" type="text" value="{pigcms{$now_store.wxapp_param.hour.0}" name="hour_start" readonly style="width:70px"/>	至
									<input id="hour_end" type="text" value="{pigcms{$now_store.wxapp_param.hour.1}" name="hour_end" readonly style="width:70px"/>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="credential"><font color="red">*</font>经营资质证件号</label></label>
									<input class="col-sm-2" size="20" name="credential" id="credential" type="text"  value="{pigcms{$now_store.wxapp_param.credential}"/>
									
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="credential">主体名字</label></label>
									<input class="col-sm-2" size="20" name="company_name" id="company_name" type="text" value="{pigcms{$now_store.wxapp_param.company_name}"/>
									<span class="form_tips">主体名字如果复用公众号主体，则为空 如果不复用公众号主体，则为具体的主体名字</span>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label><font color="red">*</font>门店图片</label></label>
									<input type="text" name="photo" id="bgs" class="px input-image" value="{pigcms{$now_store.wxapp_param.pic_list}" style="width:210px;"/>
									<a class="fileupload-exists btn J_selectImages" style="margin-left:20px;font-size:12px;" >上传图片</a>
									
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label><font color="red">*</font>证明材料</label></label>
									<input type="text" name="qualification_list" id="bgs" class="px input-image" value="{pigcms{$now_store.wxapp_param.qualification_list}" style="width:210px;"/>
									<a class="fileupload-exists btn J_selectImage" style="margin-left:20px;font-size:12px;" >上传图片</a>
									
								</div>
								<div class="form-group">
									<label class="col-sm-1" for="card">是否同步会员卡</label>
									<select name="card" id="card">
										<option value="1" selected="selected">同步</option>
										<option value="0">不同步</option>
									</select>
								</div>
						
							
							</div>
						</div>
						<div class="space"></div>
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


<script type="text/javascript">
var area = $.parseJSON('{pigcms{$area_list}')

var province_list = area[0]
var city_list = area[1]

var area_list = area[2]
for(p in province_list){
	province_list[p]['key'] = p;
}
add_option_html(get_domid(0),province_list)

change_area(0,province_list[0])


$('#hour_start').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'10','minute':'52','second':'25'}));
	$('#hour_end').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm','hour':'10','minute':'52','second':'25'}));
	
function add_option_html(domid,area_list){
	html ='';
	for( x in area_list){
		if(domid=='second_catid'){
			html += '<option value="'+area_list[x]['id']+'" data-id='+area_list[x]['key']+' data-name="'+area_list[x]['fullname']+'" data-sensitive_type="'+area_list[x]['sensitive_type']+'">'+area_list[x]['fullname']+'</option>'
		}else{
			html += '<option value="'+area_list[x]['id']+'" data-id='+area_list[x]['key']+' data-name="'+area_list[x]['fullname']+'">'+area_list[x]['fullname']+'</option>'
		}
	}
	$('#'+domid+'_name').val(area_list[0].fullname)
	$("#"+domid).html(html)
}



function get_domid(type){
	if(type==0){
		domid = 'province'
	}else if(type==1){
		domid = 'city'
	}else if(type==2){
		domid = 'district'
	}else if(type==3){
		domid = 'first_catid'
	}else if(type==4){
		domid = 'second_catid'
	}
	return domid;
}

//0 province 1 city 
function change_area(type,area){
	if(typeof(area['cidx'])!='undefined'){
		start = area.cidx[0];
		end = area.cidx[1];
		tmp_area=[]
		for(i=start;i<=end;i++){
			if(type==0){
				city_list[i]['key'] = i;
				tmp_area.push(city_list[i])
			}else if(type==1){
			
				area_list[i]['key'] = i;
				tmp_area.push(area_list[i])
			}
		}
		add_option_html(get_domid(type+1),tmp_area)
		$("#"+get_domid(type+1)).show()
	}else if(type<2){
		
		$("#"+get_domid(type+1)).hide()
	}
	
	if(type<2){
		change_area(type+1,tmp_area[0])
	}
}



$(function(){
	$('.area_list').change(function(){
		var name = $(this).attr('name');
		var index = $(this).find('option:selected').data('id')
		var area_name = $(this).find('option:selected').data('name')
	
		if(name=='province_id'){
			$('#province_name').val(area_name)
			change_area(0,province_list[index])
		}else if(name=='city_id'){
			$('#city_name').val(area_name)
			change_area(1,city_list[index])
		}else if(name="district"){
			$('#district_name').val(area_name)
		}
	})
	
	$('.search_store_name').click(function(){
		if($('#district option:selected').length==1){
			districtid = $('#district option:selected').val();
		}else{
			districtid = $('#city option:selected').val();
			
		}
		$.post('{pigcms{:U('store_search')}',{districtid:districtid,'store_name':$('#store_name').val()},function(date){
		
			if(date.store.length>0){
				
				$('#map_poi_id').html('')
				var store_html ='';
				for( x in date.store){	
					store_html += '<option value="'+date.store[x]['sosomap_poi_uid']+'" >'+date.store[x]['branch_name']+'</option>'
				}
				$('#map_poi_id').html(store_html)
			}else{
				$('#map_poi_id').html('<option value="" >无</option>')
			}
		},'json');
	});
	

	
});
</script>

<style>
.BMap_cpyCtrl{display:none;}
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
#upload_pic_box img{width:100px;height:70px;border:1px solid #ccc;}
</style>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
KindEditor.ready(function(K){
			var site_url = "{pigcms{$config.site_url}";
			var editor = K.editor({
				allowFileManager : true
			});
			
			$('.J_selectImages').click(function(){
				var upload_file_btn = $(this);
				editor.uploadJson = "{pigcms{:U('Config/ajax_upload_pic_wx')}";
				editor.loadPlugin('image', function(){
					editor.plugin.imageDialog({
						showRemote : false,
						clickFn : function(url, title, width, height, border, align) {
							
						
							var orignal_url = upload_file_btn.siblings('.input-image').val();
							count_url = orignal_url.split(';');
							if(count_url.length>5){
								alert('最多只能穿5个材料')
							}else{
								orignal_url+=url+';';
								upload_file_btn.siblings('.input-image').val(orignal_url)
							}
							editor.hideDialog();
						}
					});
				});
				});
				
				
			$('.J_selectImage').click(function(){
				var upload_file_btn = $(this);
				editor.uploadJson = "{pigcms{:U('Config/ajax_upload_wx_media')}";
				editor.loadPlugin('image', function(){
					editor.plugin.imageDialog({
						showRemote : false,
						clickFn : function(url, title, width, height, border, align) {
							url = url.substring(1,url.length)
							upload_file_btn.siblings('.input-image').val(url);
							editor.hideDialog();
						}
					});
				});
			});

		});
</script>

<include file="Public:footer"/>
