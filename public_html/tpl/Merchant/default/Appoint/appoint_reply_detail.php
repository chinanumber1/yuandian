<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-comments-o comments-o-icon"></i>
			<li class="active">预约管理</li>
			<li><a href="{pigcms{:U('appoint_reply')}">预约评论</a></li>
			<li class="active">回复评论</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="profile-user-info profile-user-info-striped">
				<div class="profile-info-row">
					<div class="profile-info-name">评论ID</div>
					<div class="profile-info-value">
						<span class="editable">{pigcms{$reply_detail.pigcms_id}</span>
					</div>
				</div>
				<div class="profile-info-row">
					<div class="profile-info-name">订单ID</div>
					<div class="profile-info-value">
						<span class="editable">{pigcms{$reply_detail.order_id}</span>
					</div>
				</div>
				<div class="profile-info-row">
					<div class="profile-info-name">顾客ID</div>
					<div class="profile-info-value">
						<span class="editable">{pigcms{$reply_detail.uid}</span>
					</div>
				</div>
				<div class="profile-info-row">
					<div class="profile-info-name">店铺名称</div>
					<div class="profile-info-value">
						<span class="editable">{pigcms{$now_store.name}</span>
					</div>
				</div>
				<!--div class="profile-info-row">
					<div class="profile-info-name">订单名称</div>
					<div class="profile-info-value">
						<span class="editable">{pigcms{$reply_detail.order_name}</span>
					</div>
				</div-->
				<div class="profile-info-row">
					<div class="profile-info-name">评论内容</div>
					<div class="profile-info-value">
						<span class="editable">{pigcms{$reply_detail.comment}</span>
					</div>
				</div>
				<div class="profile-info-row">
					<div class="profile-info-name">评论时间</div>
					<div class="profile-info-value">
						<span class="editable">{pigcms{$reply_detail.add_time|date='Y-m-d H:i:s',###}</span>
					</div>
				</div>
				<div class="profile-info-row">
					<div class="profile-info-name">评分</div>
					<div class="profile-info-value">
						<span class="editable">{pigcms{$reply_detail.score}</span>
					</div>
				</div>
				<if condition="$reply_detail['merchant_reply_time']">
					<div class="profile-info-row">
						<div class="profile-info-name">回复内容</div>
						<div class="profile-info-value">
							<span class="editable">{pigcms{$reply_detail.merchant_reply_content}</span>
						</div>
					</div>
					<div class="profile-info-row">
						<div class="profile-info-name">回复时间</div>
						<div class="profile-info-value">
							<span class="editable">{pigcms{$reply_detail.merchant_reply_time|date='Y-m-d H:i:s',###}</span>
						</div>
					</div>
				<else/>
					<div class="profile-info-row">
						<div class="profile-info-name">是否回复</div>
						<div class="profile-info-value">
							<span class="editable">未回复</span>
						</div>
					</div>
				</if>
                
                <if condition='$reply_pic_list'>
                	<div class="profile-info-row">
                    <div class="profile-info-name">图片</div>
                    <div class="profile-info-value">
                        <volist name='reply_pic_list' id='vo'>
                            <img class="see_pic" src="{pigcms{$config.site_url}/upload/reply/appoint/{pigcms{$vo}" width="200" height="200" style=" margin-left:10px" />
                        </volist>
                    </div>
                    </div>
                </if>
                
			</div>
			<if condition="!$reply_detail['merchant_reply_time']">
				<div style="margin-top:20px;">
					<div class="alert in alert-block fade alert-info" style="width:98%;margin:0 auto 10px auto;">
						<a href="#" class="close" data-dismiss="alert">×</a>*请认真填写回复的内容,回复后将不能删除和修改;回复的内容不能为空,最长只能回复300个字！
					</div>
					<div style="width:98%;margin:0 auto 10px auto;"></div>
					<form action="" method="post" id="reply_form">
						<div class="col-xs-12 col-sm-6 widget-container-span" style="width:100%;margin:0px auto 20px auto;">
							<div class="widget-box">
								<div class="widget-header">
									<h5>回复内容</h5>
								</div>
								<div class="widget-body" style="padding:20px;">
									<label><input type="radio" value="1" name="status" <if condition="$reply_detail['status'] neq 2">checked="checked"</if>>&nbsp;&nbsp;审核通过</label>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <label><input type="radio" value="2" name="status" <if condition="$reply_detail['status'] eq 2">checked="checked"</if>>&nbsp;&nbsp;审核不通过</label>		
								</div>
							</div>
						</div>	
                        
                        
                        <div class="col-xs-12 col-sm-6 widget-container-span" style="width:100%;margin:0px auto 20px auto;">
							<div class="widget-box">
								<div class="widget-header">
									<h5>回复内容</h5>
								</div>
								<div class="widget-body" style="padding:20px;">
									<textarea style="width:500px;height:100px;" name="reply_content" id="reply_content"></textarea>						
								</div>
							</div>
						</div>		
						<div style="clear:both;"></div>
						<div class="form-actions" style="width:98%;margin:20px auto;">
							<button class="btn btn-info" type="submit">提交</button>	
						</div>
					</form>
				</div>
                
            <else/>
            <form action="" method="post" id="reply_form">
                        <div class="col-xs-12 col-sm-6 widget-container-span" style="width:100%;margin:0px auto 20px auto;">
							<div class="widget-box">
								<div class="widget-header">
									<h5>回复内容</h5>
								</div>
								<div class="widget-body" style="padding:20px;">
									<label><input type="radio" value="1" name="status" <if condition="$reply_detail['status'] neq 2">checked="checked"</if>>&nbsp;&nbsp;审核通过</label>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <label><input type="radio" value="2" name="status" <if condition="$reply_detail['status'] eq 2">checked="checked"</if>>&nbsp;&nbsp;审核不通过</label>		
								</div>
							</div>
						</div>		
						<div style="clear:both;"></div>
						<div class="form-actions" style="width:98%;margin:20px auto;">
							<button class="btn btn-info" type="submit">提交</button>	
						</div>
					</form>
			</if>
		</div>
	</div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
	$(function(){
		$('#reply_form').submit(function(){
			$('#reply_content').val($.trim($('#reply_content').val()));
			if($('#reply_content').val().length == 0){
				alert('请填写回复内容！');
				return false;
			}
			if($('#reply_content').val().length > 300){
				alert('回复内容不能大于300字！现在字数为：'+$('#reply_content').val().length);
				return false;
			}
			if(confirm('确认要提交吗?')){
				return true;
			}else{
				return false;
			}
		});
		
		$('.see_pic').click(function(){
			art.dialog.open($(this).attr('src'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'查看大图',
				padding: 0,
				width: 600,
				height: 400,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: null,
				left: '50%',
				top: '38.2%',
				opacity:'0.5'
			});
			return false;
		});
	});

</script>
<include file="Public:footer"/>
