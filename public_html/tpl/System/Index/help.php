<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{pigcms{$config.site_name}_使用帮助</title>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
</head>
<body style="padding:0; margin:0">
<script type="text/javascript" language="javascript">

    function iFrameHeight() {

        var ifm= document.getElementById("iframepage");


            if(ifm != null) {

            ifm.height = $(document).height();

            }

    }
    
$(document).ready(function()
{ 

});
$(window).resize(function(){
   iFrameHeight();
});
</script> 
<iframe marginheight="0" marginwidth="0" frameborder="0" width="100%" id="iframepage" name="iframepage" class="wt" onLoad='iFrameHeight()' src="https://o2o-service.pigcms.com/workorder/answer.php?answer_id={pigcms{$answer_id}"></iframe>

</body>
</html>
