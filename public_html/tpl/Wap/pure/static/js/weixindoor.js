var tipTimer = null,lightTimer = null;
$('#scanDeviceBtn').click(function(){
	$(this).hide();
	$('#scanDeviceTip').show();
	
	var tmpJ = 0;
	tipTimer = setInterval(function(){
		if(tmpJ == 3){
			tmpJ = 1;
		}else{
			tmpJ++;
		}
		var scanDeviceTip = '扫描中';
		for(var i=0;i<tmpJ;i++){
			scanDeviceTip+= '.';
		}
		$('#scanDeviceTip').html(scanDeviceTip);
		
	},500);
	
	var tmpI = 0;
	opt.speed = 10;
	lightTimer = setInterval(function(){
		console.log(tmpI);
		if(tmpI == 5){
			tmpI = 0;
		}else{
			tmpI ++;
		}
		opt.lightAlpha = tmpI;
	},500);
});

var sketch = Sketch.create(), center = {
	x: sketch.width / 2,
	y: sketch.height / 2
}, orbs = [], dt = 1, opt = {
	total: 500,
	count: 500,
	spacing: 2,
	speed: 1,
	scale: 1,
	jitterRadius: 0,
	jitterHue: 0,
	clearAlpha: 10,
	toggleOrbitals: true,
	orbitalAlpha: 100,
	toggleLight: true,
	lightAlpha: 0,
	clear: function (){
		sketch.clearRect(0, 0, sketch.width, sketch.height), orbs.length = 0;
	}
};
var Orb = function (x, y) {
var dx = x / opt.scale - center.x / opt.scale, dy = y / opt.scale - center.y / opt.scale;
this.angle = atan2(dy, dx);
this.lastAngle = this.angle;
this.radius = sqrt(dx * dx + dy * dy);
this.size = this.radius / 300 + 1;
this.speed = random(1, 10) / 300000 * this.radius + 0.015;
};
Orb.prototype.update = function () {
this.lastAngle = this.angle;
this.angle += this.speed * (opt.speed / 50) * dt;
this.x = this.radius * cos(this.angle);
this.y = this.radius * sin(this.angle);
};
Orb.prototype.render = function () {
if (opt.toggleOrbitals) {
	var radius = opt.jitterRadius === 0 ? this.radius : this.radius + random(-opt.jitterRadius, opt.jitterRadius);
	radius = opt.jitterRadius != 0 && radius < 0 ? 0.001 : radius;
	sketch.strokeStyle = 'hsla( ' + ((this.angle + 90) / (PI / 180) + random(-opt.jitterHue, opt.jitterHue)) + ', 100%, 50%, ' + opt.orbitalAlpha / 100 + ' )';
	sketch.lineWidth = this.size;
	sketch.beginPath();
	if (opt.speed >= 0) {
		sketch.arc(0, 0, radius, this.lastAngle, this.angle + 0.001, false);
	} else {
		sketch.arc(0, 0, radius, this.angle, this.lastAngle + 0.001, false);
	}
	;
	sketch.stroke();
	sketch.closePath();
}
;
if (opt.toggleLight) {
	sketch.lineWidth = 0.5;
	sketch.strokeStyle = 'hsla( ' + ((this.angle + 90) / (PI / 180) + random(-opt.jitterHue, opt.jitterHue)) + ', 100%, 70%, ' + opt.lightAlpha / 100 + ' )';
	sketch.beginPath();
	sketch.moveTo(0, 0);
	sketch.lineTo(this.x, this.y);
	sketch.stroke();
}
;
};
var createOrb = function (config) {
var x = config && config.x ? config.x : sketch.mouse.x, y = config && config.y ? config.y : sketch.mouse.y;
orbs.push(new Orb(x, y));
};
var turnOnMove = function () {
sketch.mousemove = createOrb;
};
var turnOffMove = function () {
sketch.mousemove = null;
};
sketch.mousedown = function () {
createOrb();
turnOnMove();
};
sketch.mouseup = turnOffMove;
sketch.resize = function () {
center.x = sketch.width / 2;
center.y = sketch.height / 2;
sketch.lineCap = 'round';
};
sketch.setup = function () {
while (opt.count--) {
	if (window.CP.shouldStopExecution(1)) {
		break;
	}
	createOrb({
		x: random(sketch.width / 2 - 300, sketch.width / 2 + 300),
		y: random(sketch.height / 2 - 300, sketch.height / 2 + 300)
	});
}
;
window.CP.exitedLoop(1);
};
sketch.clear = function () {
sketch.globalCompositeOperation = 'destination-out';
sketch.fillStyle = 'rgba( 0, 0, 0 , ' + opt.clearAlpha / 100 + ' )';
sketch.fillRect(0, 0, sketch.width, sketch.height);
sketch.globalCompositeOperation = 'lighter';
};
sketch.update = function () {
dt = sketch.dt < 0.1 ? 0.1 : sketch.dt / 16;
dt = dt > 5 ? 5 : dt;
var i = orbs.length;
opt.total = i;
while (i--) {
	if (window.CP.shouldStopExecution(2)) {
		break;
	}
	orbs[i].update();
}
window.CP.exitedLoop(2);
};
sketch.draw = function () {
sketch.save();
sketch.translate(center.x, center.y);
sketch.scale(opt.scale, opt.scale);
var i = orbs.length;
while (i--) {
	if (window.CP.shouldStopExecution(3)) {
		break;
	}
	orbs[i].render();
}
window.CP.exitedLoop(3);
sketch.restore();
};

function closeScanDoor(){
	clearInterval(tipTimer);
	clearInterval(lightTimer);
	$('#scanDeviceTip').html('扫描中').hide();
	$('#scanDeviceBtn').show();
}

wx.ready(function () {
	wx.invoke('openWXDeviceLib', {'connType':'blue'}, function(res) {
		if(res.err_msg == 'openWXDeviceLib:ok'){
			if(res.bluetoothState != 'on'){		//on、off、restting、unauthorized、unknow
				var errorTip = '';
				switch(res.bluetoothState){
					case 'off':
						errorTip = '蓝牙没有打开';
						break;
					case 'restting':
						errorTip = '蓝牙正在复位';
						break;
					case 'unauthorized':
						errorTip = '您没有授权微信使用蓝牙功能';
						break;
					default:
						errorTip = '蓝牙不是正在开启状态';
						break;
				}
				closeScanDoor();
				return false;
			}
			
			wx.invoke('startScanWXDevice', {'connType':'blue'}, function(startScanRes) {
				if(startScanRes.err_msg != 'startScanWXDevice:ok'){
					alert('扫描设备失败，原因：'+startScanRes.err_msg);
				}
			});
			
			/*wx.invoke('getWXDeviceTicket', {'deviceId':deviceId,'type':'1', 'connType':'blue'}, function(tiketRes) {
				if(tiketRes.err_msg == 'getWXDeviceTicket:ok'){
					$.post('/wap.php?c=Test2&a=bind_user',{device_id:deviceId,ticket:tiketRes.ticket},function(result){
						
					});
				}else{
					alert('获取设备ticket标识失败，原因：'+tiketRes.err_msg);
				}
				// alert(JSON.stringify(tiketRes));
			});*/
		}else if(res.err_msg == 'openWXDeviceLib:fail_UsernameError'){
			alert('您需要从公众号中进入');
			closeScanDoor();
			$('.scanWechatCon').css('margin-top',($(window).height()-270)/2);
			$('#scanWechatQrcode').show();
		}else{
			alert('打开硬件支持失败，请重试。原因：'+res.err_msg);
			closeScanDoor();
		}
	});
	var deviceList = [];
	wx.on('onScanWXDeviceResult', function (res) {
		for(var i in res.devices){
			deviceList.push(res.devices[i].deviceId);
			$('body').append('<br/><br/>'+res.devices[i].deviceId);
			
			wx.invoke('connectWXDevice', {'deviceId':res.devices[i].deviceId,'connType':'blue'}, function(connectScanRes) {
				if(connectScanRes.err_msg == 'connectWXDevice:ok'){
					$('body').append('<br/><br/>连接设备'+res.devices[i].deviceId+'成功');
					
					setTimeout(function(){
						wx.invoke('sendDataToWXDevice', {'deviceId':res.devices[i].deviceId, 'connType':'blue', 'base64Data':'aGVsbG93b3JsZA=='}, function(sendDataRes) {
							// console.log('sendDataToWXDevice',res);
							if(sendDataRes.err_msg.toLowerCase() == 'senddatatowxdevice:ok'){
								$('body').append('<br/><br/>发送数据到'+res.devices[i].deviceId+'成功');
							}else{
								alert('发送数据到'+res.devices[i].deviceId+'失败，原因：'+sendDataRes.err_msg);
							}
						});
					},1000);
					setTimeout(function(){
						wx.invoke('disconnectWXDevice', {'deviceId':res.devices[i].deviceId,'connType':'blue'}, function(disconnectScanRes) {
							if(disconnectScanRes.err_msg.toLowerCase() == 'disconnectwxdevice:ok'){
								$('body').append('<br/><br/>断开设备'+res.devices[i].deviceId+'成功');
							}else{
								alert('断开设备'+res.devices[i].deviceId+'失败，原因：'+disconnectScanRes.err_msg);
							}
						});	
					},5000);
				}else{
					alert('连接设备'+res.devices[i].deviceId+'失败，原因：'+connectScanRes.err_msg);
				}
			});
		}
	});
	wx.on('onReceiveDataFromWXDevice', function (res) {
		alert(JSON.stringify(res));
	});
});