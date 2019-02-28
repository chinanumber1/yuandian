$("#qrcode").click(function() {
	$("#qrcode-img").show();
	$("#share-copy-wrap").addClass('qrcode').show().click(function() {
		$("#qrcode-img").hide();
		$("#share-copy-wrap").removeClass('qrcode').hide();
	});
})
$("#open-shop").click(function() {
	$("#pigcms-header-left").trigger('click');
	setTimeout(function(){
		$("#slide_menu").find('.icon-shop').parent().trigger('click');
	},700);
})
$("#open-item").click(function() {
	$("#pigcms-header-left").trigger('click');
	setTimeout(function(){
		$("#slide_menu").find('.icon-goods').parent().trigger('click');
	},700);
})
$("#open-order").click(function() {
	$("#pigcms-header-left").trigger('click');
	setTimeout(function(){
		$("#slide_menu").find('.icon-form').parent().trigger('click');
	},700);
})
$("#open-menu").click(function() {
	$("#pigcms-header-left").trigger('click');
})
wx.ready(function() {
	$("#share-link").click(function() {
		wx.showOptionMenu();
	});
	$("#copy-link").click(function() {
		wx.showOptionMenu();
	});

	if (os == 'android') {
		$("#share-link").click(function() {
			$("#android-share-img").show();
			$("#share-copy-wrap").show().click(function() {
				$("#android-share-img").hide();
				$("#share-copy-wrap").hide();
				wx.hideOptionMenu();
			});
		});
		$("#copy-link").click(function() {
			$("#android-copy-img").show();
			$("#share-copy-wrap").show().click(function() {
				$("#android-copy-img").hide();
				$("#share-copy-wrap").hide();
				wx.hideOptionMenu();
			});
		});
	} else if (os == 'iphone') {
		$("#share-link").click(function() {
			$("#ios-share-img").show();
			$("#share-copy-wrap").show().click(function() {
				$("#ios-share-img").hide();
				$("#share-copy-wrap").hide();
				wx.hideOptionMenu();
			});
		});
		$("#copy-link").click(function() {
			$("#ios-copy-img").show();
			$("#share-copy-wrap").show().click(function() {
				$("#ios-copy-img").hide();
				$("#share-copy-wrap").hide();
				wx.hideOptionMenu();
			});
		});
	}
});
var exit_href = $("#pigcms-header-right").attr('href');
$("#pigcms-header-right").removeAttr('href').attr('data-href', exit_href).click(function(event) {
	confirm_open('退出店铺', '退出后将无法管理该店铺', '确认退出吗？', $(this));
});
$("#order-count-container").click(function() {
	$("#canvas-title").html("每日订单趋势图 <span>(单位: 单)</span>");
}).trigger('click');
$("#income-count-container").click(function() {
	$("#canvas-title").html("收入总数趋势图 <span>(单位: 元)</span>");
})
$("#member-count-container").click(function() {
	$("#canvas-title").html("客户总数趋势图 <span>(单位: 人)</span>");
})

function chart_ajax(str) {
		var myLine = new Chart(document.getElementById("myChart").getContext("2d"));
		var datasets = '';
		var lineChartData = null;
		var obj = null;
		var data = null;

		$.post(chart_url, {
			'act': str
		}, function(data) {
			/*data = $.parseJSON(data);*/
			lineChartData = {
				labels: data.key,
				datasets: [{
					fillColor: "rgba(36,165,222,0.1)",
					strokeColor: "rgba(36,165,222,1)",
					pointColor: "rgba(36,165,222,1)",
					pointStrokeColor: "#fff",
					pointHighlightFill: "#fff",
					pointHighlightStroke: "rgba(36,165,222,1)",
					data: data.value
				}]
			}
			obj = myLine.Line(lineChartData, {
				responsive: true
			});
		},'JSON');
}