<if condition="empty($no_footer_appoint) && (!$is_app_browser)">		
	<footer class="footerMenu <if condition="!$is_wexin_browser">wap</if>">
		<ul>
			<volist name="footer_menu_list" id="vo" offset="0" length="2">
				<li>
					<a href="{pigcms{$vo['url']}" <if condition='stripos($vo["url"],MODULE_NAME) && stripos($vo["url"],ACTION_NAME)'>class="active"</if>><em <if condition='stripos($vo["url"],MODULE_NAME) && stripos($vo["url"],ACTION_NAME)'>style="background:url({pigcms{$config.site_url}/upload/slider/{pigcms{$vo.hover_pic_path}) no-repeat center left; background-size:22px 20px"<else />style="background:url({pigcms{$config.site_url}/upload/slider/{pigcms{$vo.pic_path}) no-repeat center left; background-size:22px 20px"</if>></em><p>{pigcms{$vo.name}</p></a>
				</li>
			</volist>
			<li class="voiceBox"><a href="{pigcms{:U('Search/voice')}" class="voiceBtn" data-nobtn="true"></a></li>
			<volist name="footer_menu_list" id="vo" offset="2" length="2">
				<li>
					<a href="{pigcms{$vo['url']}" <if condition='stripos($vo["url"],MODULE_NAME) && stripos($vo["url"],ACTION_NAME)'>class="active"</if>><em <if condition='stripos($vo["url"],MODULE_NAME) && stripos($vo["url"],ACTION_NAME)'>style="background:url({pigcms{$config.site_url}/upload/slider/{pigcms{$vo.hover_pic_path}) no-repeat center left; background-size:22px 20px"<else />style="background:url({pigcms{$config.site_url}/upload/slider/{pigcms{$vo.pic_path}) no-repeat center left; background-size:22px 20px"</if>></em><p>{pigcms{$vo.name}</p></a>
				</li>
			</volist>
		</ul>
	</footer>
</if>
<div style="display:none;">{pigcms{$config.wap_site_footer}</div>