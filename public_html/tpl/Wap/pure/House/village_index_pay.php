<if condition="$index_service_cat_list">
	<!--section class="slider" style="height:auto;">
		<div class="headBox">社区服务</div>
		<div class="swiper-container swiper-container2" style="height:auto;padding-bottom:10px;">
			<div class="swiper-wrapper">
				<div class="swiper-slide">
					<ul class="icon-list">
						<volist name="index_service_cat_list" id="vo">
							<li class="icon">
								<a href="{pigcms{$vo.cat_url}">
									<span class="icon-circle">
										<img src="{pigcms{$vo.cat_img}"/>
									</span>
									<span class="icon-desc">{pigcms{$vo.cat_name}</span>
								</a>
							</li>
						</volist>
					</ul>
				</div>
			</div>
			<div class="swiper-pagination swiper-pagination2"></div>
		</div>
	</section-->
	
	
	
	<section class="slider">
		<div class="swiper-container swiper-container2" style="height:168px;">
			<div class="swiper-wrapper">
				<volist name="index_service_cat_list" id="vo">
					<div class="swiper-slide">
						<ul class="icon-list">
							<volist name="vo" id="voo">
								<li class="icon">
									<a href="{pigcms{$voo.cat_url}">
										<span class="icon-circle">
											<img src="{pigcms{$voo.cat_img}">
										</span>
										<span class="icon-desc">{pigcms{$voo.cat_name}</span>
									</a>
								</li>
							</volist>
						</ul>
					</div>
				</volist>
			</div>
			<div class="swiper-pagination swiper-pagination2"></div>
		</div>
	</section>
</if>