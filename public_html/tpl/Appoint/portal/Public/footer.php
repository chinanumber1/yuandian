<div class="footer">
	<!-- 左侧按钮begin -->
	<div id="tbox">
		<!--div class="icon">
			<a id="coder" href="#tag_cmt"></a>
			<div id="icon-01"></div>
			<div id="coder_img"></div>
		</div-->
		<div class="icon-t">
			<a id="code" href="#tag_about"></a>
			<div id="icon-02"></div>
			<div id="code_img" style="background-image:url({pigcms{$config.wechat_qrcode});"></div>
		</div>
		<a id="gotop" href="javascript:void(0)" style="display:none;"></a>
	</div>
	<!-- 左侧按钮end -->  
	<div class="nav-bottom">
		<p>
			<pigcms:slider cat_key="web_yue_fslider" limit="10" var_name="web_yue_fslider" reverse="true">
				<a href="{pigcms{$vo.url}" target="_blank">{pigcms{$vo.name}</a><if condition="$i neq count($web_yue_fslider)">|</if>
			</pigcms:slider>
		</p>
		{pigcms{$config.appoint_footer_code}
	</div>
</div>