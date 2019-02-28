<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-tablet"></i>
				<a href="{pigcms{:U('News/index')}">新闻列表</a>
			</li>
			<li class="active">内容发布</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form" action="{pigcms{:U('News/news_edit_do')}">
					   <input  name="news_id" type="hidden" value="{pigcms{$news_info['news_id']}"/>
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="title">标题</label></label>
									<input class="col-sm-2" size="80" name="title" id="title" type="text" value="{pigcms{$news_info['title']}"/>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="title">选择分类</label></label>
									<select name='cat_id'>
									<volist name='news_categorys' id='cate'>
										<option  value='{pigcms{$cate.cat_id}' <if condition="$news_info['cat_id'] eq $cate['cat_id']" >selected</if> >{pigcms{$cate.cat_name}</option>
									</volist>
									</select>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="sort">发布内容</label></label>
									<textarea id="description" name="description"  placeholder="写上一些想要发布的内容">{pigcms{$news_info['content']|htmlspecialchars_decode=ENT_QUOTES}</textarea> 
								</div>
								<div class="form-group">
									<label class="col-sm-1">是否热门</label>
									<label><input value="1" name="is_hot" type="radio" <if condition="$news_info['is_hot'] eq 1"> checked="checked" </if> />&nbsp;&nbsp;是</label>
									&nbsp;&nbsp;&nbsp;
									<label><input value="0" name="is_hot" type="radio" <if condition="$news_info['is_hot'] neq 1"> checked="checked" </if> />&nbsp;&nbsp;否</label>
									<span class="form_tips">若设置为热门，则会靠前显示</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">状态</label>
									<label><input  value="1" name="status" type="radio" <if condition="$news_info['status'] eq 1"> checked="checked" </if> />&nbsp;&nbsp;显示</label>
									&nbsp;&nbsp;&nbsp;
									<label><input  value="0" name="status" type="radio" <if condition="$news_info['status'] neq 1"> checked="checked" </if> />&nbsp;&nbsp;关闭</label>
								</div>
								<div class="space"></div>
								<div class="clearfix form-actions">
									<div class="col-md-offset-3 col-md-9">
										<button class="btn btn-info" type="submit" onclick="$(this).attr('type','text')" <if condition="!in_array(159,$house_session['menus']) && !in_array(160,$house_session['menus'])">disabled="disabled"</if>>
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
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script type="text/javascript">
KindEditor.ready(function(K){
			kind_editor = K.create("#description",{
				width:'400px',
				height:'400px',
				resizeType : 1,
				allowPreviewEmoticons:false,
				allowImageUpload : true,
				filterMode: true,
				items : [
					'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
					'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
					'insertunorderedlist', '|', 'emoticons', 'image', 'link'
				],
				emoticonsPath : './static/emoticons/',
				uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=merchant/news"
			});
		});

</script>

<include file="Public:footer"/>
