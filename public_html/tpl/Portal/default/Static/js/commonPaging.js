function get_data_paging(callback){
	var url = nowdomain + 'request_paging.ashx?jsoncallback=?';
	$.getJSON(url,keyvalues,function(data){
		callback&&callback.call(this,data);
	});
}
function getNewPage(page){
	getPagingGlobal({'p':page});
	return false;
}
window['categoryJson']=[];
function categoryData_set(categoryidStyleid,bigcategoryidName,JsonNum,categoryidselectID,categoryid){
	var bigcategoryid = $("#" + bigcategoryidName).val();//获取一级选中值
	if(categoryJson[JsonNum] != null){showcategory_sel(JsonNum,bigcategoryid,categoryidselectID,categoryid);return;}//带缓存直接运行数据分析
	var ajaxUrl = nowdomain + "request.ashx?jsoncallback=?&action=category&id=" +　categoryidStyleid;
	var Digital=new Date().getTime();
	ajaxUrl=ajaxUrl+"&_k="+encodeURIComponent(Digital);
	$.getJSON(ajaxUrl,function(data){
		categoryJson[JsonNum] = data[0].MSG;//category接口不带islogin直接获取MSG
    	showcategory_sel(JsonNum,bigcategoryid,categoryidselectID,categoryid);
	});
}
function showcategory_sel(JsonNum,bigcategoryid,categoryidselectID,_categoryid){
	if(categoryJson[JsonNum] == null){return;}
	var sel=document.getElementById(categoryidselectID);
	var val="::请选择::";
	sel.options.length=0;
	sel.options.add(new Option( val,""));
	for(var i=0;i<categoryJson[JsonNum].length;i++){
		if(categoryJson[JsonNum][i].id == bigcategoryid){
			for(var _Tcategory in categoryJson[JsonNum][i].arr){
				sel.options.add(new Option(categoryJson[JsonNum][i].arr[_Tcategory],_Tcategory)); 
				if(_categoryid==_Tcategory){
					sel.options[sel.options.length-1].selected=true;
				}
			}
			return;
		}
	}
}