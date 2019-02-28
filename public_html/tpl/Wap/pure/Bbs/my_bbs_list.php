<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8"/>
        <title>我的帖子</title>
        <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,width=device-width"/>
        <meta http-equiv="pragma" content="no-cache"/>
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name='apple-touch-fullscreen' content='yes'/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
        <meta name="format-detection" content="telephone=no"/>
        <meta name="format-detection" content="address=no"/>
        <link href="{pigcms{$static_path}village_list/css/pigcms.css" rel="stylesheet"/>
    </head>
    <body>
        <div class="home_list">
            <ul>
            	<empty name="my_bbs_list">
                <style>
					.line-p { line-height:60px; text-align:center}
				</style>
                <p class="line-p">您还没有发布帖子!</p>
                <else />
                <volist name="my_bbs_list" id="vo">
                <li>
                    <a href="{pigcms{:U('Bbs/web_bbs_aricele_details')}&aricle_id={pigcms{$vo.aricle_id}&village_id={pigcms{$_GET['village_id']}&cat_id={pigcms{$vo.cat_id}&status=1">
                        <div class="home_top clr">
                            <div class="img fl">
                                <img src="{pigcms{$user.avatar}">
                            </div>
                            <div class="p100">
                                <h2>{pigcms{$user.name}</h2>
                                <p>{pigcms{$vo.address_text}</p>
                            </div>
                            <div class="time">{pigcms{$vo.update_time|date='Y-m-d',###}</div>
                        </div>
                        <div class="home_end">
                            <span>{pigcms{$vo.cat_name}|</span>{pigcms{$vo.aricle_title|substr=0,60}
                        </div>
                        <div class="dd_list">
                            <dl class=clr"">
                                <dd class="y">{pigcms{$vo.num}</dd>
                                <dd class="z">{pigcms{$vo.aricle_praise_num}</dd>
                                <dd class="p">{pigcms{$vo.aricle_comment_total}</dd>
                            </dl>
                        </div>
                    </a>
                    <div class="del" data-aricle-id="{pigcms{$vo.aricle_id}"></div>
                </li>
                </volist>
                </empty>
                
            </ul>
        </div>
        
        <section class="popup-tip">
            <div class="p400">
                <p class="tip-text"></p>
                <div class="clr button">
                    <div class="center binding">确定</div>
                </div>
            </div>
        </section> 
    
        <script src="{pigcms{$static_path}js/jquery-1.8.3.min.js"></script>
        <script src="{pigcms{$static_path}village_list/js/common.js"></script>
    </body>
</html>
<script>
    $(".home_list li").each(function(){
        var phone=$(this).find("h2").html();
        $(this).find("h2").html(phone.substring(0,3)+"****"+phone.substring(7,11));
    });
    $(".del").click(function(){
		if(!confirm('你确定删除帖子吗?')){
			return false;
		}else{
			var data_aricle_id = $(this).attr('data-aricle-id');
			var d_this = $(this);		
			$.post('{pigcms{:U("ajax_bbs_delete")}&village_id={pigcms{$_GET["village_id"]}' , {aricle_id:data_aricle_id} , function(data){
				if(data.status==0){
					d_this.parents("li").fadeOut(function(){
						d_this.parents("li").remove();
					})
				}
				motify.log(data.msg);
				
			},"json");
		}
        
    })
</script>


