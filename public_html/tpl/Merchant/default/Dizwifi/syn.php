<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-cloud"></i>
			<li class="active">微硬件</li>
			<li class="active"><a href="{pigcms{:U('Dizwifi/index')}">微信链接WIFI</a></li>
			<li class="active">同步门店至微信</li>
		</ul>
	</div>
	<div class="page-content">
    <div class="alert alert-info" style="margin:10px;">
        <button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>温馨提示：<br/>
        以下修改的内容只是为了添加微信门店，修改的内容不影响本系统的任何内容
    </div>
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1">门店名</label>
									<input class="col-sm-2" size="20" name="business_name" value="{pigcms{$store['business_name']}" type="text" readonly/>
								</div>
                                <div class="form-group">
                                    <label class="col-sm-1">分店名</label>
                                    <input class="col-sm-2" size="20" name="branch_name" value="{pigcms{$store['name']}" type="text"/>
                                    <span class="form_tips">分店名不得含有区域地址信息（如，“北京国贸店”中的“北京”）</span>
                                </div>
								<div class="form-group">
									<label class="col-sm-1">类目</label>
                                    <select name="categories" id="categories">
                                        <volist name="category_list" id="category">
                                            <option value="{pigcms{$category}">{pigcms{$category}</option>
                                        </volist>
                                    </select>
								</div>
                                <div class="form-group">
                                    <label class="col-sm-1">电话</label>
                                    <input class="col-sm-2" size="20" name="telephone" value="{pigcms{$store['phone']}" type="text"/>
                                    <span class="form_tips">固定电话需加区号；区号、分机号均用“-”连接</span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1">省市区</label>
                                    <input class="col-sm-2" size="20" value="{pigcms{$store['province']} {pigcms{$store['city']} {pigcms{$store['district']}" type="text" readonly/>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1">地址</label>
                                    <input class="col-sm-2" size="20" name="address" value="{pigcms{$store['adress']}" type="text"/>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1">营业时间</label>
                                    <input class="col-sm-2" size="20" name="open_time" value="{pigcms{$store['open_time']}" type="text"/>
                                    <span class="form_tips">如，10:00-21:00</span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1">人均价格</label>
                                    <input class="col-sm-2" size="20" name="avg_price" value="{pigcms{$store['permoney']}" type="text"/>
                                    <span class="form_tips">大于零的整数，须如实填写，默认单位为人民币</span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1">推荐</label>
                                    <textarea class="col-sm-2" name="recommend">{pigcms{$store['feature']}</textarea>
                                    <span class="form_tips">如，推荐菜，推荐景点，推荐房间</span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1">特色服务</label>
                                    <textarea class="col-sm-2" name="special">{pigcms{$store['special']}</textarea>
                                    <span class="form_tips">如，免费停车，WiFi</span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1">简介</label>
                                    <textarea class="col-sm-2" name="introduction">{pigcms{$store['txt_info']}</textarea>
                                    <span class="form_tips">对品牌或门店的简要介绍</span>
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
<include file="Public:footer"/>
