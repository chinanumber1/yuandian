var upload_loading = false;
//jssdkconfig.debug = true;
function upLoadImg(obj) {
	$this = $(obj);
	upload_loading = false;
	wx.chooseImage({
		success: function(res) {
			localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
			setTimeout(function() {
				for (var i = 0; i < localIds.length; i++) {
					wx.uploadImage({
						localId: localIds[i], // 需要上传的图片的本地ID，由chooseImage接口获得
						isShowProgressTips: 1, // 默认为1，显示进度提示
						success: function(res) {
							var serverId = res.serverId; // 返回图片的服务器端ID
							var params = {
								'media_id': serverId,
								'width': 640,
								'imgcfy':'store',
								'height': 640
							}
							if (!upload_loading) {
								upload_loading = true;
								$.post(upLoadImg_url, params, function(data) {
									$this.find("input[name='pic_url']").val(data);
									var img_content = "<img src='" + attachurl + data + "'>";
									$(img_content).appendTo($this);
								})
							}
						}
					});
				}
			}, 10)
		}
	});
};

function upLoadBigImg(obj) {
	$this = $(obj);
	upload_loading = false;
	wx.chooseImage({
		success: function(res) {
			localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
			setTimeout(function() {
				for (var i = 0; i < localIds.length; i++) {
					wx.uploadImage({
						localId: localIds[i], // 需要上传的图片的本地ID，由chooseImage接口获得
						isShowProgressTips: 1, // 默认为1，显示进度提示
						success: function(res) {
							var serverId = res.serverId; // 返回图片的服务器端ID
							var params = {
								'media_id': serverId,
								'width': 640,
								'imgcfy':'store',
								'height': 640
							}
							if (!upload_loading) {
								upload_loading = true;
								$.post(upLoadImg_url, params, function(data) {
									$this.find("input[name='banner_url']").val(data);
									var img_content = "<img src='" + attachurl + data + "'>";
									$(img_content).appendTo($this);
								})
							}
						}
					});
				}
			}, 10)
		}
	});
};
console.log(picarr[0]);
if (picarr[0] != '') {
	for (var i = 0; i < picarr.length; i++) {
		var detail_img_content = "<div class='detail-img' onclick='changeDetailImg(this)'><img src='" + picarr[i] + "' alt='商品图片" + (i + 1) + "'></div>";
		$(detail_img_content).insertBefore('#detail-img-add');
	};
}

function upLoadDetailImg() {
	wx.chooseImage({
		success: function(res) {
			localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
			setTimeout(function() {
				for (var i = 0; i < localIds.length; i++) {
					wx.uploadImage({
						localId: localIds[i], // 需要上传的图片的本地ID，由chooseImage接口获得
						isShowProgressTips: 1, // 默认为1，显示进度提示
						success: function(res) {
							var serverId = res.serverId; // 返回图片的服务器端ID
							var params = {
								'media_id': serverId,
								'width': 640,
								'imgcfy':'store',
								'height': 640
							}
							$.post(upLoadImg_url, params, function(data) {
								pic_detail.push(data.imgpath);
								var img_url = attachurl + data.imgsrc;
								var detail_img_content = "<div class='detail-img' onclick='changeDetailImg(this)'><img src='" + img_url + "' alt='商品图片" + (i + 1) + "'></div>";
								$(detail_img_content).insertBefore('#detail-img-add');
								$("input[name='pic_detail']").val(pic_detail.join(';'));
							},'JSON')
						}
					});
				}
			}, 10);
		}
	});
};

function changeDetailImg(obj) {
	$this = $(obj);
	img_index = $this.index();
	//pic_detail_arr = pic_detail.toString().split(",");
	wx.chooseImage({
		success: function(res) {
			localIds = res.localIds; // 返回选定照片的本地ID列表，localId可以作为img标签的src属性显示图片
			setTimeout(function() {
				for (var i = 0; i < localIds.length; i++) {
					wx.uploadImage({
						localId: localIds[i], // 需要上传的图片的本地ID，由chooseImage接口获得
						isShowProgressTips: 1, // 默认为1，显示进度提示
						success: function(res) {
							var serverId = res.serverId; // 返回图片的服务器端ID
							var params = {
								'media_id': serverId,
								'width': 640,
								'imgcfy':'store',
								'height': 640
							}
							$.post(upLoadImg_url, params, function(data) {
								pic_detail[img_index] = data.imgpath;
								$this.find('img').attr('src', attachurl + data.imgsrc);
								$("input[name='pic_detail']").val(pic_detail.join(';'));
							},'JSON')
						}
					});
				}
			}, 10)
		}
	});
};