<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title><if condition="$_GET['type'] eq 1">寄件<else/>收件</if>人地址</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/address1.css"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/ceshi.css"/>
		<style>
      .tongyi{width:100%;}
		</style>
	</head>
	<body>
	<header class="pageSliderHide"><div id="backBtn"></div><if condition="$_GET['type'] eq 1">寄件<else/>收件</if>人地址</header>
	<div class="content">
		<div class="tongyi  add1">
			<span>姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名</span>
			<input type="text" name="uname" id="uname" value="{pigcms{$adress_info.uname}" placeholder="请输入您的姓名" />
		</div>
		<div class="tongyi  add1">
			<span>联系方式</span>
			<input type="tel" name="phone" id="phone" value="{pigcms{$adress_info.phone}" placeholder="请输入手机号" />
		</div>
		<div class="tongyi  add1">
			<span>省/市/区</span>
			<!-- <input type="text" id="city-picker" value="" placeholder="请选择省市区县" readonly=""> -->
			<input type="text" id="city-picker" value="{pigcms{$adress_info.city}" placeholder="请选择省市区县"/>
		</div>
		<div class="tongyi  add1">
			<span>详细地址</span>
			<input type="text" name="adress" id="adress" value="{pigcms{$adress_info.adress}" placeholder="请输入详细地址" />
		</div>
		<div class="tongyi">
			<input type="hidden" name="type" id="type" value="{pigcms{$_GET['type']}">
			<button type="button" class="btnss" onclick="btn_submit()">提交</button>
		</div>
		
	</div>
	<link href="{pigcms{$static_public}city-picker/css/layout.min.css" rel="stylesheet" />
	<link href="{pigcms{$static_public}city-picker/css/scs.min.css" rel="stylesheet" />
	<script src="{pigcms{$static_path}js/jquery-1.8.3.min.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="{pigcms{$static_public}city-picker/js/jquery.scs.min.js?t=43243232423"></script>
	<script type="text/javascript" src="{pigcms{$static_public}city-picker/js/CNAddrArr.min.js?t=43243232423"></script>
	<script>
    $(function() {
        /**
         * 通过数组id获取地址列表数组
         *
         * @param {Number} id
         * @return {Array} 
         */ 
        function getAddrsArrayById(id) {
            var results = [];
            if (addr_arr[id] != undefined)
                addr_arr[id].forEach(function(subArr) {
                    results.push({
                        key: subArr[0],
                        val: subArr[1]
                    });
                });
            else {
                return;
            }
            return results;
        }
        /**
         * 通过开始的key获取开始时应该选中开始数组中哪个元素
         *
         * @param {Array} StartArr
         * @param {Number|String} key
         * @return {Number} 
         */         
        function getStartIndexByKeyFromStartArr(startArr, key) {
            var result = 0;
            if (startArr != undefined)
                startArr.forEach(function(obj, index) {
                    if (obj.key == key) {
                        result = index;
                        return false;
                    }
                });
            return result;
        }

        //bind the click event for 'input' element
        $("#city-picker").click(function() {
            var PROVINCES = [],
                startCities = [],
                startDists = [];
            //Province data，shall never change.
            addr_arr[0].forEach(function(prov) {
                PROVINCES.push({
                    key: prov[0],
                    val: prov[1]
                });
            });
            //init other data.
            var $input = $(this),
                dataKey = $input.attr("data-key"),
                provKey = 1, //default province 北京
                cityKey = 36, //default city 北京
                distKey = 37, //default district 北京东城区
                distStartIndex = 0, //default 0
                cityStartIndex = 0, //default 0
                provStartIndex = 0; //default 0
            
            console.log($input.val());
            if (dataKey != "" && dataKey != undefined) {
                var sArr = dataKey.split("-");
                if (sArr.length == 3) {
                    provKey = sArr[0];
                    cityKey = sArr[1];
                    distKey = sArr[2];

                } else if (sArr.length == 2) { //such as 台湾，香港 and the like.
                    provKey = sArr[0];
                    cityKey = sArr[1];
                }
                startCities = getAddrsArrayById(provKey);
                startDists = getAddrsArrayById(cityKey);
                provStartIndex = getStartIndexByKeyFromStartArr(PROVINCES, provKey);
                cityStartIndex = getStartIndexByKeyFromStartArr(startCities, cityKey);
                distStartIndex = getStartIndexByKeyFromStartArr(startDists, distKey);
            }else if($input.val() != ''){
              var cityArr = $input.val().split(' ');
              var pro_id = 0;
              var city_id = 0;
              var area_id = 0;
              if(cityArr.length >= 2){
                for(var i in addr_arr[0]){
                  var subArr = addr_arr[0][i];
                  if(subArr[1] == cityArr[0]){
                    pro_id = subArr[0];
                    continue;
                  }
                }
                for(var i in addr_arr[pro_id]){
                  var subArr = addr_arr[pro_id][i];
                  if(subArr[1] == cityArr[1]){
                    city_id = subArr[0];
                    continue;
                  }
                }
                if(cityArr[2]){
                  for(var i in addr_arr[city_id]){
                    var subArr = addr_arr[city_id][i];
                    if(subArr[1] == cityArr[2]){
                      area_id = subArr[0];
                      continue;
                    }
                  }
                }
              }
              $input.attr('data-key',pro_id+'-'+city_id+'-'+area_id);
              $("#city-picker").trigger('click');
              return false;
            }else{
              $input.attr('data-key','1-36-37');
              $("#city-picker").trigger('click');
              return false;
            }
            var navArr = [{//3 scrollers, and the title and id will be as follows:
                title: "省",
                id: "scs_items_prov"
            }, {
                title: "市",
                id: "scs_items_city"
            }, {
                title: "区",
                id: "scs_items_dist"
            }];
            SCS.init({
                navArr: navArr,
                onOk: function(selectedKey, selectedValue) {
                    $input.val(selectedValue).attr("data-key", selectedKey);
                }
            });
            var distScroller = new SCS.scrollCascadeSelect({
                el: "#" + navArr[2].id,
                dataArr: startDists,
                startIndex: distStartIndex
            });
            var cityScroller = new SCS.scrollCascadeSelect({
                el: "#" + navArr[1].id,
                dataArr: startCities,
                startIndex: cityStartIndex,
                onChange: function(selectedItem, selectedIndex) {
                    distScroller.render(getAddrsArrayById(selectedItem.key), 0); //re-render distScroller when cityScroller change
                }
            });
            var provScroller = new SCS.scrollCascadeSelect({
                el: "#" + navArr[0].id,
                dataArr: PROVINCES,
                startIndex: provStartIndex,
                onChange: function(selectedItem, selectedIndex) { //re-render both cityScroller and distScroller when provScroller change
                    cityScroller.render(getAddrsArrayById(selectedItem.key), 0);
                    distScroller.render(getAddrsArrayById(cityScroller.getSelectedItem().key), 0);
                }
            });
        });
    });
    </script>

	<script type="text/javascript">
	  	$('#backBtn').click(function(e){
			window.location.href="{pigcms{:U('express_send_add',array('village_id'=>$_GET['village_id']))}";
		});
		
	  	function btn_submit(){
	  		var uname = $("#uname").val();
	  		var phone = $("#phone").val();
	  		var city = $("#city-picker").val();
	  		var adress = $("#adress").val();
	  		var type = $("#type").val();
	  		if(!uname){
	  			return false;
	  		}
	  		if(!phone){
	  			return false;
	  		}
	  		if(!city){
	  			return false;
	  		}
	  		if(!adress){
	  			return false;
	  		}
	  		var send_adress_ajax_url = "{pigcms{:U('send_adress_ajax')}";
	  		$.post(send_adress_ajax_url,{uname:uname,phone:phone,city:city,adress:adress,type:type},function(data){
	  			location.href = "{pigcms{:U('express_send_add',array('village_id'=>$_GET['village_id']))}";
	  		});
	  	}
	</script>
</html>