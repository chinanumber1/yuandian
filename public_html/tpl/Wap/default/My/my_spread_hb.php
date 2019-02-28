


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>推广海报</title>
<link href="{pigcms{$static_path}css/spread_hb.css" rel="stylesheet"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
</head>
<body style="background: #f0efed;">
    <section class="code_n">
        <div class="pd15">
            <img src="{pigcms{$image}?{pigcms{$_SERVER['REQUEST_TIME']}" width="100%" height="100%">
        </div>
        <div class="trade">
            <span>换个样式</span>
        </div>
        <div class="keep clr">
      
            <span class="span_14">长按上图可发送给朋友或保存到手机哦</span>
        </div>
    </section>
</body>
{pigcms{$hideScript}
</html>

<script>
     // 图片比例
    // $(".pd15 img").each(function(){
        // $(this).height($(this).width()*1.172)
    // })
	$(function(){
		$('.trade').click(function(){
			window.location.reload();
		})
	})
</script>



