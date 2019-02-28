<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
<meta http-equiv="X-UA-Compatible" content="ie=edge"/>
<title>{pigcms{$page_title}</title>
<link rel="stylesheet" href="{pigcms{$static_path}classifynew/css/weui.css"/>
<link rel="stylesheet" href="{pigcms{$static_path}classifynew/css/custom.css"/>
<style>
	.weui-dialog__btn, .weui-navbar__item.weui_bar__item_on, .color-strong, .chip-row .toutiao, .weui-cells_checkbox .weui-check:checked+.weui-icon-checked:before, .weui-vcode-btn, .weui-dialog__btn_primary, .weui-cells_radio .weui-check:checked+.weui-icon-checked:before, .weui-icon-checked:before, .weui-agree__checkbox:checked:before, .weui-icon-success-circle, .weui-icon-success-no-circle, .weui-search-bar__cancel-btn, .weui-tabbar__item.weui-bar__item_on .weui-tabbar__icon, .weui-tabbar__item.weui-bar__item_on .weui-tabbar__icon>i, .weui-tabbar__item.weui-bar__item_on .weui-tabbar__label,.main_color,.weui-tabbar__item.weui-bar__item--on .weui-tabbar__label,.picker-button,.weui-form-preview__btn_primary {color:#ff4d00!important}
	.weui_bar__item_on span:after,.weui-btn_primary, .weui-btn_primary:not(.weui-btn_disabled):active,.weui-btn_mini,.x_header, .main_bg,.position li.current, .position1 li.current,.post-tags .tag-on.weui-btn_default,.is-green, .weui-slider__track{background-color:#ff4d00!important}
</style>
<script>
	var IN_WECHAT = '', AVATAR = "{pigcms{$user_session.avatar|default='./static/images/user_avatar.jpg'}", UID = '{pigcms{$user_session.uid|intval=###}', PLZINPUT = '请输入文字', BODA = '拨打电话', DELCONFIRM = '确定删除?', SUIBIANSHUO = '随便说点什么', HUIFU1 = '回复：', ERROR_TIP = '数据异常，请刷新页面再试。';
	var loading = false, page = 1, _APPNAME = '{pigcms{$config.site_url}/wap.php?c=Classifynew', _APPTITLE = '{pigcms{$config.classify_title}', scrollto =0, plzinput_mobile = '请输入手机号码';
	var cookiepre = 'classify_', cookiedomain = '', cookiepath = '/', IN_APP='', LISTINCR = '1', _URLEXT = '', GSITE='{pigcms{$config.site_url}/', MAXTAG = '5', MAXTAGTIP = "最多选5个标签", FASIXIN = '发私信',XL=1, LXFS ='查看联系方式',CKXFF = '查看联系方式需支付<strong class="amount">', QRZF ='</strong>元<br>确认支付？';
</script>
<script src="{pigcms{$static_path}classifynew/js/jquery-2.1.4.js" type="text/javascript"></script>
<script src="{pigcms{$static_path}classifynew/js/jquery-weui.js" type="text/javascript" charset="UTF-8"></script>