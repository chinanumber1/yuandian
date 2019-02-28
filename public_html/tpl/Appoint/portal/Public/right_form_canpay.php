<!-- right start -->
    <div class="right fr">
     <form method="post" id="deal-buy-form" action="__SELF__">
      <div class="pin-wrapper" >
        <div class="panel" style="width: 290px;">
          <div class="form">
            <if condition='$cat_info["is_autotrophic"] eq 1'>
              <h2 class="title">在线预约</h2>
			  <div style="padding: 20px 0 20px 19px;">
			  <if condition='$formData'>
				  <volist name="formData" id="vo">
					<div class="form-field" data-name="{pigcms{$vo.name}">
					  <label for="address-detail">
						<if condition="$vo['iswrite']"><em>*</em></if>
						&nbsp;{pigcms{$vo.name}：</label>
					  <switch name="vo['type']">
						<case value="0">
						  <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
						  <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
						  <p class="cover"><input class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value]" data-role="text" 
							<if condition="$vo['iswrite']">data-required="required"</if>
							/></p>
						</case>
						<case value="1">
						  <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
						  <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
						  <p class="cover"><textarea class="ipt-attr" style=" height:60px" name="custom_field[{pigcms{$i}][value]" data-role="textarea" <if condition="$vo['iswrite']">  data-required="required"</if> ></textarea>
						  </p>
						</case>
						<case value="2">
						  <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
						  <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
						  <input type="hidden" name="custom_field[{pigcms{$i}][long]" data-type="long"/>
						  <input type="hidden" name="custom_field[{pigcms{$i}][lat]" data-type="lat"/>
						  <input type="hidden" name="custom_field[{pigcms{$i}][address]" data-type="address"/>
						  <p class="cover"> <input data-role="position" class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value]" readonly style="cursor:pointer;color:#666;" value="请点击标注地图" 
							<if condition="$vo['iswrite']">data-required="required"</if>
							/> </p>
						  <p class="cover" style="margin-top:10px;">
							<input data-role="position-desc" class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value-desc]" data-role="text" style="color:#999;" value="请标注地图后填写详细地址"/>
						  </p>
						</case>
						<case value="3">
						  <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
						  <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
						  <p class="cover select"> <select name="custom_field[{pigcms{$i}][value]" class="dropdown--small" data-role="select" 
							<if condition="$vo['iswrite']">data-required="required"</if>
							>
							<volist name="vo['use_field']" id="voo">
							  <option value="{pigcms{$voo}">{pigcms{$voo}</option>
							</volist>
							</select>
						  </p>
						</case>
						<case value="4">
						  <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
						  <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
						  <p class="cover"><input class="ipt-attr" type="tel" name="custom_field[{pigcms{$i}][value]" data-role="number" 
							<if condition="$vo['iswrite']">data-required="required"</if>
							/></p>
						</case>
						<case value="5">
						  <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
						  <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
						  <p class="cover"><input class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value]" data-role="email" 
							<if condition="$vo['iswrite']">data-required="required"</if>
							/></p>
						</case>
						<case value="6">
						  <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
						  <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
						  <p class="cover"><input class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value]" data-role="date" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日'})" style="cursor:pointer;color:#666;" value="请点击选择日期" 
							<if condition="$vo['iswrite']">data-required="required"</if>
							/></p>
						</case>
						<case value="7">
						  <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
						  <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
						  <p class="cover"><input class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value]" data-role="time" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'HH时mm分'})" style="cursor:pointer;color:#666;" value="请点击选择时间" 
							<if condition="$vo['iswrite']">data-required="required"</if>
							/></p>
						</case>
						<case value="8">
						  <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
						  <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
						  <p class="cover"><input class="ipt-attr" type="tel" name="custom_field[{pigcms{$i}][value]" data-role="phone" 
							<if condition="$vo['iswrite']">data-required="required"</if>
							/></p>
						</case>
						<case value="9">
						  <input type="hidden" name="custom_field[{pigcms{$i}][name]" value="{pigcms{$vo.name}"/>
						  <input type="hidden" name="custom_field[{pigcms{$i}][type]" value="{pigcms{$vo.type}"/>
						  <p class="cover"><input class="ipt-attr" type="text" name="custom_field[{pigcms{$i}][value]" data-role="datetime" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日 HH时mm分ss秒'})" style="cursor:pointer;color:#666;" value="请点击选择时间" 
							<if condition="$vo['iswrite']">data-required="required"</if>
							/></p>
						</case>
					  </switch>
					</div>
				  </volist>
				<input type="submit" id="confirmOrder" value="提交订单" class="submit-btn styfff" style="height:50px; border:none;margin-top:20px;"/>
			  </if>
			  </div>
              <div class="phone-search">
                <div class="company-tel" href="javascript:;"> <i class="phone-icon"></i> <strong class="tel-name">电话咨询
                  :</strong> <strong>{pigcms{$config.appoint_site_phone}</strong> </div>
              </div>
              <else />
              <!-- 编辑模板时去除   如果第三方入驻的，上面表单不显示，仅显示下面电话 -->
              <div class="phone-search">
                <div class="company-tel"> <i class="phone-icon"></i> <strong class="tel-name">电话咨询
                  :</strong> <strong>{pigcms{$cat_info.outsourced_phone}</strong> </div>
              </div>
            </if>
          </div>
        </div>
        
      </form>
      </div>
<script type="text/javascript">
var login_url="{pigcms{:UU('category_list')}";
var map_url="{pigcms{:U('Map/frame_select')}";
var setPointDom = {};

function setPoint(randNum,lng,lat,address){
	var objDom = setPointDom[randNum];
	objDom.closest('div').find('input[data-type="long"]').val(lng);
	objDom.closest('div').find('input[data-type="lat"]').val(lat);
	objDom.closest('div').find('input[data-type="address"]').val(address);
	objDom.data({'long':lng,'lat':lat,'address':address}).val(address);
}
</script>