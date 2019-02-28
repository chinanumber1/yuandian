<?php
class orderPrint
{
	public $serverUrl;
	
	public $key;
	
	public $topdomain;
	
	public function __construct($server_key, $server_topdomain)
	{
		$this->serverUrl = 'http://up.pigcms.cn/';
		$this->key = C('config.print_server_key');
		$this->topdomain = C('config.print_server_topdomain');
		if(!$this->topdomain){
			$this->topdomain = $this->getTopDomain();
		}
	}
	
    public function newPrint($usePrinter, $content = '')
    {
        if (empty($content)) return false;
        if ($usePrinter['is_big'] == 1) {
            $content = '<FH><FW>' . $content . '</FW></FH>';
        } elseif ($usePrinter['is_big'] == 2) {
            $content = '<FH2><FW2>' . $content . '</FW2></FH2>';
        }
        if ($usePrinter['mp']) {
            $data = array('content' => $content, 'machine_code' => $usePrinter['mcode'], 'machine_key' => $usePrinter['mkey']);
            $url = $this->serverUrl . 'server.php?m=server&c=orderPrint&a=printit&count=' . $usePrinter['count'] . '&key=' . $this->key . '&domain=' . $this->topdomain;
            $rt = $this->api_notice_increment($url, $data);
        } elseif ($usePrinter['username']) {
            $data = array('content' => '|5' . $content);
            if ($qr == '') {
                $qrlink = $usePrinter['qrcode'];
            } else {
                $qrlink = $qr;
            }
            $url = $this->serverUrl.'server.php?m=server&c=orderPrint&a=fcprintit&productid=3&count=' . $usePrinter['count'] . '&mkey=' . $usePrinter['mkey'] . '&mcode=' . $usePrinter['mcode'] . '&name=' . $usePrinter['username'] . '&qr=' . urlencode($qrlink) . '&domain=' . $this->topdomain;
            $rt = $this->api_notice_increment($url, $data);
        } else {
            /***WIFI小票打印机****/
            $data = array('content' => $content, 'machine_code' => $usePrinter['mcode'], 'machine_key' => $usePrinter['mkey']);
			
			if($usePrinter['print_type'] == 4){
				$data['print_type'] = 'feie';
				$data['feie_user'] = $usePrinter['feie_user'];
				$data['feie_ukey'] = $usePrinter['feie_ukey'];
			}
			
            $url = $this->serverUrl . 'server.php?m=server&c=orderPrint&a=printit&count=' . $usePrinter['count'] . '&key=' . $this->key . '&domain=' . $this->topdomain;
            $rt = $this->api_notice_increment($url, $data);
        }
    }
    
	public function printit($mer_id, $store_id = 0, $content = '', $paid = 0, $print_id = 0)
	{
		if ($print_id) {
			$usePrinter = D('Orderprinter')->where(array('mer_id' => $mer_id, 'store_id' => $store_id, 'pigcms_id' => $print_id))->find();
			$usePrinters = $usePrinter ? array($usePrinter) : '';
		} else {
			$usePrinters = D('Orderprinter')->where(array('mer_id' => $mer_id, 'store_id' => $store_id, 'is_main' => 1))->select();
			if (empty($usePrinters)) {
				$usePrinters = D('Orderprinter')->where(array('mer_id' => $mer_id, 'store_id' => $store_id, 'is_main' => 0))->order('pigcms_id asc')->select();
				$usePrinter = count($usePrinters) > 0 ? $usePrinters[0] : '';
				$usePrinters = $usePrinter ? array($usePrinter) : '';
			}
		}
		if ($usePrinters) {
			foreach ($usePrinters as $rowset) {
				$rowset['paid'] = explode(',', $rowset['paid']);
				if ($paid == -1 || in_array($paid, $rowset['paid'])) {
					if ($rowset['mp']) {
						$data = array('content' => $content, 'machine_code' => $rowset['mcode'], 'machine_key' => $rowset['mkey']);
						$url = $this->serverUrl . 'server.php?m=server&c=orderPrint&a=printit&count=' . $rowset['count'] . '&key=' . $this->key . '&domain=' . $this->topdomain;
						$rt = $this->api_notice_increment($url, $data);
					} elseif ($rowset['username'])  {
						$data = array('content' => '|5' . $content);
						if ($qr == '') {
							$qrlink = $rowset['qrcode'];
						} else {
							$qrlink = $qr;
						}
						$url = $this->serverUrl.'server.php?m=server&c=orderPrint&a=fcprintit&productid=3&count=' . $rowset['count'] . '&mkey=' . $rowset['mkey'] . '&mcode=' . $rowset['mcode'] . '&name=' . $rowset['username'] . '&qr=' . urlencode($qrlink) . '&domain=' . $this->topdomain;
						$rt = $this->api_notice_increment($url, $data);
					}else{
						/***WIFI小票打印机****/
					   	$data = array('content' => $content, 'machine_code' => $rowset['mcode'], 'machine_key' => $rowset['mkey']);
						$url = $this->serverUrl . 'server.php?m=server&c=orderPrint&a=printit&count=' . $rowset['count'] . '&key=' . $this->key . '&domain=' . $this->topdomain;
						$rt = $this->api_notice_increment($url,$data);
					}
				}
			}
			
		}
	}
	function get_own_printer($mkey)
	{
		$url = 'http://up.pigcms.cn/server.php?m=server&c=orderPrint&domain=pigcms.com&a=getcableprint&utf8=1&mkey='.$mkey;
		import('ORG.Net.Http');
		$http = new Http();
		$return = Http::curlGet($url);
		if($return == '-1'){
			$return = '';
		}else if(strpos($return,'<html>') !== false || strpos($return,'</head>') !== false || strpos($return,'502 Bad Gateway') !== false){
			$return = '';
		}else{
			$return_arr = explode('||&&||',$return);			
			foreach($return_arr as &$value){
				$value = trim($value);
			}
			$return = implode('<br/>',$return_arr);
		}
		return $return;
	}
	function api_notice_increment($url, $data)
	{
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno=curl_errno($ch);
		if ($errorno) {
			return $errorno;
		} else {
			return $tmpInfo;
		}
	}
	
	function getTopDomain()
	{
		$host = $_SERVER['HTTP_HOST'];
		$host = strtolower($host);
		if (strpos($host,'/') !== false) {
			$parse = @parse_url($host);
			$host = $parse['host'];
		}
		$topleveldomaindb = array('com','edu','gov','int','mil','net','org','biz','info','pro','name','museum','coop','aero','xxx','idv','mobi','cc','me');
		$str = '';
		foreach ($topleveldomaindb as $v) {
			$str .= ($str ? '|' : '') . $v;
		}
		$matchstr = "[^\.]+\.(?:(".$str.")|\w{2}|((".$str.")\.\w{2}))$";
		if (preg_match("/".$matchstr."/ies", $host, $matchs)) {
			$domain = $matchs['0'];
		} else {
			$domain = $host;
		}
		return $domain;
	}
}
