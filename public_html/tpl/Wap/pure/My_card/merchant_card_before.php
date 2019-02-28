
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name='apple-touch-fullscreen' content='yes'/>
<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
<meta name="format-detection" content="telephone=no"/>
<meta name="format-detection" content="address=no"/>

<meta charset="utf-8">
<title>{pigcms{$now_merchant['name']}会员卡</title>
<link type="text/css" rel="stylesheet" href="{pigcms{$static_path}my_card/css/card_new.css"/>
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
<script src="{pigcms{$static_path}js/qrcode.js"></script>
<script src="{pigcms{$static_path}layer/layer.m.js"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->

</head>
<body>
   
</body>
{pigcms{$hideScript}
<script>
	<if condition="!$is_wexin_browser">
		var is_wexin_browser = false;
	<else />
		var is_wexin_browser = true;
	</if>
	var wxcardid  = '{pigcms{$now_card.wx_cardid}';
	var sign = '{pigcms{$now_card.cardsign}';
	var tickettime = '{pigcms{$now_card.wx_ticket_addtime}';
</script>
<script>
	$(function(){
			window.location.reload();
		if(wxcardid!='' && is_wexin_browser){
			wx.ready(function () {
				var cardlist = [];
				cardlist.push({'cardId':wxcardid,'cardExt':'{"code": "", "openid": "", "timestamp":"'+tickettime+'","signature":"'+sign+'"}'});
				wx.addCard({
				  cardList: 
					cardlist
				 ,
				  success: function (res) {
						layer.open({
							content: '已成功同步微信会员卡'
							,btn: ['我知道了']
						  });
					
					window.location.reload();
				  },
				   cancel: function (res) {
					window.location.href="{pigcms{:U('merchant_card')}&mer_id={pigcms{$now_card.mer_id}&cancel_wx=1"
				  }
				});
			});
		}
	})
</script>


</html>