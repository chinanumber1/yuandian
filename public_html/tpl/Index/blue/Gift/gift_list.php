<include file="Public:gift_header"/>
<section class="breadNav">
    <div class="w1200">
        <div class="crumbs">
            <a href="{pigcms{:U('index')}">全部</a>
			<if condition='empty($_GET["type"])'>
				<if condition="$now_gift_category['cat_fid']">
					<a href="{pigcms{:U('gift_list',array('cat_id'=>$now_gift_category['cat_fid']))}">
				<else/>
					<a href="{pigcms{:U('gift_list',array('cat_id'=>$now_gift_category['cat_id']))}">
				</if>
				 {pigcms{$parent_gift_category['cat_name'] ? $parent_gift_category['cat_name'] : $now_gift_category['cat_name']}
			<else />
				<a href="{pigcms{:U('gift_list',array('type'=>$_GET["type"]))}">
				<if condition='$_GET["type"] eq "hot"'>
					我能兑换
				</if>
			</if>
           </a>
        </div>
    </div>
</section>
<section class="filterSec">
    <div class="w1200">
        <div class="tagBowl">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
					<if condition='empty($_GET["type"])'>
						<th colspan="2" scope="row">
							<p class="searchTit">
								<strong>
									{pigcms{$parent_gift_category['cat_name'] ? $parent_gift_category['cat_name'] : $now_gift_category['cat_name']}
								</strong>
								<span><em class="sx">筛选</em>（共搜到<em class="orange-font num">&nbsp;&nbsp;{pigcms{$son_category_list['gift_count'] ? $son_category_list['gift_count'] : 0}&nbsp;&nbsp;</em>款礼品）</span>
							</p>
						</th>
					<else />
						<if condition='$_GET["type"] eq "hot"'>
							<th colspan="2" scope="row">
								<p class="searchTit">
									<strong>
										我能兑换
									</strong>
									<span><em class="sx">筛选</em>（共搜到<em class="orange-font num">&nbsp;&nbsp;{pigcms{:count($gift_list['list'])}&nbsp;&nbsp;</em>款礼品）</span>
								</p>
							</th>
						</if>
					</if>
                </tr>
				
				<if condition='!empty($son_category_list["gift_category_list"])'>
					<tr>
						<th scope="row">筛选</th>
						<td><div class="tags">
							<div class="pre-link-list clearfix"> <span class="tags-title">{pigcms{$parent_gift_category['cat_name'] ? $parent_gift_category['cat_name'] : $now_gift_category['cat_name']}： </span> </div>
							<div class="link-list clearfix js_poolTagLink">
							
							<volist name='son_category_list["gift_category_list"]' id='gift_category'>
								<a href="{pigcms{:U('gift_list',array('cat_id'=>$gift_category['cat_id'],'integral_start'=>$_GET['integral_start'],'integral_end'=>$_GET['integral_end'],'exchange_type'=>$_GET['exchange_type']))}" <if condition='$_GET["cat_id"] eq $gift_category["cat_id"]'>class="on"</if> title="{pigcms{$gift_category['cat_name']}">{pigcms{$gift_category['cat_name']}</a> 
							</volist>
							<a href="javascript:;" class="more"><span class="down">更多<i class="fa fa-angle-down"></i> </span><span class="up">收起<i class="fa fa-angle-up"></i> </span></a> </div>
						</div></td>
					</tr>
				</if>
				
				<if condition='empty($_GET["type"])'>
					<tr>
						<th scope="row">{pigcms{$config['score_name']}范围：</th>
						<td><div class="states">
								<div class="link-list clearfix"> 
								<a href="{pigcms{:U('gift_list',array('cat_id'=>$_GET['cat_id'],'exchange_type'=>$_GET['exchange_type'],'type'=>$_GET['type']))}" <if condition='empty($_GET["integral_start"]) && empty($_GET["integral_end"])'>class="on"</if>>不限</a> 
								<a href="{pigcms{:U('gift_list',array('cat_id'=>$_GET['cat_id'],'integral_start'=>0,'integral_end'=>1000,'exchange_type'=>$_GET['exchange_type'],'type'=>$_GET['type']))}" <if condition='($_GET["integral_start"] eq 0) && ($_GET["integral_end"] eq 1000)'>class="on"</if>>0-1000</a>
								<a href="{pigcms{:U('gift_list',array('cat_id'=>$_GET['cat_id'],'integral_start'=>1000,'integral_end'=>2000,'exchange_type'=>$_GET['exchange_type'],'type'=>$_GET['type']))}" <if condition='($_GET["integral_start"] eq 1000) && ($_GET["integral_end"] eq 2000)'>class="on"</if>>1000-2000</a>
								<a href="{pigcms{:U('gift_list',array('cat_id'=>$_GET['cat_id'],'integral_start'=>2000,'integral_end'=>3000,'exchange_type'=>$_GET['exchange_type'],'type'=>$_GET['type']))}" <if condition='($_GET["integral_start"] eq 2000) && ($_GET["integral_end"] eq 3000)'>class="on"</if>>2000-3000</a>
								<a href="{pigcms{:U('gift_list',array('cat_id'=>$_GET['cat_id'],'integral_start'=>3000,'integral_end'=>5000,'exchange_type'=>$_GET['exchange_type'],'type'=>$_GET['type']))}" <if condition='($_GET["integral_start"] eq 3000) && ($_GET["integral_end"] eq 5000)'>class="on"</if>>3000-5000</a>
								<a href="{pigcms{:U('gift_list',array('cat_id'=>$_GET['cat_id'],'integral_start'=>5000,'integral_end'=>10000,'exchange_type'=>$_GET['exchange_type'],'type'=>$_GET['type']))}" <if condition='($_GET["integral_start"] eq 5000) && ($_GET["integral_end"] eq 10000)'>class="on"</if>>5000-10000</a>
								<a href="{pigcms{:U('gift_list',array('cat_id'=>$_GET['cat_id'],'integral_start'=>10000,'integral_end'=>20000,'exchange_type'=>$_GET['exchange_type'],'type'=>$_GET['type']))}" <if condition='($_GET["integral_start"] eq 10000) && ($_GET["integral_end"] eq 20000)'>class="on"</if>>10000-20000</a>
								<a href="{pigcms{:U('gift_list',array('cat_id'=>$_GET['cat_id'],'integral_start'=>20000))}" <if condition='$_GET["integral_start"] eq 20000'>class="on"</if>>20000及以上</a>
							</div>
						</div></td>
					</tr>
				<else />
					<tr>
						<th scope="row">{pigcms{$config['score_name']}范围：</th>
						<td><div class="states">
								<div class="link-list clearfix"> 
								<a href="{pigcms{:U('gift_list',array('type'=>$_GET['type'],'exchange_type'=>$_GET['exchange_type'],'type'=>$_GET['type']))}" <if condition='empty($_GET["integral_start"]) && empty($_GET["integral_end"])'>class="on"</if>>不限</a> 
								<a href="{pigcms{:U('gift_list',array('type'=>$_GET['type'],'integral_start'=>0,'integral_end'=>1000,'exchange_type'=>$_GET['exchange_type'],'type'=>$_GET['type']))}" <if condition='($_GET["integral_start"] eq 0) && ($_GET["integral_end"] eq 1000)'>class="on"</if>>0-1000</a>
								<a href="{pigcms{:U('gift_list',array('type'=>$_GET['type'],'integral_start'=>1000,'integral_end'=>2000,'exchange_type'=>$_GET['exchange_type'],'type'=>$_GET['type']))}" <if condition='($_GET["integral_start"] eq 1000) && ($_GET["integral_end"] eq 2000)'>class="on"</if>>1000-2000</a>
								<a href="{pigcms{:U('gift_list',array('type'=>$_GET['type'],'integral_start'=>2000,'integral_end'=>3000,'exchange_type'=>$_GET['exchange_type'],'type'=>$_GET['type']))}" <if condition='($_GET["integral_start"] eq 2000) && ($_GET["integral_end"] eq 3000)'>class="on"</if>>2000-3000</a>
								<a href="{pigcms{:U('gift_list',array('type'=>$_GET['type'],'integral_start'=>3000,'integral_end'=>5000,'exchange_type'=>$_GET['exchange_type'],'type'=>$_GET['type']))}" <if condition='($_GET["integral_start"] eq 3000) && ($_GET["integral_end"] eq 5000)'>class="on"</if>>3000-5000</a>
								<a href="{pigcms{:U('gift_list',array('type'=>$_GET['type'],'integral_start'=>5000,'integral_end'=>10000,'exchange_type'=>$_GET['exchange_type'],'type'=>$_GET['type']))}" <if condition='($_GET["integral_start"] eq 5000) && ($_GET["integral_end"] eq 10000)'>class="on"</if>>5000-10000</a>
								<a href="{pigcms{:U('gift_list',array('type'=>$_GET['type'],'integral_start'=>10000,'integral_end'=>20000,'exchange_type'=>$_GET['exchange_type'],'type'=>$_GET['type']))}" <if condition='($_GET["integral_start"] eq 10000) && ($_GET["integral_end"] eq 20000)'>class="on"</if>>10000-20000</a>
								<a href="{pigcms{:U('gift_list',array('type'=>$_GET['type'],'integral_start'=>20000))}" <if condition='$_GET["integral_start"] eq 20000'>class="on"</if>>20000及以上</a>
							</div>
						</div></td>
					</tr>
				</if>
				
				<if condition='empty($_GET["type"])'>
                <tr>
                    <th scope="row">支付方式：</th>
                    <td>
						<div class="states">
							<div class="link-list clearfix"> 
								<a href="{pigcms{:U('gift_list',array('cat_id'=>$_GET['cat_id'],'integral_start'=>$_GET['integral_start'],'integral_end'=>$_GET['integral_end'],'exchange_type'=>2,'type'=>$_GET['type']))}" <if condition='$_GET["exchange_type"] eq 2'>class="on"</if>>不限</a> 
								<a href="{pigcms{:U('gift_list',array('cat_id'=>$_GET['cat_id'],'integral_start'=>$_GET['integral_start'],'integral_end'=>$_GET['integral_end'],'exchange_type'=>0,'type'=>$_GET['type']))}" <if condition='$_GET["exchange_type"] eq 0'>class="on"</if>>全{pigcms{$config['score_name']}</a> 
								<a href="{pigcms{:U('gift_list',array('cat_id'=>$_GET['cat_id'],'integral_start'=>$_GET['integral_start'],'integral_end'=>$_GET['integral_end'],'exchange_type'=>1,'type'=>$_GET['type']))}" <if condition='$_GET["exchange_type"] eq 1'>class="on"</if>>{pigcms{$config['score_name']}+现金</a>
							</div>
						</div>
					</td>
                </tr>
				<else />
					<tr>
						<th scope="row">支付方式：</th>
						<td>
							<div class="states">
								<div class="link-list clearfix"> 
									<a href="{pigcms{:U('gift_list',array('type'=>$_GET['type'],'integral_start'=>$_GET['integral_start'],'integral_end'=>$_GET['integral_end'],'exchange_type'=>2))}" <if condition='$_GET["exchange_type"] eq 2'>class="on"</if>>不限</a> 
									<a href="{pigcms{:U('gift_list',array('type'=>$_GET['type'],'integral_start'=>$_GET['integral_start'],'integral_end'=>$_GET['integral_end'],'exchange_type'=>0))}" <if condition='$_GET["exchange_type"] eq 0'>class="on"</if>>全{pigcms{$config['score_name']}</a> 
									<a href="{pigcms{:U('gift_list',array('type'=>$_GET['type'],'integral_start'=>$_GET['integral_start'],'integral_end'=>$_GET['integral_end'],'exchange_type'=>1))}" <if condition='$_GET["exchange_type"] eq 1'>class="on"</if>>{pigcms{$config['score_name']}+现金</a>
								</div>
							</div>
						</td>
					</tr>
				</if>
                </tbody>
            </table>
        </div>
    </div>
</section>
<section class="ranking">
    <div class="ranking-nav w1200 clearfix">
        <ul class="clearfix">
            <li <if condition="empty($_GET['order'])">class="on"</if>>
			<if condition='!empty($_GET["type"])'>
                <a href="{pigcms{:U('gift_list',array('type'=>$_GET['type']))}">默认</a>
			<else />
				<a href="{pigcms{:U('gift_list',array('cat_id'=>$_GET['cat_id']))}">默认</a>
			</if>
            </li>
            <!--li>
                <a class="on" href="javascript:;">出价次数<i class="down fa fa-long-arrow-down"></i><i class="up fa fa-long-arrow-up"></i></a>
            </li-->
            <li <if condition="!empty($_GET['order']) && (in_array($_GET['order'],array('asc','desc')))">class="on"</if>>
                <a <if condition="$_GET['order'] eq asc">href="{pigcms{:U('gift_list',array('cat_id'=>$_GET['cat_id'],'integral_start'=>$_GET['integral_start'],'integral_end'=>$_GET['integral_end'],'exchange_type'=>$_GET['exchange_type'],'order'=>'desc','type'=>$_GET['type']))}"<else />href="{pigcms{:U('gift_list',array('cat_id'=>$_GET['cat_id'],'integral_start'=>$_GET['integral_start'],'integral_end'=>$_GET['integral_end'],'exchange_type'=>$_GET['exchange_type'],'order'=>'asc','type'=>$_GET['type']))}"</if>>{pigcms{$config['score_name']}
				<if condition='$_GET["order"] eq "asc"'>
					<i class="up fa fa-long-arrow-up"></i>
				<elseif condition='$_GET["order"] eq "desc"' />
					<i class="up fa  fa-long-arrow-down"></i></a>
				</if>
            </li>
        </ul>
    </div>
</section>
<section class="mainSection">

    <div class="w1200">
        <div class="jfItemRow">
            <ul class="JSitem clearfix">
			<if condition='!empty($gift_list["list"])'>
					<volist name='gift_list["list"]' id='gift'>
						<li class="item">
							<div class="wrap">
								<div class="i-pic">
									<a href="{pigcms{:U('gift_detail',array('gift_id'=>$gift['gift_id']))}">
										<img src="{pigcms{$static_path}gift/images/palceholder/item.png"/>
									</a>
								</div>
								<ol class="min-i-pic">
									<volist name='gift["pc_pic_list"]' id='pic'>
										<li>
											<img src="{pigcms{$config.site_url}/upload/system/gift/{pigcms{$pic}"/>
										</li>
									</volist>
								</ol>

								<h2>{pigcms{$gift.gift_name}</h2>
								
								<!--p class="bonus"><span>889 <em>{pigcms{$config['score_name']}</em></span><span><em>+</em>889 <em>元</em></span></p-->
								
								<if condition='in_array($gift["exchange_type"],array(0,2))'><p class="bonus">{pigcms{$config['score_name']}：{pigcms{$gift.payment_pure_integral}<em>{pigcms{$config['score_name']}</em></p></if>
								<if condition='in_array($gift["exchange_type"],array(1,2))'>
								 <p class="bonus">{pigcms{$gift.payment_integral}<em>{pigcms{$config['score_name']}</em><em>+</em>{pigcms{$gift.payment_money}<em>元</em></p>
								</if>
							</div>
						</li>
				   </volist>
			   <else />
					<p style=" text-align:center; margin-top:10px">暂无礼品</p>
			   </if>
            </ul>
        </div>

        <div class="pagination">
			{pigcms{$gift_list['pagebar']}
        </div>
    </div>

</section>
<include file="Public:gift_footer"/>
