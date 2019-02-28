<footer class="footerMenu wap">
	<ul>
		<li>
			<a <if condition="ACTION_NAME eq 'index'"> class="active" </if>href="{pigcms{:U('Mall/index')}"><em class="home"></em><p>首页</p></a>
		</li>
		<li>
			<a <if condition="ACTION_NAME eq 'category'"> class="active" </if>href="{pigcms{:U('Mall/category')}"><em class="store"></em><p>分类</p></a>
		</li>
		<li>
			<a <if condition="ACTION_NAME eq 'cart'"> class="active" </if>href="{pigcms{:U('Mall/cart')}"><em class="group"></em><p>购物车</p></a>
		</li>
		<li>
			<a href="{pigcms{:U('My/index')}"><em class="my"></em><p>我的</p></a>
		</li>
	</ul>
</footer>