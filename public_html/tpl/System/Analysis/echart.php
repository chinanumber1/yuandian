<include file="Public:header" />

	<!-- 内容头部 -->
        <div class="mainbox">
            <div id="nav" class="mainnav_title">
                    <ul>
                    </ul>
            </div>
            <div class="page-content">
                    <div class="page-content-area">
                            <div class="row">
                                    <div class="col-xs-12" style="padding-left:0px;padding-right:0px;">
                                        <div class="widget-box">
                                            <div class="widget-header">
                                                <div class="year"></div>
                                                <div class="month"></div>
                                            </div>
                                            <div class="widget-body" id="main" style="text-align:center;">
                                                 <div id="chart1" style="width:100%;height:600px;"></div>
                                            </div>
                                            <div style="clear:both;"></div>
                                            <script type="text/javascript" src="{pigcms{$static_public}echarts/echarts.js"></script>
                                            <script type="text/javascript">
                                                var title = '';
                                                var star_year={pigcms{$star_year};
                                                var now = new Date();
                                                var now_year=now.getFullYear();
                                                    $(document).ready(function(){
                                                        $.ajax({
                                                            url:'/admin.php?g=System&c=Analysis&a=getmenu',
                                                            type:"post",
                                                            dataType:"JSON",
                                                            success:function(data){
                                                                $.each(data,function(func,value){
                                                                    if(func=='getuserc'){
                                                                        $('.mainnav_title ul').append('<a href="JavaScript:void(0)" id ="'+func+'" onclick="ajaxSend(\''+func+'\''+",'','',2016,''"+')" class="on">'+value+'</a>');
                                                                    }else{
                                                                        $('.mainnav_title ul').append('<a href="JavaScript:void(0)" id ="'+func+'" onclick="ajaxSend(\''+func+'\''+",'','',2016,''"+')">'+value+'</a>');
                                                                    }
                                                                });
                                                            }
                                                        });
                                                        ajaxSend('getuserc','','',2016,'');
                                                    });
                                                    var myChart1 = echarts.init(document.getElementById('chart1'));
                                                    myChart1.on('click', function (param) {
                                                        if(param.data.type<3||param.data.functionName=='villagec'){
                                                            ajaxSend(param.data.functionName,param.data.id,param.data.type,param.data.year,param.data.month);
                                                        }
                                                        console.log(param);
                                                    });
                                                    function ajaxSend(func,areaid,_type,year,month){
                                                        getYear(func,areaid,_type,year,month);
                                                        year=year==null?now_year:year;
                                                        getMonth(func,areaid,_type,year,month);
                                                        $('.mainnav_title ul a').removeClass('on');
                                                        $('#'+func).addClass('on');
                                                        if(year!=null){
                                                            $('.year').removeClass('on');
                                                            $('#'+func+year).addClass('on');
                                                        }
                                                        if(month!=null){
                                                            $('.month').removeClass('on');
                                                            $('#'+func+month).addClass('on');
                                                        }
                                                        $.ajax({
                                                            url:'/admin.php?g=System&c=Analysis&a='+func,
                                                            type:"post",
                                                            dataType:"JSON",
                                                            data: {area_id: areaid,type:_type,year:year,month:month},
                                                            success:function(date){
                                                                var json=eval(date);
                                                                if(json.error.length>0){
                                                                    alert(json.error);
                                                                }
                                                                var series =new Array();
                                                                var title = json.area_pname;
                                                                if(json.msg!=null&&json.msg!=''){
                                                                    var tmp = new Array();
                                                                    $.each(json.msg,function(i,value){
                                                                        var k_=new Array();
                                                                        for(var k in value){
                                                                            k_.push(k);
                                                                        }
                                                                        tmp.push({value:value[k_[2]],name:value[k_[1]],id:value[k_[0]],type:value[k_[3]],functionName:func,year:year,month:month});
                                                                    });
                                                                    series.push(editSeries(tmp,'1',['25%', 300]));
                                                                }else{
                                                                    series.push(editSeries('','1',['25%', 300]));
                                                                }
                                                                if (json.type_money!=null) {
                                                                    var tmp =new Array();
                                                                    $.each(json.type_money,function(type,money){
                                                                        var t_=new Array();
                                                                        var link = '';
                                                                        for(var t in money){
                                                                            t_.push(t);
                                                                        }
                                                                        tmp.push({value:money[t_[2]],name:money[t_[0]]});
                                                                    });
                                                                    if(json.msg==null||json.msg==''){
                                                                        series.push(editSeries(tmp,'2', ['25%', 300],function (value){ return "#"+("00000"+((Math.random()*16777215+0.5)>>0).toString(16)).slice(-6); }));
                                                                    }else{
                                                                        series.push(editSeries(tmp,'2', ['75%', 300],function (value){ return "#"+("00000"+((Math.random()*16777215+0.5)>>0).toString(16)).slice(-6); }));
                                                                    }
                                                                }else{
                                                                    series.push(editSeries('','2',['75%', 300]));
                                                                }
                                                                var option={  
                                                                    title: {
                                                                        text:title,
                                                                    },
                                                                    series : series
                                                                };
                                                                console.log(series);
                                                                myChart1.setOption(option);
                                                            }
                                                        });
                                                    }
                                                    function editSeries(datas,title,cen,color){
                                                        var serie = {
                                                            name: title,
                                                            type: 'pie',
                                                            radius: '55%',
                                                            center:cen,
                                                            data:datas,
                                                            startAngle:270,
                                                            itemStyle: {
                                                                normal: {
                                                                    shadowBlur: 200,
                                                                    color:color,
                                                                    shadowColor: 'rgba(0, 0, 0, 0.5)',
                                                                    label:{ 
                                                                        show: true, 
                                                                        formatter: '{b} : {c} {d}%' 
                                                                    }, 
                                                                    labelLine :{show:true,length:50}
                                                                }
                                                            }
                                                        };
                                                        return serie;
                                                    }
                                                    function getYear(func,areaid,_type,year,month){
                                                        if(func!='getuserc'&&func!='merc'&&func!='fanc'){
                                                            $('.year').empty();
                                                            var year_list='<div id="nav" class="mainnav_title"><ul>';
                                                            year_list+='<font color="#000">年 :</font>' ;
                                                            for(var year=star_year;year<=now.getFullYear();year++){
                                                                year_list+='<a href="JavaScript:void(0)" id="'+func+year+'" onclick="ajaxSend('+"'"+func+"','"+areaid+"','"+_type+"','"+year+"',''"+')" >'+year+'</a> | ';
                                                            }
                                                            year_list+='</ul></div>'
                                                            $('.year').append(year_list);
                                                        }else{
                                                            $('.year').empty();
                                                        }
                                                    }
                                                    function getMonth(func,areaid,_type,year,month){
                                                        if(func!='getuserc'&&func!='merc'&&func!='fanc'){
                                                            $('.month').empty();
                                                            var now = new Date();
                                                            var month_list = '<div id="nav" class="mainnav_title"><ul>';
                                                            month_list+='<font color="#000">月 :</font>' ;
                                                            var month_end = year<now.getFullYear()?12:now.getMonth()+1;
                                                            for (var i = 1; i <= month_end; i++) {
                                                               month_list +='<a href="JavaScript:void(0)" id='+func+i+' onclick="ajaxSend('+"'"+func+"','"+areaid+"','"+_type+"','"+year+"','"+i + '\')" >'+i+'月 '+'</a>';
                                                            }
                                                            month_list+='</ul></div>';
                                                            $('.month').empty();
                                                            $('.month').append(month_list);
                                                        }else{
                                                            $('.month').empty();
                                                        }
                                                    }
                                            </script>

                                        </div>
                                    </div>
                            </div>
                    </div>
            </div>
  
<include file="Public:footer"/>

