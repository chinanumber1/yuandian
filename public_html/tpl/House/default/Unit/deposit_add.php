
<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('Unit/deposit_management')}">押金管理</a>
            </li>
            <li class="active">添加押金</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<form  class="form-horizontal" method="post">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group" style="position: relative;">
									<label class="col-sm-1"><label for="room_num">房间编号</label></label>

									<div class="col-sm-4" style="padding:0px ;position:relative">
										<input class="col-sm-2"  size="20" name="room_num" id="room_num" type="text"  value="" autocomplete="off" style="width:100%"/>
										<div id="searchBox" style="display: none;border:1px solid #F59942;position:absolute;left:0px;width:100%;max-height:300px;overflow-y:auto">
                                		</div>
										<div id="dropdown-menu" class="dropdown-menus " style="display:none">
										</div>
									</div>
								</div>
                               
								<div class="form-group">
									<label class="col-sm-1"><label for="pay_type">支付方式</label></label>
									<select name="pay_type" id="pay_type">
										<option value='0'>--请选择--</option>
										<volist name="pay_type_list" id="vol">
										<option value='{pigcms{$vol.id}'>{pigcms{$vol.name}</option>
										</volist>
									</select>
								</div>

								<div class="form-group deposit_name">
									<label class="col-sm-1"><label for="deposit_name">押金项目</label></label>
									<input class="col-sm-2" size="20" name="deposit_name" id="deposit_name" type="text"  value="" />
								</div>

								<div class="form-group payment_money">
									<label class="col-sm-1"><label for="payment_money">应缴金额</label></label>
									<input class="col-sm-2" size="20" name="payment_money" id="payment_money" type="text"  value="" />
								</div>

								<div class="form-group actual_money">
									<label class="col-sm-1"><label for="actual_money">实缴金额</label></label>
									<input class="col-sm-2" size="20" name="actual_money" id="actual_money" type="text"  value="" />
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>备注</label></label>
									<label><textarea name="deposit_note" id="deposit_note" maxlength="255" style="width:286px;height:90px;resize:none" placeholder="最多输入255个字"></textarea></label>
								</div>
							</div>
						</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info submit_info" type="button">
										<i class="ace-icon fa fa-check bigger-110"></i>
										增加
									</button>
								</div>
							</div>
					</form>
			    </div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div id="myModal" class="modal hide fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false" style="width:800px;    margin-left: -400px;">
	<div class="modal-header" style="background:#428bca">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel" style="color:#fff">选择住户</h3>
	</div>
	<div class="modal-body" style="padding-top:0px">
		<img src=""/>
		<div id="user_select" class="grid-view" style="display: block;padding-top:0px">
			<div id="" class="grid-view">
				<div class="navbar" style="background:none">
					<div class="navbar-inner">
						<div class="container" style="width:auto">
							<div class="nav-collapse collapse navbar-responsive-collapse" style="display:block">
								<ul class="nav" style="float:left">
									<!-- <li><a href="#">查询住户：</a></li> -->
									<li class="dropdown">
										<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span>业主手机号码</span><b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li><a href="#" data-type="2">业主手机号码</a></li>
											<li><a href="#" data-type="3">业主房间号</a></li>
											<li><a href="#" data-type="1">业主姓名</a></li>
										</ul>
									</li>
								</ul>
								<form class="navbar-search pull-left" action="">
									<div class="input-append" style="position:relative">
										<input class="span2" name="find_value" autocomplete="off" id="appendedInputButton" type="text" placeholder="请输入业主手机号码查询" style="padding-right:30px">
										<i class="removeInput hidden" >+</i>
										<button class="btn btn-primary btnGrayS" type="button" style="margin-left:-5px;border:none">搜索</button>
									</div>
								</form>
							</div>
						</div>
					</div><!-- /navbar-inner -->
				</div>
				<table class="table table-striped table-bordered table-hover" style="margin-bottom:0px">
					<thead>
					<tr>
						<th style="min-width:90px">姓名</th><th>手机号码</th><th>地址</th><th>房间号</th><th style="min-width:50px">操作</th>
					</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn  back" data-dismiss="modal">关闭</button>
		<button class="btn btn-primary prev hide btn_pre" >上一页</button>
		<button class="btn btn-primary next hide btn_pre">下一页</button>
	</div>
</div>
<style>
	#dropdown-menu{
		font-family: Monospaced Number,Chinese Quote,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,PingFang SC,Hiragino Sans GB,Microsoft YaHei,Helvetica Neue,Helvetica,Arial,sans-serif;
		line-height: 1.5;
		color: rgba(0,0,0,.65);
		margin: 0;
		padding: 0;
		list-style: none;
		-webkit-box-shadow: 0 2px 8px rgba(0,0,0,.15) !important;
		box-shadow: 0 2px 8px rgba(0,0,0,.15) !important;
		border-radius: 4px;
		-webkit-box-sizing: border-box;
		box-sizing: border-box;
		outline: none;
		font-size: 14px;
		min-width: auto;right:0px;left:0px;
		top:33px;
		position:absolute;
		z-index: 10;
		display: block;
		background: #fff;
	}
	#dropdown-menu .ant-select-dropdown-menu-item-active {
		background-color: #e6f7ff;
	}
	#dropdown-menu .ant-select-dropdown-menu-item {
		position: relative;
		display: block;
		padding: 5px 12px;
		line-height: 22px;
		font-weight: 400;
		color: rgba(0,0,0,.65);
		white-space: nowrap;
		cursor: pointer;
		overflow: hidden;
		text-overflow: ellipsis;
		-webkit-transition: background .3s ease;
		transition: background .3s ease;
	}
	#dropdown-menu .ant-select-dropdown-menu-item{line-height:35px;list-style: none;border:none !important;}
	#dropdown-menu .ant-select-dropdown-menu-item:hover {
		background-color: #e6f7ff
	}

	#dropdown-menu .ant-select-dropdown-menu-item:first-child {
		border-radius: 4px 4px 0 0;
	}

	#dropdown-menu .ant-select-dropdown-menu-item:last-child {
		border-radius: 0 0 4px 4px
	}
  .modal-body td,.modal-body th{text-align:center}


	.modal-backdrop {
		position: fixed;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		z-index: 1040;
		background-color: #000000;
	}

	.modal-backdrop.fade {
		opacity: 0;
	}

	.modal-backdrop,
	.modal-backdrop.fade.in {
		opacity: 0.8;
		filter: alpha(opacity=80);
	}

	.modal {
		position: fixed;
		top: 10%;
		left: 50%;
		z-index: 1050;
		width: 560px;
		margin-left: -280px;
		background-color: #ffffff;
		border: 1px solid #999;
		border: 1px solid rgba(0, 0, 0, 0.3);
		*border: 1px solid #999;
		-webkit-border-radius: 6px;
		-moz-border-radius: 6px;
		border-radius: 6px;
		outline: none;
		-webkit-box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
		-moz-box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
		box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);
		-webkit-background-clip: padding-box;
		-moz-background-clip: padding-box;
		background-clip: padding-box;
		bottom:auto !important;
	}


	.modal-backdrop, .modal-backdrop.fade.in {
		opacity: 0.4 !important;
		filter: alpha(opacity=40) !important;
	}
	.modal.fade {
		top: -25%;
		-webkit-transition: opacity 0.3s linear, top 0.3s ease-out;
		-moz-transition: opacity 0.3s linear, top 0.3s ease-out;
		-o-transition: opacity 0.3s linear, top 0.3s ease-out;
		transition: opacity 0.3s linear, top 0.3s ease-out;
	}

	.modal.fade.in {
		top: 10%;
	}

	.modal-header {
		padding: 9px 15px;
		border-bottom: 1px solid #eee;
	}

	.modal-header .close {
		margin-top: 2px;
	}

	.modal-header h3 {
		margin: 0;
		line-height: 30px;
	}

	.modal-body {
		position: relative;
		max-height: 400px;
		padding: 15px;
		overflow-y: auto;
	}

	.modal-form {
		margin-bottom: 0;
	}

	.modal-footer {
		padding: 14px 15px 15px;
		margin-bottom: 0;
		text-align: right;
		background-color: #f5f5f5;
		border-top: 1px solid #ddd;
		-webkit-border-radius: 0 0 6px 6px;
		-moz-border-radius: 0 0 6px 6px;
		border-radius: 0 0 6px 6px;
		*zoom: 1;
		-webkit-box-shadow: inset 0 1px 0 #ffffff;
		-moz-box-shadow: inset 0 1px 0 #ffffff;
		box-shadow: inset 0 1px 0 #ffffff;
	}

	.modal-footer:before,
	.modal-footer:after {
		display: table;
		line-height: 0;
		content: "";
	}

	.modal-footer:after {
		clear: both;
	}

	.modal-footer .btn + .btn {
		margin-bottom: 0;
		margin-left: 5px;
	}

	.modal-footer .btn-group .btn + .btn {
		margin-left: -1px;
	}

	.modal-footer .btn-block + .btn-block {
		margin-left: 0;
	}

	.pagination {
		margin: 20px 0;
	}

	.pagination ul {
		display: inline-block;
		*display: inline;
		margin-bottom: 0;
		margin-left: 0;
		-webkit-border-radius: 4px;
		-moz-border-radius: 4px;
		border-radius: 4px;
		*zoom: 1;
		-webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
		-moz-box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
		box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
	}

	.pagination ul > li {
		display: inline;
	}

	.pagination ul > li > a,
	.pagination ul > li > span {
		float: left;
		padding: 4px 12px;
		line-height: 20px;
		text-decoration: none;
		background-color: #ffffff;
		border: 1px solid #dddddd;
		border-left-width: 0;
	}

	.pagination ul > li > a:hover,
	.pagination ul > li > a:focus,
	.pagination ul > .active > a,
	.pagination ul > .active > span {
		background-color: #f5f5f5;
	}

	.pagination ul > .active > a,
	.pagination ul > .active > span {
		color: #999999;
		cursor: default;
	}

	.pagination ul > .disabled > span,
	.pagination ul > .disabled > a,
	.pagination ul > .disabled > a:hover,
	.pagination ul > .disabled > a:focus {
		color: #999999;
		cursor: default;
		background-color: transparent;
	}

	.pagination ul > li:first-child > a,
	.pagination ul > li:first-child > span {
		border-left-width: 1px;
		-webkit-border-bottom-left-radius: 4px;
		border-bottom-left-radius: 4px;
		-webkit-border-top-left-radius: 4px;
		border-top-left-radius: 4px;
		-moz-border-radius-bottomleft: 4px;
		-moz-border-radius-topleft: 4px;
	}

	.pagination ul > li:last-child > a,
	.pagination ul > li:last-child > span {
		-webkit-border-top-right-radius: 4px;
		border-top-right-radius: 4px;
		-webkit-border-bottom-right-radius: 4px;
		border-bottom-right-radius: 4px;
		-moz-border-radius-topright: 4px;
		-moz-border-radius-bottomright: 4px;
	}

	.pagination-centered {
		text-align: center;
	}

	.pagination-right {
		text-align: right;
	}

	.pagination-large ul > li > a,
	.pagination-large ul > li > span {
		padding: 11px 19px;
		font-size: 17.5px;
	}

	.pagination-large ul > li:first-child > a,
	.pagination-large ul > li:first-child > span {
		-webkit-border-bottom-left-radius: 6px;
		border-bottom-left-radius: 6px;
		-webkit-border-top-left-radius: 6px;
		border-top-left-radius: 6px;
		-moz-border-radius-bottomleft: 6px;
		-moz-border-radius-topleft: 6px;
	}

	.pagination-large ul > li:last-child > a,
	.pagination-large ul > li:last-child > span {
		-webkit-border-top-right-radius: 6px;
		border-top-right-radius: 6px;
		-webkit-border-bottom-right-radius: 6px;
		border-bottom-right-radius: 6px;
		-moz-border-radius-topright: 6px;
		-moz-border-radius-bottomright: 6px;
	}

	.pagination-mini ul > li:first-child > a,
	.pagination-small ul > li:first-child > a,
	.pagination-mini ul > li:first-child > span,
	.pagination-small ul > li:first-child > span {
		-webkit-border-bottom-left-radius: 3px;
		border-bottom-left-radius: 3px;
		-webkit-border-top-left-radius: 3px;
		border-top-left-radius: 3px;
		-moz-border-radius-bottomleft: 3px;
		-moz-border-radius-topleft: 3px;
	}

	.pagination-mini ul > li:last-child > a,
	.pagination-small ul > li:last-child > a,
	.pagination-mini ul > li:last-child > span,
	.pagination-small ul > li:last-child > span {
		-webkit-border-top-right-radius: 3px;
		border-top-right-radius: 3px;
		-webkit-border-bottom-right-radius: 3px;
		border-bottom-right-radius: 3px;
		-moz-border-radius-topright: 3px;
		-moz-border-radius-bottomright: 3px;
	}

	.pagination-small ul > li > a,
	.pagination-small ul > li > span {
		padding: 2px 10px;
		font-size: 11.9px;
	}

	.pagination-mini ul > li > a,
	.pagination-mini ul > li > span {
		padding: 0 6px;
		font-size: 10.5px;
	}
	.pagination-mini a.active{color:#08c}
	#myModal .nav>li{float:left}
	.navbar-search{
		position: relative;
		float: left;
		margin-top: 5px;
		margin-bottom: 0;
	}

	.removeInput{
		position: absolute;
		transform: rotate(45deg);
		background: #ddd;
		cursor: pointer;
		font-size: 22px;
		text-align: center;
		height: 24px;
		right: 55px;
		top: 3px;
		width: 24px;
		border-radius: 50%;
		display: block;
	}
	.container {
    	margin-bottom: 15px;
    	margin-left:150px;
	}
	.btn {
		font-size: 13px;
	}
</style>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="./static/js/artdialog/simplite.js"></script>
<script type="text/html" id="index">
       <& if(_this.length>0) {&>
	     <& for(var i=0;i< _this.length;i++) {&>
	      <& var item=_this[i] &>
	      <tr><td data-num="<&= item.room_addrss&>" value="<&= item.pigcms_id&>"><&= item.name&></td><td><&= item.phone&></td><td><&= item.address&></td><td><&= item.room_addrss&></td><td><a href="javascript:(0)" class="select_user">选中</a></td></tr>
	     <&}&>
	   <& }&>
	   <& if(View.isEmpty(_this)) {&>
	     <tr>
			 <td colspan="5">暂无数据</td>
		 </tr>
	   <& }&>
	   <& if(View.isNomore(_this)) {&>
	   <tr>
		   <td colspan="5">暂无更多数据</td>
	   </tr>
	   <& }&>




</script>
<script>
	 var View={
	      init:function(){
	           this.initView();
	           this.back();
	           this.submit()
		  },
         settings: {
             pageNo: 1,
             totalPages: 0,
             pigcms_id: '',
             find_type:'2',
             room_num: '',
			 phone:false,
             value:'',
			 newValue:'',
			 pageSize:0
         },
         isEmpty: function(list){
             var b = View.settings.pageNo == 1 && !list.length;
             return b;
         },
         isNomore: function(list){
             var b = (this.settings.pageNo >= this.settings.pageSize)&&this.settings.pageSize>0 ;
             return b;
         },
         initView:function(){
	          var _this=this;
	          _this.settings.value='';
             $('#room_num').focus(function(){
                 //$("#myModal").modal()
                 View.settings.pageNo=1;
				 var datas={
                     'find_value':'',
					 'find_type':'',
					 page:1
				 }
                 $('#room_num').blur()
				 _this.passage(datas)

             })
		 },
         passage:function(datas){
             $.post("{pigcms{:U('Cashier/ajax_user_info')}",datas,function(data){
                 if (data) {
                     console.log(data);
                     if (data.user_list.user_list && data.user_list.user_list!=null && data.user_list.user_list.length> 0) {
                         View.settings.pageSize=data.user_list.totalPage
                         View.settings.totalPages++;
                         //检测返回的结果是否为空
                         var index = Simplite.getTemplate('index');
                         var indexTpl = Simplite.compile(index)(data.user_list.user_list);
                         $("#myModal tbody").html(indexTpl);
                         console.log(data.user_list.totalPage)
                         if(data.user_list.totalPage==View.settings.totalPages){
                             if(View.settings.totalPages==1){
                                 $(".prev").addClass("hide")
                                 $(".next").addClass("hide")
                             }else{
                                 $(".prev").removeClass("hide")
                                 $(".next").addClass("hide")
                             }

                         }else{
                             if(View.settings.totalPages==1){
                                 $(".prev").addClass("hide")
                                 $(".next").removeClass("hide")
                             }else{
                                 $(".prev").removeClass("hide")
                                 $(".next").removeClass("hide")
                             }
                             View.settings.pageNo++;

                         }

                         //将搜索到的结果展示出来
                         $("#myModal").modal('show').removeClass('hide')   ;
                         return false;
                     }else {
                         //$("#myModal").html("").hide();
                         var index = Simplite.getTemplate('index');
                         var indexTpl = Simplite.compile(index)([]);
                         $("#myModal tbody").html(indexTpl);
                         $(".prev").addClass("hide")
                         $(".next").addClass("hide")
                         $("#myModal").modal('show').removeClass('hide')
                     }
                 }else{
                     var index = Simplite.getTemplate('index');
                     var indexTpl = Simplite.compile(index)([]);
                     $("#myModal tbody").html(indexTpl);
                     $(".prev").hide()
                     $(".next").hide()
                     $("#myModal").modal('show').removeClass('hide')
                 }
             },'json');
		 },
         validate:function(res,is_global,type){
             var result=res.replace(/(^\s+)|(\s+$)/g,"");
             if(is_global.toLowerCase()=="g") {
				 if(type){View.settings.value=result = result.replace(/\s/g,"");}
                 View.settings.newValueresult = result.replace(/\s/g,"");
                 if(result==''){
                     return false
                 }else{
                         return true
                 }
             }
		 },
         back:function(){
	          var _this=this;
             var bind_name = 'input';var dropload2='';
             if (navigator.userAgent.indexOf("MSIE") != -1){ bind_name = 'propertychange' }
             $(document).bind(bind_name,'#appendedInputButton' ,function(){
                 var length=$('#appendedInputButton').val();
                 if(_this.validate(length,'g')){
                      $(".removeInput").removeClass('hidden')
                 }else{
                     $(".removeInput").addClass('hidden')
				 }

             })
             $(document).on("click",'.removeInput',function(){
                 $('#appendedInputButton').val('');
                 $(this).addClass('hidden');
                 $(".btnGrayS").click()

             })
             $(document).on("click",'.btnGrayS',function(){
                 var find_val=$("#user_select").find("input[name=find_value]").val();
                     View.settings.pageNo=1;
                     View.settings.totalPages=0;
                     View.settings.pageSize=0;
                     if(_this.validate(find_val,'g',true)){
                         var datas={
                             'find_value':View.settings.value,
                             'find_type':View.settings.value==''?'':View.settings.find_type,
                             page:1
                         }
                         //  var datas=eval('(' + data + ')');
                         _this.passage(datas)
                     }else{
                         var datas={
                             'find_value':find_val,
                             'find_type':View.settings.value==''?'':View.settings.find_type,
                             page:1
                         }
                         //  var datas=eval('(' + data + ')');
                         _this.passage(datas)
                     }



             })
             $(document).on("click",'.btn_pre',function(){
                 var find_val=$("#user_select").find("input[name=find_value]").val();
                 //是否是最后一页
                 var r=_this.validate(find_val,'g',true);
                 if($("#myModal .next").is(":hidden")){
                     View.settings.pageNo=View.settings.pageNo-1
                     View.settings.totalPages=View.settings.totalPages-2;
                 }else{
                     if($(this).hasClass("prev")){
                         View.settings.pageNo=View.settings.pageNo-2
                         View.settings.totalPages=View.settings.totalPages-2;
                     }

                 }
                 if(_this.settings.value!=_this.settings.newValue){
                     View.settings.pageNo=1
                     View.settings.totalPages=0;
				 }
                     var datas={
                         'find_value':find_val,
                         'find_type':View.settings.find_type,
                         page:View.settings.pageNo
                     }
                     //  var datas=eval('(' + data + ')');
                     _this.passage(datas)


             })
             $(document).on("click",'.modal-backdrop',function(){
                 $('#myModal').modal('hide')

             })
             $(document).on('hidden.bs.modal','#myModal', function (e) {
                 $("#myModal input[name=find_value]").attr("readonly",false);
                 $(".nav .dropdown span").html('业主手机号码')
                 $("#myModal input[name=find_value]").attr("placeholder",'请输入业主手机号码查询')
                 $("#myModal input[name=find_value]").val('')
                 View.settings.pageNo=1;
                 View.settings.totalPages=0;
                 View.settings.find_type='2';
                 View.settings.phone=false;
                 $(".btn_pre").addClass('hide');
				 $(".removeInput").addClass('hidden')
             })
             $(document).on("click",'.select_user',function(){
                        var $par=$(this).closest('tr');
                        if(!$par.find("td").eq(0).html()){
                        	var val=$par.find("td").eq(1).html()+'   -    '+$par.find("td").eq(2).html()
                        }
                        if(!$par.find("td").eq(1).html()){
                        	var val=$par.find("td").eq(0).html()+'   -    '+$par.find("td").eq(2).html()
                        }
                        if($par.find("td").eq(1).html() && $par.find("td").eq(0).html()){
                        	var val=$par.find("td").eq(0).html()+'   -   '+$par.find("td").eq(1).html()+'   -    '+$par.find("td").eq(2).html()
                        }
                        if(!$par.find("td").eq(1).html() && !$par.find("td").eq(0).html()){
                        	var val=$par.find("td").eq(2).html()
                        }
                        $("#room_num").val(val);
                 View.settings.room_num=$par.find("td").eq(0).attr('data-num');
                 View.settings.pigcms_id=$par.find("td").eq(0).attr('value');
                 $('#myModal').modal('hide')

                 })
			 $(document).on("click",'.dropdown-menu a',function(){
			      var type=$(this).attr("data-type");
			        if(type!=0){
                        View.settings.find_type=$(this).attr("data-type");
                        $(".nav .dropdown span").html($(this).html())
			            if(type==4){
                            $("#myModal input[name=find_value]").attr("readonly",'readonly')
                            $("#myModal input[name=find_value]").attr("placeholder",'')
                            $("#myModal input[name=find_value]").val('')
						}else{
                            $("#myModal input[name=find_value]").attr("readonly",false)
                            $("#myModal input[name=find_value]").attr("placeholder",'请输入'+$(this).html()+'查询')
						}
						//$(".btn_pre").addClass('hide')

					}

			 })
		 },
		 submit:function(){
             $('.submit_info').click(function(){
                 if(View.settings.room_num==''){
                     layer.msg('房间编号不能为空!',{icon:2});
                     return false;
                 }
                 var deposit_name = $('#deposit_name').val();
                 if(!deposit_name){
                     layer.msg('押金项目不能为空!',{icon:2});
                     return false;
                 }

                 var pay_type = $('#pay_type').val();
                 if(!pay_type){
                     layer.msg('支付方式不能为空!',{icon:2});
                     return false;
                 }
                 if(View.settings.pigcms_id==''){
                     layer.msg('客户不能为空!',{icon:2});
                     return false;
                 }

                 var payment_money = $('#payment_money').val();
                 if(!payment_money){
                     layer.msg('应缴金额不能为空!',{icon:2});
                     return false;
                 }
                 var actual_money = $('#actual_money').val();
                 if(!actual_money){
                     layer.msg('实缴金额不能为空!',{icon:2});
                     return false;
                 }
                 var deposit_note = $('#deposit_note').val();
                 $.post("{pigcms{:U('deposit_add')}",{'room_num':View.settings.room_num,'pay_type':pay_type,'pigcms_id':View.settings.pigcms_id,'payment_money':payment_money,'actual_money':actual_money,'deposit_note':deposit_note,'deposit_name':deposit_name},function(data){
                     if(data.code == 1){
                         layer.msg(data.msg,{icon: 1},function(){
                             // location.reload();
                             location.href='{pigcms{:U('Unit/deposit_management')}';
                         });
                     }
                     if(data.code == 2){
                         layer.msg(data.msg,{icon: 2});
                     }
                 },'json');
             })
		 }

	 }
     window.View=View;
     View.init()
</script>


<include file="Public:footer"/>