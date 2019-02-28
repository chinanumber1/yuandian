<include file="Public:header"/>
<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
<link href="{pigcms{$static_path}css/workertime.css" type="text/css"  rel="stylesheet" />
<table class="aui_dialog">
  <tbody>
    <tr>
      <td class="aui_icon" style="display: none;"><div class="aui_iconBg" style="background: transparent none repeat scroll 0% 0%;"></div></td>
      <td class="aui_main" style="width: 538px; height: auto; visibility: visible;"><div class="aui_content" style="padding: 0px;">
          <section id="service-date">
		<div class="yxc-pay-main yxc-payment-bg pad-bot-comm">
			<div class="yxc-time-con number-{pigcms{:count($timeOrder)}">
				<volist name="timeOrder" id="timeOrderInfo">
					<dl <if condition="$i eq count($timeOrder)">class="last"</if>>
						<dt <if condition="$i eq 1">class="active"</if> data-role="date" data-text="<if condition="$key eq date('Y-m-d')" > 今天<elseif condition="$key eq date('Y-m-d',strtotime('+1 day'))" />明天
	<elseif condition="$key eq date('Y-m-d',strtotime('+2 day'))" />后天<else />{pigcms{$key}
								</if>" data-date="{pigcms{$key}">
								<if condition="$key eq date('Y-m-d')" > 今天
								<elseif condition="$key eq date('Y-m-d',strtotime('+1 day'))" />明天
								<elseif condition="$key eq date('Y-m-d',strtotime('+2 day'))" />后天
								<else />
								</if>
							<span>{pigcms{$key}</span>
						</dt>
					</dl>
				</volist>
			</div>
			<div class="yxc-time-con" data-role="timeline">
				<volist name="timeOrder" id="timeOrderInfo">
					<div class="date-{pigcms{$key} timeline" <if condition="$i neq 1">style='display:none'</if> >
					   <volist name="timeOrderInfo" id="vo">
						<dl>
							<dd data-role="item" data-peroid="{pigcms{$vo['time']}" <if condition="$vo['order'] eq 'no' || $vo['order'] eq 'all' ">class="disable"</if>>{pigcms{$vo['time']}<br>
							<if condition="$vo['order'] eq 'no' ">不可预约<elseif condition="$vo['order'] eq 'all' " />已约满<else />可预约</if></dd>
						</dl>
						</volist>
					</div>
				</volist>
            </div>
		</div>
	</section>
        </div></td>
    </tr>
    <tr>
      <td class="aui_footer" colspan="2"><div class="aui_buttons" style="display: none;"></div></td>
    </tr>
  </tbody>
</table>
<script type="text/javascript" src="{pigcms{$static_path}/js/workertime.js"></script>
<include file="Public:footer"/>