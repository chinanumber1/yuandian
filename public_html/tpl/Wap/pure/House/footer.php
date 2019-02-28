<if condition="empty($no_footer)">
	<footer class="footerMenu wap house">
		<div class="footer_top"></div>
		<ul>	
			<li>
				<a <if condition="in_array(ACTION_NAME,array('village','village_manager_list','village_more_list'))">class="hover"<else/>href="{pigcms{:U('House/village',array('village_id'=>$now_village['village_id']))}"</if>><em class="home"></em><p>首页</p></a>
			</li>
			<li>
				<a <if condition="in_array(MODULE_NAME,array('Houseservice'))">class="hover"<else/>href="{pigcms{:U('Houseservice/index',array('village_id'=>$now_village['village_id']))}"</if>><em class="group"></em><p>便民</p></a>
			</li>
			<php>if($config['house_show_center'] == 0){</php>
				<li class="phoneBtn">
					<a <if condition="in_array(MODULE_NAME,array('Housephone'))">class="hover"<else/>href="{pigcms{:U('Housephone/index',array('village_id'=>$now_village['village_id']))}"</if>><em class="phoneBtn"></em><p>常用电话</p></a>
				</li>
			<php>}else{</php>
				<li class="phoneBtn">
					<a <if condition="in_array(MODULE_NAME,array('Housemarket'))">class="hover"<else/>href="{pigcms{:U('Housemarket/index',array('village_id'=>$now_village['village_id']))}"</if>><em class="marketBtn"></em><p>{pigcms{$config.house_market_name}</p></a>
				</li>
			<php>}</php>
			<li>
				<a <if condition="in_array(MODULE_NAME,array('Bbs'))">class="hover"<else/>href="{pigcms{:U('Bbs/web_index',array('village_id'=>$now_village['village_id'],'referer'=>urlencode(U('House/village',array('village_id'=>$now_village['village_id'])))))}"</if>><em class="bbs"></em><p>邻里</p></a>
			</li>
			<li>
				<a <if condition="strpos(ACTION_NAME,'village_my') nheq false">class="active"<else/></if> href="{pigcms{:U('House/village_my',array('village_id'=>$now_village['village_id']))}"><em class="my"></em><p>我的</p></a>
			</li>
		</ul>
	</footer>
</if>
<div style="display:none;">{pigcms{$config.wap_site_footer}</div> 