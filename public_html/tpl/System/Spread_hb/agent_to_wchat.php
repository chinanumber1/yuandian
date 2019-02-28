<include file="Public:header"/>
<style>
    .agent-to-wchat{
        height: 180px;
        width: 180px;
       /* padding: 10px 10px 10px 10px;*/
        margin-top: 100px;
        margin-left: 400px;
        border:1px #463B3B solid;
    }
</style>
<div class="mainbox">
    <div id="nav" class="mainnav_title">
        <ul>
            <a href="" class="on">微信绑定</a>
        </ul>
    </div>
    <div>
        <?php if($qrcode['error_code'] == 0){?>
             <div class="agent-to-wchat">
                 <img  src="<?php echo $qrcode['ticket'];?>" style="height:180px;width:180px;">
             </div>
        <?php } else {?>
            <span style="margin-left: 350px;"><?php echo $qrcode['msg'];?></span>
        <?php }?>
    </div>
    <div style="margin-left: 380px;margin-top: 10px;">
        <?php if($qrcode['error_code'] == 0 && $userInfo['open_id']){?>
            <span>您已绑定微信，如果更换手机可重新绑定。</span><br/><br/><br/>

            <span id='button' style="
            margin-left: 90px;
            color:#fff;
            border:1px solid #20BBEA;
            padding: 6px 8px;
            cursor: pointer;
            display: inline-block;
            text-align: center;
            line-height: 1;
            letter-spacing: 2px;
            font-family: Tahoma, Arial/9!important;
            width: auto;
            overflow: visible;
            border-radius: 5px;
            background: #20BBEA;
            ">解绑</span>
        <?php } else {?>
            <span>扫描绑定微信，可了解平台对店铺的审核进度。</span>
        <?php }?>
    </div>
</div>
<script>
    $('#button').click(function(){
        if (!confirm("您确定解除绑定？")) {
            return false;
        }
        var url = '<?php echo U('Promotion/detach');?>';

        var id = '<?php echo $userInfo['id'];?>';
        $.post(url, {'id':id},function(result){
            //if(result.error == 0){
                alert('解绑成功');
            //}else{
            //    alert(result.message);
            //}
        });
    });
</script>

<include file="Public:footer"/>