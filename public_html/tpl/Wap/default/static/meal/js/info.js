var date_elm = null;
var datetime_elm = null;
var table_id = 0;
$(document).ready(function(){
	table_id = config.editInfo.seattype;
	$(".date").val(config.editInfo.date);
	$(".datetime").val(config.editInfo.time);
	//get_table();
	if (config.dishes_status == 2) {
		$("#pre label").html("取消修改");
		$("#next label").html("我同意并提交");	
		showinfo();	
		//$("#pre").get(0).onclick=function(){loaction.href=$('#pre').attr('href');}
	} else if(config.dishes_status == 0) {
		$("#pre label").html("上一步");
		$("#next label").html("下一步");
		showinfo();	
//		$("#pre").get(0).onclick=function(){
//			config.utype=0;
//			submit_F();
//		}
		$("#next").get(0).onclick=function(){
			config.utype=1;
			submit_F();
		}
	} else {
		$("#pre label").html("重新选择店铺");
		$("#next label").html("我同意并提交");
//		var nowdate= new Date();
//		var month = nowdate.getMonth()+1;
//		if(month<10){ month="0"+month;}
//		var date= nowdate.getDate();
//		if(date<10){ date="0"+date;}
//		$('.date').val(nowdate.getFullYear()+"-"+month+"-"+date)
//		
//		var h = nowdate.getHours();
//		if(h<10){ h="0"+h;}
//		var M = nowdate.getMinutes();
//		if(M<10){ M="0"+M;}
//		$('.datetime').val(h+":"+M);
		$(".date").val(config.editInfo.date);
		$(".datetime").val(config.editInfo.time);
		$("#pre").get(0).onclick=function(){window.history.go(-1);}
	}
	
//	if (config.dishes_status == 1) {
		date_elm = $('.date').mobiscroll()["date"]({
			lang: 'zh',
			display: 'bottom',
			minWidth: 64,
			dayText: '日', monthText: '月', yearText: '年', //面板中年月日文字
			dateFormat: 'yy-mm-dd',
			onSelect: function (valueText, inst) {
				get_table();
	        }
		});
		
		datetime_elm = $('.datetime').mobiscroll()["time"]({
			lang: 'zh',
			display: 'bottom',
			minWidth: 64,	
			stepMinute:15,
			dateFormat: 'yy-mm-dd',
			onSelect: function (valueText, inst) {
				get_table();
	        }
		});
//	}
//	$('.datetime').eq(0).on("change", function(){
//		var requestDate = $(this).val();
//		if(!checkTime(requestDate)){
//			$(".datetime").val("");
//		}
//	});
	
			
	var selectObj=document.getElementById("select_num");
	for(var i=0;i<config.max_seat_num;i++){
		selectObj.options[i] = new Option(i+1, i+1); 
	}
	$("#select_num").val(config.seat_num_default);
	$("#input_num").html(selectObj.options[selectObj.options.selectedIndex].text);
	
	
	document.getElementById("select_num").onchange=function(){
		var obj= document.getElementById("select_num");
		$("#input_num").html(obj.options[obj.options.selectedIndex].text);
	}
	document.getElementById("select_type").onchange=function(){
		var obj= document.getElementById("select_type");
		table_id = $(this).val();
		$("#input_type").html(obj.options[obj.options.selectedIndex].text);
		if(parseFloat(config.table_fee)>0&obj.value==2){
			$(".line.mh").show();
		}
		else{
			$(".line.mh").hide();
		}
	}
	
});


function get_table()
{

	var date = $(date_elm).val();
	var time = $(datetime_elm).val();
	var html = '<option value="0">不限</option>';
	$.post(config.GetTableURL, {'date':date, 'time':time}, function(response){
		if (response.errcode == 0){
			$.each(response.data, function(i, data){
				html += '<option value="' + data.pigcms_id + '">' + data.name + '(' + data.num + '人座)</option>'
			});
		} else {
			alert(response.msg);
		}
		$('#select_type').html(html);
	}, 'json');
}
function showinfo(){
	//editInfo:{id:'',date:'',time:'',num:2,seattype:1,tel:"",name:'',sex:0,mark:""}
	if (config.dishes_status==1) {
		document.getElementById("tel").value=config.editInfo.tel;
		document.getElementById("name").value=config.editInfo.name;	
		if(config.editInfo.sex==0) document.getElementById("sex").checked=true;
	}
	document.getElementById("mark").value=config.editInfo.mark;
	
	$("#select_num").val(config.editInfo.num);
	$("#input_num").html(config.editInfo.num);
	
	var obj= document.getElementById("select_type");
	$("#select_type").val(config.editInfo.seattype);
	$("#input_type").html($("select[name=seat] option[value='"+$('#select_type').val()+"']").text());

	
	$(".date").val(config.editInfo.date);
	$("#select_time .datetime").val(config.editInfo.time);
}
function _sort(){
	var List = config.businessHours;
	if(List.length>1){
		var sDate = $(date_elm).val()
		for(var i=0;i<List.length;i++){
			for(var j=i;j<List.length;j++){
				var b_str =sDate+" "+List[i]["stime"];
				var a_str =sDate+" "+List[j]["stime"];
				var B_c_time_date = new Date(b_str);
				var A_c_time_date = new Date(a_str);
				if(B_c_time_date<A_c_time_date){
					var temp=List[i];
					List[i]=List[j];
					List[j]=temp;
				}
			}
		}
	}
	return List;
}
function checkTime(time){
	var date=$(".date").val();
	var c_time = new Date();
	var arr=date.split("-")
	var _date_year=c_time.setFullYear(arr[0],arr[1],arr[2]);
	var arr1= time.split(":");
	c_time.setHours(arr1[0],arr1[1])
	
	time=new Date(_date_year);
	var _h = parseInt(arr1[0]);
	var _m = parseInt(arr1[1])+15;
	if(_m>=60){
		_h+=1;
		_m=_m-60;
	}
	if(_h>23){
		_h=23;
		_m=59;
	}
	time.setHours(_h,_m);
	if(c_time< new Date()){
		alert("预定时间已经过期");
		return false;
	}
	var businessHours=_sort();		
	var isInBs=false;
	var str = "";
	for(var i=0;i<businessHours.length;i++){
		var _arr= businessHours[i].stime.split(":");
		var _arr1= businessHours[i].etime.split(":");
		var s =new Date(_date_year);
		s.setHours(_arr[0],_arr[1])
		var e=new Date(_date_year);
		e.setHours(_arr1[0],_arr1[1])
		var _str = ""+businessHours[i].stime+"-"+businessHours[i].etime+",";
		str=str+_str;
		if(time>=s & time<=e ){
			isInBs=true;
		}
	}
	if(isInBs){
		return isInBs;
	}else{
		alert("不在营业时间:"+str.substring(0,str.length-1)+"内，请重新选择");
		return isInBs;
	}
		
}
var isajax = false;
function submit_F() {
	if (isajax == true) return false;
	isajax = true;
	var num_elm = document.getElementById("input_num");
	var type_elm = document.getElementById("select_type");
	var num = num_elm.innerHTML;
	var seat_type = table_id;
	var mark = document.getElementById("mark").value;
	
	var tel = '',  name = '', address = '', sex = 1, date = '', time = '';
	if (config.dishes_status==1) {
		var date = $(date_elm).val();
		var time = $(datetime_elm).val();
		var tel_elm = document.getElementById("tel");
		var name_elm = document.getElementById("name");
		var address_elm = document.getElementById("address");
		tel = tel_elm.value;
		name = name_elm.value;
		address = address_elm.value;
		if(document.getElementById("sex").checked) sex=2;
	}
	if (config.utype == 1) {
		if(isNull(date) && config.dishes_status == 1) {
			alert("请选择预约日期");
			isajax = false;
			return false;
		} else if(isNull(time) && config.dishes_status == 1) {
			alert("请选择预约时间");
			isajax = false;
			return false;
		} else if(isNull(tel)) {
			if (config.dishes_status == 1) {
				alert("请输入手机号码");
				isajax = false;
				return false;
			} 
		} else if(!(/^1[3|4|5|7|8][0-9]\d{4,8}$/.test(tel))){ 
			if (config.dishes_status == 1) {
				alert("请输入正确手机号码");
				isajax = false;
				return false;
			}
		} else if(isNull(name)){
			if (config.dishes_status == 1) {
				alert("请输入您的姓名");
				isajax = false;
				return false;
			}
		} else {
			
		}
	}
	//editInfo:{id:'',date:'',time:'',num:2,seattype:1,tel:"",name:'',sex:0,mark:""},//修改预订信息
	config.editInfo.date=date;
	config.editInfo.time=time;
	config.editInfo.num=num;
	config.editInfo.seattype=seat_type;
	config.editInfo.tel=tel;
	config.editInfo.name=name;
	config.editInfo.sex=sex;
	config.editInfo.mark=mark;
	config.editInfo.address=address;
	layer.open({
        type: 2,
        //shade: false,
        time: 20
        //content: '加载测试中…',
    });
	var isdeposit = 0;
	if ($('.paytypediv input[name=isdeposit]:checked').val() != undefined) {
		isdeposit = $('.paytypediv input[name=isdeposit]:checked').val();
	}
	$.ajax({
		type: "POST",
		url: config.postURL,
		data: {
			id:config.editInfo.id,
			date:config.editInfo.date,
			time:config.editInfo.time,
			num:config.editInfo.num,
			seattype:config.editInfo.seattype,
			tel:config.editInfo.tel,
			name:config.editInfo.name,
			address:config.editInfo.address,
			sex:config.editInfo.sex,
			mark:config.editInfo.mark,	
			table_fee:config.table_fee,
			is_reserve:config.dishes_status,
			order_sn:config.order_sn,
			isdeposit:isdeposit,
			utype:config.utype
		},
		//async:true,
		success: function(res){
			layer.closeAll();
			isajax = false;
			if(res.status==0) {
				layer.open({
			        time: 2,
			        content: res.info
			    });
				if(res.url != ""){
					window.location.href=res.url;
				}
			} else {
				window.location.href=res.url;
			}
		},
		dataType: "json"
	});				
}
function isNull( str ){ 
	if ( str == "" ) return true; 	
	var regu = "^[ ]+$"; 
	var re = new RegExp(regu); 			
	return re.test(str); 
}