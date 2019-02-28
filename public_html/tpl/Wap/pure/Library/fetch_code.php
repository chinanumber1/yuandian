<!DOCTYPE html>
<html style="font-size: 20px;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title></title>
    <style type="text/css">
    	*{margin: 0;padding: 0;}
    	body{background: #F4F4F4;}
    	.ft{float:left;}
    	.rg{float:right;}
    	.clear{content: " ";display:block;clear: both;}
    	ol,ul,li{list-style:none;}
    	.express{
    		margin: 15px 0;
    	}
    	.express li{
    		width: 94%;
			padding: 10px 3% 10px 3%;
			display: -webkit-flex;
		    display: flex;
		    -webkit-box-pack: justify;
		    -webkit-justify-content: space-between;
		    justify-content: space-between;
		    -webkit-box-align: center;
		    -webkit-align-items: center;
		    align-items: center;
		    background: #FFFFFF;
		    border-bottom:1px solid #F5F5F5;
    	}
    	.express li>span{
    		font-size: 14px;
    		color: #8E8E8E;
    	}
    	.express li p{
    		display: -webkit-flex;
		    display: flex;
		    -webkit-box-pack: justify;
		    -webkit-justify-content: space-between;
		    justify-content: space-between;
		    -webkit-box-align: center;
		    -webkit-align-items: center;
		    align-items: center;
		    background: #FFFFFF;
		    padding: 5px 0;
		    font-size: 16px;
		    font-weight: bold;
		    width: 70%;
		    color: #333;
    	}
    	.express li p b{
    		display: inline-block;
    		width: 12px;height: 7px;
    		background: url({pigcms{$static_path}img/1-3_11.png) center no-repeat;
    		background-size: contain;
    		vertical-align: middle;
    	}
    	.express li p input{
    		font-size: 16px;
    		height: 16px;
    		padding: 5px 0;
    		border:none;
    	}
    	.express li p i.add{
    		display: inline-block;
    		width: 20px;height: 20px;
    		background: url({pigcms{$static_path}img/1-3_03.png) center no-repeat;
    		background-size:contain;
    		vertical-align: middle;
    	}
    	.express li p i.reduce{
    		display: inline-block;
    		width: 20px;height: 20px;
    		background: url({pigcms{$static_path}img/1-3_06.png) center no-repeat;
    		background-size:contain;
    		vertical-align: middle;
    	}
    	a{
    		text-decoration: none;
    		display: block;
    		width: 90%;
    		margin: 30px 5%;
    		background: #06C1AE;
    		border-radius: 5px;
    		text-align: center;
    		height: 40px;
    		line-height: 40px;font-size: 16px;color: #fff;
    	}
    	.mask{
    		position: fixed;
    		top:0;left: 0;right: 0;bottom: 0;
    		background: rgba(0,0,0,.6);
    		display: none;
    	}
    	.poppicker{
		    position: fixed;
		    left: 0px;
		    width: 100%;
		    z-index: 1500;
		    background-color: #eee;
		    border-top: solid 1px #ccc;
		    background-color: #ddd;
		    -webkit-transition: .3s;
		    bottom: 0px;
		    height: 200px;
		    display: none;
    	}
    	.mui-pciker-list{
    		height: 200px;
    		overflow-y: auto;
    		font-size: 15px;
    		text-align: center;
    	}
    	.mui-pciker-list li{
    		padding: 15px 0 0 0;
    	}
    	.mui-pciker-list li:last-child{
    		padding-bottom: 15px;
    	}

    	.sub{    
			text-decoration: none;
		    display: block;
		    width: 90%;
		    margin: 30px 5%;
		    background: #06C1AE;
		    border-radius: 5px;
		    text-align: center;
		    height: 40px;
		    line-height: 40px;
		    font-size: 16px;
		    color: #fff;
		}
    </style>
</head>
<body>
	<form action="{pigcms{:U('collection_list')}" method="post">
		<div class="contanir">
			<div class="all_express">
				<ul class="express">
					<li>
						<span>取货码</span>
						<p>
							<input type="tel" name="fetch_code" value="" />
							<i class="add"></i>
						</p>
					</li>
				</ul>
			</div>
			<input class="sub" type="submit"  value="查询">
		</div>
	</form>
	
	<!-- <div class="mask"></div>
	<div class="poppicker">
		<ul class="mui-pciker-list">
			<volist name='express_list' id='vo'>
	    		<li data-id="{pigcms{$vo.id}">{pigcms{$vo.name}</li>
	        </volist>
		</ul>
	</div> -->
	<script src="{pigcms{$static_path}js/jquery-1.8.3.min.js" type="text/javascript" charset="utf-8"></script>
<!-- 	<script type="text/javascript">
		var i = 1;
		//添加快递
		$('.add').click(function(e){
			i = i+1;
			var str='<ul class="express"><li><span>取货码</span><p><input type="tel" name="fetch_code[]" id="" value="" /><i class="reduce"></i></p></li></ul>';
			$('.all_express').append(str);
		});
		//减少快递
		$('.all_express').off('click','.reduce').on('click','.reduce',function(e){
			$(this).parents('ul').remove();
		});
		//快递公司
		$('.all_express').off('click','.change_express').on('click','.change_express',function(e){
			$('.mask').show();$('.poppicker').show();
			var me=this;
			//选中
			$('.poppicker').off('click','ul li').on('click','ul li',function(e){
				var text=$(this).text();
				var id = $(this).data("id");
				$(me).find('span').text(text);
				$(me).find('input').val(id);
				$('.mask').hide();$('.poppicker').hide();
			});
		});
	</script> -->
</body>
</html>