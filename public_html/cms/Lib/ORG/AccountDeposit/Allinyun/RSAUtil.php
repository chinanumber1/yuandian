<?php
class RSAUtil {
	private $publicKey;
	private $privateKey;
	
	public function __construct($publicKey, $privateKey) {
		$this->publicKey = $publicKey;
		$this->privateKey = $privateKey;
	}
	
	/**
	 * 从证书文件中装入私钥
	 * @param alias 证书别名
	 * @param path 证书路径
	 * @param password 证书密码
	 * @return 私钥
	 * @throws Exception
	 */
	static function loadPrivateKey($alias, $path, $pwd) {
		$priKey = file_get_contents($path);
		$res = openssl_get_privatekey($priKey, $pwd);
		($res) or die('您使用的私钥格式错误，请检查私钥配置');
		return $res;
	}
	
	/**
	 * 从证书文件中装入公钥
	 * @param alias 证书别名
	 * @param path 证书路径
	 * @param password 证书密码
	 * @return 公钥
	 * @throws Exception
	 */
	static function loadPublicKey($alias, $path, $pwd) {
		$priKey = file_get_contents($path);
		$res = openssl_get_publickey($priKey);
		($res) or die('您使用的私钥格式错误，请检查私钥配置');
		return $res;
	}
	
	/**
	 * 私钥签名
	 * @param privateKey 私钥
	 * @param text 签名内容
	 * @return 签名
	 * @throws Exception
	 */
	static function sign($privateKey, $text) {
		openssl_sign($text, $sign, $privateKey);
		openssl_free_key($privateKey);
		$sign = base64_encode($sign);
		return $sign;
	}
	
	/**
	 * 用公钥对签名进行验证
	 * @param publicKey 公钥
	 * @param text 签名的原始内容
	 * @param sign 签名
	 * @return true/false
	 * @throws Exception
	 */
	static function verify($publicKey, $text, $sign) {
		$result = (bool)openssl_verify($text, base64_decode($sign), $publicKey, OPENSSL_ALGO_SHA1);
		openssl_free_key($publicKey);
		return $result;
	}
	
	public function encrypt($str) {
		$blocks = self::splitCN($str, 0, 30, 'utf-8');
		$chrtext  = null;
		$encodes  = array();
		foreach ($blocks as $n => $block) {
			if (!openssl_private_encrypt($block, $chrtext, $this->privateKey)) {
				echo "<br/>" . openssl_error_string() . "<br/>";
			}
			$encodes[] = $chrtext;
		}
		$chrtext = base64_encode(implode(",", $encodes));
		return $chrtext;
	}

	public function decrypt($str) {
		$decodes = explode(',', base64_decode($str));
		$strnull = "";
		$dcyCont = "";
		if (!openssl_public_decrypt(base64_decode($str), $dcyCont, $this->publicKey)) {
			echo "<br/>" . openssl_error_string() . "<br/>";
		}
		$strnull .= $dcyCont;
		return $strnull;
	}
	
	static function splitCN($cont, $n = 0, $subnum, $charset) {
		//$len = strlen($cont) / 3;
		$arrr = array();
		for ($i = $n; $i < strlen($cont); $i += $subnum) {
			$res = self::subCNchar($cont, $i, $subnum, $charset);
			if (!empty ($res)) {
				$arrr[] = $res;
			}
		}
		return $arrr;
	}
	
	static function subCNchar($str, $start = 0, $length, $charset = "utf-8") {
		if (strlen($str) <= $length) {
			return $str;
		}
		$re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join("", array_slice($match[0], $start, $length));
		return $slice;
	}
}