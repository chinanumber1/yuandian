// JavaScript Document
$(document).ready(function(){
/*	openPage1.init();
	var LRturn1= new lrTurnfun();
	LRturn1.init({
		"turnwrapid":"#lrturnwrap",
		"turnconid":"#lrturncon",
		"turntabid":"#lrturntab",
		"swipepage":"#lrturncon .playli",
		"swipenum":1,
		"rownum":1,
		'waittime':5000,
		"turnstatus":false
	});*/

});
$(window).resize(function(){
	openPage1.init();
});

var openPage1 ={
	init:function(){
		var winW=$(window).width();
		var winH=$(window).height();
		//var bW=parseInt($(".ebox-con").width());
		var playliW=winW*1;
		$('.playli').width(playliW);
		$('.playli').height(winH);
		var playW=winW;
		$('.play').width(playW);

	}	
}

var lrTurnfun=function(){
	this.lock=false;
	this.Animtime=300;
	this.wrapWidth=0;
	this.pageWidth=0;
	this.pagesLen=0;
	this.conWidth=0;
	this.swipeWidth=0;
	this.turnStatus=null;
	this.turnTimeout=null;
	this.waitTime=1500;
	this.dir=null;
	this.swipelen=null;
	this.showGroup=null;
	this.curswipe=null;
	this.turnstatus=false;
	this.showingid=0;
};
lrTurnfun.prototype={
	init:function(args){
		var TfThis = this;
		this.wrapWidth=parseFloat($(args.turnwrapid).css("width"));
		this.pageWidth=parseFloat($(args.swipepage).css("width"))+parseFloat($(args.swipepage).css('margin-right'))+parseFloat($(args.swipepage).css('margin-left'))+parseFloat($(args.swipepage).css('padding-right'))+parseFloat($(args.swipepage).css('padding-left'))+0.5;
		this.pagesLen=$(args.swipepage).length;
		this.swipelen=Math.ceil(this.pagesLen/(args.rownum*args.swipenum));
		this.conWidth=this.pageWidth*Math.ceil(this.pagesLen/args.rownum);		
		this.swipeWidth=this.pageWidth*args.swipenum;
		$(args.turnwrapid).css({"overflow":"hidden"});
		/*$(args.swipepage).css({"float":"left"});*/
		$(args.turnconid).css("width",this.conWidth);
		$(args.turntabid).append(this.tabscreate());
		var tabs = $("span",args.turntabid);
		
		if(tabs.length==0){return;}
		if(args.waittime){
			this.waitTime=args.waittime;
		}
	   	this.showGroup=new Array();
	    var i = 0;
	    var dirleftML=0;
	    var dirrightML=0;
	    for(i = 0;i<tabs.length;i++){
    		dirleftML=-(i*this.swipeWidth)+(this.wrapWidth-this.swipeWidth)/2;
    		dirrightML=-((i*this.swipeWidth)-(this.wrapWidth-this.swipeWidth)/2);
	    	if(i==0){
		    	dirleftML=0;
		    	dirrightML=0;
	    	}
	    	if(i==tabs.length-1){
	    		dirleftML=-(this.conWidth-this.wrapWidth);
	    		dirrightML=-(this.conWidth-this.wrapWidth);
	    		
	    	}
	        this.showGroup.push({"dirleft":dirleftML,"dirright":dirrightML,"tab":tabs[i]});
	    };
	    //console.log( this.showGroup);

		this.turnstatus=args.turnstatus;
		this.curswipe=0;
		if(args.showingid){
			this.curswipe=args.showingid;
		}
		this.dir="left";
		$("span",args.turntabid).removeClass('cur');
		$(this.showGroup[this.curswipe].tab).addClass('cur');	
		//new touch
		var touchObjId=document.getElementById($(args.turnwrapid).attr("id"));
		var hm=new Hammer(touchObjId);
		var pan = new Hammer.Pan({ direction:Hammer.DIRECTION_HORIZONTAL, threshold: 5 });
		var tap = new Hammer.Tap({ time:500, threshold:5});
		hm.add(pan);
		hm.add(tap);
		tap.requireFailure(pan);
		if(this.turnstatus!=true){
			hm.on('panend pancancel',function(event){
					if(!TfThis.lock){
						if(event.deltaX<0){
							var curtabid=parseInt($(".cur",args.turntabid).attr("tabid"));
							if(curtabid<TfThis.swipelen-1){
								TfThis.lock=true;
								TfThis.curswipe=curtabid+1;
								TfThis.dir="left";
								TfThis.swipelrfun(args,TfThis.dir);
								
							}
						}else{
							var curtabid=parseInt($(".cur",args.turntabid).attr("tabid"));
							if(curtabid!=0){
								TfThis.lock=true;
								TfThis.curswipe=curtabid-1;					
								TfThis.dir="right";			
								TfThis.swipelrfun(args,TfThis.dir);						
							}
						}
					}
					return false;
			})
			.on("tap", function(ev){
				//$(ev.target).trigger("click");
			})		
		}
		
/*
		if(this.turnstatus==true){
			this.turnTimeout=setTimeout(this.setTimeoutWrap(args,this.dir,this),this.waitTime);
			hm.on('pan', (function(TfThis){
					return function(event) {
						TfThis.turnstatus=false;
						clearTimeout(TfThis.turnTimeout);
						return false;
					}
			})(this))
	        .on('swipeEnd',(function(TfThis){
					return function(event) {
						TfThis.turnstatus=true;
						TfThis.turnTimeout=setTimeout(TfThis.setTimeoutWrap(args,TfThis.dir,TfThis),TfThis.waitTime);				
						return false;
					}

			})(this));
		}*/
		//初始化 showingid
		this.Animtime=0;
		TfThis.swipelrfun(args,this.dir);
		this.Animtime=300;

	},
	tabscreate:function(){
	    var tabshtml=[];
	        tabshtml.push('');
	        for(var a=0; a<this.swipelen; a++){
	            tabshtml.push('<span class="tab" tabId="'+a+'"></span>');
	        }   
	    return tabshtml.join('');
	},
	setTimeoutWrap:function(args,dir,TfThis){
		return function(){
			TfThis.swipelrfun(args,dir);
		}	
	},
	swipelrfun:function(args,dir){
		this.lock=true;	
		
		if(this.curswipe==this.swipelen-1){
			this.dir="right";
		}
		if(this.curswipe==0){
			this.dir="left";
		}		
		$("span",args.turntabid).removeClass('cur');
		$(this.showGroup[this.curswipe].tab).addClass('cur');


		if(this.dir=="left"){
			var conMLeft=this.showGroup[this.curswipe].dirleft;
		}else{
			var conMLeft=this.showGroup[this.curswipe].dirright;
		}

		//console.log("conMLeft:"+conMLeft);
		$(args.turnconid).animate({
		"margin-left":conMLeft+"px"
		}, this.Animtime,(function(TfThis){
			return function(event) {
				TfThis.lock=false;			
			}
		})(this));	

		if(this.turnstatus==true){
			this.turnTimeout=setTimeout(this.setTimeoutWrap(args,this.dir,this),this.waitTime);
			if(this.dir=="left"){
				this.curswipe=this.curswipe+1;
			}else{
				this.curswipe=this.curswipe-1;
			}


		}


	}
}
