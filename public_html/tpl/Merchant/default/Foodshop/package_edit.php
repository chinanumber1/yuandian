<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Foodshop/index')}">{pigcms{$config.meal_alias_name}管理</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Foodshop/package', array('store_id' => $now_store['store_id']))}">{pigcms{$now_store['name']}</a></li>
			<li class="active">修改套餐</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">
							<li class="active">
								<a data-toggle="tab" href="#basicinfo">基本信息</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtintro">商品详情</a>
							</li>
							
							<!--li>
								<a data-toggle="tab" href="#seckill">限时优惠</a>
							</li-->
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane  active">
								<if condition="$error_tips">
									<div class="alert alert-danger">
										<p>请更正下列输入错误:</p>
										<p>{pigcms{$error_tips}</p>
									</div>
								</if>
								<if condition="$ok_tips">
									<div class="alert alert-info">
										<p>{pigcms{$ok_tips}</p>				
									</div>
								</if>
								<div class="form-group">
									<label class="col-sm-1"><label for="name">套餐名称</label></label>
									<input class="col-sm-2" size="20" name="name" id="name" type="text" value="{pigcms{$package.name}"/>
									<span class="form_tips">必填。</span>
								</div>
								
								<!--div class="form-group">
									<label class="col-sm-1"><label for="price">商品原价</label></label>
									<input class="col-sm-1" size="20" name="old_price" id="old_price" type="text" value="{pigcms{$package.old_price|floatval}"/>
									<span class="form_tips">原价可不填，不填和现价一样</span>
								</div-->
								<div class="form-group">
									<label class="col-sm-1"><label for="price">套餐价格</label></label>
									<input class="col-sm-1" size="20" name="price" id="price" type="text" value="{pigcms{$package.price|floatval}"/>
									<span class="form_tips">元</span>
								</div>
							
								<div class="form-group">
									<label class="col-sm-1">是否可用：</label>
									<label><input type="radio" name="status" value="0" <if condition="$package['status'] eq 0">checked="checked"</if>>&nbsp;&nbsp;否</label>&nbsp;&nbsp;&nbsp;
									<label><input type="radio" name="status" value="1" <if condition="$package['status'] eq 1">checked="checked"</if>>&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;
								</div>
								<!--div class="form-group">
									<label class="col-sm-1"><label for="price">{pigcms{$now_store['pack_alias']|default='打包费'}</label></label>
									<input class="col-sm-1" size="20" name="packing_charge" id="packing_charge" type="text" value="{pigcms{$package.packing_charge|floatval}"/>
								</div-->
										
								<div class="form-group">
									<label class="col-sm-1"><label for="price">使用说明</label></label>
									<textarea class="col-sm-2" rows="4" cols="10" name="note">{pigcms{$package.note}</textarea>
									<span class="form_tips">最多200个字</span>
								</div>
                                <div class="form-group">
                                    <label class="col-sm-1">套餐图</label>
                                    <div style="display:inline-block;" id="image">
                                        <div class="btn btn-sm btn-success" style="position:relative;width:78px;height:34px;">上传图片</div>
                                    </div>
                                    <span class="form_tips">图片宽度建议为：900px，高度建议为：500px</span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1">图片预览</label>
                                    <div id="upload_pic_box">
                                        <ul id="upload_image_li">
                                            <if condition="isset($package['pic']) AND !empty($package['pic'])">
                                                <li class="upload_image_li"><img src="{pigcms{$package['pic']['url']}"/><input type="hidden" name="image" value="{pigcms{$package['pic']['title']}"/><br/><a href="#" onclick="deleteImage('{pigcms{$package['pic']['title']}',this);return false;">[ 删除 ]</a></li>
                                            </if>
                                        </ul>
                                    </div>
                                </div>
							</div>
							<div id="txtintro" class="tab-pane">
								<div class="alert alert-info" style="margin:10px 0;">
									<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
									添加一项表示添加一个菜品系列，可以添加多个菜品供选择;可选数：表示该系列下最多能选择几个菜品
									<br/><br/>
									默认可选择一个菜品;可选数后面的删除表示删除该系列，菜品后面的删除表示删除该菜品！
								</div>
								<div class="topic_box">
									<volist name="package['goods_detail']" id="goods_detail" key="out">
									<div class="question_box spec">
										<p class="question_info"><span>可选数：</span>
											<input type="text" class="txt" value="{pigcms{$goods_detail['num']}" name="nums[]"/>
											<input type="hidden" class="txt" value="{pigcms{$goods_detail['id']}" name="dids[]"/>
											<a href="javascript:;" class="box_del">删除</a>
										</p>
										<div class="optionul_r">
											<if condition="!empty($goods_detail['goods_list'])">
											<table class="table table-striped table-bordered table-hover">
												<tr>
													<td>菜品名称</td>
													<td>菜品价格</td>
													<!--td>规格</td-->
													<td>操作</td>
												</tr>
												<volist name="goods_detail['goods_list']" id="detail">
												<tr>
													<td>{pigcms{$detail['name']}<input type="hidden" name="goods_ids[{pigcms{$out-1}][]" value="{pigcms{$detail['goods_id']}" /></td>
													<td>{pigcms{$detail['price']|floatval}</td>
													<!--td></td-->
													<td class="button-column">
														<a title="删除" class="red" style="padding-right:8px;" href="javascript:;">
															<i class="ace-icon fa fa-trash-o bigger-130"></i>
														</a>
													</td>
												</tr>
												</volist>
											</table>
											</if>
											<p class="bot_add"><a href="javascript:;" class="btn btn-sm btn-success">添加菜品</a></p>
										</div>
									</div>
									</volist>
									<p class="add_spec" style="margin-top:10px"><a href="javascript:;" title="添加" class="btn btn-sm btn-success">添加一项</a></p>
							</div>
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
<script>var menu_url = "{pigcms{:U('Foodshop/menu',array('store_id'=>$now_store['store_id']))}";</script>
<link rel="stylesheet" href="{pigcms{$static_path}css/package.css">
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/package.js"></script>
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
#upload_pic_box .upload_image_li{width:130px;float:left;list-style:none;}
#upload_pic_box img{width:100px;height:70px;}
.webuploader-element-invisible {
    position: absolute !important;
    clip: rect(1px 1px 1px 1px);
    clip: rect(1px,1px,1px,1px);
}
.webuploader-pick-hover .btn{
    background-color: #629b58!important;
    border-color: #87b87f;
}
</style>
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script>
$(document).ready(function(){
    var uploaderBackGround = WebUploader.create({
        auto: true,
        swf: '{pigcms{$static_public}js/Uploader.swf',
        server: "{pigcms{:U('Foodshop/ajax_upload_pic')}",
        pick: {
            id:'#image',
            multiple:false
        },
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,png',
            mimeTypes: 'image/gif,image/jpeg,image/jpg,image/png'
        }
    });
    uploaderBackGround.on('fileQueued',function(file){
        if($('.upload_image_li').size() >= 1){
            uploader.cancelFile(file);
            alert('最多上传1个图片！');
            return false;
        }
    });
    uploaderBackGround.on('uploadSuccess',function(file,response){
        if(response.error == 0){
            $('#upload_image_li').append('<li class="upload_image_li"><img src="'+response.url+'"/><input type="hidden" name="image" value="'+response.title+'"/><br/><a href="#" onclick="deleteImage(\''+response.title+'\',this);return false;">[ 删除 ]</a></li>');
        }else{
            alert(response.info);
        }
    });
    uploaderBackGround.on('uploadError', function(file,reason){
        $('.loading'+file.id).remove();
        alert('上传失败！请重试。');
    });
});
function deleteImage(path,obj){
    $.post("{pigcms{:U('Foodshop/ajax_del_pic')}",{path:path});
    $(obj).closest('li').remove();
}
</script>
<include file="Public:footer"/>