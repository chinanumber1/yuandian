<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Wxapp_store/index')}">门店小程序</a>
			</li>
			<li class="active">腾讯地图添加门店</li>
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
							<if condition="$now_store.wxapp_map_status gt 0">
                <div class="form-group">
                  <if condition="$now_store.wxapp_map_status eq 1">
									<label class="col-sm-1"><label for="name" style="color:red;">审核中，请勿重复提交</label></label>
									<elseif condition="$now_store.wxapp_map_status eq 2" />
									<label class="col-sm-1"><label for="name" style="color:red;">审核成功</label></label>
									<elseif condition="$now_store.wxapp_map_status eq 3" />
									<label class="col-sm-1"><label for="name" style="color:red;">审核失败</label></label>
									{pigcms{$now_store.wxapp_map_errmsg}
									</if>

								</div>
              </if>
								<div class="form-group">
									<label class="col-sm-1"><label for="name">店铺名称</label></label>
									<input class="col-sm-2" size="20" name="name" id="name" value="{pigcms{$now_store.name}" type="text"/>
									
								
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="name">经度</label></label>
									<input class="col-sm-2" size="20" name="longitude" value="{pigcms{$now_store.long}" type="text"/>
								
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="name">纬度</label></label>
									<input class="col-sm-2" size="20" name="latitude"  value="{pigcms{$now_store.lat}" type="text"/>
								
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>分类</label></label>
									<fieldset id="choose_cityarea">
										<select id="first_catid" name="first_catid" class="col-sm-2 category_list" style="margin-right:10px;"></select>
										
										<select id="second_catid" name="second_catid" class=
										"col-sm-2 category_list" style="margin-right:10px;"></select>
										<input type="hidden" id="second_catid_name" name="second_catid_name">
										<input type="hidden" id="first_catid_name" name="first_catid_name">
									</fieldset>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>店铺所在地</label></label>
									<fieldset id="choose_cityarea">
										<select id="province" name="province_id" class="col-sm-2 area_list" style="margin-right:10px;"></select>
										
										<select id="city" name="city_id" class=
										"col-sm-2 area_list" style="margin-right:10px;"></select>
										
										<select id="district" name="district" class="col-sm-2 area_list" style="margin-right:10px;"></select>
										<input type="hidden" id="province_name" name="province_name">
										<input type="hidden" id="city_name" name="city_name">
										<input type="hidden" id="district_name" name="district_name">
										<input type="hidden" name="districtid">
									</fieldset>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="adress">详细地址</label></label>
									<input class="col-sm-2" size="20" name="address" id="adress" value="{pigcms{$now_store.adress}" type="text"/>
									
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="adress">电话</label></label>
									<input class="col-sm-2" size="20" name="phone" id="phone" value="{pigcms{$now_store['phone']}" type="text"/>
									<span class="form_tips">可多个，使用英文分号间隔 010-6666666-111; 010-6666666; 010- 6666666-222</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>营业执照：</label></label>
									<input type="text" name="license" id="license" class="px input-image" value="{pigcms{$now_store.wxapp_map_param.license}" style="width:210px;"/>
									<a class="fileupload-exists btn J_selectImage" style="margin-left:20px;font-size:12px;" >上传图片</a>
									
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>门店图片：</label></label>
									<input type="text" name="photo" id="bgs" class="px input-image" value="{pigcms{$now_store.wxapp_map_param.photo}" style="width:210px;"/>
									<a class="fileupload-exists btn J_selectImage" style="margin-left:20px;font-size:12px;" >上传图片</a>
									
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>介绍：</label></label>
									<input class="col-sm-2" size="20" name="introduct" id="introduct" type="text" value="{pigcms{$now_store.wxapp_map_param.introduct}"/>
									
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
var category = $.parseJSON('{pigcms{$category_list}')
var province="{pigcms{$now_store.wxapp_map_param.province}"
var city="{pigcms{$now_store.wxapp_map_param.city}"
var district="{pigcms{$now_store.wxapp_map_param.district}"
var first_catid="{pigcms{$now_store.wxapp_map_param.category.0}"
var second_catid="{pigcms{$now_store.wxapp_map_param.category.1}"

var province_list = area[0]
var city_list = area[1]

var area_list = area[2]
for(p in province_list){
	province_list[p]['key'] = p;
}
add_option_html(get_domid(0),province_list)
add_option_html(get_domid(3),category)
add_option_html(get_domid(4),category[0]['childrens'])
change_area(0,province_list[0])



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
		console.log(area_name)
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
	
	$('.category_list').change(function(){
		var name = $(this).attr('name');
		var index = $(this).find('option:selected').data('id')
		var cate_name = $(this).find('option:selected').data('name')
	
		if(name=='first_catid'){
			$('#first_catid_name').val(cate_name)
			add_option_html(get_domid(4),category[index]['childrens'])
		}else if(name="second_catid"){
			$('#second_catid_name').val(cate_name)
		}
	})
	
	
	
});

   window.onload = function () {

         if(province!=''){
         var  province_id =0;
          $("#province").find("option").each(function(){
        
            if($(this).html()==province){
             province_id = $(this).data('id');
              $(this).attr("selected", "selected");
            }

          });
           change_area(0,province_list[province_id]);
          } 
          if(city!=''){
            var city_id = 0;
            $("#city").find("option").each(function(){
              if($(this).html()==city){
                   city_id = $(this).data('id');
                $(this).attr("selected", "selected");
              }
            });
            
            change_area(1,city_list[city_id]);
          }
          
          if(district!=''){
           $("#district").show();
            $("#district").find("option").each(function(){
              if($(this).html()==district){
           
                $(this).attr("selected", "selected");
              }
            });
             
          }
          
          if(first_catid!=''){
          $("#first_catid").find("option").each(function(){
              if($(this).html()==first_catid){
                add_option_html(get_domid(4),category[$(this).data('id')]['childrens'])
                $(this).attr("selected", "selected");
              }
            });
          }
          
          if(second_catid!=''){
          $("#second_catid").find("option").each(function(){
              if($(this).html()==second_catid){
           
                $(this).attr("selected", "selected");
              }
            });
          }


    };
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
							
			$('.J_selectImage').click(function(){
				var upload_file_btn = $(this);
				editor.uploadJson = "{pigcms{:U('Config/ajax_upload_pic_wx')}";
				editor.loadPlugin('image', function(){
					editor.plugin.imageDialog({
						showRemote : false,
						clickFn : function(url, title, width, height, border, align) {
							upload_file_btn.siblings('.input-image').val(url);
							editor.hideDialog();
						}
					});
				});
			});

		});
</script>

<include file="Public:footer"/>
