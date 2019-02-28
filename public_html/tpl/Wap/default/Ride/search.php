<aside class="pc_search ok_search" style="height:736px;">
        <form action="" method="post" id="sxForm">
            <div class="close"><span>X</span></div>
            <div id="tjsx">
                <div class="sx">筛选条件</div>
                <div class="tj">
                    <dl class="block2">
                        <dt>座位：</dt>
                        <dd class="cattsel">不限
                            <input name="driver_seat" type="radio" value="" checked="">
                        </dd>
                        <dd>1人
                            <input name="driver_seat" type="radio" value="1">
                        </dd>
                        <dd>2人
                            <input name="driver_seat" type="radio" value="2">
                        </dd>
                        <dd>3人
                            <input name="driver_seat" type="radio" value="3">
                        </dd>
                        <dd>≥4人
                            <input name="driver_seat" type="radio" value="4">
                        </dd>
                    </dl>
                    <dl class="block2">
                        <dt>费用：</dt>
                        <dd class="cattsel">不限
                            <input name="driver_price" type="radio" value="1000000" checked="">
                        </dd>
                        <dd>0-20元
                            <input name="driver_price" type="radio" value="20">
                        </dd>
                        <dd>20-40元
                            <input name="driver_price" type="radio" value="40">
                        </dd>
                        <dd>40-60元
                            <input name="driver_price" type="radio" value="60">
                        </dd>
                        <dd>≥60元
                            <input name="driver_price" type="radio" value="1000">
                        </dd>
                    </dl>
                    <dl class="block2">
                        <dt>目的地：</dt>
                        <input type="text" name="driver_destination" value="" placeholder="请填写目的地" class="mdd">
                    </dl>
                </div>
                <a href="javascript:void(0)" onclick="$('#sxForm').submit();" class="btn-tj"><span>提交信息</span></a>
            </div>
        </form>
    </aside>

<style type="text/css">
	.ok_search {
	    transform: rotateY(0deg);
	    -webkit-transform: rotateY(0deg);
	    -moz-transform: rotateY(0deg);
	}
	.pc_search {
	    background: rgba(0,0,0,0.78);
	    width: 100%;
	    position: absolute;
	    z-index: 1000;
	    top: 0;
	    transform: rotateY(90deg);
	    -webkit-transform: rotateY(90deg);
	    -moz-transform: rotateY(90deg);
	    -webkit-transition: -webkit-transform 0.3s ease-out 0s;
	    -moz-transition: -moz-transform 0.3s ease-out 0s;
	    transition: transform 0.3s ease-out 0s;
	}
	.close {
	    height: 2em;
	    color: #fff;
	    padding-right: 0.8em;
	    margin-top: 0.8em;
	    cursor: pointer;
	    width: 15%;
	    float: right;
	}
	.close span {
	    display: inline-block;
	    font-size: 1em;
	    width: 1.5em;
	    height: 1.5em;
	    background: #fff;
	    color: #333;
	    float: right;
	    text-align: center;
	    line-height: 1.5em;
	    margin-top: 0.5em;
	    border-radius: 0.8em;
	}
	.sx {
	    color: #fff;
	    width: 60%;
	    margin-left: 15px;
	}
	.tj {
	    width: 96%;
	    margin-left: 2%;
	    font-size: 0.89em;
	    padding-top: 1em;
	}
	.tj dl {
	    overflow: hidden;
	    margin-left: 0.8em;
	    border-bottom: 1px solid #444;
	}
	.tj dl dt {
	    display: inline-block;
	    float: left;
	    margin: 0.5em 0.3em;
	    margin-right: 0;
	    padding: 0.1em;
	    color: #999;
	}
	.tj dl dd.cattsel {
	    background: #008CD6;
	}
	.tj dl dd {
	    display: inline-block;
	    float: left;
	    color: #fff;
	    margin: 0.5em 0.3em;
	    padding: 0.1em 0.3em;
	    cursor: pointer;
	}
	.mdd {
	    font-size: 0.89em;
	    padding: 0.5em 0.4em;
	    margin: 0.4em 0.3em;
	    width: 75%;
	    background: #8f8f8f;
	    border: 1px solid #333;
	}
	.btn-tj {
	    font-size: 1em;
	    text-align: center;
	    width: 90%;
	    display: block;
	    margin: 0 auto;
	    color: #fff;
	    border-radius: 0.3em;
	    background: #008CD6;
	    padding: 0.6em;
	    margin-top: 1em;
	}
	a:visited {
	    text-decoration: none;
	    color: #353535;
	}
</style>