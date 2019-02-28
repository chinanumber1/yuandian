<if condition="!empty($scroll_msg)">
<div style="background:#fff7ea; height: 50px;" class="scroll_msg">
	<div >
		<div class="" style="font-size:14px; line-height: 50px;" id="scrollText">
			<marquee  scrollamount="5" onmouseover = this.stop()  onmouseout=this.start() >
			<volist name="scroll_msg" id="vo">
				<div style="display:inline-block">
					<span style="padding-right:30px;color:#ff2c4d;">
						<i style="background:url({pigcms{$static_path}images/lbt_03.png) left center no-repeat; background-size: 15px; width: 20px; height: 20px; display:block; float: left; margin-top: 15px;"></i>
						<a href="#">{pigcms{$vo.content}</a>
					</span>
				</div>
			</volist>
			</marquee>
		</div>
	</div>
</div>


<style>
#scrollText div a{ color: #ff2c4d;}
</style>
<link rel="stylesheet" href="{pigcms{$static_public}font-awesome/css/font-awesome.min.css">
</if>