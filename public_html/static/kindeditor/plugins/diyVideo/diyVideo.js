KindEditor.plugin('diyVideo', function (K) {
	var self = this, name = 'diyVideo';

	self.plugin.diyVideo = {
		edit : function() {
			art.dialog.data('editer', self);
			// 此时 iframeA.html 页面可以使用 art.dialog.data('test') 获取到数据，如：
			// document.getElementById('aInput').value = art.dialog.data('test');
			art.dialog.open(diyVideo,{lock:false,title:'上传视频链接',width:600,height:450,yesText:'关闭',background: '#000',opacity: 0.87});


		},
		'delete' : function() {


		}
	};
	self.clickToolbar(name, self.plugin.diyVideo.edit);
});