<footer class="footer">
	<div class="w1200">
		<div class="footer_list">
			<ul>
				<pigcms:footer_link var_name="footer_link_list">
					<li><a href="{pigcms{$vo.url}" target="_blank">{pigcms{$vo.name}</a><if condition="$i neq count($footer_link_list)"><span>|</span></if></li>
				</pigcms:footer_link>
			</ul>
		</div>
		<div class="footer_txt">{pigcms{:nl2br($config['site_show_footer'],'<a>')}</div>
	</div>
</footer>
<div style="display:none;">{pigcms{$config.site_footer}</div>