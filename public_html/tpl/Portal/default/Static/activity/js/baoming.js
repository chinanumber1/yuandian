var chrbaotruename_error=errorold+"<font color=red > 对不起,请填写您的真实姓名!</font>";
var chrbaotruename11_error=errorold+"<font color=red > 请正确填写您的真实姓名!</font>";
var chkbaochrtel_error=errorold+"<font color=red > 对不起,请填写您的手机号码!</font>"; 
var chkbaoquyu_error=errorold+"<font color=red > 对不起,请选择您所在区域!</font>";
var chkchrage_error=errorold+"<font color=red > 对不起,请选择您的年龄段范围!</font>";
var chknum_error=errorold+"<font color=red > 对不起,请填写您参与这次活动的参加人数!</font>";
var chkchrmark_error=errorold+"<font color=red > 对不起,简短附言限定在100字内!</font>";
/********用于判断报名所填写的**********/
function checkbaoming(){
	trimspace($$("truename"))
	if($$("truename").value==""){
		setmsg('chkbaotruename','chrbaotruename_error');
		return false;
	}
	if($$("truename").value.length<2){
		setmsg('chkbaotruename','chrbaotruename11_error');
		return false;
	}	
	setmsg('chkbaotruename','okokok');
	trimspace($$("chrtel"))
	if($$("chrtel").value==""){
		setmsg('chkbaochrtel','chkbaochrtel_error');
		return false;
	}
	if($$("chrtel").value.length<7){
		setmsg('chkbaochrtel','chkbaochrtel_error');
		return false;
	}
	if(!$$("check").checked){
		alert('请确认阅读并接受活动协议！');
		return false;
	}
	var sex = '',chrage = '',num = '',chrdiqu1 = '';
	/*if($$("chrage").value==""){
		setmsg('chkbaochrage','chkchrage_error');
		return false;
	}
	trimspace($$("num"))
	if($$("num").value==""){
		setmsg('chkbaonum','chknum_error');
		return false;
	}
	if($$("chrdiqu1").value==""){
		setmsg('chkbaoquyu','chkbaoquyu_error');
		return false;
	}*/
	trimspace($$("chrmark"))
	if($$("chrmark").value.length>100){
		setmsg('chrmark','chkchrmark_error');
		return false;
	}
	var selectedIndex = -1;
    var form1 = document.getElementById("form1");
    var i = 0;
	for (i=0; i<form1.styleid.length; i++)
    {
        if (form1.styleid[i].checked)
        {
            selectedIndex = form1.styleid[i].value;
            break;
        }
    }
	addbaoming($$("activeid").value,$$("truename").value,sex,$$("chrtel").value,$$("chrqq").value,chrage,num,chrdiqu1,selectedIndex,$$("chrmark").value);
	return false;
}

function checkbaonum(){
	trimspace($$("num"))
	if($$("num").value.length==0 || $$("num").value=="0"){
		setmsg('chkbaonum','chknum_error');
		return false;
	}
	else{
		setmsg('chkbaonum','okokok');
	}
}
function checkbaochrmark(){
	trimspace($$("chrmark"))
	if($$("chrmark").value.length>100){
		setmsg('chkbaochrmark','chkchrmark_error');
		return false;
	}
	else{
		setmsg('chkbaochrmark','okokok');
	}
}




function checkbaotruename(){
	trimspace($$("truename"))
	if($$("truename").value==""){
		setmsg('chkbaotruename','chrbaotruename_error');
		return false;
	}
	else{
		if($$("truename").value.length<2){
			setmsg('chkbaotruename','chrbaotruename11_error');
			return false;
		}else{
			setmsg('chkbaotruename','okokok');
		}
	}
}
function checkbaochrtel(){
	trimspace($$("chrtel"))
	if($$("chrtel").value.length<7){
		setmsg('chkbaochrtel','chkbaochrtel_error');
		return false;
	}
	else{
		setmsg('chkbaochrtel','okokok');
	}
}
function showdididi(aa){
	trimspace($$("chrdiqu1"))
	if($$("chrdiqu1").value.length==0){
		setmsg('chkbaoquyu','chkbaoquyu_error');
		return false;
	}
	else{
		setmsg('chkbaoquyu','okokok');
	}
}
function checkbaochrage(){
	trimspace($$("chrage"))
	if($$("chrage").value.length==0){
		setmsg('chkbaochrage','chkchrage_error');
		return false;
	}
	else{
		setmsg('chkbaochrage','okokok');
	}
}

function addbaoming(activeid,truename,chrsex,chrtel,chrqq,chrage,num,quyu,styleid,chrmark)
{
      	var url="request.aspx?action=baoming&activeid="+escape(activeid);
		url =url+"&truename="+escape(truename)+"&chrsex="+escape(chrsex)+"&chrqq="+escape(chrqq)+"&chrage="+escape(chrage)+"&num="+escape(num);
		url =url+"&chrtel="+escape(chrtel)+"&quyu="+escape(quyu)+"&styleid="+escape(styleid)+"&chrmark="+escape(chrmark);
		var  Digital=new  Date();
		Digital=Digital+40000;
		url=url+"&k="+Digital;
		xmlHttp=GetXmlHttpObject(requestaddbaoming)
		xmlHttp.open("get", url , true)
		xmlHttp.send(null)
}

function requestaddbaoming()
{
	if(xmlHttp.readyState==4)
	{
		if(xmlHttp.status==200)
		{
			if(xmlHttp.responseText!=1)
			{
				alert(xmlHttp.responseText);	
			}
			else{
				alert("恭喜您,报名成功,请等待审核！");
				parent.LoginHide();
			}
		}
	}
}