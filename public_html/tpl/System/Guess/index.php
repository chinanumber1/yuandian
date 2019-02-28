<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Guess/index')}" class="on">猜你喜欢</a>
				</ul>
			</div>
			<form method="post" action="" refresh="true" enctype="multipart/form-data" >
				<table cellpadding="0" cellspacing="0" class="table_form" width="100%">
					<tr>
						<th width="160">猜你喜欢展示内容类型</th>
						<td>
							<select name="content_type">
								<option value="group" <if condition="$config.guess_content_type  eq 'group'">selected="selected"</if>>{pigcms{$config.group_alias_name}</option>
								<option value="shop" <if condition="$config.guess_content_type  eq 'shop'">selected="selected"</if>>{pigcms{$config.shop_alias_name}</option>
								<option value="meal" <if condition="$config.guess_content_type eq 'meal'">selected="selected"</if>>{pigcms{$config.meal_alias_name}</option>
								<if condition="isset($config['is_examine'])"><option value="yuedan" <if condition="$config.guess_content_type eq 'yuedan'">selected="selected"</if>>约单</option></if>
                                <option value="mall" <if condition="$config.guess_content_type eq 'mall'">selected="selected"</if>>商城</option>
                                <option value="store" <if condition="$config.guess_content_type eq 'store'">selected="selected"</if>>店铺</option>
							</select>
						</td>
					</tr>
					<tr id="guessNum" <if condition="$config.guess_content_type eq 'mall'">style="display:none"</if>>
						<th width="160">猜你喜欢展示数量</th>
						<td>
							<input  class="input-text valid" size="10" type="text"  validate="required:true,min:0" name="guess_num" value={pigcms{$config.guess_num} onkeyup="value=value.replace(/[^1234567890]+/g,'')" >
						</td>
					</tr>
				</table>
				<div class="btn">
					<input type="submit"  name="dosubmit" value="提交" class="button" />
					<input type="reset"  value="取消" class="button" />
				</div>
			</form>
		</div>
		<style>
			.table_form{border:1px solid #ddd;}
			.tab_ul{margin-top:20px;border-color:#C5D0DC;margin-bottom:0!important;margin-left:0;position:relative;top:1px;border-bottom:1px solid #ddd;padding-left:0;list-style:none;}
			.tab_ul>li{position:relative;display:block;float:left;margin-bottom:-1px;}
			.tab_ul>li>a {
				position: relative;
				display: block;
				padding: 10px 15px;
				margin-right: 2px;
				line-height: 1.42857143;
				border: 1px solid transparent;
				border-radius: 4px 4px 0 0;
				padding: 7px 12px 8px;
				min-width: 100px;
				text-align: center;
				}
				.tab_ul>li>a, .tab_ul>li>a:focus {
				border-radius: 0!important;
				border-color: #c5d0dc;
				background-color: #F9F9F9;
				color: #999;
				margin-right: -1px;
				line-height: 18px;
				position: relative;
				}
				.tab_ul>li>a:focus, .tab_ul>li>a:hover {
				text-decoration: none;
				background-color: #eee;
				}
				.tab_ul>li>a:hover {
				border-color: #eee #eee #ddd;
				}
				.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
				color: #555;
				background-color: #fff;
				border: 1px solid #ddd;
				border-bottom-color: transparent;
				cursor: default;
				}
				.tab_ul>li>a:hover {
				background-color: #FFF;
				color: #4c8fbd;
				border-color: #c5d0dc;
				}
				.tab_ul>li:first-child>a {
				margin-left: 0;
				}
				.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
				color: #576373;
				border-color: #c5d0dc #c5d0dc transparent;
				border-top: 2px solid #4c8fbd;
				background-color: #FFF;
				z-index: 1;
				line-height: 18px;
				margin-top: -1px;
				box-shadow: 0 -2px 3px 0 rgba(0,0,0,.15);
				}
				.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
				color: #555;
				background-color: #fff;
				border: 1px solid #ddd;
				border-bottom-color: transparent;
				cursor: default;
				}
				.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
				color: #576373;
				border-color: #c5d0dc #c5d0dc transparent;
				border-top: 2px solid #4c8fbd;
				background-color: #FFF;
				z-index: 1;
				line-height: 18px;
				margin-top: -1px;
				box-shadow: 0 -2px 3px 0 rgba(0,0,0,.15);
				}
				.tab_ul:before,.tab_ul:after{
				content: " ";
				display: table;
				}
				.tab_ul:after{
				clear: both;
				}
		</style>
        <script>
        $(document).ready(function(){
            $('select').change(function(){
                if ($(this).val() == 'mall') {
                    $('#guessNum').hide();
                } else {
                    $('#guessNum').show();
                }
            });
        });
        </script>
<include file="Public:footer"/>