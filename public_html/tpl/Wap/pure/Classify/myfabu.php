<!DOCTYPE html>
<html lang="en">
<head>
    <title>我的发布</title>
    <include file="Public:classify_header" />
	<style type="text/css">
	.content{ position:static}
	.list-block.media-list .item-media img{ width:80px; height:80px}
	.list-block.media-list .item-title{
		    width: 65%;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
	}
	</style>
<div class="content">
    <div class="buttons-tab">
        <a href="#fb1" class="tab-link button active">已发布（<if condition='!empty($pass_data)'>{pigcms{:count($pass_data)}<else />0</if>）</a>
        <a href="#fb2" class="tab-link button">审核中（<if condition='!empty($unpass_data)'>{pigcms{:count($unpass_data)}<else />0</if>）</a>
    </div>

    <div class="tabs">
        <div id="fb1" class="tab active">
            <div class="itemList">
                <div class="list-block media-list">
                    <ul>
                       <volist name='pass_data' id='classify_info'>
								<li class="hasPic">
									<div class="item-link item-content">
										<if condition='$classify_info["imgs"]'>
											<div class="item-media"><img src="{pigcms{$classify_info['imgs'][0]}" width="80"></div>
										</if>
										<div class="item-inner" onclick="location.href='{pigcms{:U('Classify/ShowDetail',array('vid'=>$classify_info['id']))}'">
											<div class="item-title-row">
												<div class="item-title">{pigcms{$classify_info['title']}</div>
											</div>
											<div class="item-texts mt075"><span class="fr">{pigcms{$classify_info['timestr']}</span><!--span>浏览次数：47</span--></div>
										</div>
									</div>
									<div class="btn">
									
									<if condition='$classify_info["is_assure"] eq 0'>
										<a href="{pigcms{:U('classify_edit',array('id'=>$classify_info['id']))}" class="reload">
											编辑
										</a>
									</if>
										<a href="javascript:void(0)" class="delete" onclick="delItem({pigcms{$classify_info['id']})">
										删除
									</a>
									</div>
								</li>
                      </volist>
                    </ul>
                </div>
            </div>
        </div>
        <div id="fb2" class="tab">
            <div class="itemList">
                <div class="list-block media-list">
                    <ul>
                       <volist name='unpass_data' id='classify_info'>
								<li class="hasPic">
									<div class="item-link item-content">
										<if condition='$classify_info["imgs"]'>
											<div class="item-media"><img src="{pigcms{$classify_info['imgs'][0]}" width="80"></div>
										</if>
										<div class="item-inner" onclick="location.href='{pigcms{:U('Classify/ShowDetail',array('vid'=>$classify_info['id']))}'">
											<div class="item-title-row">
												<div class="item-title">{pigcms{$classify_info['title']}</div>
											</div>
											<div class="item-texts mt075"><span class="fr">{pigcms{$classify_info['timestr']}</span><!--span>浏览次数：47</span--></div>
										</div>
									</div>
									<div class="btn">
										<a href="javascript:void(0)" class="delete" onclick="delItem({pigcms{$classify_info['id']})">
										删除
									</a>
									</div>
								</li>
                      </volist>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<include file="Public:classify_footer" />
<script type="text/javascript" language="javascript">
function delItem(vid){
vid=parseInt(vid);
if(vid>0){
  if(confirm('您确认要删除此信息吗？')){

 $.post("{pigcms{:U('Classify/delItem')}",{vid:vid},function(ret){
	 if(!ret.error){
		alert('删除成功！');
		 window.location.reload();
	 }else{
		alert('删除失败！');
	 }
 },'JSON');

}
}
}
</script>
</body>
</html>