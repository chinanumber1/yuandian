<?php

class IndexAction extends BaseAction
{

    public function index()
    {
        $categoyList = D('Goods_category')->get_list();
        $this->assign('categoryList', $categoyList);
        
        $database_adver_category  = D('Adver_category');
        $count = 0;
        if ($category = D('Adver_category')->field(true)->where(array('cat_key' => 'web_mall_middle'))->find()) {
            $count = D('Adver')->field(true)->where(array('cat_id' => $category['cat_id'], 'status' => 1))->count();
        }
        
        $this->assign('count', $count);
        
        $this->display();
    }
    
    public function mallGoods()
    {
//         $cateId = isset($_POST['cateId']) ? intval($_POST['cateId']) : 0;
//         $flist = D('Goods_category')->field('fid')->where(array('id' => $cateId, 'is_hot' => 1))->find();
//         $fid = isset($flist['fid']) ? intval($flist['fid']) : 0;
        
//         $sql = "SELECT `g`.`goods_id`, `g`.`name`, `g`.`image`, `g`.`price`, `g`.`sell_count` ,`sh`.`score_mean` FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS sh ON `s`.`status`=1 AND `s`.`have_shop`=1 AND `sh`.`store_theme`=1 AND `s`.`store_id`=`sh`.`store_id` INNER JOIN " . C('DB_PREFIX') . "shop_goods AS g ON `sh`.`store_id`=`g`.`store_id` AND `s`.`status`=1 WHERE g.cat_id={$cateId} LIMIT 10";
//         $return = D('Shop_goods')->get_list_by_option(array('cat_id' => $cateId), 1);
//         exit(json_encode(array('error' => 0, 'data' => $return['goods_list'], 'fid' => $fid)));
        
        
        
        $where = array();
        $where['cat_fid'] = isset($_POST['catefid']) ? intval($_POST['catefid']) : 0;
        $where['cat_id'] = isset($_POST['cateid']) ? intval($_POST['cateid']) : 0;
        $fid = 0;
        if ($where['cat_fid']) {
            $fid = $where['cat_fid'];
        } elseif ($where['cat_id']) {
            $flist = D('Goods_category')->field('fid')->where(array('id' => $where['cat_id']))->find();
            $fid = isset($flist['fid']) ? intval($flist['fid']) : 0;
        }
        
        $where['store_id'] = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $where['page'] = isset($_POST['page']) ? intval($_POST['page']) : 1;
        
        $pids = isset($_POST['pids']) ? trim(htmlspecialchars($_POST['pids'])) : '';
        
        
        $where['key'] = isset($_POST['key']) ? trim(htmlspecialchars($_POST['key'])) : '';
        
        $search_type = isset($_POST['search_type']) ? intval($_POST['search_type']) : 0;
        $sort = isset($_POST['sort']) ? intval($_POST['sort']) : 1;//排序字段(1:goods_id, 2:sell_count, 3:price)
        if (!in_array($sort, array(1, 2, 3))) $sort = 1;
        $sort_type = isset($_POST['sort_type']) ? intval($_POST['sort_type']) : 1;//排序方式(1:DESC, 2:ASC)
        if ($sort_type != 1 && $sort_type != 2) $sort_type = 1;
        
        $where['pids'] = null;
        if ($pids) $where['pids'][] = $pids;
        if ($search_type == 0) {
            $return = D('Shop_goods')->get_list_by_option($where, $sort, $sort_type);
        } else {
            $return = D('Merchant_store_shop')->get_store_by_search($where);
        }
        $return['fid'] = $fid;
        exit(json_encode($return));
        
        
    }
    
    public function hotList()
    {
        $items = D('Goods_category')->field(true)->where('((fid>0 AND status=1) OR fid=0) AND is_hot=1')->order('`sort` DESC, `id` ASC')->select();
        
        $tmpMap = array();
        foreach ($items as $item) {
            if ($item['image']) {
                $item['image'] = $this->config['site_url'] . '/upload/goodscategory/' . $item['image'];
            }
            $tmpMap[$item['id']] = $item;
        }
        $list = array();
        $tlist = array();
        foreach ($items as $item) {
            if (isset($tmpMap[$item['fid']])) {
                $tmpMap[$item['fid']]['son_list'][] = &$tmpMap[$item['id']];
            } else {
                $tlist[] = &$tmpMap[$item['id']];
            }
        }
        foreach ($tlist as $tl) {
            if ($tl['status'] == 1) {
                $list[] = $tl;
            }
        }
        unset($tmpMap);
        foreach ($list as $key => $va) {
            if(empty($va['son_list'])){
                unset($list[$key]);
            }
        }
        exit(json_encode(array('error' => 0, 'data' => $list)));
    }
    
    
    public function detail()
    {
        $fid = isset($_GET['catefid']) ? intval($_GET['catefid']) : 0;
        $cid = isset($_GET['cateid']) ? intval($_GET['cateid']) : 0;
        $category = D('Goods_category')->field(true)->where(array('id' => $fid))->find();
        if (empty($category) || $category['fid']) {
            $this->error_tips('请选择正确的分类');
        }
        $sonCategorys = D('Goods_category')->field(true)->where(array('fid' => $fid, 'status' => 1))->select();
        $banners = D('Goods_category_banner')->field(true)->where(array('cat_id' => $fid))->select();
        foreach ($banners as &$banner) {
            $banner['image'] = $this->config['site_url'] . '/upload/goodsbanner/' . $banner['image'];
        }
        
        $proList = D('Goods_properties')->field(true)->where(array('cat_id' => $cid, 'status' => 1))->select();
        $pids = array();
        $pdata = array();
        foreach ($proList as $row) {
            if (!in_array($row['id'], $pids)) {
                $pids[] = $row['id'];
            }
            $row['value'] = array();
            $pdata[$row['id']] = $row;
        }
        if ($pids) {
            $valueList = D('Goods_properties_value')->field(true)->where(array('pid' => array('in', $pids)))->select();
            foreach ($valueList as $val) {
                if (isset($pdata[$val['pid']])) {
                    $pdata[$val['pid']]['value'][] = $val;
                }
            }
        }
        $this->assign('banners', $banners);
        $this->assign('fcategory', $category);
        $this->assign('sonCategorys', $sonCategorys);
        $categoyList = D('Goods_category')->get_list();
        $this->assign('categoryList', $categoyList);
        $this->assign('pdata', $pdata);
        $this->assign('cid', $cid);
        $this->assign('fid', $fid);
        $this->display();
    }
    
    public function goods()
    {
        $goodsid = isset($_GET['goodsid']) ? intval($_GET['goodsid']) : 0;
        $database_shop_goods = D('Shop_goods');
        $now_goods = $database_shop_goods->get_goods_by_id($goodsid);
        if(empty($now_goods)){
            $this->error_tips('商品不存在！');
        }
        
        $cateids = array($now_goods['cat_fid'], $now_goods['cat_id']);
        $fcate = $scate = null;
        if ($cateids) {
            $dlist = D('Goods_category')->field(true)->where(array('id' => array('in', $cateids)))->select();
            foreach ($dlist as $d) {
                if ($d['fid']) {
                    $scate = $d;
                } else {
                    $fcate = $d;
                }
            }
        }
        

        
        $this->assign(array('fcate' => $fcate, 'scate' => $scate));
        
        $categoyList = D('Goods_category')->get_list();
        $this->assign('categoryList', $categoyList);
        $store_id = $now_goods['store_id'];
        $store = $this->now_store($store_id);
        if (empty($store)) {
            $this->error('不存在的店铺信息');
        }
        $goodsList = $this->limitGoods($store_id);
        $this->assign('goodsList', $goodsList);
        $store['phone'] = explode(' ', $store['phone']);
        $cartGoods = array();
        $cartGoods['productId'] = $now_goods['goods_id'];
        $cartGoods['store_id'] = $now_goods['store_id'];
        $cartGoods['name'] = $store['name'];
        $cartGoods['image'] = isset($now_goods['pic_arr'][0]['url']) ? $now_goods['pic_arr'][0]['url'] : '';
        $cartGoods['productName'] = $now_goods['name'];
        $cartGoods['productStock'] = $now_goods['stock_num'];
        $cartGoods['productPrice'] = $now_goods['price'];
        $cartGoods['productExtraPrice'] = $now_goods['extra_pay_price'];
        $cartGoods['productPackCharge'] = $now_goods['packing_charge'];
        $cartGoods['maxNum'] = $now_goods['max_num'];
        $cartGoods['isSeckill'] = $now_goods['is_seckill_price'];
        $cartGoods['oldPrice'] = $now_goods['old_price'];
        $cartGoods['count'] = 0;
        $cartGoods['productParam'] = '';
        $this->assign('cartGoods', json_encode($cartGoods));
        
        $this->assign('store', $store);
        $this->assign('goods_detail', $now_goods['list'] ? json_encode($now_goods['list']) : '');
        $this->assign('now_goods', $now_goods);
        $this->display();
    }
    
    public function comment()
    {
        $goodsid = isset($_GET['goodsid']) ? intval($_GET['goodsid']) : 0;
        $database_shop_goods = D('Shop_goods');
        $now_goods = $database_shop_goods->get_goods_by_id($goodsid);
        if(empty($now_goods)){
            $this->error_tips('商品不存在！');
        }
        
        
        $cateids = array($now_goods['cat_fid'], $now_goods['cat_id']);
        $fcate = $scate = null;
        if ($cateids) {
            $dlist = D('Goods_category')->field(true)->where(array('id' => array('in', $cateids)))->select();
            foreach ($dlist as $d) {
                if ($d['fid']) {
                    $scate = $d;
                } else {
                    $fcate = $d;
                }
            }
        }
        $this->assign(array('fcate' => $fcate, 'scate' => $scate));
        
        $store_id = $now_goods['store_id'];
        $store = $this->now_store($store_id);
        if (empty($store)) {
            $this->error('不存在的店铺信息');
        }
        $goodsList = $this->limitGoods($store_id);
        $this->assign('goodsList', $goodsList);
        $store['phone'] = explode(' ', $store['phone']);
        $tab = isset($_GET['tab']) ? trim(htmlspecialchars($_GET['tab'])) : '';
        $reply_return = D('Reply')->get_page_reply_list($store_id, 3, $tab, '', '', true, 2);
        $this->assign('tab', $tab);
        
        $this->assign($reply_return);
        $this->assign('store', $store);
        $cartGoods = array();
        $cartGoods['productId'] = $now_goods['goods_id'];
        $cartGoods['store_id'] = $now_goods['store_id'];
        $cartGoods['name'] = $store['name'];
        $cartGoods['image'] = isset($now_goods['pic_arr'][0]['url']) ? $now_goods['pic_arr'][0]['url'] : '';
        $cartGoods['productName'] = $now_goods['name'];
        $cartGoods['productStock'] = $now_goods['stock_num'];
        $cartGoods['productPrice'] = $now_goods['price'];
        $cartGoods['productExtraPrice'] = $now_goods['extra_pay_price'];
        $cartGoods['productPackCharge'] = $now_goods['packing_charge'];
        $cartGoods['maxNum'] = $now_goods['max_num'];
        $cartGoods['isSeckill'] = $now_goods['is_seckill_price'];
        $cartGoods['oldPrice'] = $now_goods['old_price'];
        $cartGoods['count'] = 0;
        $cartGoods['productParam'] = '';
        $this->assign('cartGoods', json_encode($cartGoods));
        $this->assign('now_goods', $now_goods);
        
        $categoyList = D('Goods_category')->get_list();
        $this->assign('categoryList', $categoyList);
        $this->display();
    }
    
    public function search()
    {
        $key = isset($_GET['key']) ? trim(htmlspecialchars($_GET['key'])) : '';
        $where = array();
        if (!empty($key)) {
            $where['key'] = $key;
        }
        $search_type = isset($_GET['search_type']) ? intval($_GET['search_type']) : 0;
        $return = array();
        if ($where) {
            if ($search_type == 0) {
                $return = D('Shop_goods')->get_list_by_option($where, 1, 1);
            } else {
                $return = D('Merchant_store_shop')->get_store_by_search($where);
            }
        }
        
        $categoyList = D('Goods_category')->get_list();
        $this->assign('categoryList', $categoyList);
        $this->assign('key', $key);
        $this->assign('return', $return);
        $this->assign('search_type', $search_type);
        $this->display();
    }
    
    private function limitGoods($store_id)
    {
        $g_list = D('Shop_goods')->field(true)->where(array('store_id' => $store_id, 'status' => 1))->order('sort DESC, goods_id DESC')->limit(3)->select();
        $goods_image_class = new goods_image();
//         $timeNow = time();
        foreach ($g_list as &$row) {
            //新增限时显示
//             if (!($row['show_start_time'] == $row['show_end_time'] || ($row['show_start_time'] == '00:00:00' && $row['show_end_time'] == '23:59:00'))) {
//                 $st = strtotime(date('Y-m-d') . ' ' . $row['show_start_time']);
//                 $et = strtotime(date('Y-m-d') . ' ' . $row['show_end_time']);
//                 if (!($st <= $timeNow && $timeNow <= $et)) {
//                     continue;
//                 }
//             }
            if ($row['seckill_type'] == 1) {
                $now_time = date('H:i');
                $open_time = date('H:i', $row['seckill_open_time']);
                $close_time = date('H:i', $row['seckill_close_time']);
            } else {
                $now_time = time();
                $open_time = $row['seckill_open_time'];
                $close_time = $row['seckill_close_time'];
            }
            $row['is_seckill_price'] = false;
            $row['o_price'] = floatval($row['price']);
            if ($open_time < $now_time && $now_time < $close_time && floatval($row['seckill_price']) > 0) {
                $row['price'] = floatval($row['seckill_price']);
                $row['is_seckill_price'] = true;
            } else {
                $row['price'] = floatval($row['price']);
            }
            
            $row['old_price'] = floatval($row['old_price']);
            $row['seckill_price'] = floatval($row['seckill_price']);
            $tmp_pic_arr = explode(';', $row['image']);
            $image = '';
            foreach ($tmp_pic_arr as $key => $value) {
                if (empty($image)) {
                    $image = $goods_image_class->get_image_by_path($value, 's');
                }
            }
            $row['image'] = $image;
//             $return = $this->format_spec_value($row['spec_value'], $row['goods_id'], $row['is_properties']);
//             $row['properties_list'] = isset($return['properties_list']) ? $return['properties_list'] : '';
//             $row['properties_status_list'] = isset($return['properties_status_list']) ? $return['properties_status_list'] : '';
//             $row['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
//             $row['list'] = isset($return['list']) ? $return['list'] : '';
            
//             if (isset($s_list[$row['sort_id']])) {
//                 if (isset($s_list[$row['sort_id']]['goods_list'])) {
//                     $s_list[$row['sort_id']]['goods_list'][] = $row;
//                 } else {
//                     $s_list[$row['sort_id']]['goods_list'] = array($row);
//                 }
//             }
        }
        return $g_list;
    }
    public function shop()
    {
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
        
        $store = $this->now_store($store_id);
        if (empty($store)) {
            $this->error('不存在的店铺信息');
        }
        $this->assign('store', $store);
        
        $categoyList = D('Goods_category')->get_list();
        $this->assign('categoryList', $categoyList);
        
        $sortId = isset($_GET['sort_id']) ? $_GET['sort_id'] : 0;
        
        $sortList = D('Shop_goods_sort')->lists($store_id);
        $firstSort = reset($sortList);
        if (empty($sortId)) {
            $url = '/mall/shop/' . $store_id;
            $sortId = isset($firstSort['sort_id']) ? $firstSort['sort_id'] : 0;
        } else {
            $url = '/mall/shop/' . $store_id . '/' . $sortId;
        }
        $sort = isset($_GET['order']) ? htmlspecialchars(trim($_GET['order'])) : '';
        
        $order = 'sort DESC, goods_id ASC';
        if ($sort == 'asell') {
            $order = 'sell_count ASC, goods_id ASC';
        } elseif ($sort == 'dsell') {
            $order = 'sell_count DESC, goods_id ASC';
        } elseif ($sort == 'aprice') {
            $order = 'price ASC, goods_id ASC';
        } elseif ($sort == 'dprice') {
            $order = 'price DESC, goods_id ASC';
        }
        $this->assign('sort', $sort);
        $goodsList = $this->getGoodsBySortId($sortId, $store_id, $order);
        
        $select_ids = D('Shop_goods_sort')->getIds($sortId, $store_id);
        
        $secondSort = array();
        $threeSort = array();
        switch (count($select_ids)) {
            case 1:
                $secondSort = isset($sortList[$select_ids[0]]['son_list']) ? $sortList[$select_ids[0]]['son_list']: array();
                if ($secontSort) {
                    $tempSort = reset($secondSort);
                    $threeSort = isset($tempSort['son_list']) ? $tempSort['son_list'] : array();
                }
                break;
            case 2:
            case 3:
                $secondSort = isset($sortList[$select_ids[0]]['son_list']) ? $sortList[$select_ids[0]]['son_list']: array();
                $threeSort = isset($secondSort[$select_ids[1]]['son_list']) ? $secondSort[$select_ids[1]]['son_list']: array();
                break;
        }
        $this->assign(array('secondSort' => $secondSort, 'threeSort' => $threeSort, 'select_ids' => $select_ids));
        $this->assign('sort_list', array_values($sortList));
        $this->assign('goodsList', $goodsList);
        $this->assign('url', $url);
        $this->display();
    }
    
    
    private function getGoodsBySortId($sortId, $store_id, $order)
    {
        $now_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find();
        $sortIds = D('Shop_goods_sort')->getAllSonIds($sortId, $store_id);
        $product_list = D('Shop_goods')->getGoodsBySortIds($sortIds, $store_id, false, $order);
        $list = array();
        foreach ($product_list as $row) {
            $temp = array();
            $temp['cat_id'] = $row['sort_id'];
            $temp['cat_name'] = $row['sort_name'];
            $temp['sort_discount'] = $row['sort_discount']/10;
            foreach ($row['goods_list'] as $r) {
                $glist = array();
                $glist['product_id'] = $r['goods_id'];
                $glist['product_name'] = $r['name'];
                $glist['product_price'] = $r['price'];
                $glist['is_seckill_price'] = $r['is_seckill_price'];
                $glist['o_price'] = $r['o_price'];
                $glist['number'] = $r['number'];
                $glist['max_num'] = $r['max_num'];
                $glist['packing_charge'] = floatval($r['packing_charge']);
                $glist['unit'] = $r['unit'];
                if (isset($r['pic_arr'][0])) {
                    $glist['product_image'] = $r['pic_arr'][0]['url']['s_image'];
                }
                $glist['product_sale'] = $r['sell_count'];
                $glist['product_reply'] = $r['reply_count'];
                $glist['has_format'] = false;
                if ($r['spec_value'] || $r['is_properties']) {
                    $glist['has_format'] = true;
                }
                if($r['extra_pay_price']>0){
                    $glist['extra_pay_price']=$r['extra_pay_price'];
                    $glist['extra_pay_price_name']=$this->config['extra_price_alias_name'];
                }
                
                if ($r['seckill_type'] == 1) {
                    $now_time = date('H:i');
                    $open_time = date('H:i', $r['seckill_open_time']);
                    $close_time = date('H:i', $r['seckill_close_time']);
                    
                    //秒杀库存的计算
                    if ($today == $r['sell_day']) {
                        $seckill_stock_num = $r['seckill_stock'] == -1 ? -1 : (intval($r['seckill_stock'] - $r['today_seckill_count']) > 0 ? intval($r['seckill_stock'] - $r['today_seckill_count']) : 0);
                    } else {
                        $seckill_stock_num = $r['seckill_stock'];
                    }
                } else {
                    $now_time = time();
                    $open_time = $r['seckill_open_time'];
                    $close_time = $r['seckill_close_time'];
                    $seckill_stock_num = $r['seckill_stock'] == -1 ? -1 : (intval($r['seckill_stock'] - $r['today_seckill_count']) > 0 ? intval($r['seckill_stock'] - $r['today_seckill_count']) : 0);
                }
                
                $r['is_seckill_price'] = false;
                $r['o_price'] = floatval($r['price']);
                if ($open_time < $now_time && $now_time < $close_time && floatval($r['seckill_price']) > 0 && $seckill_stock_num != 0) {
                    $r['price'] = floatval($r['seckill_price']);
                    $r['is_seckill_price'] = true;
                } else {
                    $r['price'] = floatval($r['price']);
                }
                
                
                $r['sell_day'] = $now_shop['stock_type'] ? $today : $r['sell_day'];
                if ($r['is_seckill_price']) {
                    $glist['stock'] = $seckill_stock_num;
                } else {
                    if ($today == $r['sell_day']) {
                        $glist['stock'] = $r['stock_num'];
                    } else {
                        $glist['stock'] = $r['original_stock'];
                    }
                }
                $temp['product_list'][] = $glist;
            }
            $list[] = $temp;
        }
        return $list;
    }
    
    
    public function scomment()
    {
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
        $store = $this->now_store($store_id);
        if (empty($store)) {
            $this->error('不存在的店铺信息');
        }
        $tab = isset($_GET['tab']) ? trim(htmlspecialchars($_GET['tab'])) : '';
        $reply_return = D('Reply')->get_page_reply_list($store_id, 3, $tab, '', '', true, 2);
        $this->assign('tab', $tab);
        
        $this->assign($reply_return);
        $this->assign('store', $store);
        
        $categoyList = D('Goods_category')->get_list();
        $this->assign('categoryList', $categoyList);
        $this->display();
        
    }
    
    
    private function now_store($store_id)
    {
        $where = array('store_id' => $store_id);
        $now_store = D('Merchant_store')->field(true)->where($where)->find();
        //资质认证
        if ($this->config['store_shop_auth'] == 1 && $now_store['auth'] < 3) {
//             $this->error_tips('您查看的'.$this->config['shop_alias_name'].'没有通过资质审核！');
//             exit;
        }
        $now_shop = D('Merchant_store_shop')->field(true)->where($where)->find();
        if (empty($now_shop) || empty($now_store)) {
            $this->error_tips('店铺信息错误');
        }
        
        if (!empty($now_shop['background'])) {
            $image_tmp = explode(',', $now_shop['background']);
            $now_shop['background'] = C('config.site_url') . '/upload/background/' . $image_tmp[0] . '/' . $image_tmp['1'];
        }
        
        
        $city_name = $province_name = '';
        $areas = D('Area')->field(true)->where(array('area_id' => array('in', array($now_store['province_id'], $now_store['city_id']))))->select();
        foreach ($areas as $area) {
            if ($area['area_pid']) {
                $city_name = $area['area_name'];
            } else {
                $province_name = $area['area_name'];
            }
        }
        
        $discounts = D('Shop_discount')->getDiscounts($now_store['mer_id'], $store_id);
        $row = array_merge($now_store, $now_shop);
        $store = array();
        $store_image_class = new store_image();
        $images = $store_image_class->get_allImage_by_path($row['pic_info']);
        
        $store['id'] = $row['store_id'];
        $store['store_id'] = $row['store_id'];
        $store['phone'] = $row['phone'];
        $store['background'] = $row['background'];
        $store['long'] = $row['long'];
        $store['lat'] = $row['lat'];
        $store['store_theme'] = $row['store_theme'];
        $store['adress'] = $row['adress'];
        $store['is_close'] = 1;
        if (D('Merchant_store_shop')->checkTime($row)) {
            $store['is_close'] = 0;
        }
        $store['time'] = D('Merchant_store_shop')->getBuniessName($row);
        
        $store['now_city_name'] = $province_name . ' ' . $city_name;
        
        
        $now_time = date('H:i:s');
        
        $store['name'] = $row['name'];
        $store['store_notice'] = $row['store_notice'];
        $store['txt_info'] = $row['txt_info'];
        $store['image'] = isset($images[0]) ? $images[0] : '';
        $store['star'] = $row['score_mean'];
        $store['month_sale_count'] = $row['sale_count'];
        $store['reply_count'] = $row['reply_count'];
        $store['delivery'] = $row['deliver_type'] == 2 ? false : true;//是否支持配送
        $store['delivery_time'] = $row['send_time'];//配送时长
        $store['delivery_price'] = floatval($row['basic_price']);//起送价
        
        $store['delivery_money'] = floatval($store['delivery_money']);
        
        $store['pack_alias'] = $row['pack_alias'];//打包费别名
        $store['freight_alias'] = $row['freight_alias'];//运费别名
        $store['coupon_list'] = array();
        if ($row['is_invoice']) {
            $store['coupon_list']['invoice'] = floatval($row['invoice_price']);
        }
        if ($row['store_discount'] != 0 && $row['store_discount'] != 100) {
            $store['coupon_list']['discount'] = $row['store_discount']/10;
        }
        $system_delivery = array();
        
        $sys_newuser = '';
        $sys_minus  = '';
        $sys_delivery = '';
        if (isset($discounts[0]) && $discounts[0]) {
            foreach ($discounts[0] as $row_d) {
                if ($row_d['type'] == 0) {//新单
                    $store['coupon_list']['system_newuser'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
                    $sys_newuser .= '满' . floatval($row_d['full_money']) . '元减' . floatval($row_d['reduce_money']) . '元,';
                } elseif ($row_d['type'] == 1) {//满减
                    $store['coupon_list']['system_minus'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
                    $sys_minus  .= '满' . floatval($row_d['full_money']) . '元减' . floatval($row_d['reduce_money']) . '元,';
                } elseif ($row_d['type'] == 2) {//配送
                    $system_delivery[] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
                    $sys_delivery .= '满' . floatval($row_d['full_money']) . '元减' . floatval($row_d['reduce_money']) . '元,';
                }
            }
        }
        
        $mer_newuser = '';
        $mer_minus  = '';
        $mer_delivery = '';
        if (isset($discounts[$store_id]) && $discounts[$store_id]) {
            foreach ($discounts[$store_id] as $row_m) {
                if ($row_m['type'] == 0) {
                    $store['coupon_list']['newuser'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
                    $mer_newuser .= '满' . floatval($row_m['full_money']) . '元减' . floatval($row_m['reduce_money']) . '元,';
                } elseif ($row_m['type'] == 1) {
                    $store['coupon_list']['minus'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
                    $mer_minus .= '满' . floatval($row_m['full_money']) . '元减' . floatval($row_m['reduce_money']) . '元,';
                }
            }
        }
        if ($store['delivery']) {
            if ($store['delivery_system']) {
                $system_delivery && $store['coupon_list']['delivery'] = $system_delivery;
            } else {
                $sys_delivery = '';
                if ($is_have_two_time) {
                    if ($row['reach_delivery_fee_type2'] == 0) {
                        if ($row['basic_price'] > 0 && $row['delivery_fee2'] > 0) {
                            $store['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee2']));
                            $mer_delivery .= '满' . floatval($row['basic_price']) . '元减' . floatval($row['delivery_fee2']) . '元,';
                        }
                    } elseif ($row['reach_delivery_fee_type'] == 1) {
                        //$store['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
                    } elseif ($row['delivery_fee2']) {
                        $store['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value2']), 'minus' => floatval($row['delivery_fee2']));
                        $mer_delivery .= '满' . floatval($row['no_delivery_fee_value2']) . '元减' . floatval($row['delivery_fee2']) . '元,';
                    }
                } else {
                    if ($row['reach_delivery_fee_type'] == 0) {
                        if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
                            $store['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
                            $mer_delivery .= '满' . floatval($row['basic_price']) . '元减' . floatval($row['delivery_fee']) . '元,';
                        }
                    } elseif ($row['reach_delivery_fee_type'] == 1) {
                        //$store['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
                    } elseif ($row['delivery_fee']) {
                        $store['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
                        $mer_delivery .= '满' . floatval($row['no_delivery_fee_value']) . '元减' . floatval($row['delivery_fee']) . '元,';
                    }
                }
            }
        }
        $store['txt_discount'] = array('sys_newuser' => rtrim($sys_newuser, ','), 'sys_minus' => rtrim($sys_minus, ','), 'sys_delivery' => rtrim($sys_delivery, ','), 'mer_newuser' => rtrim($mer_newuser, ','), 'mer_minus' => rtrim($mer_minus, ','), 'mer_delivery' => rtrim($mer_delivery, ','));
        $store['goods_count'] = D('Shop_goods')->where(array('store_id' => $store_id, 'status' => 1))->count();
        return $store;
    }
    public function ajaxMall()
    {
        $cookieData = isset($_POST['pc_mall_cart']) && $_POST['pc_mall_cart'] ? htmlspecialchars_decode($_POST['pc_mall_cart']) : '';
        $_SESSION['pc_mall_cart'] = $cookieData;
        if (empty($cookieData)) {
            exit(json_encode(array('error' => 1, 'msg' => '请购物')));
        } else {
            exit(json_encode(array('error' => 0, 'msg' => 'ok')));
        }
    }
    
    public function cart()
    {
        $categoyList = D('Goods_category')->get_list();
        $this->assign('categoryList', $categoyList);
        $this->display();
    }
}