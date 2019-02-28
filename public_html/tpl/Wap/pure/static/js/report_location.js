$(function(){
//	$.getScript("https://api.map.baidu.com/getscript?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2",function(){
//	});
	var geolocation = new BMap.Geolocation();
	function upLocation() {
		geolocation.getCurrentPosition(function(r){
			if(this.getStatus() == BMAP_STATUS_SUCCESS){
				$.post('/wap.php?g=Wap&c=Deliver&a=location', "lng=" + r.point.lng+"&lat=" + r.point.lat, function(json){});
			} else {
				alert('failed'+this.getStatus());
			}
		},{enableHighAccuracy: true})
    }
    setInterval(upLocation, 10000);
});