var t_DiglogX=0;
var t_DiglogY=0;
var t_DiglogW=0;
var t_DiglogH=0;
var xmlHttp="";
/****************************去全角、半角空格*********************************/
/**
*去除头部和尾部空格*obj参数：含有string的对象*返回值:去除了空格后的对象*/
function trimspace(obj)
{
	String.prototype.Trim = function()
	{
		return this.replace(/(^\s*)|(\s*$)/g, "");
	}			
	obj.value = obj.value.Trim();
}
function display(kk)
{
	$$("floater"+kk).style.display="none";
}
/****************************测字符串的长度*********************************/
/**
*去除头部和尾部空格*obj参数：strValue，要测试的字符串；len，最大长度,msg出错信息
*返回值:大于最大值，返回false；负责返回true。*/
function checkStrLen(strValue,len,msg)
{
    var newvalue = strValue.replace(/[^\x00-\xff]/g, "**");  
    var length = newvalue.length;
    if(msg==null)
    {
     msg="您";
    }
    if(length>len)
    {
        alert(msg+"输入太长了。");
        return false;
    } 
    else
    {
        return true;
    }
}
function showvideolist(tt){
	if(tt=="1"){
		$$("showvidelist0").className="";
		$$("showvidelist1").className="selected";
		$$('tuijianvideo').style.display='none';$$('remenvideo').style.display='';	
	}
	else{
		$$("showvidelist0").className="selected";
		$$("showvidelist1").className="";
		$$('tuijianvideo').style.display='';$$('remenvideo').style.display='none';	
	}
}
function showxieyi(id){
	 if($$(id).style.display==''){
		 $$(id).style.display='none';}
	 else{$$(id).style.display='';}
}
function itMouse()
{
	var layerid=document.getElementById("city-pop");
	if(layerid.style.display=="none")
	{
		layerid.style.display="block";
	}
	else{
		layerid.style.display="none";	
	}
}

//密码强度检测 Begin =======================
function CharMode(iN){
	if (iN>=48 && iN <=57) //数字
		return 1;
	if (iN>=65 && iN <=90) //大写字母
		return 2;
	if (iN>=97 && iN <=122) //小写
		return 4;
	else
		return 8; //特殊字符
}

function checkStrong(sPW){
	if (sPW.length<=4)
		return 0;  //密码太短
	Modes=0;
	for (i=0;i<sPW.length;i++){
		Modes|=CharMode(sPW.charCodeAt(i));
	}
	return bitTotal(Modes);
}

function bitTotal(num){
	modes=0;
	for (i=0;i<4;i++){
		if (num & 1) modes++;
		num>>>=1;
	}
	return modes;
}

function pwStrength(pwd){
	O_color="#eeeeee";
	L_color="#FF0000";
	M_color="#FF9900";
	H_color="#33CC00";
	if (pwd==null||pwd==''){
		Lcolor=Mcolor=Hcolor=O_color;
	}
	else{
		$("#safety").show();
		S_level=checkStrong(pwd);
		switch(S_level)	 {
			case 0:
			case 1:
				Lcolor=L_color;
				$("#safety").removeClass("safety_2");
				$("#safety").removeClass("safety_3");
				$("#safety").addClass("safety_1");
				Mcolor=Hcolor=O_color;
				break;
			case 2:
				Lcolor=Mcolor=M_color;
				$("#safety").removeClass("safety_1");
				$("#safety").addClass("safety_2");
				Hcolor=O_color;
				break;
			default:
				$("#safety").addClass("safety_3");
				Lcolor=Mcolor=Hcolor=H_color;
				}
	 }
	return;
}
//密码强度检测 End =======================
function chongaddpay(o){
	if(o.shu.value==""){
		alert("对不起,您正确输入!");
		o.shu.focus();
		return false;
	}
//	var url="../pay/alipaydefault.aspx";

	//	document.Form1.action= "../pay/default.aspx";
		//"?action=consumesave";		

}
function judgeString(str){
 	var len = str.length;
	var tt=0;
    for(var i=0;i<len;i++){
       var txt = str.charCodeAt(i);
       if(txt>128){     //ascii码大于128的是汉字
          tt= tt+2;
       } else{
          tt= tt+1;
       }
    }
 	return tt;
}
function isRightEmail(email) {
   var re="^[\s]*[a-zA-Z0-9._%-]+@[a-zA-Z0-9._%-]+\.[a-zA-Z0-9]{2,4}[\s]*$";
   if(email.match(re)==null)
       return false;
   else
       return true;
}
function isRightPW(pwd) {
   var re= "^[a-zA-Z0-9_]*$";
   if (pwd.match(re) == null)
      return false;
   else
     return true;
}
function CheckUserName(namet){
	var lent=judgeString(namet);
	if(lent < 3 || lent > 15)
		return false;
	else
		return true;
}
function CheckUserPwd(namet){
	var lent=judgeString(namet);
	if(lent < 6 || lent > 32)
		return false;
	else
		return true;
}
function requestischecklogintttt()
{
	if(xmlHttp.readyState==4)
	{
		if(xmlHttp.status==200)
		{
			var output=xmlHttp.responseText;
			if(output=="@agan@0")
			{
				$('#islogin').fadeIn();
				$('#qqlogin').hide();
			}
			else if(output=="@agan@1")
			{
				$('#islogin').fadeIn();
				$('#qqlogin').show();
			}
			else if(output.indexOf("@agan@")!=-1 && output.length>10)
			{
				$('#islogin').fadeIn();
				document.getElementById("islogin").innerHTML =output.replace("@agan@","");
			}
			else
			{
				if(output.indexOf("toplogin")!=-1){
					output =output.replace("toplogin","");	
					$('#login_node').fadeIn();
				}
				if(output.indexOf("@feng@")!=-1){
					document.getElementById("islogin").innerHTML =output.split("@feng@")[0];
					if(document.getElementById("isloginother")){
						document.getElementById("isloginother").innerHTML =output.split("@feng@")[1];
					}
				}
				else{
					if(parent.document.getElementById("islogin"))
						parent.document.getElementById("islogin").innerHTML = output;
					else
						document.getElementById("islogin").innerHTML = output;
				}
			}
		}
	}
}
function copyToClipBoard(){
    var clipBoardContent="";
    clipBoardContent+=document.title;
    clipBoardContent+="";
    clipBoardContent+=window.location.href;
    window.clipboardData.setData("Text",clipBoardContent);
    alert("复制成功，请粘贴到你的QQ/MSN上推荐给你的好友");
}

function copyTo(val,tt){
	window.clipboardData.setData("Text",val);
	if(tt=="")
		tt="QQ/MSN";
    alert("复制成功，请粘贴到你的"+tt+"上推荐给你的好友");
}

﻿/****************************通过ID号取得对象***********************************/
function $$(id){
  if (typeof(id)=="object")
        return id;
    if (typeof(id)=="string")
	{
        var obj = document.getElementById(id);
        if(obj != null)
            return obj;
        obj = document.getElementsByName(id);
        if(obj != null && obj.length > 0)
            return obj[0];
    }
    return null;
}
function getCookie(name)
{
	 var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
     if(arr != null) return decodeURIComponent(arr[2]); return null;
}
function setCookie(name,value)
{
  var Days = 30; //此 cookie 将被保存 30 天
  var exp  = new Date();    //new Date("December 31, 9998");
  exp.setTime(exp.getTime() + Days*24*60*60*1000);
  document.cookie = name + "="+ encodeURIComponent(value) +";expires="+ exp.toGMTString()+";path=/" ;
}
function delCookie(name)
{
  var exp = new Date();
  exp.setTime(exp.getTime() - 1);
  var cval=getCookie(name);
  if(cval!=null) document.cookie=name +"="+cval+";expires="+exp.toGMTString();
}
function loadss(){
	if(getCookie("themeIndexTom")){
		setTtCss(getCookie("themeIndexTom"))
	}
}
function setTtCss(index){
	if(document.getElementById('themecss'))
	{
		if(window.location.href.indexOf("learn/")!=-1){
			document.getElementById('themecss').href = "/learn/template/default/skin/theme/theme" + index + ".css?"+new Date();		
		}
		else{
			document.getElementById('themecss').href = "/template/default/skin/theme/theme" + index + ".css?t="+new Date();	
		}
	}
}
function GetXmlHttpObject(handler)
{ 
	var objXmlHttp;
	//开始初始化XMLHttpRequest对象
	if(window.XMLHttpRequest) { //Mozilla 浏览器
			objXmlHttp = new XMLHttpRequest();
			if (objXmlHttp.overrideMimeType) {//设置MiME类别
				objXmlHttp.overrideMimeType("text/xml");
			}
	}
	else if (window.ActiveXObject) { // IE浏览器
			try {
				objXmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
			} catch (e) {
				try {
					objXmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (e) {}
			}
	}
	if (!objXmlHttp) { // 异常，创建对象实例失败
			window.alert("不能创建XMLHttpRequest对象实例.");
			return false;
	}
	objXmlHttp.onreadystatechange = handler;
	return objXmlHttp
}

function checkcontact(o)
{
	if(o.chrname.value.length<2){
		alert("对不起，请输入您的称呼！");
		o.chrname.focus();
		return false;
	}
	if(o.chrtel.value.length<7){
		alert("对不起，请输入联系方式，至少七位！");
		o.chrtel.focus();
		return false;
	}
	if(o.chrmark.value.length<2){
		alert("对不起，请输入留言内容！");
		o.chrmark.focus();
		return false;
	}
	if(o.chrmark.value.length>300){
		alert("对不起，留言内容不能大于300字！");
		o.chrmark.focus();
		return false;
	}
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="../request.aspx?action=addqiye&qiyeid="+encodeURIComponent(o.qiyeid.value)+"&chrtel="+encodeURIComponent(o.chrtel.value)+"&chrmark="+encodeURIComponent(o.chrmark.value)+"&chrname="+encodeURIComponent(o.chrname.value) ;
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestaddqiye;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	return false;
	function requestaddqiye()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="1"){
					alert("恭喜您,留言成功，我们会马上和您取得联系!");
					window.location.href= window.location.href;
				}
				else
					alert(xmlhttp.responseText);	
			}
		}
	}
}
function sendquan(aa)
{
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="../request.aspx?action=sendquan&orderid="+aa ;
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestsendquan;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	return false;
	function requestsendquan()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="1"){
					
					MSGwindowShow('tg','0','恭喜您,短信发送成功!');
				}
				else	
					MSGwindowShow('tg','0',xmlhttp.responseText);
			}
		}
	}
}
function setcommentnews(aa,bb,o){
	var xmlhttp=createxmlhttp();
	
	
	if($("#revert"+aa).attr('data-isclose') === '1'){alert('对不起，您已经支持或反对过一次！');return false;}
	if(!xmlhttp){
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="../request.aspx?action=setcommentnews&aa="+aa+"&bb="+bb ;
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestsetcommentnews;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	
	function requestsetcommentnews(){
		if(xmlhttp.readyState==4){
			if(xmlhttp.status==200){
				
				
				$("#revert"+aa).html(xmlhttp.responseText).attr('data-isclose','1');	
			}
		}
	}
	
	return false;
}
function checkyuyue()
{
	if($$("chrname").value==""){
		alert("对不起，请输入您的姓名！");
		$$("chrname").focus();
		return false;
	}
	if($$("chrtel").value==""){
		alert("对不起，请输入您的联系电话！");
		$$("chrtel").focus();
		return false;
	}
	if($$("chrmark").value==""){
		alert("对不起，请输入您给商家的留言！");
		$$("chrmark").focus();
		return false;
	}
	if($$("chrcode").value==""){
		alert("对不起，请输入验证码！");
		$$("chrcode").focus();
		return false;
	}
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="../request.aspx?action=yuyue&chrname="+encodeURIComponent($$("chrname").value)+"&chraddress="+encodeURIComponent($$("chraddress").value)+"&chrtel="+encodeURIComponent($$("chrtel").value)+"&chrcode="+encodeURIComponent($$("chrcode").value);
	url = url +"&chremail="+encodeURIComponent($$("chremail").value) +"&chrmark="+encodeURIComponent($$("chrmark").value) +"&shopid="+encodeURIComponent($$("shopid").value) ;
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestyuyue;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	return false;
	function requestyuyue()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="1"){
					alert("恭喜您,预约成功，我们会马上和您取得联系!");
					window.location.href= window.location.href;
				}
				else
					alert(xmlhttp.responseText);	
			}
		}
	}
}
function dingyuetg(obj)
{
	var myreg = /^(13|15|18|14)[0-9]{9}$/;
	if(! myreg.test(obj.value) )
	{
		alert("对不起，请正确输入手机号！");
		obj.focus();
		return false;	
	}
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="../request.aspx?action=dingyue&tt="+obj.value;
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestdatasetarticle;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	return false;
	function requestdatasetarticle()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="1"){
					alert("手机订阅成功!");
				}
				else
					alert(xmlhttp.responseText);	
			}
		}
	}
}

function setjobarticle(tt,id){
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="../request.aspx?action=dingjobnews&aa="+tt+"&bb="+id;
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestdatasetarticle;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	return false;
	function requestdatasetarticle()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="1")
				{
					if(tt==1)
					{
						$("#cainews").html( (parseInt($("#cainews").html())+1) );
					}
					else
					{
						$("#dingnews").html( (parseInt($("#dingnews").html())+1) );
					}
				}
				else
					alert(xmlhttp.responseText);	
			}
		}
	}
}
function setqiyearticle(tt,id){
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="../request.aspx?action=dingqiyenews&tt="+tt+"&id="+id;
	var  Digital=new  Date();
		Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestsetqiyearticle;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	return false;
	function requestsetqiyearticle()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="1"){
					alert("恭喜您,表态成功!");
					window.location.href= window.location.href;
				}
				else
					alert(xmlhttp.responseText);	
			}
		}
	}
}
function sethousearticle(tt,id){
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="../request.aspx?action=dinghouse&tt="+tt+"&id="+id;
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestdatasetarticlehouse;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	return false;
	function requestdatasetarticlehouse()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="1"){
					if(tt==1)
					{
						$("#cainews").html( (parseInt($("#cainews").html())+1) );
					}
					else
					{
						$("#dingnews").html( (parseInt($("#dingnews").html())+1) );
					}
				}
				else
					alert(xmlhttp.responseText);	
			}
		}
	}
}
function setxianhua(id,shopid,colname,pageno){
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="../request.aspx?action=xianhua&id="+id;
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestdatasetarticle;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	return false;
	function requestdatasetarticle()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="1"){
					getdata("../request.aspx?action=showcompanycomment&colname="+colname+"&PageNo="+pageno+"&shopid="+shopid,"showcomment","showcomment");
					//alert("恭喜您,表态成功!");
					//window.location.href= window.location.href;
				}
				else
					alert(xmlhttp.responseText);	
			}
		}
	}
}
function setvideoxianhua(id,tt){
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="../request.aspx?action=videoxianhua&id="+id+"&tt="+tt;
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestdatasetarticle;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	return false;
	function requestdatasetarticle()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="1"){
					//window.location.href= window.location.href;
					if(tt=="0")
						$$("xianhuashu").innerHTML=parseInt($$("xianhuashu").innerHTML)+1;
					else
						$$("jidanshu").innerHTML=parseInt($$("jidanshu").innerHTML)+1;
				}
				else
					alert(xmlhttp.responseText);	
			}
		}
	}
}

function setshoparticle(tt,id){
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="../request.aspx?action=shopding&tt="+tt+"&id="+id;
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestdatasetshoparticle;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	return false;
	function requestdatasetshoparticle()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="1"){
					alert("恭喜您,表态成功!");
					window.location.href= window.location.href;
				}
				else
					alert(xmlhttp.responseText);	
			}
		}
	}
}

function checklogin(tt,o){
	if(o.chrnamet){
		if(!CheckUserName(o.chrnamet.value)){
			alert("对不起,用户名长度必须是3到15位!");
			o.chrnamet.focus();
			return false;
		}
	}
	if(o.chrpwdt){
		if(!CheckUserPwd(o.chrpwdt.value)){
			alert("对不起,密码至少是是6到32位!");
			o.chrpwdt.focus();
			return false;
		}
	}
	var str3="false";
	//if(o.chrcodet){
		//str3 = "true";
	//}
	var url=tt+"request.ashx?action=login&str1="+(o.chrnamet.value)+"&str2="+(o.chrpwdt.value)+"&str3="+(str3);
	if(tt.indexOf("www.") !=-1 && window.location.href.indexOf("www.")==-1 && window.location.href.indexOf("index.htm")!=-1)
	{
		url="request.ashx?action=login&str1="+(o.chrnamet.value)+"&str2="+(o.chrpwdt.value)+"&str3="+(str3);
	}
	//SP8加的
	url="/request.ashx?action=login&str1="+(o.chrnamet.value)+"&str2="+(o.chrpwdt.value)+"&str3="+(str3);
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	xmlhttp.onreadystatechange=requestdatalogin;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	return false;
	function requestdatalogin()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				var data= xmlhttp.responseText;
				if(data=="1")
				{
					var   f=document.createElement("IFRAME")   
					f.height=0;   
					f.width=0   
					f.src="../other.aspx?action=login"   
					document.body.appendChild(f) ;
					setTimeout("alert(\"恭喜您,登陆成功!\");window.location.href= window.location.href;",2500);
				}
				else if(data.indexOf("http://") != -1 )
				{
					alert("您的账户未完成激活，请先完成激活！");
					window.location.href=data;
				}
				else
				{
					alert(data);	
				}
			}
		}
	}
}

function setmoban(aa,bb)
{
	if(confirm("您确认要选择此企业模板吗？"))
	{
		window.parent.document.getElementById('mobanName').innerHTML=bb;
		window.parent.document.getElementById('mobanid').value=aa;
		parent.LoginHide();
		/*
		if(parent.window.document.getElementById("mobanid"))
		{
			parent.window.document.getElementById("mobanid").value=aa;
			parent.window.document.getElementById("selectmobanshow").innerHTML=cc;
			
		}
		else{
			
			var url= "/other.aspx?action=moban&_clienttype=2&mobanid="+aa;
			
			var  Digital=new  Date();
			Digital=Digital+40000;
			url=url+"&k="+(Digital);
			var xmlhttp=createxmlhttp();
			if(!xmlhttp)
			{
				alert("你的浏览器不支持XMLHTTP！！");
				return;
			}
			xmlhttp.onreadystatechange=requestsetmoban;
			xmlhttp.open("GET",url,true);
			xmlhttp.send(null);
			return false;
			function requestsetmoban()
			{
				if(xmlhttp.readyState==4)
				{
					if(xmlhttp.status==200)
					{
						if(xmlhttp.responseText=="1")
						{
							alert("模板设置成功，您可以刷新预览。");
							parent.LoginHide();
						}
						else
						{
							alert(xmlhttp.responseText);	
						}
					}
				}
			}
		}*/
	}
}
function checkservice(o){
	//console.info('00000');
	if($$("chrmark").value==""){
		alert("对不起,请输入留言内容!");
		$$("chrmark").focus();
		return false;
	}
	if($$("chrmark").value.length>600){
		alert("对不起,留言内容不得大于300字!");
		$$("chrmark").select();
		return false;
	}
	if($$("chrcode").value.length!=5){
		alert("对不起,请正确输入验证码!");
		$$("chrcode").focus();
		return false;
	}
	
	var k = $('#isLogin').val();
	if(k==='0'){ alert('对不起,请先登录再发表留言!'); return false;}
	var chrname="",chrpwd="";
	addcomment(chrname,chrpwd,$$("chrcode").value,$$("chrmark").value);
	return false;
}
function addcomment(chrname,chrpwd,chrcode,chrmark){
	var url="../request.aspx?action=addcomment&chrname="+encodeURIComponent(chrname)+"&chrpwd="+encodeURIComponent(chrpwd)+"&chrcode="+encodeURIComponent(chrcode)+"&chrmark="+encodeURIComponent(chrmark);
	//window.open(url);
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+encodeURIComponent(Digital);
	xmlHttp=GetXmlHttpObject(requestaddshopcomment)
	xmlHttp.open("get", url , true)
	xmlHttp.send(null)
}

function requestaddshopcomment()
{
	if(xmlHttp.readyState==4)
	{
		if(xmlHttp.status==200)
		{
			var tt=window.location.href;
			if(tt.indexOf("#")>0)
				tt =tt.substring(0,tt.indexOf("#"))
			if(xmlHttp.responseText==0)
			{
				alert("恭喜您，发布成功，请等待管理员审核！");	
				window.location.href=tt;
			}
			else if(xmlHttp.responseText==1)
			{
				alert("恭喜您，发布成功！");	
				window.location.href=tt;
			}
			else{
				alert(xmlHttp.responseText);
			}
		}
	}
}

function checkshopcomment(){
	var commentyouke=$$("commentyouke");
	if(commentyouke)
	{
		if(commentyouke.checked)
			commentyouke="1";
		else
			commentyouke="0";
	}
	else
		commentyouke="0";
	
	if($$("code")){
		trimspace($$("code"))
		if($$("code").value==""){
			$$("code").focus();
			alert("对不起，请输入验证码！");
			return false;
		}	
	}
	trimspace($$("chrmark"))
	if($$("chrmark").value==""){
		$$("chrmark").focus();
		alert("对不起，请输入回复内容！");
		return false;
	}
	if($$("chrmark").value.length>300){
		$$("chrmark").select();
		alert("对不起，回复内容请控制在300字内！");
		return false;
	}
	var chrname="",chrpwd="";
	
	var k = $('#isLogin').val();
	var k2 = $('#commentyouke').prop('checked');
	if(k==='0'&&k2===false){ alert('对不起,请先登录再发表留言!'); return false;}
	
	var agan="0";
	if($$("agan")){
		if($$("agan").value!="12")
			agan="1";	
		else
			agan="12";
	}
	addshopcomment(chrname,chrpwd,$$("code").value,commentyouke,$$("chrmark").value,$$("shopid").value,agan);
	return false;
}

function addshopcomment(chrname,chrpwd,code,commentyouke,chrmark,shopid,agan)
{
      	var url="../request.aspx?action=addshopcomment&chrname="+encodeURIComponent(chrname);
		url =url+"&chrpwd="+encodeURIComponent(chrpwd)+"&code="+encodeURIComponent(code)+"&commentyouke="+encodeURIComponent(commentyouke)+"&chrmark="+encodeURIComponent(chrmark)+"&shopid="+encodeURIComponent(shopid)+"&agan="+encodeURIComponent(agan);
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+encodeURIComponent(Digital);
		xmlHttp=GetXmlHttpObject(requestaddshopcomment)
		xmlHttp.open("get", url , true)
		xmlHttp.send(null)
}
function showmobanid(tt){
	$$("mask").style.display='';
	Demo_login('<div align=center><iframe src="/other.aspx?action=moban&PageSize=6&_clienttype=2" scrolling="no" frameBorder=0 width=600 height=515></iframe></div>',600,515,600,515)
}
function showduihuan(val){
	var is_login = $('#isLogin').val();
	if(is_login === '1'){
		$$("mask").style.display='';
		Demo_login('<div align=center><iframe src="duihuan.aspx?id='+val+'&tt='+new Date()+'" scrolling="no" frameBorder=0 width=944 height=768></iframe></div>',944,768,944,768)
	}else{
		alert('对不起，请先登录再申请兑换！');
		window.location.href=nowdomain+"member/login.html?from="+(encodeURIComponent(window.location.href));
		return false;
	}
}
function Demo_login(string,ow,oh,w,h){
	 ShowDiv=string;
	 DialogShow(ShowDiv,ow,oh,w,h);	
	 var objDialog = document.getElementById("DialogMove");
	  var lstd = document.getElementById("lstd");
	 
}
function DialogShow(showdata,ow,oh,w,h){
	 var objDialog = document.getElementById("DialogMove");
	 if (!objDialog) 
	 objDialog = document.createElement("div");
	 t_DiglogW = ow;
	 t_DiglogH = oh;
	 DialogLoc();
	 objDialog.id = "DialogMove";
	 var oS = objDialog.style;
	 oS.display = "block";
	 oS.top = t_DiglogY + "px";	
	 oS.left = t_DiglogX + "px"; 
	 oS.margin = "0px";
	 oS.padding = "0px";
	 oS.width = w + "px";
	 oS.height = h + "px";
	 oS.position = "absolute";
	 oS.zIndex = "999";
	 oS.background = "#FFF";
	 oS.border = "solid #ddd 1px";
	 objDialog.innerHTML = showdata;
	 document.body.appendChild(objDialog);
	 delselect();
}
function DialogLoc(){
	 var dde = document.documentElement;
	 if (window.innerWidth){
	 	var ww = window.innerWidth;
		var wh = window.innerHeight;
		var bgX = window.pageXOffset;
		var bgY = window.pageYOffset;	
	 }else{	 	
		var ww = dde.offsetWidth;
		var wh = dde.offsetHeight;
		var bgX = dde.scrollLeft;
		var bgY = dde.scrollTop;	  
	 }
	 t_DiglogX = (bgX + ((ww - t_DiglogW)/2));
	 t_DiglogY = (bgY + ((wh - t_DiglogH)/2));
}
function LoginHide()
{
	ScreenClean();
	var objDialog = document.getElementById("DialogMove");
	 if (objDialog)
	 {
		 objDialog.style.display = "none";
		 $$("mask").style.display='none';
	 }
}

function ScreenClean(){
	 var objScreen = document.getElementById("ScreenOver");
	 if (objScreen)
	 objScreen.style.display = "none";
	 var allselect = document.getElementsByTagName("select");
	 for (var i=0; i<allselect.length; i++) 
	 allselect[i].style.visibility = "visible";
}

function delselect(){
	var allselect = document.getElementsByTagName("select");
	 for (var i=0; i<allselect.length; i++) 
	 allselect[i].style.visibility = "hidden";	
}
function showselect(){
	var allselect = document.getElementsByTagName("select");
	 for (var i=0; i<allselect.length; i++) 
	 allselect[i].style.visibility = "visible";	
}
function ScreenConvert(str){
	 var browser = new Browser();
	 var objScreen = document.getElementById("ScreenOver");
	 if(!objScreen) 
	 var objScreen = document.createElement("div");
	 var oS = objScreen.style;
	 objScreen.id = "ScreenOver";
	 oS.display = "block";
	 oS.top = oS.left = oS.margin = oS.padding = "0px";
	 if (document.body.scrollHeight)	{
	 	if(document.body.scrollHeight>document.body.clientHeight){
	 		var wh = document.body.scrollHeight + "px";	
	 	}else{
	 		var wh = document.body.clientHeight + "px";
	 	}	 
	 }else if (window.innerHeight){
	 var wh = window.innerHeight + "px";
	 }else{
	 var wh = "100%";
	 }
	 oS.width = "100%";	 
	 oS.height = wh;
	 oS.position = "absolute";
	 oS.zIndex = "3";
	 if ((!browser.isSF) && (!browser.isOP)){
	 oS.background = "black";
	 }else{
	 oS.background = "black";
	 }
	 oS.filter = "alpha(opacity=50)";
	 oS.opacity = 40/100;
	 oS.MozOpacity = 40/100;
	 document.body.appendChild(objScreen);
	 var allselect = document.getElementsByTagName("select");
	 for (var i=0; i<allselect.length; i++) 
	 allselect[i].style.visibility = "hidden";
}
var Set;
var IsRoll = false;
var DefaultRoll = false;//程序根据ChannelType输出默认是否循环
var CookieValue = getCookie('myRollCookie');
if(CookieValue==null)
	CookieValue=0;
if (CookieValue==0){
	IsRoll = DefaultRoll;
}else{
	if (CookieValue.toString() == '1'){
		IsRoll = true;
	}else{
		IsRoll = false;
	}
}

function NextPage(){
	var NextImgUrl=document.getElementById('nextone').value;
	if (IsRoll){
        var mytime = 5;
		Set = setInterval("window.location.href='"+NextImgUrl+"'",mytime*1000);
	}
}
function stop(){
		IsRoll=false;
		setCookie('myRollCookie','0');
		clearInterval(Set);
		document.getElementById('huangdeng').innerHTML = '<a href="javascript:play();" class="a_3" >幻灯片</a>';
}
function play(){
	IsRoll = true;
	var NextImgUrl=document.getElementById('nextone').value;
		document.getElementById('huangdeng').innerHTML = '<a href="javascript:stop();" class="a_3" >停止播放</a>';
	if (IsRoll){
		setCookie('myRollCookie','1');
		time = 5;	
		SetRoll(false);
		IsRoll = true;
		NextPage();
    }else{
		clearInterval(Set);
		setCookie('myRollCookie','0');
		SetRoll(true);
		IsRoll = false;
	}
}

function SetRoll(cRoll){
	
}

function checkcompanycomment(){
	var commentyouke=$("input[name='commentyouke']").prop('checked');
	
	if(commentyouke){
		commentyouke="1";
	}else{
		commentyouke="0";
	}
	
	if($("input[name='code']").length>0){
		var code = $("input[name='code']");
		//trimspace(code.val())
		if(code.val()==""){
			code.focus();
			alert("对不起，请输入验证码！");
			return false;
		}	
	}
	var chrmark = $("textarea[name='chrmark']");
	//trimspace(chrmark.val());
	if(chrmark.val()==""){
		chrmark.focus();
		alert("对不起，请输入评论！");
		return false;
	}
	
	if(chrmark.val().length>300){
		chrmark.select();
		alert("对不起，评论不能大于300汉字！");
		return false;
	}
	
	var chrname="";
	
	var chrpwd="";
	if(commentyouke === '0' && $('#isLogin').val() !=='1'){
		alert('对不起，请先登录再发表评论！');
		return false;
	}
	
	
	addcompanycomment(chrname,chrpwd,code.val(),chrmark.val(),commentyouke,$("input[name='shopid']").val(),$("input[name='total_score']").val(),$("input[name='score_1']").val(),$("input[name='score_2']").val(),$("input[name='score_3']").val(),$("input[name='score_4']").val());
	return false;
}

function addcompanycomment(chrname,chrpwd,code,chrmark,commentyouke,shopid,score,score1,score2,score3,score4)
{
      	var url="../request.aspx?action=addcommpanycomment&chrname="+encodeURIComponent(chrname);
		url =url+"&chrpwd="+encodeURIComponent(chrpwd)+"&commentyouke="+encodeURIComponent(commentyouke)+"&code="+encodeURIComponent(code)+"&chrmark="+encodeURIComponent(chrmark)+"&shopid="+encodeURIComponent(shopid)+"&score="+encodeURIComponent(score)+"&score1="+encodeURIComponent(score1)+"&score2="+encodeURIComponent(score2)+"&score3="+encodeURIComponent(score3)+"&score4="+encodeURIComponent(score4);
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+encodeURIComponent(Digital);
		xmlHttp=GetXmlHttpObject(requestaddshopcomment)
		xmlHttp.open("get", url , true)
		xmlHttp.send(null)
}
function echo(obj,html)
{
	$$(obj).innerHTML=html;
}
function fopen(obj)
{
	$$(obj).style.display="";
}
function fclose(obj)
{
	$$(obj).style.display="none";
}
function getdata(url,obj1,obj2)
{
		var xmlhttp=createxmlhttp();
		if(!xmlhttp)
		{
			alert("你的浏览器不支持XMLHTTP！！");
			return;
		}
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+(Digital);
		xmlhttp.onreadystatechange=requestdata;
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
		function requestdata()
		{
				fopen(obj1);
				echo(obj1,"正在加载数据，请稍等......");
				if(xmlhttp.readyState==4)
				{
					if(xmlhttp.status==200)
					{
						if(obj1!=obj2){fclose(obj1);};
						echo(obj2,xmlhttp.responseText);
					}
				}
			
		}
}

function showwybaoming(val,tt){
	if(tt=="1"){
		Demo_login('<div align=center><iframe src="/portal.php?c=activity&a=baoming&activeid='+val+'" scrolling="no" frameBorder=0 width=600 height=445></iframe></div>',600,445,600,445);
	}
	else{
		if(document.getElementById('isLogin').value !== '1'){
			window.location.href=nowdomain+"member/login.html?from="+(encodeURIComponent(window.location.href));
		}
		else{
			Demo_login('<div align=center><iframe src="/portal.php?c=activity&a=baoming&activeid='+val+'" scrolling="no" frameBorder=0 width=600 height=445></iframe></div>',600,445,600,445)	
		}
	}
	return false;
}

function showorder(val){
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var  Digital=new  Date();
	Digital=Digital+40000;
				if(document.getElementById('isLogin').value !=='1'){
					delCookie("aganparent");
					
					delCookie("nextcookie");
					
					window.location.href=nowdomain+"member/login.html?from="+(encodeURIComponent(window.location.href));
				}
				else{
					Demo_login('<div align=center><iframe src="../woyaoorder.html?id='+val+'&uuu=fdsa" scrolling="no" frameBorder=0 width=935 height=621></iframe></div>',935,621,935,621)	
				}
}


function delorder(id)
{
    if ( confirm("该操作将不可逆！\n您确定要取消订单吗？"))
    {
      	var url="../request.aspx?action=delorder&str1="+encodeURIComponent(id);
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+(Digital);
		xmlHttp=GetXmlHttpObject(requestdelordert)
		xmlHttp.open("get", url , true)
		xmlHttp.send(null)
    }
}

function requestdelordert()
{
	if(xmlHttp.readyState==4)
	{
		if(xmlHttp.status==200)
		{
			if(xmlHttp.responseText!=1)
			{
				alert(xmlHttp.responseText);	
			}else{
				alert("订单取消成功！");
				window.location.href="myorder.aspx";
			}
						
		}
	}
}
function delbudan(id)
{
    if ( confirm("该操作将不可逆！\n您确定要删除此补单吗？"))
    {
      	var url="../request.aspx?action=delbudan&str1="+encodeURIComponent(id);
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+(Digital);
		//window.open(url);
		xmlHttp=GetXmlHttpObject(requestdelbudan)
		xmlHttp.open("POST", url , true)
		xmlHttp.send(null)
    }
}

function requestdelbudan()
{
	if(xmlHttp.readyState==4)
	{
		if(xmlHttp.status==200)
		{
			if(xmlHttp.responseText!=1)
			{
				alert(xmlHttp.responseText);	
			}else{
				alert("补单删除成功！");
				window.location.href="myorder.aspx?action=bu";
			}
						
		}
	}
}

function delduihuan(id)
{
    if ( confirm("该操作将不可逆！\n您确定要取消此礼品兑换订单吗？"))
    {
      	var url="../request.aspx?action=delduihuan&str1="+encodeURIComponent(id);
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+(Digital);
		//window.open(url);
		xmlHttp=GetXmlHttpObject(requestdelduihuan)
		xmlHttp.open("POST", url , true)
		xmlHttp.send(null)
    }
}
function requestdelduihuan()
{
	if(xmlHttp.readyState==4)
	{
		if(xmlHttp.status==200)
		{
			if(xmlHttp.responseText!=1)
			{
				alert(xmlHttp.responseText);	
			}else{
				alert("礼品兑换订单取消成功！");
				window.location.href="myduihuan.aspx";
			}
						
		}
	}
}



function checkbudan(o){
	trimspace(o.chrshop);
	if(o.chrshop.value==""){
		alert("对不起,请填写消费商家！");
		o.chrshop.focus();
		return false;
	}
	if(o.xiaofeidate.value==""){
		alert("对不起,请选择消费时间！");
		o.xiaofeidate.focus();
		return false;
	}
	trimspace(o.username);
	if(o.username.value==""){
		alert("对不起,请填写消费人！");
		o.username.focus();
		return false;
	}
	trimspace(o.chrtel);
	if(o.chrtel.value==""){
		alert("对不起,请填写您的电话！");
		o.chrtel.focus();
		return false;
	}
	trimspace(o.xiaofei);
	if(o.xiaofei.value==""){
		alert("对不起,请填写消费金额！");
		o.xiaofei.focus();
		return false;
	}
	trimspace(o.chrmark);
	if(o.chrmark.value.length>200){
		alert("对不起,其他反馈限定200字！");
		o.chrmark.select();
		return false;
	}
	var url="../request.aspx?action=company&str1="+encodeURIComponent(o.chrshop.value);
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlHttp=GetXmlHttpObject(requestcompany)
	xmlHttp.open("POST", url , true)
	xmlHttp.send(null)
	return false;
}

function requestcompany()
{
	if(xmlHttp.readyState==4)
	{
		if(xmlHttp.status==200)
		{
			if(xmlHttp.responseText.length>10)
			{
				alert(xmlHttp.responseText);	
				document.getElementById("chrshop").select();
			}else{
				document.getElementById("shopid").value=xmlHttp.responseText;
				document.form1.submit();
			}
			
		}
	}
}


function delbaoming(id)
{
    if ( confirm("该操作将不可逆！\n您确定要取消此次报名吗？"))
    {
      	var url="../request.aspx?action=delbao&str1="+encodeURIComponent(id);
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+(Digital);
		xmlHttp=GetXmlHttpObject(requestdelbaoming)
		xmlHttp.open("POST", url , true)
		xmlHttp.send(null)
    }
}

function requestdelbaoming()
{
	if(xmlHttp.readyState==4)
	{
		if(xmlHttp.status==200)
		{
			if(xmlHttp.responseText!=1)
			{
				alert(xmlHttp.responseText);	
			}else{
				alert("报名取消成功！");
				window.location.href="myactive.aspx";
			}
						
		}
	}
}


function checkbaoming(o){
	trimspace(o.truename);
	if(o.truename.value==""){
		alert("对不起,请输入您的真实姓名！");
		o.truename.focus();
		return false;
	}
	trimspace(o.chrtel);
	if(o.chrtel.value==""){
		alert("对不起,请输入您的联系电话！");
		o.chrtel.focus();
		return false;
	}
	trimspace(o.chrmark);
	if(o.chrmark.value.length>200){
		alert("对不起,简短附言限定200字！");
		o.chrmark.select();
		return false;
	}
}

function delrevert(id)
{
    if ( confirm("该操作将不可逆！\n您确定要删除此条点评吗？"))
    {
      	var url="../request.aspx?action=delrevert&str1="+encodeURIComponent(id);
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+(Digital);
		xmlHttp=GetXmlHttpObject(requestdeldelrevertg)
		xmlHttp.open("POST", url , true)
		xmlHttp.send(null)
    }
}

function requestdeldelrevertg()
{
	if(xmlHttp.readyState==4)
	{
		if(xmlHttp.status==200)
		{
			if(xmlHttp.responseText!=1)
			{
				alert(xmlHttp.responseText);	
			}else{
				alert("删除点评成功！");
				window.location.href="myrevert.aspx";
			}
						
		}
	}
}


function delactive(id)
{
    if ( confirm("该操作将不可逆！\n您确定要删除此条自发活动吗？"))
    {
      	var url="../request.aspx?action=delactive&str1="+encodeURIComponent(id);
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+(Digital);
		xmlHttp=GetXmlHttpObject(requestdelactive)
		xmlHttp.open("POST", url , true)
		xmlHttp.send(null)
    }
}

function requestdelactive()
{
	if(xmlHttp.readyState==4)
	{
		if(xmlHttp.status==200)
		{
			if(xmlHttp.responseText!=1)
			{
				alert(xmlHttp.responseText);	
			}else{
				alert("删除自发活动成功！");
				window.location.href="myactive.aspx?action=my";
			}
						
		}
	}
}

//调整图片大小
var imageObject;
function ResizeImage(obj,MaxW,MaxH,flag)
{
    if(obj!=null) imageObject=obj;
    var state=imageObject.readyState;
    if(state!='complete')
    {
        setTimeout("ResizeImage(null,"+MaxW+","+MaxH+")",50);
        return;
    }
    var oldImage=new Image();
    oldImage.src=imageObject.src;
    var dW=oldImage.width;
    var dH=oldImage.height;
    if(dW>MaxW||dH>MaxH)
    {
        a=dW/MaxW;
        b=dH/MaxH;
        if(b>a)a=b;dW=dW/a;dH=dH/a;
    }
    if(dW>0&&dH>0)
    {
        imageObject.width=dW;
        imageObject.height=dH;
    }
    var dtop=MaxH/2-dH;
    if(dH!=MaxH && flag)
    {
        imageObject.style.marginTop=(MaxH/2-dH/2)+'px';
    }
}

function checkmyactive(o){
	trimspace(o.activenum);
	if(o.activenum.value==""){
		alert("请输入活动编号!");
		o.activenum.focus();
		return false;
	}
	trimspace(o.chrtitle);
	if(o.chrtitle.value==""){
		alert("请输入活动名称!");
		o.chrtitle.focus();
		return false;
	}
	if(o.categoryid.value==""){
		alert("请选择活动分类!");
		o.categoryid.focus();
		return false;
	}
	if(o.quyu.value==""){
		alert("请选择所在区域!");
		o.quyu.focus();
		return false;
	}
	trimspace(o.jihe);
	if(o.jihe.value==""){
		alert("请输入集合时间!");
		o.jihe.focus();
		return false;
	}
	trimspace(o.chraddress);
	if(o.chraddress.value==""){
		alert("请输入活动地点!");
		o.chraddress.focus();
		return false;
	}
	if(o.enddate.value==""){
		alert("请选择结束时间!");
		o.enddate.focus();
		return false;
	}
	trimspace(o.chrtruename);
	if(o.chrtruename.value==""){
		alert("请输入您的真实姓名!");
		o.chrtruename.focus();
		return false;
	}
	trimspace(o.chrmancode);
	if(o.chrmancode.value==""){
		alert("请输入身份证号!");
		o.chrmancode.focus();
		return false;
	}
	trimspace(o.chrtel);
	if(o.chrtel.value==""){
		alert("请输入联系电话!");
		o.chrtel.focus();
		return false;
	}
	trimspace(o.chrguanxi);
	if(o.chrguanxi.value==""){
		alert("请输入与此次活动的商家关系!");
		o.chrguanxi.focus();
		return false;
	}
	document.all.btn.style.display='none';
   document.all.submit1.innerHTML="<span style='font-weight:bold; font-size::15px;'>&nbsp;正在提交数据，请稍等......</span>";
}


/**
* 客户端预览图片
* 页面中必须包含<div id="previewImage"></div>的标签
*/
function preview(id)
{
    var pic = document.getElementById(id);
    if(!pic || !pic.value)
    {
        document.getElementById('previewImage').innerHTML = '没有图片';
        return;
    }
    var patn = /\.jpg$|\.jpeg$|\.bmp$|\.png$|\.gif$/i;
    if(patn.test(pic.value.toLowerCase()))
    {
        document.getElementById('previewImage').innerHTML = '<img src=\''+pic.value+'\' width=100 height=100  onload=\'ResizeImage(this, 100, 100,true);\' align=absmiddle>';
    }
    else
    {    
        alert("对不起，图片格式不正确，请重新浏览选择！"); 
        pic.outerHTML=pic.outerHTML;
        pic.value='';
    }
}

//选中表单复选框的所有的值
function CheckAll(form)
{
    for (var i=0;i<form.elements.length;i++)
    {
        var e = form.elements[i];
        if (e.type=="checkbox" && e.id != 'chkall') e.checked = form.chkall.checked;
    }
}

function confirmAlert(o,title){
	var flag=false;
	for (var i=0;i<o.elements.length;i++)
    {
        var e = o.elements[i];
        if (e.type=="checkbox" && e.id != 'chkall' && e.checked)
		{
			flag=true;	
		}
    }
	if(!flag){
		alert("对不起,请先选择!");
		return false;
	}	
	//delother(title);
	//return false;
}


function delother(title)
{
    if ( confirm("该操作将不可逆！\n"+title+"？"))
    {
      	var url="../request.aspx?action=other";
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+(Digital);
		xmlHttp=GetXmlHttpObject(requestdelactive)
		xmlHttp.open("POST", url , true)
		xmlHttp.send(null)
    }
}

function requestdelactive()
{
	if(xmlHttp.readyState==4)
	{
		if(xmlHttp.status==200)
		{
			if(xmlHttp.responseText.length>8)
			{
				alert(xmlHttp.responseText);	
			}else{
				alert("操作成功！");
				window.location.href="myactive.aspx?action=bao&id="+xmlHttp.responseText;
			}
						
		}
	}
}


var aganoutput="1";
function showloginagan(val,tt)
{
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var  Digital=new  Date();
	Digital=Digital+40000;
	url="../request.aspx?action=checklogin";
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestdatashowloginagan;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	function requestdatashowloginagan()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="1"){
					if(youke=="0"){
						$$("mask").style.display='';
						Demo_login('<div align=center><iframe src="../request.aspx?action=liveyou" scrolling="no" frameBorder=0 width=655 height=160></iframe></div>',655,160,655,160)
						return false;
					}
					else{
						//aganoutput="1";
						delCookie("aganparent");
						//setCookie("aganparent","1");
						delCookie("nextcookie");
						//setCookie("nextcookie","3,livefabu");
						delCookie("gotourl"); //不需要跳转,直接继续
						//$$("mask").style.display='';
						//Demo_login('<div align=center><iframe src="../member/register.html?login=login" scrolling="no" frameBorder=0 width=695 height=540></iframe></div>',695,540,695,540)
						window.location.href=WebSiteUrl+"member/login.html?from="+(encodeURIComponent(window.location.href));
						return false;
					}
				}
				else{
					aganoutput="0";	
					delCookie("aganparent");
					setCookie("aganparent","0");
					delCookie("nextcookie");
				}
				if(tt!="0"){
					checkaddlive(document.form1);
				}
			}
		}
	}
}
function checkyoukelive(){
	youke="1";
	showloginagan("../","0");
}
function fontkeyup(aa,bb,obj){
	if(aa.value.length>bb){
		window.event.returnValue = false;
	}
	else{
		$$(obj).innerHTML=bb-(aa.value.length)	
	}
}

/**
   * @name  限制文本框、文本域输入的最大值
   *               onpropertychange和oninput事件能够捕获每次输入值的变化，
   *               但是每种浏览器支持的方法不一样，为了兼容所有浏览器这两个事件都要添加

   *               Struts自带的标签不支持这2个属性
   * @todo  IE浏览器支持的方法:onpropertychange
   *             firefox等其他浏览器支持的方法：oninput事件
   * 
   */
  function MaxLength(field,maxlimit,obj){ 
   var j = field.value.length; 
   //alert(j); 
   var tempString=field.value; 
   var tt=""; 
   if(j > maxlimit){
    for(var i=0;i<maxlimit;i++){
     if(tt.length < maxlimit)
      tt = tempString.substr(0,i+1);
     else 
      break; 
    } 
    if(tt.length > maxlimit) 
     tt=tt.substr(0,tt.length-1); 
     field.value = tt; 
   }else{ 
    ; 
   } 
	if($$(obj)){
	   $$(obj).innerHTML=maxlimit-(field.value.length)	;
	}
  } 

function checkfabuanswer(o){
	trimspace(o.answer);
	var strEmail=o.answer.value;	   
	if(strEmail=="" || strEmail!=o.canswer.value){
	   setmsg('chkanswer','chkanswer_nothing_error')
	}else{	   	
	   setmsg('chkanswer','ok');
	 }
}
function checkaddlive(o){
	var edittt = "0";
	if($$("id")){
		edittt="1";
	}
	
	if(edittt=="0" && youke!="0"){
		if(aganoutput=="1"){
			showloginagan("../","1");
			return false;
		}
		if(aganoutput=="1"){
			return false;
		}
		
	}
	trimspace($$("chrtitle"));
	if($$("chrtitle").value==""){
		alert("对不起,请输入信息标题!");
		$$("chrtitle").focus();
		return false;
	}
	if($$("areaid").value=="0" || $$("areaid").value==""){
		alert("对不起,请选择地区!");
		$$("areaid").focus();
		return false;
	}
	/*if($$("chrmark")){
		if($$("chrmark").value==""){
			alert("对不起,请输入内容描述!");
			$$("chrmark").focus();
			return false;
		}
	}*/
	if($$("f_jiage")){
		/*if($$("f_jiage").value==""){
			alert("对不起,请输入价格!");
			$$("f_jiage").focus();
			return false;
		}*/
	}
	if($$("f_mianji")){
		trimspace($$("f_mianji"));
		if($$("f_mianji").value==""){
			alert("对不起,请输入面积!");
			$$("f_mianji").focus();
			return false;
		}
	}
	if($$("jiage")){
		trimspace($$("jiage"));
		/*if($$("jiage").value==""){
			alert("对不起,请输入价格!");
			$$("jiage").focus();
			return false;
		}*/
	}
	if($$("chrname")){
		trimspace($$("chrname"));
		if($$("chrname").value==""){
			alert("对不起,请输入联系人!");
			$$("chrname").focus();
			return false;
		}
	}
	if($$("t_huodong")){
		trimspace($$("t_huodong"));
		if($$("t_huodong").value==""){
			alert("对不起,请输入活动地点!");
			$$("t_huodong").focus();
			return false;
		}
	}
	if($$("t_xiaofei")){
		trimspace($$("t_xiaofei"));
		if($$("t_xiaofei").value==""){
			alert("对不起,请输入人均消费!");
			$$("t_xiaofei").focus();
			return false;
		}
	}
	if($$("t_renshu")){
		trimspace($$("t_renshu"));
		if($$("t_renshu").value==""){
			alert("对不起,请输入活动人数!");
			$$("t_renshu").focus();
			return false;
		}
	}
	if($$("j_huji")){
		trimspace($$("j_huji"));
		
	}
	//trimspace($$("chrtel"));
	trimspace($$("chrmobile"));
	trimspace($$("chrqq"));
	if($$("chrmobile").value=="" ){
		alert("对不起,请输入联系手机!");
		$$("chrtel").focus();
		return false;
	}
	if($$("chrmobile").value!=""){
		if($$("chrmobile").value.length!=11){
			alert("对不起,请输入正确的手机号码!");
			$$("chrmobile").focus();
			return false;
		}	
	}
	if(o.answer){
		trimspace(o.answer);
		var strEmail=o.answer.value;	   
		if(strEmail=="" ){
		   alert("对不起,请输入回答发布验证问题!");
			o.answer.focus();
			return false;
		}	
	}
	
	if($('#i_code1').attr('data-isopen') === '1'){
		trimspace(o.codeindex);
		var strEmail=o.codeindex.value;	   
		if(strEmail=="" ){
		   alert("对不起,请点击图片中对应字符激活发布!");
			return false;
		}	
	}
	
	if($("#url0").val()==""){
		var fmIMG = jQuery('.my_prop_imgitem').eq(0).find('img');
		if(fmIMG.length > 0){
			$("#url0").val(fmIMG.attr('src'))
		}
	}
	delCookie("nextcookie"); //不需要跳转,直接继续
}

function addlivefile(tt,aa){
	var count=parseInt($$("count").value);
	if(count<parseInt(tt)){
		if($$("showfile").innerHTML!=""){
			$$("showfile").innerHTML+="<br>";
		}
		$$("count").value=count+1;
		$$("showfile").innerHTML=	$$("showfile").innerHTML+'<input type="file" class="'+aa+'" size="50" name="file" />';
	}
	else{
		alert("对不起,目前最多可上传八张图片!");
	}
}

function BuildSel_tt(str,sel,val)
{
	//先清空原来的数据.
	sel.options.length=0;
	var arrstr = new Array();
	arrstr = str.split(",");
	//开始构建新的Select.
	sel.options.add(new Option( val,"")); 
	if(str.length>0)   
	{
		for(var i=0;i<arrstr.length-1;i++)
		{
			//分割字符串
			var subarrstr=new Array
			subarrstr=arrstr[i].split("|")
			//生成下级菜单
			sel.options.add(new Option(subarrstr[1],subarrstr[0])); 
		}
		sel.options[0].selected=true
	}
}

function showtoujianli(val){
		var isLogin = $('#isLogin').val();
		if(isLogin === '0'){
			alert('对不起,您没有登陆或者登录超时,请重新登录!');
			window.location.href=SiteUrl+"member/login.html?from="+encodeURIComponent(window.location.href);
		}else{
			showtoujianliresult(val);
		}
		
		/*var xmlhttp=createxmlhttp();
		if(!xmlhttp)
		{
			alert("你的浏览器不支持XMLHTTP！！");
			return;
		}
		var  Digital=new  Date();
		Digital=Digital+40000;
		url="../request.aspx?action=checklogin";
		url=url+"&k="+(Digital);
		xmlhttp.onreadystatechange=requestdatashowtoujianli;
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
		function requestdatashowtoujianli()
		{
			if(xmlhttp.readyState==4)
			{
				if(xmlhttp.status==200)
				{
					
					if(xmlhttp.responseText=="1"){
						
						window.location.href=SiteUrl+"member/login.html?from="+encodeURIComponent(window.location.href);
 						
					}
					else{
						
						showtoujianliresult(val);
					}
				}
			}
		}*/
}

function showtoujianliresult(val){
	var xmlhttp=createxmlhttp();
		if(!xmlhttp)
		{
			alert("你的浏览器不支持XMLHTTP！！");
			return;
		}
		var  Digital=new  Date();
		Digital=Digital+40000;
		url="../request.aspx?action=toujianli&id="+val;
		url=url+"&k="+(Digital);
		xmlhttp.onreadystatechange=requestdatashowtoujianliresult;
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
		function requestdatashowtoujianliresult()
		{
			if(xmlhttp.readyState==4)
			{
				if(xmlhttp.status==200)
				{
					if(xmlhttp.responseText=="1"){
						alert("对不起,您没有登陆或者登录超时,请重新登录!");
							window.location.href=SiteUrl+"member/login.html?from="+(window.location.href);
						//showtoujianli(val);
					}
					else if(xmlhttp.responseText=="0"){
						alert("您已经成功向该职位投出简历!");
					}
					else if(xmlhttp.responseText=="2"){
						alert("对不起,您还没有完善简历,请先完善!");
						window.location.href='../member/myjianli.aspx';
					}
					else{
						alert(xmlhttp.responseText);
					}
				}
			}
		}
}


function mianshiyqing(val){
		var isLogin = $('#isLogin').val();
		if(isLogin === '0'){
			
			alert('对不起,您没有登陆或者登录超时,请重新登录!');
			window.location.href=nowdomain+"member/login.html?from="+encodeURIComponent(window.location.href);
		}else{
			showmianshiyaoqing(val);
		}
		
		/*var xmlhttp=createxmlhttp();
		if(!xmlhttp)
		{
			alert("你的浏览器不支持XMLHTTP！！");
			return;
		}
		var  Digital=new  Date();
		Digital=Digital+40000;
		url="../request.aspx?action=checklogin";
		url=url+"&k="+(Digital);
		xmlhttp.onreadystatechange=requestdatamianshiyqing;
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
		function requestdatamianshiyqing()
		{
			if(xmlhttp.readyState==4)
			{
				if(xmlhttp.status==200)
				{
					
					if(xmlhttp.responseText=="1"){
						delCookie("aganparent");
						setCookie("aganparent","1");
						$$("mask").style.display='';
						window.location.href=SiteUrl+"member/login.html?from="+(window.location.href);
						
					}
					else{
						
						showmianshiyaoqing(val);
					}
				}
			}
		}*/
}

function showmianshiyaoqing(val){
	$$("mask").style.display='';
	Demo_login('<div align=center><iframe src="msyq_'+val+'.html" scrolling="no" frameBorder=0 width=685 height=382></iframe></div>',685,382,685,382)
}

function checkmsyq(o){
	trimspace(o.chraddress)
	if(o.chraddress.value==""){
		//setmsg('checkchraddress','mysq_error_chraddress');
		alert("对不起,请输入面试地点!");
		o.chraddress.focus();
		return false;
	}
	trimspace(o.chrdate)
	if(o.chrdate.value==""){
		alert("对不起,请输入面试时间!");
		o.chrdate.focus();
		return false;
	}
	trimspace(o.zhiweiid)
	if(o.zhiweiid.value=="" || o.zhiweiid.value=="0"){
		alert("对不起,请选择面试职位!");
		o.zhiweiid.focus();
		return false;
	}
	trimspace(o.chrman)
	if(o.chrman.value==""){
		alert("对不起,请输入联系人!");
		o.chrman.focus();
		return false;
	}
	trimspace(o.chrtel)
	if(o.chrtel.value==""){
		alert("对不起,请输入联系电话!");
		o.chrtel.focus();
		return false;
	}
	msyqresult(o.rencaiid.value,o.chraddress.value,o.chrdate.value,o.zhiweiid.value,o.chrman.value,o.chrtel.value,o.chrmark.value);
	return false;
}


function msyqresult(rencaiid,chraddress,chrdate,zhiweiid,chrman,chrtel,chrmark){
	var xmlhttp=createxmlhttp();
		if(!xmlhttp)
		{
			alert("你的浏览器不支持XMLHTTP！！");
			return;
		}
		var  Digital=new  Date();
		Digital=Digital+40000;
		url="../request.aspx?action=mysq&rencaiid="+encodeURIComponent(rencaiid)+"&chraddress="+encodeURIComponent(chraddress)+"&chrdate="+encodeURIComponent(chrdate)+"&zhiweiid="+encodeURIComponent(zhiweiid)+"&chrman="+encodeURIComponent(chrman)+"&chrtel="+encodeURIComponent(chrtel)+"&chrmark="+encodeURIComponent(chrmark);
		url=url+"&k="+(Digital);
		xmlhttp.onreadystatechange=requestdatamsyqresult;
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
		function requestdatamsyqresult()
		{
			if(xmlhttp.readyState==4)
			{
				if(xmlhttp.status==200)
				{
					if(xmlhttp.responseText=="1"){
						alert("对不起,您没有登陆或者登录超时,请重新登录!");
					}
					else if(xmlhttp.responseText=="0"){
						alert("面试邀请成功,请等待回复!");
						parent.LoginHide();
					}
					else{
						alert(xmlhttp.responseText);
					}
				}
			}
		}
}

function showhangyecategory(str)
{ 
	if (str.length > 0)
	{ 
		function requestdataquyu(){
			if(xmlhttp.readyState==4)
			{
				if(xmlhttp.status==200)
				{
					BuildSel(xmlhttp.responseText ,$$("categoryid"))
				}
			}
		}
		var xmlhttp=createxmlhttp();
		if(!xmlhttp)
		{
			alert("你的浏览器不支持XMLHTTP！！");
			return;
		}
		var url="../request.aspx?action=quyu&id="+str;
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+(Digital);
		xmlhttp.onreadystatechange=requestdataquyu;
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
		
	} 
	
} 
function showhangyesearch(str)
{ 
	if (str.length > 0)
	{ 
		var xmlhttp=createxmlhttp();
		if(!xmlhttp)
		{
			alert("你的浏览器不支持XMLHTTP！！");
			return;
		}
		var url="../request.aspx?action=quyu&id="+str;
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+(Digital);
		xmlhttp.onreadystatechange=requestshowhangyesearch;
		window.open(url);
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
		function requestshowhangyesearch()
		{
			if(xmlhttp.readyState==4)
			{
				if(xmlhttp.status==200)
				{
					BuildSel_tt(xmlhttp.responseText ,document.getElementById("s_gangwei"),"请选择岗位类别")
				}
			}
		}
	} 
	
} 

function checkaddrencaiform(o){
	if(o.chrname.value==""){
		alert("对不起,请输入姓名!");
		o.chrname.focus();
		return false;
	}
	if(o.chusheng.value==""){
		alert("对不起,请输入出生年月!");
		o.chusheng.focus();
		return false;
	}
	if(o.jiguan.value==""){
		alert("对不起,请输入籍贯!");
		o.jiguan.focus();
		return false;
	}
	if(o.mingzu.value==""){
		alert("对不起,请输入民族!");
		o.mingzu.focus();
		return false;
	}
	/*if(o.shenggao.value==""){
		alert("对不起,请输入身高!");
		o.shenggao.focus();
		return false;
	}
	if(o.tizhong.value==""){
		alert("对不起,请输入体重!");
		o.tizhong.focus();
		return false;
	}*/
	if(o.xueli.value==""){
		alert("对不起,请选择学历!");
		o.xueli.focus();
		return false;
	}
	if(o.biyexuexiao.value==""){
		alert("对不起,请输入毕业学校!");
		o.biyexuexiao.focus();
		return false;
	}
	if(o.hangyecategory.value==""){
		alert("对不起,请选择希望行业分类!");
		o.hangyecategory.focus();
		return false;
	}
	if(o.categoryid.value==""){
		alert("对不起,请选择希望岗位!");
		o.categoryid.focus();
		return false;
	}
	if(o.qiuzhileixing.value==""){
		alert("对不起,请选择求职类型!");
		o.qiuzhileixing.focus();
		return false;
	}
}

function checkaddzhiweiform(o){
	if(o.zhiweiname.value==""){
		alert("请输入职位名称!");
		o.zhiweiname.focus();
		return false;
	}
	if(o.zhiweixinzhi.value==""){
		alert("请选择职位性质!");
		o.zhiweixinzhi.focus();
		return false;
	}
	if(o.hangyecategory.value==""){
		alert("请选择行业分类!");
		o.hangyecategory.focus();
		return false;
	}
	if(o.categoryid.value==""){
		alert("请选择岗位分类!");
		o.categoryid.focus();
		return false;
	}
	if(o.renshu.value==""){
		alert("请输入招聘人数！");
		o.renshu.focus();
		return false;
	}
	if(o.youxiao.value==""){
		alert("请选择有效时间！");
		o.youxiao.focus();
		return false;
	}
	if(o.chraddress.value==""){
		alert("请输入地址！");
		o.chraddress.focus();
		return false;
	}
	if(o.chrtel.value==""){
		alert("请输入电话！");
		o.chrtel.focus();
		return false;
	}
	if(o.chrmark.value==""){
		alert("请输入职位描述！");
		o.chrmark.focus();
		return false;
	}
}

function showorderwan(val){
	$$("mask").style.display='';
	Demo_login('<div align=center><iframe src="../woyaoorder.html?action=wan&chrorder='+val+'" scrolling="no" frameBorder=0 width=935 height=250></iframe></div>',935,250,935,250)		
}
function showwytg(val){
	window.location.href=nowdomain+"zftg.html?a="+encodeURIComponent(val);	
	return false;
}
function showwytg1(val){
	window.location.href=sitedomainurl+"zftg.html?a="+encodeURIComponent(val);	
	return false;
}

function showeather(){
	var xmlhttp=createxmlhttp();
		if(!xmlhttp)
		{
			alert("你的浏览器不支持XMLHTTP！！");
			return;
		}
		var  Digital=new  Date();
		Digital=Digital+40000;
		var url="http://pfpip.sina.com/ip.js";
		url=url+"&k="+(Digital);
		xmlhttp.onreadystatechange=requestdatamsyqresult;
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
		function requestdatamsyqresult()
		{
			if(xmlhttp.readyState==4)
			{
				if(xmlhttp.status==200)
				{
					alert(xmlhttp.responseText);
				}
			}
		}
}




function hideotherlive(){
	$('#otherLive').hide();
	$('#mask').hide();
}

function showotherlive(colname,keyword){
	var mask = $('#mask'),d,top,h=Math.ceil(($(window).height()/2) - 260);
	
	if(!$('#otherLive')[0]){
		var divs = document.createElement('div');
		divs.id='otherLive';
		divs.style.display = 'none';
		
		$('body').append($(divs));
		
		$(window).bind("scroll",function(){
			var d = $(document).scrollTop();
			$('#otherLive').css('top',d+h);
		});
		$(window).bind("resize",function(){
			var d = $(document).scrollTop();
			h=Math.ceil(($(window).height()/2) - 260);
			$('#otherLive').css('top',d+h);
		});
	}
	var url = '../other.aspx?action=live&colname='+encodeURIComponent(colname)+'&keyword='+(keyword)+'&datetime='+new Date();
	var t = $('#otherLive');
	mask.css({'height':$(window).height()+'px'});
	d = $(document).scrollTop();
	t.css('top',d+h);
	t.show();
	mask.show();
	var myiframe = '<iframe src="'+url+'" scrolling="no" frameBorder="0" width="770" height="520"></iframe>';
	$('#otherLive')[0].innerHTML=myiframe;
	return false;
}

function showjubaolive(id){
	document.getElementById("mask").style.display='';
	Demo_login('<div align=center><iframe src="../other.aspx?action=jubao&id='+encodeURIComponent(id)+'" scrolling="no" frameBorder=0 width=260 height=152></iframe></div>',260,152,260,152)
}

function jubaolivesave(str,o){
	if (str.length > 0)
	{ 
		var xmlhttp=createxmlhttp();
		if(!xmlhttp)
		{
			alert("你的浏览器不支持XMLHTTP！！");
			return;
		}
		chrmark =o.chrmark.value;
		var url="request.aspx?action=jubao&id="+str+"&chrmark="+encodeURIComponent(chrmark);
		var  Digital=new  Date();
		url=url+"&k="+(Digital);
		url=url+"&k="+Digital;
		xmlhttp.onreadystatechange=requestdatajubao;
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
		return false;
		function requestdatajubao()
		{
			if(xmlhttp.readyState==4)
			{
				if(xmlhttp.status==200)
				{
					if(xmlhttp.responseText=="1"){
						alert("非常感谢您的举报，我们会马上处理！");
						parent.LoginHide();
					}
					else{
						alert(xmlhttp.responseText);	
					}
				}
			}
		}
	}
}


function search114bianming(o){
	if(o.keyword114.value=="输入您要查找的单位名称或部分名称" || o.keyword114.value==""){
		alert("输入您要查找的单位名称或部分名称");
		return false;
	}
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="../request.aspx?action=search114&keyword="+encodeURIComponent(o.keyword114.value);
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestdatasearch114;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	return false;
	function requestdatasearch114()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				$$("searchresult").innerHTML=xmlhttp.responseText;	
			}
		}
	}
}


var shangyi="<a href=\"javascript:settop()\">↑上移</a>";
var xiayi="<a href=\"javascript:settop()\">↓下移</a>";
function settop(){
	var ss=getCookie("articleindex");
	if(ss)
	{
		if(ss=="top1"){
			setCookie("articleindex","top2");
		}else{
			setCookie("articleindex","top1");
		}
	}
	else{
		setCookie("articleindex","top2");	
	}
	loadyi();
}
function loadyi(){
	var ss=getCookie("articleindex");
	if(ss)
	{
		if(ss=="top1"){
			setCookie("articleindex","top1");
			$$("top1yi").innerHTML=xiayi;
			$$("top2yi").innerHTML=shangyi;
			$$("top3").value=$$("top2").innerHTML;
			$$("top2").innerHTML=$$("top1").innerHTML;
			$$("top1").innerHTML=$$("top3").value;
		}else{
			$$("top2yi").innerHTML=xiayi;
			$$("top1yi").innerHTML=shangyi;
			$$("top3").value=$$("top1").innerHTML;
			$$("top1").innerHTML=$$("top2").innerHTML;
			$$("top2").innerHTML=$$("top3").value;
			setCookie("articleindex","top2");
		}
	}
	else{
		setCookie("articleindex","top1");	
	}
}
function showvotetishi(aa)
{
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="../request.aspx?action=checktou&aa="+aa;
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestshowvotetishi;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	return false;
	function requestshowvotetishi()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				if(document.getElementById("showvotetishi"))
				{
					document.getElementById("showvotetishi").innerHTML = "<font color=red>"+xmlhttp.responseText+"</font>" ;	
				}
			}
		}
	}
}
function showhezuo()
{
	$$("mask").style.display='';
	Demo_login('<div align=center><iframe id="iframeEl" name="iframeEl" src="../tg/hezuo.aspx?tt='+new Date()+'" scrolling="no" frameBorder=0 width=600 height=258></iframe></div>',600,258,600,258)	
	//document.getElementById("iframeEl").src = iframeEl.src;
	document.getElementById("iframeEl").src=document.getElementById("iframeEl").src;
}
function submithezuo()
{
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="../request.aspx?action=submithezuo&chrname="+encodeURIComponent(document.form1.chrname.value)+"&chrtel="+encodeURIComponent(document.form1.chrtel.value)+"&chrmark="+encodeURIComponent(document.form1.chrmark.value);
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestsubmithezuo;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	return false;
	function requestsubmithezuo()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="0")
				{
					alert("商务合作信息已经提交成功，我们会尽快与您取得联系！");
					parent.LoginHide();
				}
				else{
					alert(xmlhttp.responseText);	
					return false;
				}
			}
		}
	}
}
function showvotedetails(aa,tt){
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="../request.aspx?action=checktoupiao&aa="+aa+"&bb="+tt;
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestdatasearch114;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	return false;
	function requestdatasearch114()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="0")
				{
					$$("mask").style.display='';
					Demo_login('<div align=center><iframe src="voteyan.aspx?id='+tt+'" scrolling="no" frameBorder=0 width=600 height=260></iframe></div>',600,260,600,260)	
				}
				else{
					alert(xmlhttp.responseText);	
				}
			}
		}
	}
}
function showbianming114(){
	$$("mask").style.display='';
	Demo_login('<div align=center><iframe src="../request.aspx?action=addbianming" scrolling="no" frameBorder=0 width=600 height=355></iframe></div>',600,355,600,355)	
}
function checkbianming(o){
	if(o.chrtitle.value==""){
		alert("请输入机构名称!");
		o.chrtitle.focus();
		return false;
	}
	if(o.chrcode.value==""){
		alert("请输入经营项目/服务内容!");
		o.chrcode.focus();
		return false;
	}
	if(o.qu_classid.value==""){
		alert("请选择行业分类!");
		o.qu_classid.focus();
		return false;
	}
	if(o.chrdiqu.value==""){
		alert("请选择所在地区!");
		o.chrdiqu.focus();
		return false;
	}
	if(o.chrtel.value==""){
		alert("请输入电话号码!");
		o.chrtel.focus();
		return false;
	}
	if(o.chraddress.value==""){
		alert("请输入联系地址!");
		o.chraddress.focus();
		return false;
	}
	if(o.code.value==""){
		alert("请输入验证码!");
		o.code.focus();
		return false;
	}
}
function showbianming(o)
{
	if(o.checked)
	{
		$$("show2").style.display="";
		$$("show3").style.display="";
	}
	else
	{
		$$("show2").style.display="none";
		$$("show3").style.display="none";
	}
}
function shopbaocuo(id){
	$$("mask").style.display='';
	var  Digital=new  Date();
	Digital=Digital+40000;
	url="&k="+(Digital);
	Demo_login('<div align=center><iframe src="../request.aspx?action=addbaocuo&id='+id+url+'" scrolling="no" frameBorder=0 width=600 height=288></iframe></div>',600,288,600,288);
}
function addshopbaocuo(o){
	if(o.categoryid.value==""){
		alert("请选择反馈类型!");
		o.categoryid.focus();
		return false;
	}
	if(o.chrmark.value==""){
		alert("请填写补充说明!");
		o.chrmark.focus();
		return false;
	}
	if(o.chrmark.value.length>100){
		alert("补充说明不能大于100字!");
		o.chrmark.select();
		return false;
	}
	if(o.code.value==""){
		alert("请输入验证码");
		o.code.focus();
		return false;
	}
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="../request.aspx?action=baocuosave&categoryid="+(o.categoryid.value)+"&chrmark="+(o.chrmark.value);
	url +="&shopid="+(o.shopid.value)+"&code="+(o.code.value);
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestdatabaocuo;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	return false;
	function requestdatabaocuo()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="1"){
					alert("您已报错成功，我们会马上审核！");
					parent.LoginHide();
				}
				else{
					alert(xmlhttp.responseText);	
				}
			}
		}
	}
}

function shoprenling(id){
	var  Digital=new  Date();
	Digital=Digital+40000;
	var curl="&k="+(Digital);
	if(document.getElementById('isLogin').value !== '1'){
		alert('您好，请先登录再认领店铺！');
		window.location.href=nowdomain+"member/login.html?from="+(encodeURIComponent(window.location.href));
	}
	else{
		Demo_login('<div align=center><iframe src="../request.aspx?action=renling&id='+id+curl+'" scrolling="no" frameBorder=0 width=600 height=332></iframe></div>',600,332,600,332);
	}
}
function addshoprenling(o){
	if(o.chrname){
		if(o.chrname.value==""){
			alert("请输入您在本站的用户名!");
			o.chrname.focus();
			return false;
		}
	}
	if(o.chrpwd){
		if(o.chrpwd.value==""){
			alert("请输入密码!");
			o.chrpwd.focus();
			return false;
		}
	}
	if(o.zhizhao.value==""){
		alert("请填写您的店铺营业执照号!");
		o.zhizhao.focus();
		return false;
	}
	if(o.file.value==""){
		alert("请上传营业执照扫描件!");
		o.file.focus();
		return false;
	}
	if(o.chrman.value==""){
		alert("请填写法人代表!");
		o.chrman.focus();
		return false;
	}
	if(o.chrtel.value==""){
		alert("请填写联系方式!");
		o.chrtel.focus();
		return false;
	}
	if(o.code.value==""){
		alert("请输入验证码");
		o.code.focus();
		return false;
	}
}
function checkaddxuqiu(o){
	if(o.chrtitle.value=="" || o.chrtitle.value=="例如：我要求购一套清华大学附近总价200万以内的2居"){
		alert("对不起,请输入信息标题!");
		o.chrtitle.focus();
		return false;
	}
	var lent=judgeString(o.chrtitle.value);
	if(lent>80){
		alert("对不起,标题最长不能超过40个汉字!");
		o.chrtitle.focus();
		return false;
	}
	if(o.chrcontent.value=="" || o.chrcontent.value=="如对小区/楼层/位置/总价/租金/装修/朝向等方面的要求"){
		alert("对不起,请输入需求简介!");
		o.chrcontent.focus();
		return false;
	}
	if(o.chrman.value==""){
		alert("对不起,请输入您的称呼!");
		o.chrman.focus();
		return false;
	}
	if(o.chrtel.value==""){
		alert("对不起,请输入联系电话!");
		o.chrtel.focus();
		return false;
	}
	if(o.codeindex){
		trimspace(o.codeindex);
		var strEmail=o.codeindex.value;	   
		if(strEmail=="" ){
		   alert("对不起,请点击对应字符激活发布!");
			return false;
		}	
	}
}

function showloupancategory(str,nowcc){ 
   
	if (str.length > 0){ 
		
		var url="../request.aspx?action=quyu&id="+str;
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+(Digital);
		
		
		
		$.get(url,function(data){
			var sel=document.getElementById("qu_classid");
			var val="不限地段";
			var str =data;
			
			sel.options.length=0;
			var arrstr = new Array();
			arrstr = str.split(",");
			//开始构建新的Select.
			sel.options.add(new Option( val,"")); 
			if(str.length>0)   
			{
				for(var i=0;i<arrstr.length-1;i++)
				{
					//分割字符串
					var subarrstr=new Array
					subarrstr=arrstr[i].split("|")
					//生成下级菜单
					sel.options.add(new Option(subarrstr[1],subarrstr[0])); 
					if(nowcc==subarrstr[0])
					{  sel.options[i+1].selected=true;
					}
				}
			}
		})
		
		
		
	} 
} 

function jubaofangyuan(title,id,styleid){
	 if(confirm("您确认要举报此房源吗?"))
	 {
		var xmlhttp=createxmlhttp();
		if(!xmlhttp)
		{
			alert("你的浏览器不支持XMLHTTP！！");
			return;
		}
		var url="../request.aspx?action=jubaofang&tt="+encodeURIComponent(title)+"&id="+id+"&styleid="+styleid;
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+(Digital);
		xmlhttp.onreadystatechange=requestdatasetjubaofangyuan;
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
		return false;
		function requestdatasetjubaofangyuan()
		{
			if(xmlhttp.readyState==4)
			{
				if(xmlhttp.status==200)
				{
					if(xmlhttp.responseText=="1"){
						alert("感谢您,举报成功,我们会在第一时间内处理!");
						window.location.href= window.location.href;
					}
					else
						alert(xmlhttp.responseText);	
				}
			}
		}
	 }
}


function jirutuangou(tt){
	$$("mask").style.display='';
	Demo_login('<div align=center><iframe src="../request.aspx?action=addtg&id='+tt+'" scrolling="no" frameBorder=0 width=600 height=440></iframe></div>',600,440,600,440)	
}
function checkjiarutg(o){
	if(o.shijian.value==""){
		alert("请输入您打算购房的时间!");
		o.shijian.focus();
		return false;
	}
	if(o.yusuan.value==""){
		alert("请输入您的购房预算!");
		o.yusuan.focus();
		return false;
	}
	if(o.huxing.value==""){
		alert("请输入购买户型与面积!");
		o.huxing.focus();
		return false;
	}
	if(o.chrname.value==""){
		alert("请输入您的姓名!");
		o.chrname.focus();
		return false;
	}
	if(o.chrtel.value==""){
		alert("请输入您的联系方式!");
		o.chrtel.focus();
		return false;
	}
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="request.aspx?action=tgsave&id="+o.loupanid.value+"&shijian="+encodeURIComponent(o.shijian.value)+"&yusuan="+encodeURIComponent(o.yusuan.value);
	url +="&huxing="+encodeURIComponent(o.huxing.value)+"&chrname="+encodeURIComponent(o.chrname.value)+"&chrtel="+encodeURIComponent(o.chrtel.value)+"&fukuan="+encodeURIComponent(o.fukuan.value);
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+encodeURIComponent(Digital);
	xmlhttp.onreadystatechange=requestaddtgsave;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	return false;
	function requestaddtgsave()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="1"){
					alert("您已免费登记成功，我们会马上审核！");
					parent.LoginHide();
				}
				else{
					alert(xmlhttp.responseText);	
				}
			}
		}
	}
}

function checkxiaoqucomment(o){
	var commentyouke=$$("commentyouke");
	if(commentyouke)
	{
		if(commentyouke.checked)
			commentyouke="1";
		else
			commentyouke="0";
	}
	else
		commentyouke="0";
	if($$("chrname") && commentyouke==0 ){
		trimspace($$("chrname"))
		if($$("chrname").value==""){
			$$("chrname").focus();
			setmsg('checkcompany','shopcomment_error_name');
			return false;
		}	
	}
	if($$("chrpwd")  && commentyouke==0 ){
		trimspace($$("chrpwd"))
		if($$("chrpwd").value==""){
			$$("chrpwd").focus();
			setmsg('checkcompany','shopcomment_error_pwd');
			return false;
		}	
	}
	if($$("code")){
		trimspace($$("code"))
		if($$("code").value==""){
			$$("code").focus();
			setmsg('checkcompany','shopcomment_error_code');
			return false;
		}	
	}
	
	trimspace($$("chrmark"))
	if($$("chrmark").value==""){
		$$("chrmark").focus();
		setmsg('checkcompany','shopcomment_error_chrmark');
		return false;
	}
	if($$("chrmark").value.length>300){
		$$("chrmark").select();
		setmsg('checkcompany','shopcomment_error_chrmark1');
		return false;
	}
	var chrname="";
	if($$("chrname")){
		chrname=$$("chrname").value;
	}
	var chrpwd="";
	if($$("chrpwd")){
		chrpwd=$$("chrpwd").value;
	}	
	var total_score=0;
	if(o.score[1].checked){
		total_score=1;	
	}
	addxiaoqucomment(chrname,chrpwd,$$("code").value,commentyouke,$$("chrmark").value,$$("loupanid").value,total_score,$$("score_1").value,$$("score_2").value,$$("score_3").value,$$("score_4").value);
	return false;
}

function addxiaoqucomment(chrname,chrpwd,code,commentyouke,chrmark,loupanid,score,score1,score2,score3,score4)
{
      	var url="../request.aspx?action=addxiaoqucomment&chrname="+encodeURIComponent(chrname);
		url =url+"&chrpwd="+encodeURIComponent(chrpwd)+"&commentyouke="+encodeURIComponent(commentyouke)+"&code="+encodeURIComponent(code)+"&chrmark="+encodeURIComponent(chrmark)+"&loupanid="+encodeURIComponent(loupanid)+"&score="+encodeURIComponent(score)+"&score1="+encodeURIComponent(score1)+"&score2="+encodeURIComponent(score2)+"&score3="+encodeURIComponent(score3)+"&score4="+encodeURIComponent(score4);
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+encodeURIComponent(Digital);
		xmlHttp=GetXmlHttpObject(requestaddshopcomment)
		xmlHttp.open("get", url , true)
		xmlHttp.send(null)
}

function checkloupancomment(o){
	var commentyouke=$$("commentyouke");
	if(commentyouke)
	{
		if(commentyouke.checked)
			commentyouke="1";
		else
			commentyouke="0";
	}
	else
		commentyouke="0";
	
	
	
	if($$("code")){
		trimspace($$("code"))
		if($$("code").value==""){
			$$("code").focus();
			setmsg('checkcompany','shopcomment_error_code');
			return false;
		}	
	}
	trimspace($$("chrmark"))
	if($$("chrmark").value==""){
		$$("chrmark").focus();
		setmsg('checkcompany','shopcomment_error_chrmark');
		return false;
	}
	if($$("chrmark").value.length>300){
		$$("chrmark").select();
		setmsg('checkcompany','shopcomment_error_chrmark1');
		return false;
	}
	var k = $('#isLogin').val();
	var k2 = $('#commentyouke').prop('checked');
	if(k==='0'&&k2===false){ alert('对不起,请先登录再发表留言!'); return false;}
	var chrname="",chrpwd="";
	var total_score=0;
	if(o.score[1].checked){
		total_score=1;	
	}
	addloupancomment(chrname,chrpwd,$$("code").value,commentyouke,$$("chrmark").value,$$("loupanid").value,total_score,$$("score_1").value,$$("score_2").value,$$("score_3").value,$$("score_4").value);
	return false;
}

function addloupancomment(chrname,chrpwd,code,commentyouke,chrmark,loupanid,score,score1,score2,score3,score4)
{
	
      	var url="../request.aspx?action=addloupancomment&chrname="+encodeURIComponent(chrname);
		url =url+"&chrpwd="+encodeURIComponent(chrpwd)+"&commentyouke="+encodeURIComponent(commentyouke)+"&code="+encodeURIComponent(code)+"&chrmark="+encodeURIComponent(chrmark)+"&loupanid="+encodeURIComponent(loupanid)+"&score="+encodeURIComponent(score)+"&score1="+encodeURIComponent(score1)+"&score2="+encodeURIComponent(score2)+"&score3="+encodeURIComponent(score3)+"&score4="+encodeURIComponent(score4);
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+encodeURIComponent(Digital);
		xmlHttp=GetXmlHttpObject(requestaddshopcomment)
		xmlHttp.open("get", url , true)
		xmlHttp.send(null)
}



function checkloupanwen(o){
	var commentyouke=$$("commentyouke");
	if(commentyouke)
	{
		if(commentyouke.checked)
			commentyouke="1";
		else
			commentyouke="0";
	}
	else
		commentyouke="0";
		
	
	
	
	if($$("code")){
		trimspace($$("code"))
		if($$("code").value==""){
			$$("code").focus();
			setmsg('checkcompany','shopcomment_error_code');
			return false;
		}	
	}
	trimspace($$("chrmark"))
	if($$("chrmark").value==""){
		$$("chrmark").focus();
		setmsg('checkcompany','shopcomment_error_chrmark');
		return false;
	}
	if($$("chrmark").value.length>300){
		$$("chrmark").select();
		setmsg('checkcompany','shopcomment_error_chrmark1');
		return false;
	}
	var chrname="",chrpwd="";
	var k = $('#isLogin').val();
	var k2 = $('#commentyouke').prop('checked');
	if(k==='0'&&k2===false){ alert('对不起,请先登录再发表留言!'); return false;}
	addloupanwencomment(chrname,chrpwd,$$("code").value,commentyouke,$$("chrmark").value,$$("loupanid").value,$$("parentid").value);
	return false;
}

function addloupanwencomment(chrname,chrpwd,code,commentyouke,chrmark,loupanid,parentid)
{
	var url="../request.aspx?action=addloupanwen&chrname="+encodeURIComponent(chrname);
	url =url+"&chrpwd="+encodeURIComponent(chrpwd)+"&commentyouke="+encodeURIComponent(commentyouke)+"&code="+encodeURIComponent(code)+"&chrmark="+encodeURIComponent(chrmark)+"&loupanid="+encodeURIComponent(loupanid)+"&parentid="+encodeURIComponent(parentid);
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+encodeURIComponent(Digital);
	xmlHttp=GetXmlHttpObject(requestaddshopcomment)
	xmlHttp.open("get", url , true)
	xmlHttp.send(null)
}



function showbigmap(id1,id2,aa,bb){
	var url ='../request.aspx?action=showmap&id1='+id1+'&id2='+id2+'&aa='+aa+'&bb='+bb;
	Demo_login('<div align=center><iframe src='+url+'" scrolling="no" frameBorder=0 width=820 height=560></iframe></div>',820,560,820,560)
}
var mybgdt=new  Date();
function showheadlogin(){
	var myAlert = document.getElementById("loginPop");
	var reg = document.getElementById("loginMenu");
	myAlert.style.display = "block";
	myAlert.style.position = "absolute";
	myAlert.style.zIndex = "1000";
	myAlert.style.top = "50%";
	myAlert.style.left = "50%";
	myAlert.style.marginTop = "-131px";
	myAlert.style.marginLeft = "-200px";
	mybgdt=new  Date();
	mybg = document.createElement("div");
	mybg.setAttribute("id","mybg"+mybgdt);
	mybg.style.background = "#000";
	mybg.style.width = "100%";
	mybg.style.height = "100%";
	mybg.style.height = ((document.documentElement.clientHeight==0)?document.body.clientHeight:document.documentElement.clientHeight);
	mybg.style.position = "absolute";
	mybg.style.top = "0";
	mybg.style.left = "0";
	mybg.style.zIndex = "999";
	mybg.style.opacity = "0.3";
	mybg.style.filter = "Alpha(opacity=30)";
	document.body.appendChild(mybg);
	document.body.style.overflow = "hidden";
	delselect();
}
function hiddenheadlogin()
{
	showselect();
	if(parent.document.getElementById("loginPop")){var myAlert = document.getElementById("loginPop");myAlert.style.display = "none";}
	if(parent.document.getElementById("mybg"+mybgdt)){var mybg = document.getElementById("mybg"+mybgdt);mybg.style.display = "none";}
}
function showlogin(username){if(!parent.document.getElementById("islogin")){alert("模版缺少ID为\"islogin\"的块!");}if("undefined" == typeof usertemplate || "undefined" == typeof usertemplate1){parent.document.getElementById('islogin').innerHTML ="未发现登陆状态模版:请在模版中添加&lt;script&gt;var usertemplate=\"游客模版\";var usertemplate1=\"会员模版\";&lt;/script&gt;";}xmlhttpget("../request.ashx?action=islogin","0","parent.document.getElementById('islogin').innerHTML = usertemplate;","1","parent.document.getElementById('islogin').innerHTML = usertemplate1;","","parent.document.getElementById('islogin').innerHTML = usertemplate;","","");}
function showlogin_div(username){if(!parent.document.getElementById("islogin") || !parent.document.getElementById("register")){alert("模版缺少ID为\"islogin\",或ID为\"register\"的块!"); return;}	xmlhttpget("../request.ashx?action=islogin","0","$('#islogin').hide();$('#register').show();","","","showlogin_div_data(xmlhttp.responseText);","","","");}
function showlogin_div_data(str){if(str == "0"){$('#islogin').hide();$('#register').show();}else if(str.indexOf("|") >1){var _str = str.split("|");var t_str = $('#islogin').html();t_str = t_str.replace("{$loginuserid}",_str[0]).replace("{$loginjibie}",_str[1]).replace("{$loginname}",_str[2]).replace("{$loginstyleid}",_str[2]);$('#islogin').html(t_str); $('#register').hide();$('#islogin').show();}}
//function loginout(){xmlhttpget("../request.ashx?action=loginout","1","setTimeout(\"window.location.href='" + window.location.href + "';\",100);","","","loginout_div(xmlhttp.responseText);","","","");}
function loginout_div(_str){
	var   f=document.createElement("IFRAME")   
			f.height=0;   
			f.width=0;   
			f.src="../request.ashx?action=bbslogin&_keystr=" + _str + "&newdate" + new  Date();
			if (f.attachEvent){
				f.attachEvent("onload", function(){
					window.location.href= window.location.href;
				});
			} else {
				f.onload = function(){
					window.location.href= window.location.href;
				};
			}
			document.body.appendChild(f);

	
	}
function islogin_div(obj){if(!parent.document.getElementById(obj + "0") || !parent.document.getElementById(obj + "1")){alert("模版缺少ID为\"" + obj + "0\",或ID为\"" + obj + "1\"的块!"); return;}xmlhttpget("../request.ashx?action=islogin","0","$('#"+ obj + "1').hide();$('#"+ obj + "0').show();","","","islogin_div_data(xmlhttp.responseText,'" + obj + "');","","","");}
function islogin_div_data(str,obj){if(str == "0"){$("#"+ obj + "1").hide();$("#"+ obj + "0").show();}else if(str.indexOf("|") >1){$("#"+ obj + "0").hide();$("#"+ obj + "1").show();}}

function chrnum_div(key,numid){xmlhttpget("../request.ashx?action=chrnum&id=" + numid + "&key=" + key,"","","","","","chrnum_div_data(xmlhttp.responseText,'" + numid + "');","","");}
function chrnum_div_data(_str,numid){if(_str != "" && _str.indexOf(",") >0){var arr_str = _str.split(',');var arr_id = numid.split(',');for (var i = 0; i < arr_str.length; i++) {$("#chrnum"+ arr_id[i]).html(arr_str[i]);}}}



function checkheadlogin(){
	var logname = $$("logname");
    var logemail = logname.value;
    var originalPW  = $$("originalLogpasswd");
    var oPW = originalPW.value;
	var isnulllogemail=(logemail=="")||(logemail==null)||(logemail.length==0);
	var lent=judgeString(logemail);
    var isnullopw=oPW== "" || oPW == null || oPW.length == 0;
	if(isnulllogemail){
	   logname.focus();
	   alert("对不起,请输入您的用户名!");
	   return false;
    }
	if(lent < 3 || lent > 48) { 
		alert("对不起,用户名长度为3到24位!");
 	    return false;
    } 
    if(isnullopw) {
		alert("对不起,请输入您的密码!");
 	   return false;
    }else if(oPW.length < 6 || oPW.length > 32) {
		alert("对不起,请输入6-32位的密码!");
 	    return false;
    }  
   var remeber=document.formRegheadMain.remember_password.checked;
   xmlhttpget("../request.ashx?action=login&str1="+(logemail)+"&str2="+(oPW)+"&str3="+(remeber),"","","","","","requestheadlogin(xmlhttp.responseText);","","");
   return false;
}
function headlogin(){
	var logname = $$("username");
    var logemail = logname.value;
    var originalPW  = $$("password");
    var oPW = originalPW.value;
	var isnulllogemail=(logemail=="")||(logemail==null)||(logemail.length==0);
	var lent=judgeString(logemail);
    var isnullopw=oPW== "" || oPW == null || oPW.length == 0;
	if(isnulllogemail){
	   logname.focus();
	   alert("对不起,请输入您的用户名!");
	   return false;
    }
	if(lent < 3 || lent > 48) { 
		alert("对不起,用户名长度为3到24位!");
 	    return false;
    } 
    if(isnullopw) {
		alert("对不起,请输入您的密码!");
 	   return false;
    }else if(oPW.length < 6 || oPW.length > 32) {
		alert("对不起,请输入6-32位的密码!");
 	    return false;
    }  
   var remeber=document.formRegheadLogin.remember_password.checked;
   xmlhttpget("../request.ashx?action=login&str1="+(logemail)+"&str2="+(oPW)+"&str3="+(remeber),"","","","","","requestheadlogin(xmlhttp.responseText);","","");
   return false;
}
function requestheadlogin(output)
{
			//window.open(output);
			if(output==3){
				output=1;	
				alert("尊贵的用户,您是第一次登陆本站，请更新您的个人资料。谢谢！");
			}
			else if(output==4){
				output=3;	
				alert("尊贵的用户,您是第一次登陆本站，请更新您的个人资料。谢谢！");
			}
			var fabulive=getCookie("nextcookie");
			if(output==2 || output==1)
			{
				var   f=document.createElement("IFRAME")   
				f.height=0;   
				f.width=0   
				var tturl="/other.aspx?action=login";
				f.src=tturl;
				document.body.appendChild(f);
				hiddenheadlogin();
				alert("恭喜你,登陆本站成功!");
				window.location.href=window.location.href;
				//showlogin(nowdomain+"request.aspx?action=islogin");
			}
			else{
				alert(output);	
			}
}

function ShowIpAddress(val1,val2){if($$("chriparea")){xmlhttpget("../request.ashx?action=showip&aa="+encodeURIComponent(val1)+"&bb="+encodeURIComponent(val2),"","","","","","ShowIpAddress_eval(xmlhttp.responseText);","","");}}
function ShowIpAddress_eval(tt){var astr = tt.split("@");if(astr.length==2){if( $$("chriparea") )$$("chriparea").innerHTML=astr[0];if($$("chrmobilearea"))$$("chrmobilearea").innerHTML=astr[1];}}


function checknewscomment(o){
	var chrname="",chrpwd="";
	if($$("chrmark").value==""){
		alert("对不起,请输入评论内容!");
		$$("chrmark").focus();
		return false;
	}
	if($$("chrmark").value.length>500){
		alert("对不起,评论内容不得大于300字!");
		$$("chrmark").select();
		return false;
	}
	if($$("chrcode").value.length!=5){
		alert("对不起,请正确输入验证码!");
		$$("chrcode").focus();
		return false;
	}
	var commentyouke=$$("commentyouke");
	if(commentyouke)
	{
		if(commentyouke.checked)
			commentyouke="1";
		else
			commentyouke="0";
	}
	else
		commentyouke="0";
	
	var aa=$$("agan").value;
	
	var k = $('#isLogin').val();
	var k2 = $('#commentyouke').prop('checked');
	if(k==='0'&&k2===false){ alert('对不起,请先登录再发表留言!'); return false;}
	
	addnewscomment(chrname,chrpwd,commentyouke,$$("chrcode").value,$$("chrmark").value,$$("newsid").value,aa);
	return false;
}
function addnewscomment(chrname,chrpwd,commentyouke,chrcode,chrmark,newsid,agan){
	var url="../request.aspx?action=addnewscomment&newsid="+encodeURIComponent(newsid)+"&chrname="+encodeURIComponent(chrname)+"&chrpwd="+encodeURIComponent(chrpwd)+"&commentyouke="+encodeURIComponent(commentyouke)+"&chrcode="+encodeURIComponent(chrcode)+"&chrmark="+encodeURIComponent(chrmark)+"&agan="+encodeURIComponent(agan);
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlHttp=GetXmlHttpObject(requestaddnewscomment)
	xmlHttp.open("get", url , true)
	xmlHttp.send(null)
}

function requestaddnewscomment()
{
	if(xmlHttp.readyState==4)
	{
		if(xmlHttp.status==200)
		{
			if(xmlHttp.responseText==0)
			{
				alert("恭喜您，评论成功，请等待管理员审核！");	
				if(window.location.href.toLowerCase().indexOf("/video/")==-1)
				{
					var curl=window.location.href;
					if(curl.indexOf("#")>0)
					{
						curl=curl.substring(0,curl.indexOf("#"));	
					}
					window.location.href=curl;
				}
				else
				{
					getdata("../request.aspx?action=shownewscomment&shopid="+$$("newsid").value+"&agan=1","showcomment","showcomment");
					$$("chrmark").value="";
					$$("chrcode").value="";
					if($$("chrname"))
						$$("chrname").value="";
					if($$("chrpwd"))
						$$("chrpwd").value="";	
				}
			}
			else if(xmlHttp.responseText==1)
			{
				alert("恭喜您，发布成功！");	
				if(window.location.href.toLowerCase().indexOf("/video/")==-1)
				{
					var curl=window.location.href;
					if(curl.indexOf("#")>0)
					{
						curl=curl.substring(0,curl.indexOf("#"));	
					}
					window.location.href=curl;
				}
				else
				{
					$$("chrmark").value="";
					$$("chrcode").value="";
					if($$("chrname"))
						$$("chrname").value="";
					if($$("chrpwd"))
						$$("chrpwd").value="";
					getdata("../request.aspx?action=shownewscomment&shopid="+$$("newsid").value+"&agan=1","showcomment","showcomment");
				}
			}
			else{
				alert(xmlHttp.responseText);
			}
		}
	}
}

function selected(obj)
{
   var allsel=document.getElementsByName(obj);
   for(var i=0;i<allsel.length;i++)
   {
	   allsel[i].checked=true;
   }
}
function changestate(val,id)
{
	if(confirm("您确认要更改状态吗？"))
	{
		var url="../request.aspx?action=changetgorder&iskill="+val+"&id="+encodeURIComponent(id);
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+(Digital);
		var xmlhttp=createxmlhttp();
		if(!xmlhttp)
		{
			alert("你的浏览器不支持XMLHTTP！！");
			return;
		}
		xmlhttp.onreadystatechange=requestaddtg;
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
		return false;
		function requestaddtg()
		{
			if(xmlhttp.readyState==4)
			{
				if(xmlhttp.status==200)
				{
					window.location.href="tgOrderManage.aspx";
				}
			}
		}
	}
}
function changemystate(val,id)
{
	if(confirm("您确认要更改状态吗？"))
	{
		var url="../request.aspx?action=changemytgorder&iskill="+val+"&id="+encodeURIComponent(id);
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+(Digital);
		var xmlhttp=createxmlhttp();
		if(!xmlhttp)
		{
			alert("你的浏览器不支持XMLHTTP！！");
			return;
		}
		xmlhttp.onreadystatechange=requestaddtg;
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
		return false;
		function requestaddtg()
		{
			if(xmlhttp.readyState==4)
			{
				if(xmlhttp.status==200)
				{
					window.location.href="mytgorder.aspx";
				}
			}
		}
	}
}
function changemystatexx(val,id)
{
	if(confirm("您确认要更改状态吗？"))
	{
		var url="../request.aspx?action=changemytgorderxx&iskill="+val+"&id="+encodeURIComponent(id);
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+(Digital);
		var xmlhttp=createxmlhttp();
		if(!xmlhttp)
		{
			alert("你的浏览器不支持XMLHTTP！！");
			return;
		}
		xmlhttp.onreadystatechange=requestaddtg;
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
		return false;
		function requestaddtg()
		{
			if(xmlhttp.readyState==4)
			{
				if(xmlhttp.status==200)
				{
					window.location.href="mytgorder.aspx?action=ss";
				}
			}
		}
	}
}
function changemystatett(val,id)
{
	if(confirm("您确认要更改状态吗？"))
	{
		var url="../request.aspx?action=changemytgordertt&iskill="+val+"&id="+encodeURIComponent(id);
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+(Digital);
		var xmlhttp=createxmlhttp();
		if(!xmlhttp)
		{
			alert("你的浏览器不支持XMLHTTP！！");
			return;
		}
		xmlhttp.onreadystatechange=requestaddtg;
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
		return false;
		function requestaddtg()
		{
			if(xmlhttp.readyState==4)
			{
				if(xmlhttp.status==200)
				{
					window.location.href="mytgorder.aspx?action=s";
				}
			}
		}
	}
}
function toupiao(dd,aa,bb,cc,name,tel,imgcodeid,codeindex){
	if(name === ''){
		alert('请输入您的姓名！');
		return false;
	}
	if(tel === ''){
		alert('请输入您的手机号码！');
		return false;
	}
	if($('#i_code1').attr('data-isopen') === '1'){
		if(codeindex===''){
			alert('请点击图片中对应的字符激活验证！');
			return false;
		}
	}
	
	var url="../request.aspx?action=toupiao&voteid="+aa+"&code="+encodeURIComponent(dd)+"&xuanshouid="+encodeURIComponent(bb)+"&name="+encodeURIComponent(name)+"&tel="+encodeURIComponent(tel)+"&imgcodeid="+encodeURIComponent(imgcodeid)+"&codeindex="+encodeURIComponent(codeindex);
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	xmlhttp.onreadystatechange=requestaddtg;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	return false;
	function requestaddtg()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				var  tt=xmlhttp.responseText;
				if(tt=="1")
				{
					alert("投票成功！感谢您对“"+cc+"”的支持！");
					var aa=parseInt(parent.window.document.getElementById("vote"+bb).innerHTML)
					parent.window.document.getElementById("vote"+bb).innerHTML= aa+1; 
					parent.LoginHide();
				}
				else
					alert(tt);
			}
		}
	}
}

function submittgorder(val)
{
	
	

	

	
		$("#order_flag").val("1");
		var num=$('#order_amount').val();
		if(num==""){
			alert("对不起，请输入购买数量！");
			$('#order_amount').focus();
			$("#order_flag").val("0");
			return false;
		}
		if(num=="0")
		{
			alert("对不起，请正确输入购买数量！");
			$('#order_amount').focus();
			$("#order_flag").val("0");
			return false;
		}
		var styleid=$("#styleid").val();
		var chrtel="",chrtela="";
		var myreg = /^(((13[0-9]{1})|159|(17[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(14[0-9]{1}))+\d{8})$/;
		
			chrtel=$("#chrtel").val();
				chrtela=$("#chrtela").val();
			if(chrtel=="")
			{
				alert("对不起，请输入您的手机号码！");
				$('#chrtel').focus();
				$("#order_flag").val("0");
				return false;
			}
			if(chrtel!=chrtela)
			{
				alert("对不起，您两次输入的手机号码不一致！");
				$('#chrtel').focus();
				$("#order_flag").val("0");
				return false;
			}
			if( !(myreg.test(chrtel)) )
			{
				alert("对不起，手机号码输入不正确！");
				$("#chrtel").focus();
				$("#order_flag").val("0");
				return false;
			}
	
	
	var kindid = document.getElementsByName("payid");
	for(var i=0;i<kindid.length;i++)
	{
		if(kindid[i].checked && kindid[i].value=="2" && val=="0")
		{
		var p_mode = document.getElementById("Form11").pmode_id.value;
		  if(p_mode == "" || p_mode == 0){
			 alert("请选择支付银行和类型");
			 return false;	
		  }
		}
		
	}
			
      if(val=="0")
         {
	        $("#order_flag").val("0");
	       }else
	      {
			   $("#order_flag").val("1");
			  }

		    $("#have_login").show();
		    document.Form11.submit();
		return true;
	}

function offshow(val)
{
	if(val=="tg")	document.getElementById("have_login").style.display="none";
  
	
	}


function submitorder(val)
{
	var flag=$("#order_flag").val();
	if(flag=="0")
	{
		$("#order_flag").val("1");
		var num=$('#order_amount').val();
		if(num==""){
			alert("对不起，请输入购买数量！");
			$('#order_amount').focus();
			$("#order_flag").val("0");
			return false;
		}
		if(num=="0")
		{
			alert("对不起，请正确输入购买数量！");
			$('#order_amount').focus();
			$("#order_flag").val("0");
			return false;
		}
		var styleid=$("#styleid").val();
		var chrtel="",chrname="",chraddress="",chrmark="",chrtela="";
		var myreg = /^(((13[0-9]{1})|(17[0-9]{1})|159|(15[0-9]{1})|(18[0-9]{1})|(14[0-9]{1}))+\d{8})$/;
		if(styleid=="0")
		{
			chrtel=$("#chrtel").val();
				chrtela=$("#chrtela").val();
			if(chrtel=="")
			{
				alert("对不起，请输入您的手机号码！");
				$('#chrtel').focus();
				$("#order_flag").val("0");
				return false;
			}
			if(chrtel!=chrtela)
			{
				alert("对不起，您两次输入的手机号码不一致！");
				$('#chrtel').focus();
				$("#order_flag").val("0");
				return false;
			}
			if( !(myreg.test(chrtel)) )
			{
				alert("对不起，手机号码输入不正确！");
				$("#chrtel").focus();
				$("#order_flag").val("0");
				return false;
			}
		}
		else if(styleid=="1")
		{
			chrname=$("#chrname").val();
			if(chrname==""|| chrname=="请填写收货人姓名")
			{
				alert("对不起，请填写收货人姓名！");
				$('#chrname').focus();
				$("#order_flag").val("0");
				return false;
			}
			chrtel=$("#chrtel").val();
			if(chrtel=="")
			{
				alert("对不起，请填写收货人联系电话！");
				$('#chrtel').focus();
				$("#order_flag").val("0");
				return false;
			}
			if( !(myreg.test(chrtel)) )
			{
				alert("对不起，收货人联系电话不正确！");
				$("#chrtel").focus();
				$("#order_flag").val("0");
				return false;
			}
			chraddress=$("#chraddress").val();
			if(chraddress==""|| chraddress=="请填写收货详细准确地址")
			{
				alert("对不起，请填写收货详细准确地址！");
				$('#chraddress').focus();
				$("#order_flag").val("0");
				return false;
			}
			chrmark=$("#chrmark").val();
			if(chrmark=="" || chrmark=="附言(如对本商品的颜色、尺寸等要求)")
			{
				alert("对不起，请填写附言(如对本商品的颜色、尺寸等要求)！");
				$('#chrmark').focus();
				$("#order_flag").val("0");
				return false;
			}
		}
		else if(styleid=="2")
		{
			chrname=$("#chrname").val();
			if(chrname==""|| chrname=="您的姓名")
			{
				alert("对不起，请填写您的姓名！");
				$('#chrname').focus();
				$("#order_flag").val("0");
				return false;
			}
			chrtel=$("#chrtel").val();
			if(chrtel=="")
			{
				alert("对不起，请输入您的手机号码！");
				$('#chrtel').focus();
				$("#order_flag").val("0");
				return false;
			}
			if( !(myreg.test(chrtel)) )
			{
				alert("对不起，手机号码输入不正确！");
				$("#chrtel").focus();
				$("#order_flag").val("0");
				return false;
			}
			chraddress=$("#chraddress").val();
			if(chraddress==""|| chraddress=="送货地址")
			{
				alert("对不起，请填写送货地址！");
				$('#chraddress').focus();
				$("#order_flag").val("0");
				return false;
			}
		}
		$("#have_login").show();
		var url="../request.aspx?action=addtgorder&payid="+$("input[name='kindid']:checked").val()+"&num="+encodeURIComponent(num)+"&tgid="+encodeURIComponent($("#tgid").val())+"&chrname="+encodeURIComponent(chrname)+"&chraddress="+encodeURIComponent(chraddress)+"&chrmark="+encodeURIComponent(chrmark)+"&chrtel="+encodeURIComponent(chrtel) + "&chrtela="+encodeURIComponent(chrtela);
		var  Digital=new  Date();
		//window.open(url);
		Digital=Digital+40000;
		url=url+"&k="+(Digital);
		var xmlhttp=createxmlhttp();
		if(!xmlhttp)
		{
			alert("你的浏览器不支持XMLHTTP！！");
			$("#order_flag").val("0");
			return;
		}
		xmlhttp.onreadystatechange=requestaddtg;
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
		return false;
		function requestaddtg()
		{
			if(xmlhttp.readyState==4)
			{
				if(xmlhttp.status==200)
				{
					var  tt=xmlhttp.responseText;
					var pattern = /^([0-9])+$/; 
					if(tt=="tt")
					{
						window.location.href=sitedomainurl+"member/mytgorder.aspx";
					}
					else if(pattern.test(tt))
					{
						if(val=="1")
							window.location.href=sitedomainurl+"member/mytgorder.aspx";
						else
						{
							$("#chrorder").val(tt);	
							$("#shu").val();
							document.Form11.submit();
						}
					}
					else
					{
						alert(tt);	
						$("#have_login").hide();
						$("#order_flag").val("0");
					}
				}
			}
		}
	}
}
function getobject(id){
  if (typeof(id)=="object")
        return id;
    if (typeof(id)=="string")
	{
        var obj = document.getElementById(id);
        if(obj != null)
            return obj;
        obj = document.getElementsByName(id);
        if(obj != null && obj.length > 0)
            return obj[0];
    }
    return null;
}
function checkyuming(obj1,obj2,obj3,obj4)
{ 
	if (obj1.length > 0)
	{ 
		var xmlhttp=createxmlhttp();
		if(!xmlhttp)
		{
			alert("你的浏览器不支持XMLHTTP！！");
			return;
		}
		var url="../request.aspx?action=yuming&id="+obj3+"&str="+encodeURIComponent(obj1)+"&typeid="+obj4;
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+(Digital);
		xmlhttp.onreadystatechange=requestdataquyu;
		xmlhttp.open("GET",url,true);
		xmlhttp.send(null);
		function requestdataquyu()
		{
			if(xmlhttp.readyState==4)
			{
				if(xmlhttp.status==200)
				{
					if(getobject(obj2)){
						getobject(obj2).innerHTML="<font color=red>"+xmlhttp.responseText+"</font>";
					}
				}
			}
		}
	} 
	
} 



function loadqiyecategoryid(id,aa){
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="qiye.aspx?action=showvalue&styleid=2&id="+id+"&aa="+aa;
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestdatashopcategoryid;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	function requestdatashopcategoryid()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				document.getElementById("listcategoryshow").innerHTML=xmlhttp.responseText;
			}
		}
	}
}
function loadqiyecategoryidselect(id,aa){
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="qiye.aspx?action=showvalue&styleid=1&id="+id+"&aa="+aa;
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestdataqiyecategoryidselect;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	function requestdataqiyecategoryidselect()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				BuildSel(xmlhttp.responseText ,document.getElementById("categoryid"))
			}
		}
	}
}
function deleteqiyecategoryid(id,categoryid,aa){
	if (!confirm("您确定要删除此分类名称吗？此分类下面的信息也会一起删除!"))
	{
		return false;
	}
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="qiye.aspx?action=showvalue&styleid=3&id="+id+"&categoryid="+categoryid+"&aa="+aa;
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestdataqiyecategoryiddelete;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	function requestdataqiyecategoryiddelete()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="1"){
					loadqiyecategoryid(id,aa);
					loadqiyecategoryidselect(id,aa);
					return false;
				}
				else{
					alert(xmlhttp.responseText);	
					return false;
				}
			}
		}
	}
}
function editqiyecategory(cc){
	if(document.formt1.editchrcategory.value==""){
		alert("对不起,请输入分类名称!");
		document.formt1.editchrcategory.focus();
		return false;
	}
	var aa=document.formt1.editchrcategory.value;
	var bb=document.formt1.editcategoryid.value;
	var paixu=document.formt1.editpaixu.value;
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="qiye.aspx?action=editcategory&id="+document.formt1.ID.value+"&categoryid="+bb+"&chrcategory="+encodeURIComponent(aa)+"&paixu="+encodeURIComponent(paixu);
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestdataeditcategoryid;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	function requestdataeditcategoryid()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="1"){
					loadqiyecategoryid(document.formt1.ID.value,cc);
					loadqiyecategoryidselect(document.formt1.ID.value,cc);
					document.getElementById("editchrcategory").value="";
					document.getElementById("editcategoryid").value="";
					document.getElementById("editcategory").style.display='none';
					return false;
				}
				else{
					alert(xmlhttp.responseText);	
					return false;
				}
			}
		}
	}
}
function addqiyecategory(id,bb){
	if(document.formt1.chrcategory.value==""){
		alert("对不起,请输入分类名称!");
		document.formt1.chrcategory.focus();
		return false;
	}
	var aa=document.formt1.chrcategory.value;
	var cc=document.formt1.paixu.value;
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="qiye.aspx?action=addcategory&id="+id+"&chrcategory="+encodeURIComponent(aa)+"&aa="+encodeURIComponent(bb)+"&paixu="+cc;
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=requestqiyedataaddcategoryid;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	function requestqiyedataaddcategoryid()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="1"){
					loadqiyecategoryid(id,bb);
					loadqiyecategoryidselect(id,bb);
					return false;
				}
				else{
					alert(xmlhttp.responseText);	
					return false;
				}
			}
		}
	}
	
}
function setqiyeeditcategory(categoryid,id,chrcategory,paixu){
	document.getElementById("editchrcategory").value=chrcategory;
	document.getElementById("editcategoryid").value=categoryid;
	document.getElementById("editcategory").style.display='';
	document.getElementById("editpaixu").value=paixu;
}
function checksmscode(userid)
{
	
	var bb=document.getElementById('oldtel').value;
	var cc=document.getElementById('chrcode').value;
	var url="../request.aspx?action=show1&str1=3&str2="+encodeURIComponent(userid)+"&str3="+encodeURIComponent(bb)+"&str4="+encodeURIComponent(cc);
	
	$.get(url,function(data){
		if(data.islogin === '1'){
			alert("恭喜您，验证成功！");
			var   f=document.createElement("IFRAME")   
					f.height=0;   
					f.width=0   
					f.src="../other.aspx?action=login"  
					document.body.appendChild(f) ;
					setTimeout(function(){window.location.href='index.html';},1000);
					return false;
		}else{
			alert(data.error);	
			return false;
		}
	});
	return false;	
	
	
	
	
}

function resendemail(aa,bb,cc)
{
	var xmlhttp=createxmlhttp();
	if(!xmlhttp)
	{
		alert("你的浏览器不支持XMLHTTP！！");
		return;
	}
	var url="../request.aspx?action=show1&str1=1&str2="+encodeURIComponent(aa)+"&str3="+encodeURIComponent(bb)+"&str4="+encodeURIComponent(cc);
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	xmlhttp.onreadystatechange=resendemailssss;
	xmlhttp.open("GET",url,true);
	xmlhttp.send(null);
	function resendemailssss()
	{
		if(xmlhttp.readyState==4)
		{
			if(xmlhttp.status==200)
			{
				if(xmlhttp.responseText=="0")
				{
					alert("系统已经发送了一封激活验证邮件到您的邮箱："+cc);
					document.getElementById('oldemail').innerHTML=cc;
					return false;
				}
				else{
					alert(xmlhttp.responseText);	
					return false;
				}
			}
		}
	}
	return false;
}
function searchbianming(o)
{
	if(o.keyword.value=='输入您要查找的机构名称关键词'){o.keyword.value='';return false;}	
	if(o.mySle.value=="0")
	{
		o.action="index.html";	
	}
	else{
		o.action="qiyelist.html";		
	}
}
/**
 * 作者：我是UED ，http://www.iamued.com/qianduan/816.html
 * 回到页面顶部
 * @param acceleration 加速度
 * @param time 时间间隔 (毫秒)
 **/
function goTop(acceleration, time) {
	acceleration = acceleration || 0.1;
	time = time || 16;
 
	var x1 = 0;
	var y1 = 0;
	var x2 = 0;
	var y2 = 0;
	var x3 = 0;
	var y3 = 0;
 
	if (document.documentElement) {
		x1 = document.documentElement.scrollLeft || 0;
		y1 = document.documentElement.scrollTop || 0;
	}
	if (document.body) {
		x2 = document.body.scrollLeft || 0;
		y2 = document.body.scrollTop || 0;
	}
	var x3 = window.scrollX || 0;
	var y3 = window.scrollY || 0;
 
	// 滚动条到页面顶部的水平距离
	var x = Math.max(x1, Math.max(x2, x3));
	// 滚动条到页面顶部的垂直距离
	var y = Math.max(y1, Math.max(y2, y3));
 
	// 滚动距离 = 目前距离 / 速度, 因为距离原来越小, 速度是大于 1 的数, 所以滚动距离会越来越小
	var speed = 1 + acceleration;
	window.scrollTo(Math.floor(x / speed), Math.floor(y / speed));
 
	// 如果距离不为零, 继续调用迭代本函数
	if(x > 0 || y > 0) {
		var invokeFunction = "goTop(" + acceleration + ", " + time + ")";
		window.setTimeout(invokeFunction, time);
	}
} 



function loginm() {
            $.ajax({
                url: '../request.ashx?action=login&str1="+(str1)+"&str2="+(str2)+"&str3="+(str3)', //访问路径
                data: 'chrnamet=' + $("#chrnamet").val() + "&chrpwdt=" + $("#chrpwdt").val(), //需要验证的参数
                type: 'post',                               //传值的方式
                error: function () {                       //访问失败时调用的函数
                    alert("链接服务器错误！");
                },
                success: function (msg) {              //访问成功时调用的函数,这里的msg是request.aspx返回的值
                    alert(msg);
                }
            });
        }

function URLEncode(fld) 
{ 
if (fld == "") return false; 
var encodedField = ""; 
var s = fld; 
if (typeof encodeURIComponent == "function") 
{ 
// Use javascript built-in function 
// IE 5.5+ and Netscape 6+ and Mozilla 
encodedField = encodeURIComponent(s); 
} 
else 
{ 
// Need to mimic the javascript version 
// Netscape 4 and IE 4 and IE 5.0 
encodedField = encodeURIComponentNew(s); 
} 
//alert ("New encoding: " + encodeURIComponentNew(fld) + 
// "\n encodeURIComponent(): " + encodeURIComponent(fld)); 
return encodedField; 
} 

function str2asc(strstr){
str2asc = hex(asc(strstr));
}
function asc2str(ascasc){
asc2str = chr(ascasc);
}

function UrlEncodex(str){ 
var ret=""; 
var strSpecial="!\"#$%&'()*+,/:;<=>?[]^`{|}~%"; 
var tt= ""; 

for(var i=0;i<str.length;i++){ 
var chr = str.charAt(i); 
var c=str2asc(chr); 
tt += chr+":"+c+"n"; 
if(parseInt("0x"+c) > 0x7f){ 
ret+="%"+c.slice(0,2)+"%"+c.slice(-2); 
}else{ 
if(chr==" ") 
ret+="+"; 
else if(strSpecial.indexOf(chr)!=-1) 
ret+="%"+c.toString(16); 
else 
ret+=chr; 
} 
} 
return ret; 
} 
function UrlDecodex(str){ 
var ret=""; 
for(var i=0;i<str.length;i++){ 
var chr = str.charAt(i); 
if(chr == "+"){ 
ret+=" "; 
}else if(chr=="%"){ 
var asc = str.substring(i+1,i+3); 
if(parseInt("0x"+asc)>0x7f){ 
ret+=asc2str(parseInt("0x"+asc+str.substring(i+4,i+6))); 
i+=5; 
}else{ 
ret+=asc2str(parseInt("0x"+asc)); 
i+=2; 
} 
}else{ 
ret+= chr; 
} 
} 
return ret; 
} 
function showjifen(actions,typeid,styleid,ondate,offdate,obj){
	
	var url ="../member/modify.aspx?action=mingxi&actions=" + actions + "&typeid=" + typeid + "&styleid=" + styleid + "&ondate=" + ondate + "&offdate=" + offdate;
	if(styleid==0){
		echo('jifenname',name0);
	}else{
		echo('jifenname',name1);
	}
	
	xmlhttpget(url,"","","","","","echo(obj,xmlhttp.responseText);","",obj);

}




function echo(obj,html){$("#" + obj).innerHTML=html;document.getElementById(obj).innerHTML=html;}
function show(_showobj ,_showt){document.getElementById(_showobj).style.dispaly="";$("#" + _showobj).show();if( _showt != "" && _showt != null ){document.getElementById(_showobj).innerHTML=_showt;$("#" + _showobj).innerHTML=_showt;}}
//function show(_showobj ,_showt){document.getElementById(_showobj).style.dispaly="";if( _showt != "" && _showt != null ){document.getElementById(_showobj).innerHTML=_showt;}}

function show_val(_showobj ,_showt){$("#" + _showobj).value=_showt;document.getElementById(_showobj).value=_showt;}
function hide(_showobj){if( _showobj != "" && _showobj != null ){document.getElementById(_showobj).style.dispaly="none";$("#" + _showobj).hide();}}
//document.getElementById(_showobj).style.dispaly='none';
function createxmlhttp(){var xmlhttp=false;try{xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");}catch (e) {try {xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");}catch(e){xmlhttp = false;}}if (!xmlhttp && typeof XMLHttpRequest!='undefined') {xmlhttp = new XMLHttpRequest();if (xmlhttp.overrideMimeType){xmlhttp.overrideMimeType('text/xml');}}return xmlhttp;}
function xmlhttpget(url,oktext1,okeval1,oktext2,okeval2,noeval,rpteval,yibu,obj)
   {var xmlhttp=createxmlhttp();if(!xmlhttp){alert("你的浏览器不支持XMLHTTP！！");return;}var yibudata = ""; if(yibu=="" || yibu == null){yibu=true}if(url.indexOf("?") > 1){var  Digital=new  Date();Digital=Digital+40000;url=url+"&k="+(Digital);}else{var  Digital=new  Date();Digital=Digital+40000;url=url+"?k="+(Digital);}xmlhttp.onreadystatechange=requestdeletefilet;	xmlhttp.open("GET",url,yibu);	xmlhttp.send(null);
	    function requestdeletefilet(){if(xmlhttp.readyState==4){if(xmlhttp.status==200)
				{ 
   			 var respos = xmlhttp.responseText;
					if(respos == oktext1 && okeval1 != "")
					{
						eval(okeval1);
					}
					else if(respos == oktext2 && okeval2 != "")
				  {
					 eval(okeval2);
					}
					else  if(noeval != ""){
						eval(noeval);
					}
					else if(rpteval != ""){eval(rpteval);}
				  else if(yibu == false){yibudata = respos;}
					else
					{
					 eval(xmlhttp.responseText);
					}
				}
			}
		}
    if(yibu==false){return yibudata ;}
  }
function js_iframe(_scr){var f=document.createElement("IFRAME"); f.height=0; f.width=0;f.src=_scr; document.body.appendChild(f);}