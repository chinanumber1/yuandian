<?php
defined('THINK_PATH') or exit();
/**
 * PIGCMS标签库解析类
 */
class TagLibPigcms extends TagLib {

    // 标签定义
    protected $tags   =  array(
        // 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'one_adver'       =>  array('attr'=>'cat_key','level'=>3),
        'footer_link'       =>  array('attr'=>'var_name,key','level'=>3),
        'slider'       =>  array('attr'=>'cat_key,var_name,limit,key,reverse','level'=>3),
        'adver'       =>  array('attr'=>'cat_key,var_name,limit,key','level'=>3),
        'near_shop'       =>  array('attr'=>'limit','close'=>0),
        'system_news'       =>  array('attr'=>'limit','level'=>3),
        'coupon'       =>  array('attr'=>'type,cat_id,limit,is_new','level'=>3),
        'card_new_coupon'       =>  array('attr'=>'type,cat_id,limit,is_new','level'=>3),
    );
	/**
     * 得到平台快报
     */
    public function _system_news($attr,$content){
		static $_iterateParseCache = array();
        // 如果已经解析过，则直接返回变量值
        $cacheIterateId = md5($attr.$content);
        if(isset($_iterateParseCache[$cacheIterateId]))
            return $_iterateParseCache[$cacheIterateId];
		
		$tag   =    $this->parseXmlAttr($attr,'system_news');
        $name   = '$'.$tag['var_name'];
		$key   = $tag['key'] ? $tag['key'] : 'i';
        $parseStr   =  '<?php ';
        $parseStr  .=  $name.' = D("System_news")->get_news("'.$tag['limit'].'");';
        $parseStr  .=  'if(!empty('.$name.')): $'.$key.' = 0;';
        $parseStr .= 'foreach('.$name.' as $key=>$vo): ';
		$parseStr .= '++$'.$key.';?>';
        $parseStr .= $this->tpl->parse($content);
        $parseStr .= '<?php endforeach; endif;?>';
        $_iterateParseCache[$cacheIterateId] = $parseStr;
        if(!empty($parseStr)) {
            return $parseStr;
        }
        return ;
    }

    /**
     * 得到平台优惠券
     */
    public function _coupon($attr,$content){
        static $_iterateParseCache = array();
        // 如果已经解析过，则直接返回变量值
        $cacheIterateId = md5($attr.$content);
        if(isset($_iterateParseCache[$cacheIterateId]))
            return $_iterateParseCache[$cacheIterateId];

        $tag   =    $this->parseXmlAttr($attr,'coupon');
        $name   = '$'.$tag['var_name'];
        $key   = $tag['key'] ? $tag['key'] : 'i';
        $parseStr   =  '<?php ';
        $parseStr  .=  $name.' = D("System_coupon")->get_coupon_list_by_type(\''.$tag['type'].'\',\''.$tag['cat_id'].'\','.$tag['limit'].','.$tag['is_new'].');';
        $parseStr  .=  'if(!empty('.$name.')): $'.$key.' = 0;';
        $parseStr .= 'foreach('.$name.' as $key=>$vo): ';
        $parseStr .= '++$'.$key.';?>';
        $parseStr .= $this->tpl->parse($content);
        $parseStr .= '<?php endforeach; endif;?>';
        $_iterateParseCache[$cacheIterateId] = $parseStr;
        if(!empty($parseStr)) {
            return $parseStr;
        }
        return ;
    }

    /**
     * 得到商家新会员卡优惠券
     */
    public function _card_new_coupon($attr,$content){
        static $_iterateParseCache = array();
        // 如果已经解析过，则直接返回变量值
        $cacheIterateId = md5($attr.$content);
        if(isset($_iterateParseCache[$cacheIterateId]))
            return $_iterateParseCache[$cacheIterateId];
        $tag   =    $this->parseXmlAttr($attr,'card_new_coupon');

        $name   = '$'.$tag['var_name'];
        $key   = $tag['key'] ? $tag['key'] : 'i';
        $parseStr   =  '<?php ';
        $parseStr  .=  $name.' = D("Card_new_coupon")->get_coupon_list_by_type(\''.$tag['type'].'\',\''.$tag['cat_id'].'\','.$tag['limit'].','.$tag['is_new'].');';
        $parseStr  .=  'if(!empty('.$name.')): $'.$key.' = 0;';
        $parseStr .= 'foreach('.$name.' as $key=>$vo): ';
        $parseStr .= '++$'.$key.';?>';
        $parseStr .= $this->tpl->parse($content);
        $parseStr .= '<?php endforeach; endif;?>';

        $_iterateParseCache[$cacheIterateId] = $parseStr;

        if(!empty($parseStr)) {
            return $parseStr;
        }
        return ;
    }
	/**
     * 得到附近快店
     */
    public function _near_shop($attr,$content){
		$tag   = $this->parseXmlAttr($attr,'near_shop');
		$limit = $tag['limit'] ? $tag['limit'] : '6';
		$x = $_COOKIE['around_lat'] ? $_COOKIE['around_lat'] : 0;
		$y = $_COOKIE['around_long'] ? $_COOKIE['around_long'] :0;
		$adress = $_COOKIE['around_adress'];
		if(empty($x) || empty($y) || empty($adress)){
			$parseStr = '<?php $is_near_shop = false;$near_shop_list = D("Merchant_store")->get_hot_list("'.$limit.'");?>';
		}else{
			$parseStr = '<?php $is_near_shop = true;$near_shop_list = D("Merchant_store")->get_hot_list("'.$limit.'","'.$x.'","'.$y.'");if(empty($near_shop_list)){$is_near_shop = false;$near_shop_list = D("Merchant_store")->get_hot_list("'.$limit.'");}?>';
		}
        return $parseStr;
    }
	/**
     * 广告读取
     */
	public function _adver($attr,$content) {
        static $_iterateParseCache = array();
        //如果已经解析过，则直接返回变量值
        $cacheIterateId = md5($attr.$content);
        if(isset($_iterateParseCache[$cacheIterateId]))
            return $_iterateParseCache[$cacheIterateId];
		
		$tag   = $this->parseXmlAttr($attr,'adver');
        $name  = '$'.$tag['var_name'];
        $key   = $tag['key'] ? $tag['key'] : 'i';
        $limit = $tag['limit'] ? $tag['limit'] : '3';
        $parseStr   =  '<?php ';
        $parseStr  .=  $name.' = D("Adver")->get_adver_by_key("'.$tag['cat_key'].'","'.$limit.'");';
        $parseStr  .=  'if(is_array('.$name.')): $'.$key.' = 0;';
        $parseStr .= 'if(count('.$name.')==0) : echo "<div class=\'no_adver_list '.$tag['cat_key'].'_empty\'>列表为空</div>" ;';
        $parseStr .= 'else: ';
        $parseStr .= 'foreach('.$name.' as $key=>$vo): ';
		$parseStr .= '++$'.$key.';?>';
        $parseStr .= $this->tpl->parse($content);
        $parseStr .= '<?php endforeach; endif; else: echo "<div class=\'no_adver_list '.$tag['cat_key'].'_empty\'>列表为空</div>" ;endif; ?>';
        $_iterateParseCache[$cacheIterateId] = $parseStr;
        if(!empty($parseStr)) {
            return $parseStr;
        }
        return ;
    }
    /**
     * 得到一个分类下的一张广告图
     */
    public function _one_adver($attr,$content){
		$tag   =    $this->parseXmlAttr($attr,'one_adver');
		if(empty($tag['cat_key'])){
			return '<?php echo "标签解析错误"; ?>';
		}
		$parseStr  = '<?php $one_adver = D("Adver")->get_one_adver("'.$tag['cat_key'].'"); if(is_array($one_adver)): ?>';
		$parseStr .= $this->tpl->parse($content);
		$parseStr .= '<?php endif; ?>';
        return $parseStr;
    }
	/**
     * 底部导航
     */
	public function _footer_link($attr,$content) {
        static $_iterateParseCache = array();
        //如果已经解析过，则直接返回变量值
        $cacheIterateId = md5($attr.$content);
        if(isset($_iterateParseCache[$cacheIterateId]))
            return $_iterateParseCache[$cacheIterateId];
		
		$tag   = $this->parseXmlAttr($attr,'footer_link');
        $name  = '$'.$tag['var_name'];
        $key   = $tag['key'] ? $tag['key'] : 'i';
        $parseStr   =  '<?php ';
        $parseStr  .=  $name.' = D("Footer_link")->get_list();';
        $parseStr  .=  'if(is_array('.$name.')): $'.$key.' = 0;';
        $parseStr .= 'if(count('.$name.')==0) : echo "列表为空" ;';
        $parseStr .= 'else: ';
        $parseStr .= 'foreach('.$name.' as $key=>$vo): ';
		$parseStr .= '++$'.$key.';?>';
        $parseStr .= $this->tpl->parse($content);
        $parseStr .= '<?php endforeach; endif; else: echo "列表为空" ;endif; ?>';
        $_iterateParseCache[$cacheIterateId] = $parseStr;
        if(!empty($parseStr)) {
            return $parseStr;
        }
        return ;
    }
	/**
     * 通用导航条
     */
	public function _slider($attr,$content) {
        static $_iterateParseCache = array();
        //如果已经解析过，则直接返回变量值
        $cacheIterateId = md5($attr.$content);
        if(isset($_iterateParseCache[$cacheIterateId]))
            return $_iterateParseCache[$cacheIterateId];
		
		$tag   =    $this->parseXmlAttr($attr,'slider');
        $name   = '$'.$tag['var_name'];
        $reverse   = $tag['reverse'] ? 'true' : '';
		$key   = $tag['key'] ? $tag['key'] : 'i';
        $parseStr   =  '<?php ';
        $parseStr  .=  $name.' = D("Slider")->get_slider_by_key("'.$tag['cat_key'].'","'.$tag['limit'].'");';
        $parseStr  .=  'if(is_array('.$name.')): $'.$key.' = 0;';
        $parseStr .= 'if(count('.$name.')==0): echo "列表为空" ;';
        $parseStr .= 'else: ';
		if($reverse){
			$parseStr .= $name.'=array_reverse('.$name.');';
		}
		$parseStr .= 'foreach('.$name.' as $key=>$vo): ';
		$parseStr .= '++$'.$key.';?>';
        $parseStr .= $this->tpl->parse($content);
        $parseStr .= '<?php endforeach; endif; else: echo "列表为空" ;endif; ?>';
        $_iterateParseCache[$cacheIterateId] = $parseStr;
        if(!empty($parseStr)) {
            return $parseStr;
        }
        return ;
    }
    /**
     * volist标签解析 循环输出数据集
     * 格式：
     * <volist name="userList" id="user" empty="" >
     * {user.username}
     * {user.email}
     * </volist>
     * @access public
     * @param string $attr 标签属性
     * @param string $content  标签内容
     * @return string|void
     */
    public function _volist($attr,$content) {
        static $_iterateParseCache = array();
        //如果已经解析过，则直接返回变量值
        $cacheIterateId = md5($attr.$content);
        if(isset($_iterateParseCache[$cacheIterateId]))
            return $_iterateParseCache[$cacheIterateId];
        $tag   =    $this->parseXmlAttr($attr,'volist');
        $name  =    $tag['name'];
        $id    =    $tag['id'];
        $empty =    isset($tag['empty'])?$tag['empty']:'';
        $key   =    !empty($tag['key'])?$tag['key']:'i';
        $mod   =    isset($tag['mod'])?$tag['mod']:'2';
        // 允许使用函数设定数据集 <volist name=":fun('arg')" id="vo">{$vo.name}</volist>
        $parseStr   =  '<?php ';
        if(0===strpos($name,':')) {
            $parseStr   .= '$_result='.substr($name,1).';';
            $name   = '$_result';
        }else{
            $name   = $this->autoBuildVar($name);
        }
        $parseStr  .=  'if(is_array('.$name.')): $'.$key.' = 0;';
		if(isset($tag['length']) && '' !=$tag['length'] ) {
			$parseStr  .= ' $__LIST__ = array_slice('.$name.','.$tag['offset'].','.$tag['length'].',true);';
		}elseif(isset($tag['offset'])  && '' !=$tag['offset']){
            $parseStr  .= ' $__LIST__ = array_slice('.$name.','.$tag['offset'].',null,true);';
        }else{
            $parseStr .= ' $__LIST__ = '.$name.';';
        }
        $parseStr .= 'if( count($__LIST__)==0 ) : echo "'.$empty.'" ;';
        $parseStr .= 'else: ';
        $parseStr .= 'foreach($__LIST__ as $key=>$'.$id.'): ';
        $parseStr .= '$mod = ($'.$key.' % '.$mod.' );';
        $parseStr .= '++$'.$key.';?>';
        $parseStr .= $this->tpl->parse($content);
        $parseStr .= '<?php endforeach; endif; else: echo "'.$empty.'" ;endif; ?>';
        $_iterateParseCache[$cacheIterateId] = $parseStr;

        if(!empty($parseStr)) {
            return $parseStr;
        }
        return ;
    }

    /**
     * foreach标签解析 循环输出数据集
     * @access public
     * @param string $attr 标签属性
     * @param string $content  标签内容
     * @return string|void
     */
    public function _foreach($attr,$content) {
        static $_iterateParseCache = array();
        //如果已经解析过，则直接返回变量值
        $cacheIterateId = md5($attr.$content);
        if(isset($_iterateParseCache[$cacheIterateId]))
            return $_iterateParseCache[$cacheIterateId];
        $tag        =   $this->parseXmlAttr($attr,'foreach');
        $name       =   $tag['name'];
        $item       =   $tag['item'];
        $key        =   !empty($tag['key'])?$tag['key']:'key';
        $name       =   $this->autoBuildVar($name);
        $parseStr   =   '<?php if(is_array('.$name.')): foreach('.$name.' as $'.$key.'=>$'.$item.'): ?>';
        $parseStr  .=   $this->tpl->parse($content);
        $parseStr  .=   '<?php endforeach; endif; ?>';
        $_iterateParseCache[$cacheIterateId] = $parseStr;
        if(!empty($parseStr)) {
            return $parseStr;
        }
        return ;
    }

    /**
     * if标签解析
     * 格式：
     * <if condition=" $a eq 1" >
     * <elseif condition="$a eq 2" />
     * <else />
     * </if>
     * 表达式支持 eq neq gt egt lt elt == > >= < <= or and || &&
     * @access public
     * @param string $attr 标签属性
     * @param string $content  标签内容
     * @return string
     */
    public function _if($attr,$content) {
        $tag        =   $this->parseXmlAttr($attr,'if');
        $condition  =   $this->parseCondition($tag['condition']);
        $parseStr   =   '<?php if('.$condition.'): ?>'.$content.'<?php endif; ?>';
        return $parseStr;
    }

    /**
     * else标签解析
     * 格式：见if标签
     * @access public
     * @param string $attr 标签属性
     * @param string $content  标签内容
     * @return string
     */
    public function _elseif($attr,$content) {
        $tag        =   $this->parseXmlAttr($attr,'elseif');
        $condition  =   $this->parseCondition($tag['condition']);
        $parseStr   =   '<?php elseif('.$condition.'): ?>';
        return $parseStr;
    }

    /**
     * else标签解析
     * @access public
     * @param string $attr 标签属性
     * @return string
     */
    public function _else($attr) {
        $parseStr = '<?php else: ?>';
        return $parseStr;
    }

    /**
     * switch标签解析
     * 格式：
     * <switch name="a.name" >
     * <case value="1" break="false">1</case>
     * <case value="2" >2</case>
     * <default />other
     * </switch>
     * @access public
     * @param string $attr 标签属性
     * @param string $content  标签内容
     * @return string
     */
    public function _switch($attr,$content) {
        $tag        =   $this->parseXmlAttr($attr,'switch');
        $name       =   $tag['name'];
        $varArray   =   explode('|',$name);
        $name       =   array_shift($varArray);
        $name       =   $this->autoBuildVar($name);
        if(count($varArray)>0)
            $name   =   $this->tpl->parseVarFunction($name,$varArray);
        $parseStr   =   '<?php switch('.$name.'): ?>'.$content.'<?php endswitch;?>';
        return $parseStr;
    }

    /**
     * case标签解析 需要配合switch才有效
     * @access public
     * @param string $attr 标签属性
     * @param string $content  标签内容
     * @return string
     */
    public function _case($attr,$content) {
        $tag    = $this->parseXmlAttr($attr,'case');
        $value  = $tag['value'];
        if('$' == substr($value,0,1)) {
            $varArray   =   explode('|',$value);
            $value	    =	array_shift($varArray);
            $value      =   $this->autoBuildVar(substr($value,1));
            if(count($varArray)>0)
                $value  =   $this->tpl->parseVarFunction($value,$varArray);
            $value      =   'case '.$value.': ';
        }elseif(strpos($value,'|')){
            $values     =   explode('|',$value);
            $value      =   '';
            foreach ($values as $val){
                $value   .=  'case "'.addslashes($val).'": ';
            }
        }else{
            $value	=	'case "'.$value.'": ';
        }
        $parseStr = '<?php '.$value.' ?>'.$content;
        $isBreak  = isset($tag['break']) ? $tag['break'] : '';
        if('' ==$isBreak || $isBreak) {
            $parseStr .= '<?php break;?>';
        }
        return $parseStr;
    }

    /**
     * default标签解析 需要配合switch才有效
     * 使用： <default />ddfdf
     * @access public
     * @param string $attr 标签属性
     * @param string $content  标签内容
     * @return string
     */
    public function _default($attr) {
        $parseStr = '<?php default: ?>';
        return $parseStr;
    }

    /**
     * compare标签解析
     * 用于值的比较 支持 eq neq gt lt egt elt heq nheq 默认是eq
     * 格式： <compare name="" type="eq" value="" >content</compare>
     * @access public
     * @param string $attr 标签属性
     * @param string $content  标签内容
     * @return string
     */
    public function _compare($attr,$content,$type='eq') {
        $tag        =   $this->parseXmlAttr($attr,'compare');
        $name       =   $tag['name'];
        $value      =   $tag['value'];
        $type       =   isset($tag['type'])?$tag['type']:$type;
        $type       =   $this->parseCondition(' '.$type.' ');
        $varArray   =   explode('|',$name);
        $name       =   array_shift($varArray);
        $name       =   $this->autoBuildVar($name);
        if(count($varArray)>0)
            $name = $this->tpl->parseVarFunction($name,$varArray);
        if('$' == substr($value,0,1)) {
            $value  =  $this->autoBuildVar(substr($value,1));
        }else {
            $value  =   '"'.$value.'"';
        }
        $parseStr   =   '<?php if(('.$name.') '.$type.' '.$value.'): ?>'.$content.'<?php endif; ?>';
        return $parseStr;
    }

    public function _eq($attr,$content) {
        return $this->_compare($attr,$content,'eq');
    }

    public function _equal($attr,$content) {
        return $this->_compare($attr,$content,'eq');
    }

    public function _neq($attr,$content) {
        return $this->_compare($attr,$content,'neq');
    }

    public function _notequal($attr,$content) {
        return $this->_compare($attr,$content,'neq');
    }

    public function _gt($attr,$content) {
        return $this->_compare($attr,$content,'gt');
    }

    public function _lt($attr,$content) {
        return $this->_compare($attr,$content,'lt');
    }

    public function _egt($attr,$content) {
        return $this->_compare($attr,$content,'egt');
    }

    public function _elt($attr,$content) {
        return $this->_compare($attr,$content,'elt');
    }

    public function _heq($attr,$content) {
        return $this->_compare($attr,$content,'heq');
    }

    public function _nheq($attr,$content) {
        return $this->_compare($attr,$content,'nheq');
    }

    /**
     * range标签解析
     * 如果某个变量存在于某个范围 则输出内容 type= in 表示在范围内 否则表示在范围外
     * 格式： <range name="var|function"  value="val" type='in|notin' >content</range>
     * example: <range name="a"  value="1,2,3" type='in' >content</range>
     * @access public
     * @param string $attr 标签属性
     * @param string $content  标签内容
     * @param string $type  比较类型
     * @return string
     */
    public function _range($attr,$content,$type='in') {
        $tag        =   $this->parseXmlAttr($attr,'range');
        $name       =   $tag['name'];
        $value      =   $tag['value'];
        $varArray   =   explode('|',$name);
        $name       =   array_shift($varArray);
        $name       =   $this->autoBuildVar($name);
        if(count($varArray)>0)
            $name   =   $this->tpl->parseVarFunction($name,$varArray);

        $type       =   isset($tag['type'])?$tag['type']:$type;

        if('$' == substr($value,0,1)) {
            $value  =   $this->autoBuildVar(substr($value,1));
            $str    =   'is_array('.$value.')?'.$value.':explode(\',\','.$value.')';
        }else{
            $value  =   '"'.$value.'"';
            $str    =   'explode(\',\','.$value.')';
        }
        if($type=='between') {
            $parseStr = '<?php $_RANGE_VAR_='.$str.';if('.$name.'>= $_RANGE_VAR_[0] && '.$name.'<= $_RANGE_VAR_[1]):?>'.$content.'<?php endif; ?>';
        }elseif($type=='notbetween'){
            $parseStr = '<?php $_RANGE_VAR_='.$str.';if('.$name.'<$_RANGE_VAR_[0] || '.$name.'>$_RANGE_VAR_[1]):?>'.$content.'<?php endif; ?>';
        }else{
            $fun        =  ($type == 'in')? 'in_array'    :   '!in_array';
            $parseStr   = '<?php if('.$fun.'(('.$name.'), '.$str.')): ?>'.$content.'<?php endif; ?>';
        }
        return $parseStr;
    }

    // range标签的别名 用于in判断
    public function _in($attr,$content) {
        return $this->_range($attr,$content,'in');
    }

    // range标签的别名 用于notin判断
    public function _notin($attr,$content) {
        return $this->_range($attr,$content,'notin');
    }

    public function _between($attr,$content){
        return $this->_range($attr,$content,'between');
    }

    public function _notbetween($attr,$content){
        return $this->_range($attr,$content,'notbetween');
    }

    /**
     * present标签解析
     * 如果某个变量已经设置 则输出内容
     * 格式： <present name="" >content</present>
     * @access public
     * @param string $attr 标签属性
     * @param string $content  标签内容
     * @return string
     */
    public function _present($attr,$content) {
        $tag        =   $this->parseXmlAttr($attr,'present');
        $name       =   $tag['name'];
        $name       =   $this->autoBuildVar($name);
        $parseStr   =   '<?php if(isset('.$name.')): ?>'.$content.'<?php endif; ?>';
        return $parseStr;
    }

    /**
     * notpresent标签解析
     * 如果某个变量没有设置，则输出内容
     * 格式： <notpresent name="" >content</notpresent>
     * @access public
     * @param string $attr 标签属性
     * @param string $content  标签内容
     * @return string
     */
    public function _notpresent($attr,$content) {
        $tag        =   $this->parseXmlAttr($attr,'notpresent');
        $name       =   $tag['name'];
        $name       =   $this->autoBuildVar($name);
        $parseStr   =   '<?php if(!isset('.$name.')): ?>'.$content.'<?php endif; ?>';
        return $parseStr;
    }

    /**
     * empty标签解析
     * 如果某个变量为empty 则输出内容
     * 格式： <empty name="" >content</empty>
     * @access public
     * @param string $attr 标签属性
     * @param string $content  标签内容
     * @return string
     */
    public function _empty($attr,$content) {
        $tag        =   $this->parseXmlAttr($attr,'empty');
        $name       =   $tag['name'];
        $name       =   $this->autoBuildVar($name);
        $parseStr   =   '<?php if(empty('.$name.')): ?>'.$content.'<?php endif; ?>';
        return $parseStr;
    }

    public function _notempty($attr,$content) {
        $tag        =   $this->parseXmlAttr($attr,'notempty');
        $name       =   $tag['name'];
        $name       =   $this->autoBuildVar($name);
        $parseStr   =   '<?php if(!empty('.$name.')): ?>'.$content.'<?php endif; ?>';
        return $parseStr;
    }

    /**
     * 判断是否已经定义了该常量
     * <defined name='TXT'>已定义</defined>
     * @param <type> $attr
     * @param <type> $content
     * @return string
     */
    public function _defined($attr,$content) {
        $tag        =   $this->parseXmlAttr($attr,'defined');
        $name       =   $tag['name'];
        $parseStr   =   '<?php if(defined("'.$name.'")): ?>'.$content.'<?php endif; ?>';
        return $parseStr;
    }

    public function _notdefined($attr,$content) {
        $tag        =   $this->parseXmlAttr($attr,'_notdefined');
        $name       =   $tag['name'];
        $parseStr   =   '<?php if(!defined("'.$name.'")): ?>'.$content.'<?php endif; ?>';
        return $parseStr;
    }

    /**
     * import 标签解析 <import file="Js.Base" /> 
     * <import file="Css.Base" type="css" />
     * @access public
     * @param string $attr 标签属性
     * @param string $content  标签内容
     * @param boolean $isFile  是否文件方式
     * @param string $type  类型
     * @return string
     */
    public function _import($attr,$content,$isFile=false,$type='') {
        $tag        =   $this->parseXmlAttr($attr,'import');
        $file       =   isset($tag['file'])?$tag['file']:$tag['href'];
        $parseStr   =   '';
        $endStr     =   '';
        // 判断是否存在加载条件 允许使用函数判断(默认为isset)
        if (isset($tag['value'])) {
            $varArray  =    explode('|',$tag['value']);
            $name      =    array_shift($varArray);
            $name      =    $this->autoBuildVar($name);
            if (!empty($varArray))
                $name  =    $this->tpl->parseVarFunction($name,$varArray);
            else
                $name  =    'isset('.$name.')';
            $parseStr .=    '<?php if('.$name.'): ?>';
            $endStr    =    '<?php endif; ?>';
        }
        if($isFile) {
            // 根据文件名后缀自动识别
            $type  = $type?$type:(!empty($tag['type'])?strtolower($tag['type']):null);
            // 文件方式导入
            $array =  explode(',',$file);
            foreach ($array as $val){
                if (!$type || isset($reset)) {
                    $type = $reset = strtolower(substr(strrchr($val, '.'),1));
                }
                switch($type) {
                case 'js':
                    $parseStr .= '<script type="text/javascript" src="'.$val.'"></script>';
                    break;
                case 'css':
                    $parseStr .= '<link rel="stylesheet" type="text/css" href="'.$val.'" />';
                    break;
                case 'php':
                    $parseStr .= '<?php require_cache("'.$val.'"); ?>';
                    break;
                }
            }
        }else{
            // 命名空间导入模式 默认是js
            $type       =   $type?$type:(!empty($tag['type'])?strtolower($tag['type']):'js');
            $basepath   =   !empty($tag['basepath'])?$tag['basepath']:__ROOT__.'/Public';
            // 命名空间方式导入外部文件
            $array      =   explode(',',$file);
            foreach ($array as $val){
                list($val,$version) =   explode('?',$val);
                switch($type) {
                case 'js':
                    $parseStr .= '<script type="text/javascript" src="'.$basepath.'/'.str_replace(array('.','#'), array('/','.'),$val).'.js'.($version?'?'.$version:'').'"></script>';
                    break;
                case 'css':
                    $parseStr .= '<link rel="stylesheet" type="text/css" href="'.$basepath.'/'.str_replace(array('.','#'), array('/','.'),$val).'.css'.($version?'?'.$version:'').'" />';
                    break;
                case 'php':
                    $parseStr .= '<?php import("'.$val.'"); ?>';
                    break;
                }
            }
        }
        return $parseStr.$endStr;
    }

    // import别名 采用文件方式加载(要使用命名空间必须用import) 例如 <load file="__PUBLIC__/Js/Base.js" />
    public function _load($attr,$content) {
        return $this->_import($attr,$content,true);
    }

    // import别名使用 导入css文件 <css file="__PUBLIC__/Css/Base.css" />
    public function _css($attr,$content) {
        return $this->_import($attr,$content,true,'css');
    }

    // import别名使用 导入js文件 <js file="__PUBLIC__/Js/Base.js" />
    public function _js($attr,$content) {
        return $this->_import($attr,$content,true,'js');
    }

    /**
     * assign标签解析
     * 在模板中给某个变量赋值 支持变量赋值
     * 格式： <assign name="" value="" />
     * @access public
     * @param string $attr 标签属性
     * @param string $content  标签内容
     * @return string
     */
    public function _assign($attr,$content) {
        $tag        =   $this->parseXmlAttr($attr,'assign');
        $name       =   $this->autoBuildVar($tag['name']);
        if('$'==substr($tag['value'],0,1)) {
            $value  =   $this->autoBuildVar(substr($tag['value'],1));
        }else{
            $value  =   '\''.$tag['value']. '\'';
        }
        $parseStr   =   '<?php '.$name.' = '.$value.'; ?>';
        return $parseStr;
    }

    /**
     * define标签解析
     * 在模板中定义常量 支持变量赋值
     * 格式： <define name="" value="" />
     * @access public
     * @param string $attr 标签属性
     * @param string $content  标签内容
     * @return string
     */
    public function _define($attr,$content) {
        $tag        =   $this->parseXmlAttr($attr,'define');
        $name       =   '\''.$tag['name']. '\'';
        if('$'==substr($tag['value'],0,1)) {
            $value  =   $this->autoBuildVar(substr($tag['value'],1));
        }else{
            $value  =   '\''.$tag['value']. '\'';
        }
        $parseStr   =   '<?php define('.$name.', '.$value.'); ?>';
        return $parseStr;
    }
    
    /**
     * for标签解析
     * 格式： <for start="" end="" comparison="" step="" name="" />
     * @access public
     * @param string $attr 标签属性
     * @param string $content  标签内容
     * @return string
     */
    public function _for($attr, $content){
        //设置默认值
        $start 		= 0;
        $end   		= 0;
        $step 		= 1;
        $comparison = 'lt';
        $name		= 'i';
        $rand       = rand(); //添加随机数，防止嵌套变量冲突
        //获取属性
        foreach ($this->parseXmlAttr($attr, 'for') as $key => $value){
            $value = trim($value);
            if(':'==substr($value,0,1))
                $value = substr($value,1);
            elseif('$'==substr($value,0,1))
                $value = $this->autoBuildVar(substr($value,1));
            switch ($key){
                case 'start':   
                    $start      = $value; break;
                case 'end' :    
                    $end        = $value; break;
                case 'step':    
                    $step       = $value; break;
                case 'comparison':
                    $comparison = $value; break;
                case 'name':
                    $name       = $value; break;
            }
        }
        
        $parseStr   = '<?php $__FOR_START_'.$rand.'__='.$start.';$__FOR_END_'.$rand.'__='.$end.';';
        $parseStr  .= 'for($'.$name.'=$__FOR_START_'.$rand.'__;'.$this->parseCondition('$'.$name.' '.$comparison.' $__FOR_END_'.$rand.'__').';$'.$name.'+='.$step.'){ ?>';
        $parseStr  .= $content;
        $parseStr  .= '<?php } ?>';
        return $parseStr;
    }

    }
