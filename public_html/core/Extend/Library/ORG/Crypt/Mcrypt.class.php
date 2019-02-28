<?php
/**
* @author Yanleilei <admin@kaola.me>
* 字符串对称加密算发
*/

class Mcrypt
{
    protected $td;
    protected $iv;
    public    $key;
    protected $secret;
    protected $config;
    protected $base = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    
    public function __construct($key="pigcms.com")
    {
        $this->td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');
        $size = mcrypt_enc_get_iv_size($this->td);
        $this->iv = mcrypt_create_iv($size, MCRYPT_RAND);
        $this->key = $key;
    }
    
    public function encode($data)
    {
        mcrypt_generic_init($this->td, $this->key, $this->iv);
        $encrypt = mcrypt_generic($this->td, $this->paddingPKCS7($data));

        return $this->_encode($encrypt);
    }
    
    public function decode($data)
    {
        $decrypt = $this->_decode($data);
        mcrypt_generic_init($this->td, $this->key, $this->iv);
        $decrypt = mdecrypt_generic($this->td, $decrypt);
        $decrypt = $this->unPaddingPKCS7($decrypt);
        
        return $decrypt;
    }
    
    private function paddingPKCS7($data) {
        $block_size   = mcrypt_get_block_size(MCRYPT_3DES, MCRYPT_MODE_ECB);
        $padding_char = $block_size - (strlen($data) % $block_size);
        $data .= str_repeat(chr($padding_char), $padding_char);
        return $data;
    }
    
    private function unPaddingPKCS7($text)
    {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }
        return substr($text, 0, - 1 * $pad);
    }

    private function _encode($data)
    {
        $length = strlen($data);
        $string = '';
        $binary = array();

        for ($i = 0; $i < $length; $i++) {
            $binary[] = sprintf('%08s', base_convert(ord($data[$i]), 10, 2));
        }
        $split = str_split(implode('', $binary), 4);
        foreach ($split as $val) {
            $pos = base_convert($val.'01', 2, 10);
            $string .= $this->base[$pos];
        }
        return $string;
    }
    
    private function _decode($data)
    {
        $string = '';
        $len = strlen($data);
        for ($i = 0; $i < $len; $i++) {
            $pos = strpos($this->base, $data[$i]);
            $string .= sprintf('%06s', base_convert($pos, 10, 2));
        }
        $append = str_split($string, 6);
        $binary = '';
        foreach ($append as $val) {
            $binary .= substr($val, 0, 4);
        }
        
        $bin = '';
        $chars = str_split($binary, 8);
        foreach ($chars as $char) {
            $bin .= chr(base_convert($char, 2, 10));
        }
        return $bin;
    }
}
