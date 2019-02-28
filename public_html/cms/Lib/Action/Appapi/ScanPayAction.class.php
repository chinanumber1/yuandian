<?php
class ScanPayAction  extends BaseAction{
    public function get_payid(){
        if(empty($this->_uid)){
            $this->returnCode('20044010');
        }
        $tmp_payid = substr($this->_uid,0,1).substr($this->_uid,-1).substr(uniqid('', true), 17).substr(microtime(), 2, 6);
        if(M('Tmp_payid')->where(array('payid'=>$tmp_payid,'uid'=>$this->_uid))->find()){
            $this->pay_qrcode();
        }
        $date['uid'] = $this->_uid;
        $date['payid'] = $tmp_payid;
        $date['add_time'] = $_SERVER['REQUEST_TIME'];
        M('Tmp_payid')->add($date);
        $arr['barcode_img'] = $this->config['site_url'].U('ScanPay/cardbarcode',array('code'=>$tmp_payid));
        $arr['qrcode_img'] = $this->config['site_url'].U('ScanPay/cardqrcode',array('code'=>$tmp_payid));
        $arr['code'] = $tmp_payid;
        $this->returnCode(0,$arr);
    }

    public function scan_order(){
        $res = D('Store_order')->get_order_by_payid($this->_uid,$_POST['code']);
        if(empty($res)){
            $this->returnCode('10070011');
        }
        $this->returnCode(0,array('order_type'=>'store','order_id'=>$res['order_id']));
       // $this->returnCode(0,'store_111');
    }

    public function cardbarcode(){
        import('@.ORG.barcode');
        $colorFront = new BCGColor(0, 0, 0);
        $colorBack = new BCGColor(255, 255, 255);

        $font = new BCGFontFile($_SERVER['DOCUMENT_ROOT'].'/cms/Lib/ORG/barcode/font/Arial.ttf', 18);
		// Barcode Part
		$code = new BCGcode128();
		$code->setScale(2);
		$code->setColor($colorFront, $colorBack);
		 $code->setFont($font); 
        $code->parse($_GET['code']);
        // Drawing Part
        $drawing = new BCGDrawing('', $colorBack);
        $drawing->setBarcode($code);
        $drawing->draw();

        header('Content-Type: image/png');
        $drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
    }

    public function cardqrcode(){
        import('@.ORG.phpqrcode');
        QRcode::png($_GET['code'],false,2,10,2);
    }
}
?>