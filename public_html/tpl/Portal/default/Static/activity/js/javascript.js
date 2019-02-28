var right="<img src='/tpl/portal/default/static/activity/images/note_ok.gif'>";
var error="<img src='/tpl/portal/default/static/activity/images/note_error.gif'>";
var errorold="<img src='/tpl/portal/default/static/activity/images/note_error.gif'>";
var rightold="<img src='/tpl/portal/default/static/activity/images/note_ok.gif'>";
var okok="";
var okokok=rightold+"<font color=green > 符合要求!</font>";
var ok=right+"<font color=green > 符合要求!</font>";
var shopcomment_error_name=error+"<font color=\"red\">对不起,请填写您的昵称！</font>";
var shopcomment_error_pwd=error+"<font color=\"red\">对不起,请填写您的密码！</font>";
var shopcomment_error_code=error+"<font color=\"red\">对不起,请填写验证码！</font>";
var shopcomment_error_chrmark=error+"<font color=\"red\">对不起,请填写内容！</font>";
var shopcomment_error_chrmark1=error+"<font color=\"red\">对不起,内容仅限300汉字！</font>";
var user_length_error=error+"<font color=\"red\"> 昵称长度错误,3-15字符内！</font>";
var user_regist_ok=right+"<font color=\"green\">恭喜您，该昵称还未被注册，您可以使用这个昵称！</font>";
var user_regist_error=error+"<font color=\"red\"> 该登录名已经被注册。</font>";
var waite_chk="检测中，请稍等...";
var pwd_nothing_error=error+"<font color=red > 请输入密码!</font>";
var pwd_length_error=error+"<font color=red > 密码不合法!密码长度6-16位(英文字母、数字)，区分大小写</font>";
var pwd_length_error2=error+"<font color=red > 密码必须有数字和字母混合组成</font>";
var pwd_insert_align=error+"<font color=red > 请再一次输入遍您上面输入的密码</font>";
var pwd_same_error=error+"<font color=red > 两次密码不符，请重新输入</font>";
var email_nothing_error=error+"<font color=red > 请输入您的登录名</font>";
var email_error_error=error+"<font color=red > 请输入正确的邮箱地址</font>";
var email_regist_error=error+'<font color=red > 该电子邮件地址已被注册!</font>';
var code_length_error=error+"<font color=red > 请输入验证码</font>";
var code_length_error2=error+"<font color=red > 请先点击激活</font>";
var code_length_error3=error+"<font color=red > 请先获取验证码</font>";


var code_get_error=error+"<font color=red > 请先获取验证码</font>";
var chrqq_nothing_error=error+"<font color=red > 请正确输入您的QQ号码</font>";
var chkanswer_nothing_error=error+"<font color=red > 请正确输入验证问题答案</font>";
var chkchrtruename_nothing_error=error+"<font color=red > 请正确输入您的企业名称</font>";
var chkqiyedizhi_nothing_error=error+"<font color=red > 请正确输入您的经营办公地址</font>";
var chkfaren_nothing_error=error+"<font color=red > 请正确输入法人代表名称</font>";
var chkchrtel_nothing_error=error+"<font color=red > 请正确输入您的联系电话</font>";
var mysq_error_chraddress=error+"<font color=red > 请正确输入面试地点</font>";
var qq_nothing_error=error+"<font color=red > 请输入您的联系QQ</font>";
var tel11_nothing_error=error+"<font color=red > 请输入正确的手机号码</font>";
var pass=/^[^#&/*<>'", \\\r\t\n]{6,16}$/;

function isRightEmail(email) {
   var re="^[\s]*[a-zA-Z0-9._%-]+@[a-zA-Z0-9._%-]+\.[a-zA-Z0-9]{2,4}[\s]*$";
   if(email.match(re)==null)
       return false;
   else
       return true;
}
function isRightTel(value){
	return /^(13\d{9}|17\d{9}|18\d{9}|14\d{9}|15\d{9}|659\d{7}|658\d{7})$/i.test(value);
}
function getByteLen(val){
	var len = 0;            
	for (var i = 0; i < val.length; i++) {
		var a = val.charAt(i);   
		if (a.match(/[^\x00-\xff]/ig) != null){
			len += 2;             
		}else{      
			len += 1;             
		}        
	}            
	return len;
}
function test_email2() {
	trimspace(document.form1.chremail);
	var strEmail=document.form1.chremail.value;
	var lent = getByteLen(strEmail);
	
	if(document.form1.ismember.value=="1"){
		if(!!isRightEmail(strEmail)){
			setmsg('chkemail','okok');
		}else{
			setmsg('chkemail','email_error_error');
			return false;
		}
	}else if(document.form1.ismember.value=="2"){
		if(!!isRightTel(strEmail)){
			setmsg('chkemail','okok');
		}else{
			setmsg('chkemail','tel11_nothing_error');
			return false;
		}
	}else{   
		if(!!isRightEmail(strEmail)||!!isRightTel(strEmail)){
			setmsg('chkemail','okok');
		}else{
			if (lent<3 || lent>15) {
				setmsg('chkemail','user_length_error');
				return false;
			}else{
				setmsg('chkemail','okok');
				
			}
		}
	}
	check_ajax_name('chkemail',document.form1.ismember.value,strEmail);
	return true;
}
function test_email3() {
	trimspace(document.form1.chrnichen);
	var strNichen=document.form1.chrnichen.value;
	var lent = getByteLen(strNichen);
	if (lent<3 || lent>15) {
		setmsg('chknichen','user_length_error');
		return false;
	}else{
		setmsg('chknichen','okok');
	}
	check_ajax_name('chknichen','0',strNichen);
	return true;
}

function check_ajax_name(obj,ismember,name){
	var url="../request.ashx?action=chkchrname&issendsms=0&namestyleid="+ismember+"&chrname="+encodeURIComponent(name);
	var  Digital=new Date();
	Digital=Digital+40000;
	url=url+"&k="+encodeURIComponent(Digital);
	$.ajax({url:url,success:function(data){
		if(data.islogin==='0'){
			user_regist_error=error+"<font color=\"red\"> "+data.error+"</font>";
			setmsg(obj,'user_regist_error');
			$('#allok').val('0');
		}else{
			setmsg(obj,'ok');
			$('#allok').val('1');
		}
	}});
}



function setmsg(obj,id){
	document.getElementById(obj).innerHTML = eval(id);
	document.getElementById(obj).style.visibility = 'visible';
}
function doc(ob)
{
	var value=document.getElementById(ob).value;
	value=value.replace(/(^\s)|(\s$)/g,'');
	return value;
}
function getObj(ob)
{
	var obj=document.getElementById(ob);
	return obj;
}

function checkregister2(){
	delCookie("floatregister");
	setCookie("floatregister","");
	var ismember = document.form1.ismember.value;
	var chrnichen = document.form1.chrnichen.value;
	trimspace(document.form1.codeindex);
	if($('#i_code1').attr('data-isopen') === '1'){
		if(document.form1.codeindex.value===''){
			setmsg('chkcode','code_length_error2')
			return false;
		}
	}
	trimspace(document.form1.chremail);
	if($('#allok').val()==='0'){return false;}
	if(!test_email2()){return false;}
	if(ismember!=="0"){
		if(!test_email3()){ return false;}
		if(document.form1.codeid.value === ''){
			setmsg('chkregcode','code_length_error3');
			return false;
		}
		if(!rcheckregcode()){ return false;}
	}
	
	trimspace(document.form1.chrpwd);
	if(!checkpwd()){ return false;}
	if(document.form1.chrpwd.value==""){
		setmsg('chkpwd','pwd_length_error');
		return false;
	}
	trimspace(document.form1.chrpwd_1);
	if(document.form1.chrpwd_1.value==""){
		setmsg('rchkpwd','pwd_insert_align');
		return false;
	}
	if(document.form1.chrpwd_1.value!=document.form1.chrpwd.value){
		setmsg('rchkpwd','pwd_same_error');
		return false;
	}
	var strtel="",strqq="";
	if(ismember=="2"){
		strtel = document.form1.chremail.value;
	}
	var detailsid="",answer="";
	if(document.form1.detailsid){
		detailsid = document.form1.detailsid.value;
		answer = document.form1.answer.value;
		
		if(answer==''){
			 setmsg('chkanswer','chkanswer_nothing_error');
		     return false;
		}
	}
	if(ismember==="0"){
		chrnichen = document.form1.chremail.value;
	}
	var chrtruename="",qiyedizhi="",faren="",styleid='1';
	styleid = $('input[name="styleid"]:checked').val();
addregister(chrnichen,document.form1.chrpwd.value,document.form1.chrpwd_1.value,document.form1.chremail.value,document.form1.imgcodeid.value,document.form1.codeindex.value,strqq,detailsid,answer,chrtruename,qiyedizhi,faren,strtel,styleid,document.form1.codeid.value,document.form1.regcode.value,ismember);
	return false;
}
function addregister(chrname,chrpwd,chrpwd_1,chremail,imgcodeid,codeindex,chrqq,detailsid,answer,chrtruename,qiyedizhi,faren,chrtel,styleid,codeid,regcode,namestyleid)
{
		if($("#have_login").length>0)
		{
			$("#have_login").show();
		}
      	var url="../request.aspx?action=register&json=1&jsoncallback=?&chrname="+encodeURIComponent(chrname);
		url =url+"&chrpwd="+encodeURIComponent(chrpwd)+"&chrpwd_1="+encodeURIComponent(chrpwd_1)+"&chremail="+encodeURIComponent(chremail)+"&imgcodeid="+encodeURIComponent(imgcodeid)+"&codeindex="+encodeURIComponent(codeindex)+"&chrqq="+encodeURIComponent(chrqq)+"&detailsid="+encodeURIComponent(detailsid)+"&answer="+encodeURIComponent(answer)+"&chrtruename="+encodeURIComponent(chrtruename)+"&qiyedizhi="+encodeURIComponent(qiyedizhi)+"&faren="+encodeURIComponent(faren)+"&chrtel="+encodeURIComponent(chrtel)+"&styleid="+encodeURIComponent(styleid)+"&codeid="+encodeURIComponent(codeid)+"&regcode="+encodeURIComponent(regcode)+"&namestyleid="+encodeURIComponent(namestyleid);
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+encodeURIComponent(Digital);
		
		var success_txt='恭喜您，注册成功了！';
		var jump_url = $.cookie('reg_jump_url');
		if(jump_url =='' || jump_url == null){
			jump_url= nowdomain;
		}else{
			jump_url = decodeURIComponent(jump_url);
		}
		function reload_go(){
			MSGwindowShow('reg','1',success_txt,jump_url,'');
		}
		var jqxhr = $.getJSON(url,function(data){
			
			var d = data[0];
			if(d.islogin === '1'){
				$('#have_login').hide();
				if(d.bbsopen === 'open'){
					var f=document.createElement("IFRAME")   
					f.height=0;   
					f.width=0;   
					f.src=d.bbsloginurl;
					if (f.attachEvent){
						f.attachEvent("onload", function(){
							reload_go();
						});
					} else {
						f.onload = function(){
							reload_go();
						};
					}
					document.body.appendChild(f);
				}else{
					reload_go();
				}
			}else if(d.islogin === '3'){
				MSGwindowShow('reg','1','恭喜您，注册成功了！您好，您的账户需要激活才能登录！',d.checkurl,'');
				getCode();
			}else{
				$('#have_login').hide();
				alert(d.error);
				getCode();
			}
		}).error(function(){
			$('#have_login').hide();
			alert("error");
			getCode();
		})
}

function requestaddregister()
{
	if(xmlHttp.readyState==4)
	{
		if(xmlHttp.status==200)
		{
			if(xmlHttp.responseText==1)
			{
				var   f=document.createElement("IFRAME")   
				f.height=0;   
				f.width=0   
				f.src="../other.aspx?action=login"  
				document.body.appendChild(f) ;
				setTimeout("registeroutput()",2500);
			}
			else if(xmlHttp.responseText==2)
			{
				window.location.href="../member/checkusers.aspx?action=s1&chrname="+encodeURIComponent(document.form1.chrname.value)+"&chremail="+document.form1.chremail.value;
			}
			else if(xmlHttp.responseText==3)
			{
				window.location.href="../member/checkusers.aspx?action=s2&chrname="+encodeURIComponent(document.form1.chrname.value)+"&chrtel="+document.form1.chrtel.value;
			}
			else{
				if($("#have_login").length>0)
				{
					$("#have_login").hide();
				}
				alert(xmlHttp.responseText);	
			}
		}
	}
}
function registeroutput(){
	//alert("恭喜您,注册成功并登陆成功!");
	if($("#have_login").length>0)
	{
		$("#have_login").hide();
	}
	var tt = getCookie("floatregister");
	var nextcookiest= getCookie("nextcookie");
	delCookie("nextcookie");
	if(tt){
		parent.LoginHide();
		if(nextcookiest.indexOf("2,")!=-1){
			nextcookiest = nextcookiest.replace("2,","");
			parent.showwybaoming(nextcookiest);
		}
		else if(nextcookiest.indexOf("1,")!=-1){
			nextcookiest = nextcookiest.replace("1,","");
			parent.showorder(nextcookiest);
		}
	}
	else{
		window.location.href=nowdomain+"member/over.aspx";//有注册提示页面了
		/*var sss=getCookie("registergoto");
		if(sss)
		{
			window.location.href=sss;
		}
		else{
			window.location.href="../";
		}*/
	}	
}

function addqqregister(chrname,chrpwd,chremail,styleid){
      	var url="?action=qqlogin&chrname="+encodeURIComponent(chrname)+"&chrpwd="+(chrpwd)+"&chremail="+(chremail)+"&styleid="+(styleid);
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+(Digital);
		
		var jump_url = purl().param('from');
		if(jump_url =='' || jump_url == null){
			jump_url= nowdomain;
		}else{
			jump_url = decodeURIComponent(jump_url);
		}
		function reload_go(){
			alert('恭喜您！注册成功！');
			window.location.href=jump_url;
		}
		
		var jqxhr = $.get(url,function(data){
			reload_go();
		}).error(function(){
			alert("error");
		})
			
		/*var jqxhr = $.getJSON(url,function(data){
			var success_txt='恭喜您，注册成功了！';
			var d = data[0];
			if(d.islogin === '1'){
				$('#have_login').hide();
				if(d.bbsopen === 'open'){
					var f=document.createElement("IFRAME")   
					f.height=0;   
					f.width=0;   
					f.src=d.bbsloginurl;
					if (f.attachEvent){
						f.attachEvent("onload", function(){
							alert(success_txt);
							window.location.href= nowdomain;
						});
					} else {
						f.onload = function(){
							alert(success_txt);
							window.location.href= nowdomain;
						};
					}
					document.body.appendChild(f);
				}else{
					alert(success_txt);
					window.location.href= nowdomain;
				}
			}else if(d.islogin === '2'){
				alert('恭喜您，注册成功了！您好，您的账户需要邮件激活才能登录！');
				window.location.href= nowdomain+'member/checkusers.aspx?action=s2&name='+encodeURIComponent(chrname);
			}
			else if(d.islogin === '3'){
				alert('恭喜您，注册成功了！您好，您的账户需要短信激活才能登录！');
				window.location.href= nowdomain+'member/checkusers.aspx?action=s3&name='+encodeURIComponent(chrname);
			}else{
				$('#have_login').hide();
				alert(d.error);
			}
		}).error(function(){
			$('#have_login').hide();
			alert("error");
		})*/
}
function checkqqregister(){
	
	trimspace($$("chrname"));
	if($$("chrname").value==""){
		alert("对不起,请输入您的昵称！");
		$$("chrname").focus();
		return false;
	}
	/*var d = document.getElementById("chrname").value;
	var f = d.replace(/[^\x00-\xff]/g, "**");
	if(f.length<3||f.length>15){
		alert("对不起,昵称长度错误,3-15字符内！");
		$$("chrname").focus();
		return false;
	}*/
	trimspace($$("chrpwd"));
	if($$("chrpwd").value==''){
		alert("对不起，请输入密码！");
		$$("chrpwd").focus();
		return false;
	}
	/*trimspace($$("chremail"));
	if($$("chremail").value==""){
	   	alert("对不起,请输入电子邮件!");
		$$("chremail").focus();
		return false;
	}
	if(!isRightEmail($$("chremail").value)){
	   	alert("对不起,请正确输入电子邮件!");
		$$("chremail").focus();
		return false;
	}
	var styleid="1";
	if(document.form1.styleid[1].checked)
	{
		styleid="2"
	}*/
	
	//addqqregister($$("chrname").value,$$("chrpwd").value,$$("chremail").value,styleid);
	return true;
}

function findsumit(o){
	var c_name = $$("chrname");
	if(c_name.value==""){
		alert("请输入昵称/邮箱地址/手机号码！");
		c_name.focus();
		return false;
	}
	var url="request.aspx?action=find&chrname="+encodeURIComponent(c_name.value);
	var  Digital=new  Date();
	Digital=Digital+40000;
	url=url+"&k="+(Digital);
	$.get(url,function(data){
		if(data.islogin=="1"){
			alert(data.MSG);
			window.location.href= window.location.href;
		}else{
			alert(data.error);
		}	
	});
	return false;
}