<!DOCTYPE html>
<html lang="en">
<head>
    <title>{pigcms{$cat_name}</title> 
    <include file="Public:classify_header" />
<style type="text/css">
.list-block.media-list .item-media img{ width:80px; height:80px}
</style>
<if condition="!empty($conarr)">
<nav class="topNav filterNav list-block select_list">
    <ul class="box">
		<volist name="conarr" id="value">
			<li class="b-flex">
			 <select>
				 <option data-url="{pigcms{$thisurl}">
					{pigcms{$value.name}
				 </option>
				 
				 <volist name="value['data']" key="kk" id="dv">
					 <php>if(($value['opt']==1) && ($kk==1) && (strpos($dv, '-') === false)){
							$opt="opt,ty=".$value[opt].",fd=".$value['input'].",vv=0-".$dv;
						}elseif(($value['opt']==1) && ($kk>1) && (strpos($dv, '-') === false)){
							$opt="opt,ty=".$value[opt].",fd=".$value['input'].",vv=".$dv."-0";
						}else{
							$opt="opt,ty=".$value[opt].",fd=".$value['input'].",vv=".$dv;
						}

						$opt=base64_encode($opt);
					 </php>
					 <option <if condition="!empty($original) AND ($original eq $dv)">selected="selected"</if> data-url="{pigcms{$thisurl}&opt={pigcms{$opt}">
						{pigcms{$dv}
					 </option>
				 </volist>
			 </select>
			</li>
        </volist>
    </ul>
</nav>
</if>

<div class="itemList">
    <div class="list-block media-list">
        <ul>
		<if condition="!empty($listsdatas)">
			<volist name="listsdatas" id="vl">
            <li>
                <a <if condition="empty($vl['jumpUrl'])"> href="{pigcms{:U('Classify/ShowDetail',array('vid'=>$vl['id']))}" <else/> href="{pigcms{$vl['jumpUrl']}"</if> class="item-link item-content">
                    <if condition="isset($vl['imgthumbnail'])"><div class="item-media"><img src="{pigcms{$vl['imgthumbnail']}" width="80"></div></if>
                    <div class="item-inner">
                        <div class="item-title-row">
                            <div class="item-title">{pigcms{$vl['title']}</div>
                        </div>
						<if condition='!empty($vl["is_assure"])'>
							<div class="item-subtitle"><em class="fr">{pigcms{$vl['timestr']}</em>担保交易</div>
						<else />
							<div class="item-subtitle"><em class="fr">{pigcms{$vl['timestr']}</em><if condition="!in_array($vl['input1'],array('on','off'))">{pigcms{$vl['input1']}</if></div>
						</if>
                        
                        <!--div class="item-texts"><span class="fr"><if condition="!in_array($vl['input3'],array('on','off'))">{pigcms{$vl['input3']}</if></span><span class="intro"><if condition="!in_array($vl['input2'],array('on','off'))">{pigcms{$vl['input2']}</if></span></div-->
                    </div>
                </a>
            </li>
			</volist>
	  <else/>
		<li style="font-size: 14px;text-align: center;padding-top: 35px; padding-bottom: 35px;"><if condition="!empty($qsearch)"><a href="{pigcms{:U('Classify/Lists',array('cid'=>$cid))}" style="margin: 20px 90px;">暂无数据，点击跳到无查询状态</a><else/>没有数据！<a href="{pigcms{:U('Classify/fabu',array('cid'=>$fcid))}" style="margin:20px 0px;color:rgb(151,151,168)!important;text-align:center;display:block;">点击这里快去发布吧</a></if></li>
	  </if>   
        </ul>
    </div>
</div>
<include file="Public:classify_footer" />
 <script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script>

  var obj2String = function(_obj) {
    var t = typeof (_obj);
    if (t != 'object' || _obj === null) {
      // simple data type
      if (t == 'string') {
        _obj = '"' + _obj + '"';
      }
      return String(_obj);
    } else {
      if ( _obj instanceof Date) {
        return _obj.toLocaleString();
      }
      // recurse array or object
      var n, v, json = [], arr = (_obj && _obj.constructor == Array);
      for (n in _obj) {
        v = _obj[n];
        t = typeof (v);
        if (t == 'string') {
          v = '"' + v + '"';
        } else if (t == "object" && v !== null) {
          v = this.obj2String(v);
        }
        json.push(( arr ? '' : '"' + n + '":') + String(v));
      }
      return ( arr ? '[' : '{') + String(json) + ( arr ? ']' : '}');
    }
  };
$('.select_list ul li select').change(function(){
	var url = $(this).find('option:selected').attr('data-url');
	location.href=url
});

var strVar = "";
var i = 1;

  $(window).on("scroll",function(){
	if($(".itemList ul").height() <= $(window).scrollTop()+$(window).height()){
		i++;
		var url = "{pigcms{:U('ajax_Lists')}";
		var cid = "{pigcms{$_GET['cid']}";
		var sub3dir = "{pigcms{$_GET['sub3dir']}";
		var opt = "{pigcms{$_GET['opt']}";
		$.get(url,{'cid':cid,'sub3dir':sub3dir,'opt':opt,'page':i},function(data){

			if(data['status']){
				var data = data['listsdatas'];
				
				if(!data){
					return false;
				}
				
				for (var j in data){
					strVar += '<li><a ';
					
					if(!data[j]['jumpUrl']){
						var classify_url = "{pigcms{:U('Classify/ShowDetail')}";
						classify_url += '&vid='+data[j]['id'];
						strVar += 'href="' + classify_url + '"';
					}else{
						strVar += data[j]['jumpUrl'];
					}
					
					strVar += 'class="item-link item-content">';
					
					if(data[j]['imgthumbnail']){
						strVar += '<div class="item-media"><img src="'+data[j]['imgthumbnail']+'" width="80"></div>';
					}
					
					strVar += '<div class="item-inner"><div class="item-title-row"><div class="item-title">'+data[j]['title']+'</div></div>';
					
					if(!data[j]['is_assure']){
						strVar +='<div class="item-subtitle"><em class="fr">'+data[j]['timestr']+'</em>担保交易</div>';
					}else{
						strVar +='<div class="item-subtitle"><em class="fr">'+data[j]['timestr']+'</em>';
						if((data[j]['input1'] == 'on') || (data[j]['input1'] == 'off')){
							strVar +=data[j]['input1'];
						}
						strVar +='</div>';
					}
					strVar +='</div></a></li>';
					
				}
				$('.itemList ul').append(strVar);
			}
			
			
		},'json')
		
	}
})

	
window.shareData = {  
	"moduleName":"Classify",
	"moduleID":"0",
	"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>", 
	"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Classify/Lists',array('cid'=>$_GET['cid']))}",
	"tTitle": "{pigcms{$cat_name}分类",
	"tContent": ""
};

</script>
{pigcms{$shareScript}
</body>
</html>