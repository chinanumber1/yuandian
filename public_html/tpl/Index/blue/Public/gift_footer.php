<style>

</style>
<div class="footer">
    <div class="footer-wrap">
        <div class="footer-inner">
            <ul class="footer-about">
				<pigcms:footer_link var_name="footer_link_list">
					<li><a href="{pigcms{$vo.url}" target="_blank">{pigcms{$vo.name}</a><if condition="$i neq count($footer_link_list)"><span>&nbsp;&nbsp;|&nbsp;&nbsp;</span></if></li>
				</pigcms:footer_link>
            </ul>
        </div>
    </div>
	
    <div class="footer-wrap-black">
        <div class="footer-inner2">
            <div class="footer-copyright">
                <p>{pigcms{:nl2br(strip_tags($config['site_show_footer'],'<a>'))}</p>
            </div>
        </div>
    </div>
</div>

<script src="{pigcms{$static_path}gift/js/jquery-1.7.2.min.js"></script>
<script src="{pigcms{$static_path}gift/js/common.js"></script>
<script type="text/javascript" language="javascript">
    $(function(){
        $(".JSjfBtn").on('click',function(){
            showWindow(".bonusLow");
        });
    });
</script>
</body>
</html>