
<if condition="ACTION_NAME neq 'cart'">
		<pigcms:adver cat_key="web_mall_footer" limit="1" var_name="index_today_fav">
		<div class="normalProducts">
		<a href="{pigcms{$vo.url}" target="_blank" style="color: #666;">
		    <img src="{pigcms{$vo.pic}" alt="{pigcms{$vo.name}">
		</a>
		</div>
		</pigcms:adver>
		</if>

</setion>

<footer class="footer">
	<div class="footClass">
		<ul class="clearfix">
			<pigcms:footer_link var_name="footer_link_list">
				<li class="pull-left"><a href="{pigcms{$vo.url}" target="_blank" style="color: #666;">{pigcms{$vo.name}</a><if condition="$i neq count($footer_link_list)"><span>|</span></if></li>
			</pigcms:footer_link>
		</ul>
	</div>
    <div class="footer_txt">{pigcms{:nl2br($config['site_show_footer'],'<a>')}</div>
</footer>

<div style="display:none;">{pigcms{$config.site_footer}</div>
<style>
.no_adver_list {display:none;}
</style>