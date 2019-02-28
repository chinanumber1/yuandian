<?php

/**
 * 自定义字段
 * User: pigcms_21
 * Date: 2015/2/9
 * Time: 20:47
 */
class Merchant_store_diypage_fieldModel extends Model{
	// 富文本中链接的store_id替换成当前店铺的store_id
	private function repalace_store_id($content = '',$seller_id){
		if($content){
			$html = htmlspecialchars_decode($content, ENT_QUOTES);
			$content = preg_replace_callback('/href=[\'\"]?([^\'\" ]+).*?[\'\"]?/', create_function('$matches','return richTextFormat($matches[1],' . $seller_id . ');'), $html);
			return htmlspecialchars($content);
		} else {
			return $content;
		}
	}

	/**
	 * 查询
	 *
	 * store_id  	  店铺字段
	 * page_id      页面ID
	 *
	 */
	public function get_field($store_id, $page_id, $seller_id = 0) {
		if ($store_id) {
			$condition_field['store_id'] = $store_id;
		}
		if (empty($seller_id)) {
			$seller_id	= $store_id;
		}

		$condition_field['page_id'] = $page_id;
		$field_list = $this->field('`field_type`,`content`')->where($condition_field)->order('`field_id` ASC')->select();
		foreach ($field_list as &$value) {
			$value['content'] = unserialize($value['content']);
			if (strtolower($value['field_type']) == 'goods') {
				if (!empty($value['content']['goods'])) {
					$goods = array();
					foreach ($value['content']['goods'] as $product) {
						$product['title'] = htmlspecialchars_decode($product['title'], ENT_QUOTES);
						$product['image'] = getAttachmentUrl($product['image']);
						$goods[] = $product;
					}
					$value['content']['goods'] = $goods;
				}
			} else if (strtolower($value['field_type']) == 'rich_text') {

				//替换富文本中的store_id
				$content = $this->repalace_store_id($value['content']['content'],$seller_id);
				$value['content']['content'] = str_replace('&quot;', '"', $content);
			} else if (strtolower($value['field_type']) == 'title') {
				$value['content']['title'] = htmlspecialchars_decode($value['content']['title'], ENT_QUOTES);
				$value['content']['sub_title'] = htmlspecialchars_decode($value['content']['sub_title'], ENT_QUOTES);
			} else if (strtolower($value['field_type']) == 'text_nav') {
				$text_navs = array();
				foreach ($value['content'] as $key => $nav) {
					$nav['title'] = htmlspecialchars_decode($nav['title'], ENT_QUOTES);
					$nav['name'] = htmlspecialchars_decode($nav['name'], ENT_QUOTES);
					$text_navs[$key] = $nav;
				}
				$value['content'] = $text_navs;
			} else if (strtolower($value['field_type']) == 'image_nav') {
				$image_navs = array();
				foreach ($value['content'] as $key => $nav) {
					$nav['title'] = htmlspecialchars_decode($nav['title'], ENT_QUOTES);
					$nav['name'] = htmlspecialchars_decode($nav['name'], ENT_QUOTES);
					$nav['image'] = getAttachmentUrl($nav['image']);
					$image_navs[$key] = $nav;
				}
				$value['content'] = $image_navs;
			} else if (strtolower($value['field_type']) == 'link') {
				$links = array();
				if (!empty($value['content'])) {
					foreach ($value['content'] as $key => $link) {
						$link['title'] = htmlspecialchars_decode($link['title'], ENT_QUOTES);
						$link['name'] = htmlspecialchars_decode($link['name'], ENT_QUOTES);
						$links[$key] = $link;
					}
				}
				$value['content'] = $links;
			} else if (strtolower($value['field_type']) == 'notice') {
				$value['content']['content'] = htmlspecialchars_decode($value['content']['content'], ENT_QUOTES);
			} else if (strtolower($value['field_type']) == 'image_ad') {
				if (!empty($value['content']['nav_list'])) {
					$ads = array();
					foreach ($value['content']['nav_list'] as $ad) {
						$ad['title'] = htmlspecialchars_decode($ad['title'], ENT_QUOTES);
						$ad['name'] = htmlspecialchars_decode($ad['name'], ENT_QUOTES);
						$ad['image'] = getAttachmentUrl($ad['image']);
						$ads[] = $ad;
					}
					$value['content']['nav_list'] = $ads;
				}
			} else if (strtolower($value['field_type']) == 'tpl_shop') {
				$value['content']['title'] = htmlspecialchars_decode($value['content']['title'], ENT_QUOTES);
			} else if  (strtolower($value['field_type']) == 'tpl_shop1') {
				$value['content']['title'] = htmlspecialchars_decode($value['content']['title'], ENT_QUOTES);
			} else if (strtolower($value['field_type']) == 'map') {
				$store_contact = D('Store_contact')->where(array('store_id' => $store_id))->find();
				
				if (empty($store_contact)) {
					$store_contact['province'] = '';
					$store_contact['city'] = '';
					$store_contact['area'] = '';
					$store_contact['address'] = '';
					$store_contact['lng'] = '';
					$store_contact['lat'] = '';
					$store_contact['entity_name'] = '';
				} else {
					$store_contact['area'] = $store_contact['county'];
					$store_contact['lng'] = $store_contact['long'];
					$store_contact['entity_name'] = $store_contact['entity_name'] ? $store_contact['entity_name'] : '';
				}
				
				$value['content'] = $store_contact;
			}
		}
		return $field_list;
	}

	/**
	 *
	 *  清空数组里空的字段
	 *
	 */
	protected function clear_field($array) {
		$new_array = array();
		if (is_array($array)) {
			if (!empty($array['goods'])) {
				$goods = array();
				foreach ($array['goods'] as $product) {
					$product['title'] = htmlspecialchars(stripcslashes($product['title']), ENT_QUOTES); //解决serialize序列化单引号问题
					$product['image'] = getAttachment($product['image']);
					$goods[] = $product;
				}
				$array['goods'] = $goods;
			} else if (strtolower($array['type']) == 'rich_text') {
				$array['content'] = htmlspecialchars(stripcslashes($array['content']), ENT_QUOTES);
			} else if (strtolower($array['type']) == 'title') {
				$array['title'] = htmlspecialchars(stripcslashes($array['title']), ENT_QUOTES);
				$array['sub_title'] = htmlspecialchars(stripcslashes($array['sub_title']), ENT_QUOTES);
			} else if (strtolower($array['type']) == 'text_nav') {
				$type = array_pop($array);
				$text_navs = array();
				foreach ($array as $key => $nav) {
					$nav['title'] = htmlspecialchars(stripcslashes($nav['title']), ENT_QUOTES);
					$nav['name'] = htmlspecialchars(stripcslashes($nav['name']), ENT_QUOTES);
					$text_navs[$key] = $nav;
				}
				$array = $text_navs;
				$array['type'] = $type;
			} else if (strtolower($array['type']) == 'image_nav') {
				$type = array_pop($array);
				$image_navs = array();
				foreach ($array as $key => $nav) {
					$nav['title'] = htmlspecialchars(stripcslashes($nav['title']), ENT_QUOTES);
					$nav['name'] = htmlspecialchars(stripcslashes($nav['name']), ENT_QUOTES);
					$image_navs[$key] = $nav;
				}
				$array = $image_navs;
				$array['type'] = $type;
			} else if (strtolower($array['type']) == 'image_ad') {
				$nav_list = array();
				$array['nav_list'] = !empty($array['nav_list']) ? $array['nav_list'] : array();
				foreach ($array['nav_list'] as $key => $nav) {
					$nav['title'] = htmlspecialchars(stripcslashes($nav['title']), ENT_QUOTES);
					$nav['name'] = htmlspecialchars(stripcslashes($nav['name']), ENT_QUOTES);
					$nav_list[] = $nav;
				}
				$array['nav_list'] = $nav_list;
				$array['type'] = 'image_ad';
			} else if (strtolower($array['type']) == 'link') {
				$type = array_pop($array);
				$links = array();
				foreach ($array as $key => $link) {
					$link['title'] = htmlspecialchars(stripcslashes($link['title']), ENT_QUOTES);
					$link['name'] = htmlspecialchars(stripcslashes($link['name']), ENT_QUOTES);
					$links[$key] = $link;
				}
				$array = $links;
				$array['type'] = $type;
			} else if (strtolower($array['type']) == 'notice') {
				$array['content'] = htmlspecialchars(stripcslashes($array['content']), ENT_QUOTES);
			} else if (strtolower($array['type']) == 'component') {
				$array['name'] = htmlspecialchars(stripcslashes($array['name']), ENT_QUOTES);
			} else if (strtolower($array['type']) == 'tpl_shop') {
				$array['title'] = htmlspecialchars(stripcslashes($array['title']), ENT_QUOTES);
			} else if (strtolower($array['type']) == 'tpl_shop1') {
				$array['title'] = htmlspecialchars(stripcslashes($array['title']), ENT_QUOTES);
			}

			foreach ($array as $key => $value) {
				if (!empty($value) && $key != 'type') {
					$new_array[$key] = $value;
				}
			}
		}
		return $new_array;
	}

	/**
	 * 查询并解析，返回内容数组 返回  type,content
	 *
	 * store_id  	  店铺字段
	 * page_id        page_id
	 *
	 */
	public function getParseFields($store_id, $page_id, $seller_id = 0, $drp_level = 0, $drp_diy_store = 1) {

		if (empty($seller_id)) {
			$seller_id = $store_id;
		}
		if ($drp_level > 3) {
			$drp_level = 3;
		}

//        echo '123';
		$field_list = $this->get_field($store_id, $page_id,$seller_id);
//        echo "<pre>";
//        print_r($field_list);
		$fx_store_info = D('Store')->where("store_id='".$seller_id."'")->find();
		$supplier_store_info = D('Store')->where(array('store_id'=>$store_id))->find();
		
		if (empty($field_list))
			return array();

		$fieldHtmlArr = array();

		foreach ($field_list as $key => $value) {
			if (empty($value['content']) && !in_array($value['field_type'], array('line', 'search', 'store', 'attention_collect')))
				continue;
			$fieldHtml = array();
			$fieldHtml['type'] = $value['field_type'];
			$fieldContent = $value['content'];
			switch ($value['field_type']) {
				case 'title':
					$fieldHtml['html'] = '<!-- 标题 -->';
					$fieldHtml['html'] .= '<div' . (!empty($fieldContent['bgcolor']) ? ' class="custom-title-noline" style="background-color:' . $fieldContent['bgcolor'] . ';"' : '') . '>';
					$fieldHtml['html'] .= '<div class="custom-title ' . ($fieldContent['show_method'] == 0 ? 'text-left' : ($fieldContent['show_method'] == 1 ? 'text-center' : 'text-right')) . '">';
					$fieldHtml['html'] .= '<h2 class="title">' . htmlspecialchars_decode($fieldContent['title'], ENT_QUOTES) . '</h2>';
					$fieldHtml['html'] .= '<p class="sub_title">' . htmlspecialchars_decode($fieldContent['sub_title'], ENT_QUOTES) . '</p>';
					$fieldHtml['html'] .= '</div>';
					$fieldHtml['html'] .= '</div>';
					break;
				case 'rich_text':
					$fieldHtml['html'] = '<!-- 富文本内容区域 -->';
                    $fieldHtml['html'] .= '<div class="custom-richtext' . (!empty($fieldContent['screen']) ? ' custom-richtext-fullscreen' : '') . '" ' . (!empty($fieldContent['bgcolor']) ? 'style="background-color:' . $fieldContent['bgcolor'] . ';"' : '') . '>';
                    $fieldHtml['html'] .= htmlspecialchars_decode($fieldContent['content'], ENT_QUOTES);
                    $fieldHtml['html'] .= '</div>';
					break;
				case 'notice':
					if (!empty($fieldContent['content'])) {
						$fieldHtml['html'] = '<!-- 店铺公告 -->';
						$fieldHtml['html'] .= '<div class="custom-notice"><div class="custom-notice-inner"><div class="custom-notice-scroll"><span class="js-scroll-notice">公告：' . htmlspecialchars_decode($fieldContent['content'], ENT_QUOTES) . '</span></div></div></div>';
					}
					break;
				case 'line':
					$fieldHtml['html'] = '<!-- 辅助线 -->';
					$fieldHtml['html'] .= '<div class="custom-line-wrap"><hr class="custom-line"></div>';
					break;
				case 'white':
					$fieldHtml['html'] = '<!-- 辅助空白 -->';
					$fieldHtml['html'] .= '<div class="custom-white" style="height:' . $fieldContent['height'] . 'px;"></div>';
					break;
				case 'search':
					$fieldHtml['html'] = '<!-- 商品搜索 -->';
					$fieldHtml['html'] .= '<div class="custom-search"><form action="?c=Mall&a=search" method="get"><input type="hidden" name="c" value="Mall"/><input type="hidden" name="a" value="search"/><input type="search" class="custom-search-input" placeholder="商品搜索：请输入商品关键字" name="key" value=""/><input type="hidden" name="store_id" value="' . (empty($drp_diy_store) ? $seller_id : $store_id) . '"/><button type="submit" class="custom-search-button">搜索</button></form></div>';
					break;

				case 'attention_collect':
					$where['store_id'] = $_GET["id"]+0;
					
					
					$store_info = D('Store')->field('collect,attention_num')->where($where)->find();
					/*
					$Map['user_id'] = $_SESSION['wap_user']['uid'];
					$Map['type'] = 2;
					$Map['store_id'] = $_GET['id'];
					$self_collect_goods_num = D('User_collect')->where($Map)->select();
					$_Map['user_id'] = $_SESSION['wap_user']['uid'];
					$_Map['data_type'] = 2;
					$_Map['store_id'] = $_GET['id'];
					$self_attention_goods_num = D('User_attention')->where($_Map)->select
					*/

					$fieldHtml['html'] = '<!-- 收藏和关注店铺 -->';
					$fieldHtml['html'].='<style>.ft-links { padding: 15px; text-align: center }.ft-links>a { margin: 0 6px; color: #333 }@media only screen and (-webkit-min-device-pixel-ratio: 1.5), only screen and (min--moz-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (min-device-pixel-ratio: 1.5) {.ft-links ~ .ft-copyright {border-top-width:1px;}}.ft-copyright>a { padding-top: 45px; color: #ccc }.ft-copyright:first-child { margin-top: 0px }.footer { margin: 0; padding: 0; min-height: 1px; text-align: center; line-height: 16px; background-color: #f8f8f8 }</style>';
					$fieldHtml['html'] .= '<div class="ft-links custom-attention_collect"><a href="javascript:userCollect(' . (empty($drp_diy_store) ? $seller_id : $store_id) . ', 2)" class="js-shouchang">收藏店铺(<span>' . ($store_info['collect'] ? $store_info['collect'] : 0) . '</span>)</a><a href="home.php?id=' . (empty($drp_diy_store) ? $seller_id : $store_id) . '" class="js-guanzhu">浏览店铺</a></div>';
					break;

				case 'store':
					$fieldHtml['html'] = '<!-- 进入店铺 -->';
					$fieldHtml['html'] .= '<div class="custom-store"><a class="custom-store-link clearfix" href="' . C('now_store.url') . '"><div class="custom-store-img"></div><div class="custom-store-name">' . C('now_store.name') . '</div><span class="custom-store-enter">进入店铺</span></a></div>';
					break;
				case 'text_nav':
					$fieldHtml['html'] = '<!-- 文本导航 -->';
					$fieldHtml['html'] .= '<ul class="custom-nav clearfix">';
					foreach ($fieldContent as $v) {
						if (!empty($v['title'])) {
							if (!empty($v['url']) && empty($drp_diy_store) && stripos($v['url'], option('config.site_url')) !== false) {
								/*if (stripos($v['url'], 'home.php?id=') !== false) {
									$v['url'] = preg_replace('/home.php\?id=(\d)+/i', 'home.php?id=' . $seller_id, $v['url']);
								} else {
									$v['url'] .= '&store_id=' . $seller_id;
								}*/
                                $v['url'] = redirect_store($seller_id,$v['url']);
							}
							$fieldHtml['html'] .= '<li><a class="clearfix relative arrow-right" href="' . ($v['url'] ? $v['url'] : 'javascript:void(0);') . '" target="_blank"><span class="custom-nav-title">' . htmlspecialchars_decode($v['title'], ENT_QUOTES) . '</span></a></li>';
						}
					}
					$fieldHtml['html'] .= '</ul>';
					break;
				case 'image_nav':
					$fieldHtml['html'] = '<!-- 图片导航 -->';
					$fieldHtml['html'] .= '<ul class="custom-nav-4 clearfix">';
					foreach ($fieldContent as $v) {
						if (!empty($v['url']) && empty($drp_diy_store) && stripos($v['url'], option('config.site_url')) !== false) {
							/*if (stripos($v['url'], 'home.php?id=') !== false) {
								$v['url'] = preg_replace('/home.php\?id=(\d)+/i', 'home.php?id=' . $seller_id, $v['url']);
							} else {
								$v['url'] .= '&store_id=' . $seller_id;
							}*/
                            $v['url'] = redirect_store($seller_id,$v['url']);
						}
						$fieldHtml['html'] .= '<li><a href="' . ($v['url'] ? $v['url'] : 'javascript:void(0);') . '" target="_blank"><span class="nav-img-wap">' . ($v['image'] ? '<img class="js-lazy" src="' . ($v['image']) . '" style="display:inline;"/>' : '&nbsp;') . '</span><span class="title">' . htmlspecialchars_decode($v['title'], ENT_QUOTES) . '</span></a></li>';
					}
					$fieldHtml['html'] .= '</ul>';
					break;
				case 'component':
					if (!empty($fieldContent['id'])) {
						$fieldHtml['html'] = '<!-- 自定义模块 -->';
						$comFieldHtml = $this->getParseFields($store_id, 'custom_page', $fieldContent['id'], $seller_id, $drp_level, $drp_diy_store);
						foreach ($comFieldHtml as $value) {
							$fieldHtml['html'] .= $value['html'];
						}
					}
					break;
				case 'link':
					$fieldHtml['html'] = '<!-- 关联链接 -->';
					$fieldHtml['html'] .= '<ul class="custom-nav clearfix">';
					foreach ($fieldContent as $v) {
						if ($v['type'] == 'link') {
							if (!empty($v['url']) && empty($drp_diy_store) && stripos($v['url'], option('config.site_url')) !== false) {
								/*if (stripos($v['url'], 'home.php?id=') !== false) {
									$v['url'] = preg_replace('/home.php\?id=(\d)/i', 'home.php?id=' . $seller_id, $v['url']);
									$v['url'] = preg_replace('/home.php\?store_id=(\d)/i', 'home.php?id=' . $seller_id, $v['url']);
								} else {
									$v['url'] .= '&store_id=' . $seller_id;
								}*/
                                $v['url'] = redirect_store($seller_id,$v['url']);
							}
							$fieldHtml['html'] .= '<li><a class="custom-link-link clearfix relative arrow-right" href="' . ($v['url'] ? $v['url'] : 'javascript:void(0);') . '" target="_blank"><span class="custom-nav-title">' . ($v['name'] ? htmlspecialchars_decode($v['name'], ENT_QUOTES) : '自定义外链') . '</span></a></li>';
						} elseif ($v['widget'] == 'pagecat') {
							$page_list = M('Wei_page')->getCategoryPageNumberList($v['id'], $v['number']);
							foreach ($page_list as $jv) {
								if (empty($drp_diy_store) && stripos($v['url'], option('config.site_url')) !== false) {
									$url = option('config.wap_site_url') . '/page.php?id=' . $jv['page_id'] . '&store_id=' . $seller_id;
								} else {
									$url = option('config.wap_site_url') . '/page.php?id=' . $jv['page_id'];
								}
								$fieldHtml['html'] .= '<li><a class="clearfix relative arrow-right" href="' . $url . '" target="_blank"><span class="custom-nav-title">' . htmlspecialchars_decode($jv['page_name'], ENT_QUOTES) . '</span></a></li>';
							}
						} elseif ($v['widget'] == 'goodcat') {
							/*$product_list = M('Product')->getGroupGoodNumberList($v['id'], $v['number']);
							foreach ($product_list as $jv) {
								if (empty($drp_diy_store) && stripos($v['url'], option('config.site_url')) !== false) {
									$url = option('config.wap_site_url') . '/good.php?id=' . $jv['product_id'] . '&store_id=' . $seller_id;
								} else {
									$url = option('config.wap_site_url') . '/good.php?id=' . $jv['product_id'];
								}
								$fieldHtml['html'] .= '<li><a class="clearfix relative arrow-right" href="' . $url . '" target="_blank"><span class="custom-nav-title">' . htmlspecialchars_decode($jv['name'], ENT_QUOTES) . '</span></a></li>';
							}*/
							$url = option('config.wap_site_url') . '/goodcat.php?id=' . $v['id']. '&store_id=' . $seller_id;
							$fieldHtml['html'] .= '<li><a class="clearfix relative arrow-right" href="' . $url . '" target="_blank"><span class="custom-nav-title">' . htmlspecialchars_decode($v['name'], ENT_QUOTES) . '</span></a></li>';
						}
					}
					$fieldHtml['html'] .= '</ul>';
					break;
				case 'image_ad':
					$fieldHtml['html'] = '<!-- 图片广告 --><style type="text/css"> .custom-image-swiper .swiper-slide a {width: 100%!important;}</style>';
					if (!empty($fieldContent['nav_list'])) {
						if (empty($fieldContent['image_type'])) {
							$fieldHtml['html'] .= '<div class="custom-image-swiper" data-max-height="' . $fieldContent['max_height'] . '" data-max-width="' . $fieldContent['max_width'] . '"><div class="swiper-container" style="height:' . $fieldContent['max_height'] . 'px"><div class="swiper-wrapper">';
							foreach ($fieldContent['nav_list'] as $v) {
								if (!empty($v['url']) && empty($drp_diy_store) && stripos($v['url'], option('config.site_url')) !== false) {
									/*if (stripos($v['url'], 'home.php?id=') !== false) {
										$v['url'] = preg_replace('/home.php\?id=(\d)/i', 'home.php?id=' . $seller_id, $v['url']);
										$v['url'] = preg_replace('/home.php\?store_id=(\d)/i', 'home.php?id=' . $seller_id, $v['url']);
									} else {
										$v['url'] .= '&store_id=' . $seller_id;
									}*/
                                    $v['url'] = redirect_store($seller_id,$v['url']);
								}
								$fieldHtml['html'] .= '<div style="height:' . $fieldContent['max_height'] . 'px;" class="swiper-slide"><a href="' . ($v['url'] != '' ? $v['url'] : 'javascript:void(0);') . '" style="height:' . $fieldContent['max_height'] . 'px;">' . ($v['title'] != '' ? '<h3 class="title">' . htmlspecialchars_decode($v['title'], ENT_QUOTES) . '</h3>' : '') . '<img src="' . getAttachmentUrl($v['image']) . '"></a></div>';
							}
							$fieldHtml['html'] .= '</div></div>';
							if (count($fieldContent['nav_list']) > 1) {
								$fieldHtml['html'] .= '<div class="swiper-pagination">';
								$num = 0;
								foreach ($fieldContent['nav_list'] as $v) {
									$fieldHtml['html'] .= '<span class="swiper-pagination-switch' . ($num == 0 ? ' swiper-active-switch' : '') . '"></span>';
									$num++;
								}
								$fieldHtml['html'] .= '</div>';
							}
							$fieldHtml['html'] .= '</div>';
						} else {
							$fieldHtml['html'] .= '<ul class="custom-image clearfix">';
							foreach ($fieldContent['nav_list'] as $v) {
								if (!empty($v['url']) && empty($drp_diy_store) && stripos($v['url'], option('config.site_url')) !== false) {
									/*if (stripos($v['url'], 'home.php?id=') !== false) {
										$v['url'] = preg_replace('/home.php\?id=(\d)/i', 'home.php?id=' . $seller_id, $v['url']);
										$v['url'] = preg_replace('/home.php\?store_id=(\d)/i', 'home.php?id=' . $seller_id, $v['url']);
									} else {
										$v['url'] .= '&store_id=' . $seller_id;
									}*/
                                    $v['url'] = redirect_store($seller_id,$v['url']);
								}
								$fieldHtml['html'] .= '<li' . ($fieldContent['image_size'] == '1' ? ' class="custom-image-small"' : '') . '><a href="' . ($v['url'] != '' ? $v['url'] : 'javascript:void(0);') . '">' . ($v['title'] != '' ? '<h3 class="title">' . htmlspecialchars_decode($v['title']) . '</h3>' : '') . '<img src="' . ($v['image']) . '"/></a></li>';
							}
							$fieldHtml['html'] .= '</ul>';
						}
					}
					break;
				case 'goods':
					//echo $fieldContent['size'];
					$fieldHtml['html'] = '<!-- 商品 -->';
					if (!empty($fieldContent['goods'])) {
						if ($fieldContent['size'] == 0) {
							$fieldHtml['html'].= '<ul class="js-goods-list sc-goods-list pic clearfix size-0">';
							$i = 1;
							foreach ($fieldContent['goods'] as $key => $value) {
								$product = D('Shop_goods')->get_goods_by_id($value['id']);

								if (empty($product)) {
									continue;
								}
								$price = $product['price'];
								//商品类型
								$type = 0;

								$fieldHtml['html'].='<li class="goods-card goods-list big-pic ' . ($fieldContent['size_type'] == 2 ? 'normal' : 'card') . '">';
								$fieldHtml['html'].='<a href="' . $value['url'] . '&store_id=' . $seller_id . '" class="js-goods link clearfix" target="_blank" title="' . htmlspecialchars_decode($product['name']) . '">';
								$fieldHtml['html'].='<div class="photo-block" style="height:auto;">';
								$fieldHtml['html'].='<img class="goods-photo js-goods-lazy" src="' . ($product['pic_arr'][0]['url']) . '" style="display:inline;">';
								$fieldHtml['html'].='</div>';
								$fieldHtml['html'].='<div class="info clearfix ' . ($fieldContent['show_title'] == 1 ? 'info-title ' : 'info-no-title ') . ($fieldContent['price'] == 1 ? 'info-price ' : 'info-no-price ') . 'btn' . $fieldContent['buy_btn_type'] . ($fieldContent['show_title'] == 1 || $fieldContent['price'] == 1 ? '' : ' hide') . '">';
								$fieldHtml['html'].='<p class="goods-title">' . htmlspecialchars_decode($product['name'], ENT_QUOTES) . '</p>';
								$fieldHtml['html'].='<p class="goods-sub-title c-black hide"></p>';
								if ($fieldContent['price']) {
									$fieldHtml['html'].='<p class = "goods-price">';
									if($fx_store_info['is_point_mall'] == 1) {
										$fieldHtml['html'].='<em><span class="point_ico"></span>' . $price . '</em>';
									} else {
										$fieldHtml['html'].='<em>￥' . $price . '</em>';
									}
									$fieldHtml['html'].='</p>';
								}
								$fieldHtml['html'].='<p class="goods-price-taobao hide"></p>';
								$fieldHtml['html'].='</div>';
								$fieldHtml['html'].='</a>';
								if ($fieldContent['buy_btn'] && $fieldContent['size_type'] == 0) {
									$fieldHtml['html'].='<div class="js-goods-buy buy-response" data-id="' . $value['id'] . '"></div>';
									$fieldHtml['html'].='<a href="javascript:void(0)" class="js-goods-cart-btn" data-id="' . $value['id'] . '" data-store_id="' . $seller_id . '" data-type="' . $type . '"><div class="goods-buy btn' . $fieldContent['buy_btn_type'] . ' info-no-title"></div></a>';
								}
								
								$fieldHtml['html'].='</li>';
								$i++;
							}
							$fieldHtml['html'].='</ul>';
						} else if ($fieldContent['size'] == 1) {
							if ($fieldContent['size_type'] != 1) {
								$fieldHtml['html'].='<ul class="js-goods-list sc-goods-list pic clearfix size-1">';
								$i = 1;
								foreach ($fieldContent['goods'] as $key => $value) {
									$product = D('Shop_goods')->get_goods_by_id($value['id']);
									if (empty($product)) {
										continue;
									}

									//商品类型
									$type = 0;

									//商品限购
									$buyer_quota = !empty($product['buyer_quota']) ? $product['buyer_quota'] : 0;
									//价格
									if ($drp_level > 0 && !empty($product['unified_price_setting']) && $product['is_fx']) {
										$price = $product['drp_level_' . $drp_level . '_price'];
									} else {
										$price = $product['price'];
									}
									$fieldHtml['html'].='<li class="goods-card goods-list small-pic ' . ($fieldContent['size_type'] == 2 ? 'normal' : 'card') . '">';
									$fieldHtml['html'].='<a href="' . $value['url'] . '&store_id=' . $seller_id . '" class="js-goods link clearfix" target="_blank" title="' . htmlspecialchars_decode($product['name'], ENT_QUOTES) . '">';
									$fieldHtml['html'].='<div class="photo-block">';
									$fieldHtml['html'].='<img class="goods-photo js-goods-lazy" src="' . ($product['pic_arr'][0]['url']) . '" style="display:inline;"/>';
									$fieldHtml['html'].='</div>';
									$fieldHtml['html'].='<div class="info clearfix ' . ($fieldContent['show_title'] == 1 ? 'info-title ' : 'info-no-title ') . ($fieldContent['price'] == 1 ? 'info-price ' : 'info-no-price ') . 'btn' . $fieldContent['buy_btn_type'] . ($fieldContent['show_title'] == 1 || $fieldContent['price'] == 1 ? '' : ' hide') . '">';
									$fieldHtml['html'].='<p class="goods-title">' . htmlspecialchars_decode($product['name'], ENT_QUOTES) . '</p>';
									$fieldHtml['html'].='<p class="goods-sub-title c-black hide"></p>';
									if ($fieldContent['price']) {
										if($fx_store_info['is_point_mall'] == 1) {
											$fieldHtml['html'].='<p class="goods-price">';
											$fieldHtml['html'].='<em><span class="point_ico"></span>' . $price . '</em>';
											$fieldHtml['html'].='</p>';
										} else {
											$fieldHtml['html'].='<p class="goods-price">';
											$fieldHtml['html'].='<em>￥' . $price . '</em>';
											$fieldHtml['html'].='</p>';
										}
									}
									$fieldHtml['html'].='<p class="goods-price-taobao hide"></p>';
									$fieldHtml['html'].='</div>';
									$fieldHtml['html'].='</a>';
									if ($fieldContent['buy_btn'] && $fieldContent['size_type'] == 0) {
										$fieldHtml['html'].='<div class="js-goods-buy buy-response" data-id="' . $value['id'] . '"></div>';
										$fieldHtml['html'].='<a href="javascript:void(0)" class="js-goods-cart-btn" data-buyer-quota="' . $buyer_quota . '" data-id="' . $value['id'] . '" data-store_id="' . $seller_id . '" data-type="' . $type . '"><div class="goods-buy btn' . $fieldContent['buy_btn_type'] . ' info-no-title"></div></a>';
									}
									$fieldHtml['html'].='</li>';
									$i++;
								}
								$fieldHtml['html'].='</ul>';
							} else {
								$fieldHtml['html'].='<ul class="js-goods-list sc-goods-list pic clearfix size-1 waterfall">';
								$i = 1;
								foreach ($fieldContent['goods'] as $key => $value) {
									$product = D('Shop_goods')->get_goods_by_id($value['id']);
									if (empty($product)) {
										continue;
									}

									//商品类型
									$type = 0;

									//商品限购
									$buyer_quota = !empty($product['buyer_quota']) ? $product['buyer_quota'] : 0;
									//价格
									if ($drp_level > 0 && !empty($product['unified_price_setting']) && $product['is_fx']) {
										$price = $product['drp_level_' . $drp_level . '_price'];
									} else {
										$price = $product['price'];
									}
									$fieldHtml['html'].='<li class="goods-card goods-list small-pic waterfall" style="width:155px;">';
									$fieldHtml['html'].='<a href="' . $value['url'] . '&store_id=' . $seller_id . '" class="js-goods link clearfix" target="_blank" title="' . htmlspecialchars_decode($product['name'], ENT_QUOTES) . '">';
									$fieldHtml['html'].='<div class="photo-block">';
									$fieldHtml['html'].='<img class="goods-photo js-goods-lazy" src="' . ($product['pic_arr'][0]['url']) . '" style="display:inline;">';
									$fieldHtml['html'].='</div>';
									$fieldHtml['html'].='<div class="info clearfix ' . ($fieldContent['show_title'] == 1 ? 'info-title ' : 'info-no-title ') . ($fieldContent['price'] == 1 ? 'info-price ' : 'info-no-price ') . 'btn' . $fieldContent['buy_btn_type'] . ($fieldContent['show_title'] == 1 || $fieldContent['price'] == 1 ? '' : ' hide') . '">';
									$fieldHtml['html'].='<p class="goods-title">' . htmlspecialchars_decode($product['name'], ENT_QUOTES) . '</p>';
									$fieldHtml['html'].='<p class="goods-sub-title c-black hide"></p>';
									if ($fieldContent['price']) {
										$fieldHtml['html'].='<p class="goods-price">';
										if($fx_store_info['is_point_mall'] == 1) {
											$fieldHtml['html'].='<em><span class="point_ico"></span>' . $price . '</em>';
										} else {
											$fieldHtml['html'].='<em>￥' . $price . '</em>';
										}
										$fieldHtml['html'].='</p>';
									}
									
									$fieldHtml['html'].='<p class="goods-price-taobao hide"></p>';
									$fieldHtml['html'].='</div>';
									$fieldHtml['html'].='</a>';
									if ($fieldContent['buy_btn'] && ($fieldContent['size_type'] == 0 || $fieldContent['size_type'] == 1)) {
										$fieldHtml['html'].='<div class="js-goods-buy buy-response" data-id="' . $value['id'] . '"></div>';
										$fieldHtml['html'].='<a href="javascript:void(0)" class="js-goods-cart-btn" data-buyer-quota="' . $buyer_quota . '" data-id="' . $value['id'] . '" data-store_id="' . $seller_id . '" data-type="' . $type . '"><div class="goods-buy btn' . $fieldContent['buy_btn_type'] . ' info-no-title"></div></a>';
									}
									$fieldHtml['html'].='</li>';
									$i++;
								}
								$fieldHtml['html'].='</ul>';
							}
						} else if ($fieldContent['size'] == 2) {
							$fieldHtml['html'].='<ul class="js-goods-list sc-goods-list pic clearfix size-2">';
							$i = 1;
							foreach ($fieldContent['goods'] as $key => $value) {
								$product = D('Shop_goods')->get_goods_by_id($value['id']);
								if (empty($product)) {
									continue;
								}
								//商品类型
								$type = 0;
								
								//价格
								$price = $product['price'];
								
								$fieldHtml['html'].='<li class="goods-card goods-list ' . ($i % 3 == 1 ? 'big-pic' : 'small-pic') . ($fieldContent['size_type'] == 2 ? ' normal' : ' card') . '">';
								$fieldHtml['html'].='<a href="' . $value['url'] . '&store_id=' . $seller_id . '" class="js-goods link clearfix" target="_blank" title="' . htmlspecialchars_decode($product['name'], ENT_QUOTES) . '">';
								$fieldHtml['html'].='<div class="photo-block">';
								$fieldHtml['html'].='<img class="goods-photo js-goods-lazy" src="' . ($product['pic_arr'][0]['url']) . '" style="display:inline;">';
								$fieldHtml['html'].='</div>';
								$fieldHtml['html'].='<div class="info clearfix ' . ($fieldContent['show_title'] == 1 ? 'info-title ' : 'info-no-title ') . ($fieldContent['price'] == 1 ? 'info-price ' : 'info-no-price ') . '' . $fieldContent['buy_btn_type'] . (($fieldContent['show_title'] == 1 || $fieldContent['price'] == 1) ? '' : ' hide') . '">';
								$fieldHtml['html'].='<p class="goods-title">' . htmlspecialchars_decode($product['name'], ENT_QUOTES) . '</p>';
								$fieldHtml['html'].='<p class="goods-sub-title c-black hide"></p>';
								if ($fieldContent['price']) {
									$fieldHtml['html'].='<p class="goods-price">';
									$fieldHtml['html'].='<em>￥' . $price . '</em>';
									$fieldHtml['html'].='</p>';
								}
								
								$fieldHtml['html'].='<p class="goods-price-taobao hide"></p>';
								$fieldHtml['html'].='</div>';
								$fieldHtml['html'].='</a>';
								if ($fieldContent['buy_btn'] && $fieldContent['size_type'] == 0) {
									$fieldHtml['html'].='<div class="js-goods-buy buy-response" data-id="' . $value['id'] . '"></div>';
									$fieldHtml['html'].='<a href="javascript:void(0)" class="js-goods-cart-btn" data-buyer-quota="' . $buyer_quota . '" data-id="' . $value['id'] . '" data-store_id="' . $seller_id . '" data-type="' . $type . '"><div class="goods-buy btn' . $fieldContent['buy_btn_type'] . ' info-no-title"></div></a>';
								}
								$fieldHtml['html'].='</li>';
								$i++;
							}
							$fieldHtml['html'].='</ul>';
						} else if ($fieldContent['size'] == 3) {
							$fieldHtml['html'].='<ul class="js-goods-list sc-goods-list clearfix list size-3">';
							$i = 1;
							foreach ($fieldContent['goods'] as $key => $value) {
								$product = D('Shop_goods')->get_goods_by_id($value['id']);
								if (empty($product)) {
									continue;
								}

								//商品类型
								$type = 0;

								//商品限购
								$buyer_quota = !empty($product['buyer_quota']) ? $product['buyer_quota'] : 0;
								//价格
								if ($drp_level > 0 && !empty($product['unified_price_setting']) && $product['is_fx']) {
									$price = $product['drp_level_' . $drp_level . '_price'];
								} else {
									$price = $product['price'];
								}
								$fieldHtml['html'].='<li class="goods-card goods-list ' . ($i % 3 == 1 ? 'big-pic' : 'small-pic') . ($fieldContent['size_type'] == 2 ? ' normal' : ' card') . '">';
								$fieldHtml['html'].='<a href="' . $value['url'] . '&store_id=' . $seller_id . '" class="js-goods link clearfix" target="_blank" title="' . htmlspecialchars_decode($product['name'], ENT_QUOTES) . '">';
								$fieldHtml['html'].='<div class="photo-block">';
								$fieldHtml['html'].='<img class="goods-photo js-goods-lazy" src="' . ($product['pic_arr'][0]['url']) . '" style="display:block;">';
								$fieldHtml['html'].='</div>';
								$fieldHtml['html'].='<div class="info">';
								$fieldHtml['html'].='<p class="goods-title">' . htmlspecialchars_decode($product['name'], ENT_QUOTES) . '</p>';

								if ($fieldContent['price']) {
									$fieldHtml['html'].='<p class="goods-price">';
									if($fx_store_info['is_point_mall'] == 1) {
										$fieldHtml['html'].='<em><span class="point_ico"></span>' . $price . '</em>';
									} else {
										$fieldHtml['html'].='<em>￥' . $price . '</em>';
									}
									$fieldHtml['html'].='</p>';
								}
								
								$fieldHtml['html'].='</div>';
								$fieldHtml['html'].='</a>';
								if ($fieldContent['buy_btn']) {
									$fieldHtml['html'].='<div class="js-goods-buy buy-response" data-id="' . $value['id'] . '"></div>';
									$fieldHtml['html'].='<a href="javascript:void(0)" class="js-goods-cart-btn" data-buyer-quota="' . $buyer_quota . '" data-id="' . $value['id'] . '" data-store_id="' . $seller_id . '" data-type="' . $type . '"><div class="goods-buy btn' . $fieldContent['buy_btn_type'] . ' info-no-title"></div></a>';
								}
								$fieldHtml['html'].='</li>';
								$i++;
							}
							$fieldHtml['html'].='</ul>';
						}
					}
					break;

				//头部
				case 'tpl_shop':
					$rooturl = C('config.site_url');
					$fieldHtml['html'] = '<!-- logo抬头 -->';
					$bg1 = "style='";
					if (!empty($fieldContent['bgcolor'])) {
						$bg1 .= "background-color:" . $fieldContent['bgcolor'] . "; ";
					}
					if (!empty($fieldContent['shop_head_bg_img'])) {
						if (preg_match("/^http/", $fieldContent['shop_head_bg_img'])) {
							$bg1 .= " background-image:url(" . $fieldContent['shop_head_bg_img'] . ");";
						} else {
							$bg1 .= " background-image:url(" . $fieldContent['shop_head_bg_img'] . ");";
						}
					}
					$bg1 .= "'";

					$imgs = "";
					if (!empty($fieldContent['shop_head_logo_img'])) {
						if (preg_match("/^http/", $fieldContent['shop_head_logo_img'])) {
							$imgs = $fieldContent['shop_head_logo_img'];
						} else {
							$imgs = $fieldContent['shop_head_logo_img'];
						}
					}

					//$count = M('Product')->getTotalByStoreId($store_id);
					//全部已经上架的商品
					$count = M('Shop_goods')->where(array('store_id' => $store_id,'status'=>1))->count();
					
					$imgs = array_shift(C('now_store.all_pic'));
					$count = ($count > 9999) ? '9999+' : $count;

					$fieldHtml['html'] .= '<div class="custom-title text-left"  style="padding:0px;" ><div class="tpl-shop">';
					$fieldHtml['html'] .= '		<div class="tpl-shop-header" ' . $bg1 . '>';
					$fieldHtml['html'] .= '		<div class="tpl-shop-title">' . C('now_store.name') . '</div>';
					$fieldHtml['html'] .= '		<div class="tpl-shop-avatar"><img width="80" height="80" src="' . $imgs . '" alt=""></div></div>';
					$fieldHtml['html'] .= '	<div class="tpl-shop-content">';
					$fieldHtml['html'] .= '<ul class="clearfix"><li><a href="'.C('now_store.url').'&show_own=1"><span class="count">' . $count . '</span> <span class="text">全部商品</span></a></li><li><a href="' . U('My_card/merchant_card',array('mer_id'=>C('now_store.mer_id'))) .'"><span class="js-shouchang"><span class="count mycard"></span></span> <span class="text">会员卡</span></a></li><li><a href="' . U('My/shop_order_list',array('store_id'=>C('now_store.store_id'))) . '"><span class="count user"></span> <span class="text">我的订单</span></a></li></ul>';
					$fieldHtml['html'] .= '</div></div></div><div class="component-border"></div>';

					break;

				//头部
				case 'tpl_shop1':
					$fieldHtml['html'] = '<!-- logo抬头 -->';
					$bg1 = "style='";
					if (!empty($fieldContent['bgcolor'])) {
						$bg1 .= "background-color:" . $fieldContent['bgcolor'] . ";";
					}
					if (!empty($fieldContent['shop_head_bg_img'])) {
						$bg1 .= "background-image:url(" . $fieldContent['shop_head_bg_img'] . ");";
					} else {

					}
					$bg1 .= "'";
					$imgs = "";
					if (!empty($fieldContent['shop_head_logo_img'])) {
						$imgs = $fieldContent['shop_head_logo_img'];
					} else {

					}
					if (empty($drp_diy_store) && !empty($seller_id)) {
						$stores = D('Store')->where(array('store_id' => $seller_id))->field("attention_num as atten_num,logo,name")->find();
					} else {
						$stores = D('Store')->where(array('store_id' => $store_id))->field("attention_num as atten_num,logo,name")->find();
					}
					$atten = $stores['atten_num'];
					$imgs = C('now_store.all_pic.0');

					if ($drp_level) {
						$title = (!empty($stores['name']) ? $stores['name'] : $fieldContent['title']);
					} else {
						$title = !empty($fieldContent['title']) ? $fieldContent['title'] : $stores['name'];
					}
					if($store_id != $seller_id) {
						$title = $stores['name'];
					}

					$fieldHtml['html'] .= '<div style="padding:0px;" class="custom-title text-left">';
					$fieldHtml['html'] .= '<div class="tpl-shop1 tpl-wxd">';
					$fieldHtml['html'] .= '<div class="tpl-wxd-header" ' . $bg1 . '>';
					$fieldHtml['html'] .= '<div class="tpl-wxd-title">' . $title . '</div><div class="tpl-wxd-avatar">';
					$fieldHtml['html'] .= '<img src="' . $imgs . '" alt=""></div> </div></div>';
					$fieldHtml['html'] .= '</div>';

					break;

					//专题栏目导航
				case 'subject_menu':
					$fieldHtml['html'] = '<!-- 专题导航抬头 -->';

/*                 	$fieldHtml['html'] .= '<div class="top_sidebars">';
					$fieldHtml['html'] .= '<menu class=""> <span class=""><i></i></span>';
					$fieldHtml['html'] .= '		<div class="menu" style="overflow: hidden; display: none;">';
					$fieldHtml['html'] .= '			<div class="menu_titel"><span>切换频道</span><span>排序或删除</span></div>';
					$fieldHtml['html'] .= '			<ul><li>1精选 </li><li> 礼物 </li><li> 美食 </li><li> 家居 </li><li> 运动 </li><li> 精选 </li><li> 精选 </li><li> 礼物 </li><li> 美食 </li><li>家居 </li><li> 运动 </li><li> 精选 </li></ul>';
					$fieldHtml['html'] .= '		</div>';
					$fieldHtml['html'] .= '		<div class="mui-slider-indicator mui-segmented-control mui-segmented-control-inverted menu_list" id="sliderSegmentedControl">';
					$fieldHtml['html'] .= '			<ul class="clearfix" style="width: 630px;">';
					$fieldHtml['html'] .= '				<li class=" "><a href="#item1mobile" class="mui-control-item mui-active">2精选</a> </li>';
					$fieldHtml['html'] .= '				<li><a href="#item2mobile" class="mui-control-item"> 礼物</a> </li>';
					$fieldHtml['html'] .= '				<li><a href="#item3mobile" class="mui-control-item"> 美食</a> </li>';
					$fieldHtml['html'] .= '				<li><a href="#item4mobile" class="mui-control-item"> 家居</a> </li>';
					$fieldHtml['html'] .= '				<li><a href="#item5mobile" class="mui-control-item"> 运动</a> </li>';
					$fieldHtml['html'] .= '				<li><a href="#item6mobile" class="mui-control-item"> 精选</a> </li>';
					$fieldHtml['html'] .= '				<li><a href="#item7mobile" class="mui-control-item"> 精选</a> </li>';
					$fieldHtml['html'] .= '				<li><a href="#item8mobile" class="mui-control-item"> 礼物</a> </li>';
					$fieldHtml['html'] .= '				<li><a href="#item9mobile" class="mui-control-item"> 美食</a> </li>';
					$fieldHtml['html'] .= '				<li><a href="#item10mobile" class="mui-control-item">家居</a> </li>';
					$fieldHtml['html'] .= '				<li><a href="#item11mobile" class="mui-control-item"> 运动</a> </li>';
					$fieldHtml['html'] .= '				<li><a href="#item12mobile" class="mui-control-item"> 精选</a> </li>';
					$fieldHtml['html'] .= '			</ul>';
					$fieldHtml['html'] .= '		</div>';
					$fieldHtml['html'] .= '		<div class="mui-slider-progress-bar mui-col-xs-4" id="sliderProgressBar"></div>';
					$fieldHtml['html'] .= '	</menu>';
					$fieldHtml['html'] .= '	</div>'; */

					//专题读取数据库
					$where = array('status'=>1,'topid'=>array('>',0));
					$subtype = M('Subtype')->getLists($where,false,'px asc');

					foreach($subtype as $k => $v) {
						$subtype_info[$v['id']]=$v['typename'];
					}


					$fieldHtml['html'] .= '<div class="top_sidebars">';
					$fieldHtml['html'] .= '<menu class=""> <span class="span_iss_old"><i></i></span>';
					$fieldHtml['html'] .= '		<div class="menu" style="overflow: hidden; display: none;">';
					$fieldHtml['html'] .= '			<div class="menu_titel"><span>切换频道</span><span style="display:none">排序或删除</span><span class="iss_span" style="text-align:right"><i class="iss"></i></span></div>';
					$fieldHtml['html'] .= '			<ul><li>1精选 </li><li> 礼物 </li><li> 美食 </li><li> 家居 </li><li> 运动 </li><li> 精选 </li><li> 精选 </li><li> 礼物 </li><li> 美食 </li><li>家居 </li><li> 运动 </li><li> 精选 </li></ul>';
					$fieldHtml['html'] .= '		</div>';
					$fieldHtml['html'] .= '		<div class="mui-slider-indicator mui-segmented-control mui-segmented-control-inverted menu_list" id="sliderSegmentedControl">';
					$fieldHtml['html'] .= '			<ul class="clearfix" style1="width: 630px;">';
					$store_url = option('config.wap_site_url').'/home.php?id='.$seller_id;
					$fieldHtml['html'] .= '				<li class=" "><a href="'.$store_url.'" class="mui-control-item mui-active">精选</a> </li>';

					$array['subtype_list'] = !empty($fieldContent['subtype_list']) ? $fieldContent['subtype_list'] : array();
					foreach ($array['subtype_list'] as $key => $subtype2) {
						if($subtype_info[$subtype2['id']]) {
							$db_subtype_list2[$key] = $subtype;
							$db_subtype_list2[$key]['title'] = $subtype_info[$subtype2['id']];
						} else {
							continue;
						}
						$subtype_name = $subtype_info[$subtype2['id']];
						if($subtype_name) $subtype_name = msubstr($subtype_name,0,4,false,"utf-8");
						$fieldHtml['html'] .= '			<li><a hrefs="#item'.$key.'mobile" href="' . option('config.site_url') . '/wap/subtype.php?id='.$seller_id."&sid=".$subtype2[id].'" class="mui-control-item">'.$subtype_name.'</a> </li>';

					}
					//$fieldHtml['html'] .= '				<li class=" "><a href="#item1mobile" class="mui-control-item">2精选</a> </li>';


					$fieldHtml['html'] .= '			</ul>';
					$fieldHtml['html'] .= '		</div>';
					$fieldHtml['html'] .= '		<div class="mui-slider-progress-bar mui-col-xs-4" id="sliderProgressBar"></div>';
					$fieldHtml['html'] .= '	</menu>';
					$fieldHtml['html'] .= '	</div>';

					break;

				//29日可以使用的 ，无修改(beifen)
				case '~~~subject_display':
					$fieldHtml['html'] = '<!-- 专题展示抬头 -->';

					$fieldContent['px_style'];
					$update_hour = $fieldContent['hour'] ? ( $fieldContent['hour'].':00'):'0:00';
					$fieldContent['day'];
					$fieldContent['number'];




					$weekarray=array("日","一","二","三","四","五","六");
					//echo "星期".$weekarray[date("w")];

					if ($fieldContent['day'] === 3) {// 三天
						$time = strtotime('-2 day', $now);
						$beginTime = date('Y-m-d 00:00:00', $time);
						$endTime = date('Y-m-d 23:59:59', $now);
					} elseif ($fieldContent['day'] === 5) {// 5日
						$time = strtotime('-4 day', $now);
						$beginTime = date('Y-m-d 00:00:00', $time);
						$endTime = date('Y-m-d 23:59:59', $now);
					} elseif ($fieldContent['day'] === 7) {// 一周
						$time = strtotime('-6 day', $now);
						$beginTime = date('Y-m-d 00:00:00', $time);
						$endTime = date('Y-m-d 23:59:59', $now);
					} elseif ($fieldContent['day'] === 15) {// 半个月
						$time = strtotime('-14 day', $now);
						$beginTime = date('Y-m-d 00:00:00', $time);
						$endTime = date('Y-m-d 23:59:59', $now);
					}

					$endtime = strtotime($endtime);
					$subject_info = D('Subject')-> where("   store_id = '".$store_id."' and timestamp > '".$endtime."'")->order("timestamp desc")->select();

					foreach($subject_info as $k=>$v) {

						$date = date("Y_m_d",$v['timestamp']);
						$new[$date][] = $v;
					}
					$new = array_values($new);


					$fieldHtml['html'] .= '<article>';
					$fieldHtml['html'] .= '		<section>';
					$fieldHtml['html'] .= '			<ul class="show_list">';

					foreach ($new as $k=>$v) {

						$fieldHtml['html'] .= '			<li>';
						foreach($v as $k1=>$v1) {
							if($k1=='0')	$fieldHtml['html'] .= '		<div class="show_title clearfix"> <span>'.date("m月d日",$v1['timestamp']).'&nbsp;星期'.$weekarray[date("w",$v1['timestamp'])].'</span><i><em></em>下次更新'.$update_hour.'</i> </div>';
						}
						$fieldHtml['html'] .= '				<ul class="product_show">';

						foreach($v as $k1=>$v1) {
							if($v1['pic']) {
								$image = getAttachmentUrl($v1['pic']);
							}
							$fieldHtml['html'] .= '					<li style="background:url('.$image.') center no-repeat;"> <a href="product_info.html"><i class="active"><em ></em>5165</i>';
							$fieldHtml['html'] .= '						<p style="width:100%;text-align:left;line-height:18px;height:18px;font-size:14px;">'.$v1['name'].'</p>';
							$fieldHtml['html'] .= '						</a> ';
							$fieldHtml['html'] .= '					</li>';
						}
						$fieldHtml['html'] .= '				</ul>';
						$fieldHtml['html'] .= '			</li>';
					}

					$fieldHtml['html'] .= '</ul>';
					$fieldHtml['html'] .= '</section>';
					$fieldHtml['html'] .= '</article>';

					break;

					case 'subject_display':

						$fieldHtml['html'] = '<!-- 专题展示抬头 -->';
						$fieldHtml['html'] .= '<div  class="subject_display_div">';
						$update_hour = $fieldContent['hour'] ? ( $fieldContent['hour']):'0';
						$now_time = time();$now = time();
						$now_hour = date("H");

						if($now_hour > $update_hour) {
							if($fieldContent['hour']) {
								$next_update_time = ($now_hour - $update_hour)%$fieldContent['update_hour']+$now_hour;
							} else {
								$next_update_time = $now_hour + $fieldContent['update_hour'];
								$next_update_time = ($next_update_time >= 24) ? ($next_update_time - 24) : $next_update_time;
							}
						} elseif ($now_hour == $update_hour) {
							$next_update_time = $update_hour+$fieldContent['update_hour'];
						}else {
							$next_update_time = $update_hour;
						}

						$update_hour = $next_update_time? ( $next_update_time.':00'):'0:00';
						$weekarray=array("日","一","二","三","四","五","六");
						//$fieldContent['day'] = 1;
						if ($fieldContent['day'] == 3) {// 三天
							$time = strtotime('-2 day');
							$beginTime = date('Y-m-d 00:00:00', $time);
							$endTime = date('Y-m-d 23:59:59');
						} elseif ($fieldContent['day'] == 5) {// 5日
							$time = strtotime('-4 day');
							$beginTime = date('Y-m-d 00:00:00', $time);
							$endTime = date('Y-m-d 23:59:59', $now);
						} elseif ($fieldContent['day'] == 7) {// 一周
							$time = strtotime('-6 day');
							$beginTime = date('Y-m-d 00:00:00', $time);
							$endTime = date('Y-m-d 23:59:59', $now);
						} elseif ($fieldContent['day'] == 15) {// 半个月
							$time = strtotime('-14 day');
							$beginTime = date('Y-m-d 00:00:00', $time);
							$endTime = date('Y-m-d 23:59:59', $now);
						} elseif ($fieldContent['day'] == 1) {// 一天

							$beginTime = date('Y-m-d 00:00:00',time());
							$endTime = date('Y-m-d', $now);
							$time = $endTime.' '."23:59:59";
							$time = strtotime($time);
						}

						///echo $fieldContent['day'];
						$endtime = $time;
						if($fieldContent['day_type'] == '2') {
							//第一天数据
							$subject_before_1 = D('Subject')-> where("show_index = 1 and   store_id = '".$store_id."'")->order("timestamp desc")->limit(1)->find();

							$now_date = date("Y-m-d",$subject_before_1['timestamp']);
							$now_date_starttime = strtotime($now_date);
							$now_date_endtime = strtotime($now_date)+60*60*24;	//当天最晚时间

							$subject_before = D('Subject')-> where("show_index = 1 and   store_id = '".$store_id."' and timestamp < '".$now_date_endtime."' and timestamp >= '".$now_date_starttime."'")->order("timestamp desc")->limit($fieldContent['number'])->select();
							//第二天起始数据
							$subject_before_2 =  D('Subject')-> where("show_index = 1 and   store_id = '".$store_id."' and  timestamp <= '".$now_date_starttime."'")->order("timestamp desc")->limit(1)->find();

							for($i=0;$i<$fieldContent['day'];$i++) {

								if($subject_before_2['timestamp']) {
									if(!$subject_before_2['timestamp']) {
										//break;
									}
									//循环的第N 天数据
									$now_date = date("Y-m-d",$subject_before_2['timestamp']);
									$now_date_starttime = strtotime($now_date);
									$now_date_endtime = strtotime($now_date)+60*60*24;	//第N天最晚时间

									//第N 天数据
									$subject_before2 = D('Subject')-> where("show_index = 1 and   store_id = '".$store_id."' and timestamp <= '".$now_date_endtime."' and timestamp >= '".$now_date_starttime."'")->order("timestamp desc")->limit($fieldContent['number'])->select();

									//第N+1天 前 第一条数据
									$subject_before_2 =  D('Subject')-> where("show_index = 1 and   store_id = '".$store_id."' and  timestamp <= '".$now_date_starttime."'")->order("timestamp desc")->limit(1)->find();

									if(is_array($subject_before2)) {
										$subject_before = array_merge($subject_before,$subject_before2);
									}
								}
								//unset($subject_before_2);
							}

							//去重复数据
							if(is_array($subject_before)) {
								foreach($subject_before as $k=>$v) {
									$subject_info[$v['id']] = $v;
								}
							}
							if(is_array($subject_info)) {
								$subject_info = array_values($subject_info);
							} else {
								$subject_info['$subject_before'];
							}
							//dump($subject_info);

						} else {
							$subject_info = D('Subject')-> where("show_index = 1 and   store_id = '".$store_id."' and timestamp > '".$endtime."'")->order("timestamp desc")->select();
						}

						foreach($subject_info as $k=>$v) {
							$subjects_infos = $v;
							$date = date("Y_m_d",$v['timestamp']);
							if($v['pic']) {
								$subject_info[$k]['pic'] = getAttachmentUrl($v['pic']);
								$v['pic'] = getAttachmentUrl($v['pic']);
							}
							$subject_info[$k]['dates'] = date("m_d",$v['timestamp']);
							$url = option('config.wap_site_url') . '/subinfo.php?store_id='.$seller_id."&subject_id=".$v['id'];

							$subject_info[$k]['dates_chinese'] = date("m月d日",$v['timestamp']);
							$subject_info[$k]['dates_xingqi'] = "星期".$weekarray[date("w",$v['timestamp'])];
							$subject_info[$k]['dates_update_click'] = $update_hour;
							$subject_info[$k]['url'] =  $url;

							$aleady_dianzan_info = D('User_collect')->where(array('store_id'=>$seller_id,'type'=>'3','dataid'=>$v[id]))->find();
							if($aleady_dianzan_info) {
								$subject_info[$k]['dianzan_tip'] =  1;
								$v['dianzan_tip'] = 1;
							} else {
								$subject_info[$k]['dianzan_tip'] =  0;
								$v['dianzan_tip'] = 0;
							}

							//获取该店铺 该专题点赞总数
							$store_dz = D('Store_subject_data')->where(array('store_id'=>$seller_id,'subject_id'=> $v[id]))->field("subject_id,dz_count")->find();
							if($store_dz['dz_count']) {
								$subject_info[$k]['dz_count'] =  $store_dz['dz_count'];
								$v['dz_count'] = $store_dz['dz_count'];
							} else {
								$subject_info[$k]['dz_count'] =  0;
								$v['dz_count'] = 0;
							}
							$v['url'] = $url;
							$v['date'] = $subject_info[$k]['dates'];
							if(count($new_date[date("m-d",$v['timestamp'])]) >= $fieldContent['number']) {
								continue;
							}
							$new[$date]['subject'][] = $v;
							$new_date[date("m-d",$v['timestamp'])][] = $v;
							$subject_infos[] = $subject_info[$k];
						}
						//dump($subject_infos);
						if(is_array($new)) {
							$new = array_values($new);
							//存储节点

							$fieldHtml['html'] .= '<article>';
							$fieldHtml['html'] .= '		<section>';
							$fieldHtml['html'] .= '			<ul class="show_list">';
								$j=0;
								$s=0;
								foreach ($new as $k=>$v) {
									if($j>=3) break;

									foreach($v['subject'] as $k1=>$v1) {
										if($k1=='0') {

											$fieldHtml['html'] .= '	<li class="'.$v1['date'].'">';
											if($s == '0') {$next_objc = '<i><em></em>'.'下次更新'.$update_hour.'</i>';} else{$next_objc="";}
											$fieldHtml['html'] .= '		<div class="show_title clearfix"> <span>'.date("m月d日",$v1['timestamp']).'&nbsp;星期'.$weekarray[date("w",$v1['timestamp'])].'</span>'.$next_objc.' </div>';
										}
										$s++;
									}
									$fieldHtml['html'] .= '				<ul class="product_show">';
								//	$new[$k]['dates'] = date("m_d",$v1['timestamp']);
									foreach($v['subject'] as $k1=>$v1) {

										$url = option('config.wap_site_url') . '/subinfo.php?store_id='.$seller_id."&subject_id=".$v1['id'];

										$fieldHtml['html'] .= '					<li class="subject_'.$v1[id].'" style2="background:url('.$v1['pic'].') center no-repeat;"> <a style="display:block;width:100%;height:100%;text-align:center" hrefs="javascript:void(0)" href="'.$url.'">';

										$fieldHtml['html'] .= '						<img src="'.$v1[pic].'">';

										if($v1['dianzan_tip']) {
											$fieldHtml['html'] .= '<i data-subjectid="'.$v1[id].'"  class="active dianzan_selected"><em ></em><span class="dz_count">'.$v1[dz_count].'</span></i>';
										} else {
											$fieldHtml['html'] .= '<i data-subjectid="'.$v1[id].'"  class="active"><em ></em><span class="dz_count">'.$v1[dz_count].'</span></i>';
										}

										$fieldHtml['html'] .= '						<p style="width:100%;text-align:left;line-height:18px;height:18px;font-size:14px;">'.$v1['name'].'</p>';
										$fieldHtml['html'] .= '						</a> ';
										$fieldHtml['html'] .= '					</li>';
										$j++;
									}
									$fieldHtml['html'] .= '				</ul>';
									foreach($v['subject'] as $k1=>$v1) {
										if($k1=='0') {
											$fieldHtml['html'] .= '			</li>';
										}
									}
								}
							$fieldHtml['html'] .= '</ul>';
							$fieldHtml['html'] .= '</section>';
							$fieldHtml['html'] .= '</article>';
							$fieldHtml['html'] .= '<div class="subject_display_datas" style="height:5px;clear:both;display:none;" data-infos=\''.json_encode($subject_infos).'\'></div>';

						}
						$fieldHtml['html'] .= '</div><div style="clear:both"></div>';

						break;

				case 'coupons':
					if (!$_SESSION['sync_user']) { //非对接用户可看：优惠券
						$fieldHtml['html'] = '<!-- 优惠券 --><div style="clear:both"></div><style type="text/css"> .custom-coupon{padding:10px;text-align:center;font-size:0}.custom-coupon li{display:inline-block;margin-left:6px;width:94px;height:67px;border:1px solid #ff93b2;border-radius:4px;background:#ffeaec;float:left;}.custom-coupon li a{color:#fa5262}.custom-coupon li:nth-child(1){margin-left:0}.custom-coupon li:nth-child(4){margin-left:0}.custom-coupon li:nth-child(7){margin-left:0}.custom-coupon li:nth-child(2){background:#f3ffef;border-color:#98e27f}.custom-coupon li:nth-child(2) a{color:#7acf8d}.custom-coupon li:nth-child(3){background:#ffeae3;border-color:#ffa492}.custom-coupon li:nth-child(3) a{color:#ff9664}.custom-coupon .custom-coupon-price{height:24px;line-height:24px;padding-top:12px;font-size:24px;overflow:hidden}.custom-coupon .custom-coupon-price span{font-size:16px}.custom-coupon .custom-coupon-desc{height:20px;line-height:20px;font-size:12px;padding-top:4px;overflow:hidden}</style>';
						$fieldHtml['html'] .= '<div class="app-preview"><ul class="custom-coupon clearfix">';
						foreach ($fieldContent as $v) {
							foreach ($v as $val) {
								$coupons = D('Card_new_coupon')->get_coupon($val['id']);
								if ($coupons) {
									$fieldHtml['html'] .= '<li style="margin-top:5px; width:31%;">  <a href="' . U('My_card/merchant_coupon_list',array('mer_id'=>C('now_store.mer_id'))) . '"><div class="custom-coupon-price"><span>￥</span>' . $coupons['discount'] . '</div><div class="custom-coupon-desc">' . $coupons['name'] . '</div>  </a> </li>';
								}
							}
						}
						$fieldHtml['html'] .= '</ul></div><div style="clear:both"></div>';
					}
					break;

				case 'activity_module':
				case 'new_activity_module':
					$title = $fieldContent['name'] ? $fieldContent['name'] : "今日活动";
					$fieldHtml['html'] = '<!--活动--><div class="activity_module-nav"><div class="custom-nav"><a href="'.U('Wxapp/index',array('mer_id'=>C('now_store.mer_id'))).'" class="arrow-right"><span class="custom-nav-title">'.$title.'</span></a></div></div><div class="scrollBox" data-display_mode="'.intval($fieldContent['display']).'"><div id="scrollThis" class="scrollThis_' . rand(100000, 999999) . '"><div class="scroller"><ul>';
					foreach ($fieldContent['activity_arr'] as $v) {
						if (!empty($v['id'])) {
							$now_wxapp = M('Wxapp_list')->where(array('pigcms_id'=>$v['id']))->find();

							$images = $now_wxapp['image'];

							$fieldHtml['html'] .= '<li><a href="'.C('config.site_url').'/wap.php?c=Wxapp&a=location_href&id='.$now_wxapp['pigcms_id'].'"><img src="'.$images.'"/><h3>'.msubstr($now_wxapp['title'],0,8).'</h3></a></li>';
						}
					}

					$fieldHtml['html'] .= '</ul></div></div></div>';
					break;

				case 'goods_group1':
					$fieldHtml['html'] = '<!-- 商品分组1 --><div style="clear:both"></div>';
					$fieldHtml['html'] .='<div class="app-field clearfix editing"><div class="control-group"><ul class="goods_group1 clearfix"><div class="custom-tag-list clearfix"><div class="custom-tag-list-menu-block js-collection-region" style="min-height: 323px;"><ul class="custom-tag-list-side-menu"><li>';
					$fieldHtml['html'].='<a href="javascript:;" class="current" style="display:none">全部</a>';
					$time = time().uniqid();
					$product_group_list = array();
					foreach ($fieldContent['goods_group1'] as $k => $v) {
						if (!isset($product_group_list[$v['id']])) {
							$product_group = D('Product_group')->where(array('group_id' => $v['id']))->find();
							if (empty($product_group)) {
								continue;
							}
							$product_group_list[$v['id']] = $product_group;
						}
						
						//$fieldHtml['html'] .='<a href="javascript:;" class="current"';
						 $fieldHtml['html'] .='<a href="#'.$time.'_'.$v['id'].'" class="current"';
						if ($k == 0) {
							$fieldHtml['html'].='style="background:#fff" ';
						}
						$fieldHtml['html'].='>' . $product_group_list[$v['id']]['group_name'] . '</a>';
					}


					$fieldHtml['html'] .= '</li></ul></div>';

//                    //全部start
					$fieldHtml['html'].='<div class="custom-tag-list-goods" style="display: block;"><ul class="custom-tag-list-goods-list">';
					foreach ($fieldContent['goods_group1'] as $k => $v) {
						if (!isset($product_group_list[$v['id']])) {
							continue;
						}
						$fieldHtml['html'] .= '<p class="custom-tag-list-title" id="'.$time.'_'.$v['id'].'">' . $product_group_list[$v['id']]['group_name'] . '</p>';

						$limit = 10;
						if ($v['show_num']) {
							$limit = $v['show_num'];
						}
						
						$product_list = M('Product_to_group')->getProducts($v['id']);
						$product_arr = array();
						foreach ($product_list as $v) {
							array_push($product_arr, $v['product_id']);
						}
						$where = array();
						$where['store_id'] = $store_id;
						$where['product_id'] = array('in', $product_arr);
						
						$product_list = M('Product')->getSelling($where, '', '', 0, $limit);                     
						if ($product_list) {
							foreach ($product_list as $val) {

								//商品类型
								$type = 0;
								if (!empty($val['wholesale_product_id'])) {
									$type = 1;
								}

								if ($drp_level > 0 && !empty($val['unified_price_setting']) && $val['is_fx']) {
									$price = $val['drp_level_' . $drp_level . '_price'];
								} else {
									$price = $val['price'];
								}
								
								if($fx_store_info['is_point_mall'] == 1) {
									$price ='<span class="point_ico"></span>' . $price;
								} else {
									$price ='￥' . $price ;
								}
								
								if ($val) {
									$fieldHtml['html'] .= '<li class = "custom-tag-list-single-goods clearfix"><a href = "' . option('config.site_url') . '/wap/good.php?id=' . $val["product_id"] . '&store_id=' . $seller_id . '"><div class = "custom-tag-list-goods-img"><img src = "' . $val['image'] . '" style = "display: inline;"></div><div class = "custom-tag-list-goods-detail"><p class = "custom-tag-list-goods-title">' . $val['name'] . ' </p><span class = "custom-tag-list-goods-price">' . $price . '</span></a><a class = "js-goods-cart-btn custom-tag-list-goods-buy" href="javascript:void(0);" data-buyer-quota="' . $val['buyer_quota'] . '" data-id="' . $val["product_id"] . '" data-store_id="' . $val["store_id"] . '" data-type="' . $type . '"><span></span></a></div></li>';
								}
							}
						} else {
							$fieldHtml['html'].='<li class="custom-tag-list-single-goods clearfix"><div class="custom-tag-list-goods-img"><img src="' . option('config.site_url') . '/upload/images/kd2.jpg" style="display: inline;"></div><div class="custom-tag-list-goods-detail"><p class="custom-tag-list-goods-title">此处显示商品名称</p><span class="custom-tag-list-goods-price">￥100.00</span><a class="custom-tag-list-goods-buy" href="javascript:void(0)"><span></span></a></div></li><li class="custom-tag-list-single-goods clearfix"><div class="custom-tag-list-goods-img"><img src="' . option('config.site_url') . '/upload/images/kd1.jpg" style="display: inline;"></div><div class="custom-tag-list-goods-detail"><p class="custom-tag-list-goods-title">此处显示商品名称</p><span class="custom-tag-list-goods-price">￥100.00</span><a class="custom-tag-list-goods-buy" href="javascript:void(0)"><span></span></a></div></li><li class="custom-tag-list-single-goods clearfix"><div class="custom-tag-list-goods-img"><img src="' . option('config.site_url') . '/upload/images/kd3.jpg" style="display: inline;"></div><div class="custom-tag-list-goods-detail"><p class="custom-tag-list-goods-title">此处显示商品名称</p><span class="custom-tag-list-goods-price">￥100.00</span><a class="custom-tag-list-goods-buy" href="javascript:void(0)"><span></span></a></div></li><li class="custom-tag-list-single-goods clearfix"><div class="custom-tag-list-goods-img"><img src="' . option('config.site_url') . '/upload/images/kd4.jpg" style="display: inline;"></div><div class="custom-tag-list-goods-detail"><p class="custom-tag-list-goods-title">此处显示商品名称</p><span class="custom-tag-list-goods-price">￥100.00</span><a class="custom-tag-list-goods-buy" href="javascript:void(0)"><span></span></a></div></li>';
						}
					}
					$fieldHtml['html'] .= '</ul></div></div>';
//                    //全部end
/*
					foreach ($fieldContent['goods_group1'] as $k => $v) {
						$fieldHtml['html'] .= '<div class = "custom-tag-list-goods" style = "display:none"';
						if ($k != 0) {
							//$fieldHtml['html'] .= ' style = "display:none"';
						}
						$limit = 10;
						if ($v['show_num']) {
							$limit = $v['show_num'];
						}
						$fieldHtml['html'] .= '><ul class = "custom-tag-list-goods-list"><p class="custom-tag-list-title" id="anchor-1439862749815c193-10924959">' . $v["title"] . '</p>';


						$product_list = M('Product_to_group')->getProducts($v['id']);
						$product_arr = array();
						foreach ($product_list as $v) {
							array_push($product_arr, $v['product_id']);
						}
						
						$product_list = M('Product')->getSelling(array('product_id' => array('in', $product_arr)), '', '', 0, $limit);
						if ($product_list) {
							foreach ($product_list as $key => $val) {
								$fieldHtml['html'] .= '<li class = "custom-tag-list-single-goods clearfix"><a href="' . option('config.site_url') . '/wap/good.php?id=' . $val["product_id"] . '&store_id=' . $seller_id . '"><div class = "custom-tag-list-goods-img"><img src = "' . $val['image'] . '" style = "display: inline;"></div><div class = "custom-tag-list-goods-detail"><p class = "custom-tag-list-goods-title">' . $val['name'] . '</p><span class = "custom-tag-list-goods-price">￥' . $val['price'] . '</span></a><a class = "custom-tag-list-goods-buy" href = "/wap/good.php?id=' . $val["product_id"] . '&store_id=' . $seller_id . '" product-id="' . $val["product_id"] . '" store-id="' . $val["store_id"] . '"><span></span></a></div></li>';
							}
						} else {
							$fieldHtml['html'].='<ul class="custom-tag-list-goods-list"><li class="custom-tag-list-single-goods clearfix"><div class="custom-tag-list-goods-img"><img src="/upload/images/kd2.jpg" style="display: inline;"></div><div class="custom-tag-list-goods-detail"><p class="custom-tag-list-goods-title">此处显示商品名称</p><span class="custom-tag-list-goods-price">￥100.00</span><a class="custom-tag-list-goods-buy" href="javascript:void(0)"><span></span></a></div></li><li class="custom-tag-list-single-goods clearfix"><div class="custom-tag-list-goods-img"><img src="/upload/images/kd1.jpg" style="display: inline;"></div><div class="custom-tag-list-goods-detail"><p class="custom-tag-list-goods-title">此处显示商品名称</p><span class="custom-tag-list-goods-price">￥100.00</span><a class="custom-tag-list-goods-buy" href="javascript:void(0)"><span></span></a></div></li><li class="custom-tag-list-single-goods clearfix"><div class="custom-tag-list-goods-img"><img src="/upload/images/kd3.jpg" style="display: inline;"></div><div class="custom-tag-list-goods-detail"><p class="custom-tag-list-goods-title">此处显示商品名称</p><span class="custom-tag-list-goods-price">￥100.00</span><a class="custom-tag-list-goods-buy" href="javascript:void(0)"><span></span></a></div></li><li class="custom-tag-list-single-goods clearfix"><div class="custom-tag-list-goods-img"><img src="/upload/images/kd4.jpg" style="display: inline;"></div><div class="custom-tag-list-goods-detail"><p class="custom-tag-list-goods-title">此处显示商品名称</p><span class="custom-tag-list-goods-price">￥100.00</span><a class="custom-tag-list-goods-buy" href="javascript:void(0)"><span></span></a></div></li></ul>';
						}
						$fieldHtml['html'] .= '</ul></div>';
					}
					*/
					$fieldHtml['html'].='<div style="clear:both"></div>';
					break;

				case 'goods_group2':
					$fieldHtml['html'] = '<!-- 商品分组2 --><style> a.active {color: #ed5050;border-bottom: 1px solid #ed5050;}</style><div style="clear:both"></div>';
					if (!empty($fieldContent['goods'])) {
						$fieldHtml['html'].='<div style="clear:both"></div><div class="js-tabber-tags tabber tabber-bottom red clearfix tabber-n4 ">';
						$product_group_list = array();
						$ii = 0;
						foreach ($fieldContent['goods'] as $k => $v) {
							if (!isset($product_group_list[$v['id']])) {
								$product_group = D('Product_group')->where(array('group_id' => $v['id']))->find();
								if (empty($product_group)) {
									continue;
								}
								$product_group_list[$v['id']] = $product_group;
							}
							if ($ii == 0) {
								$fieldHtml['html'].='<a data-type="tag-1"href="javascript:;"class="active">' . $product_group_list[$v['id']]['group_name'] . '</a>';
							} else {
								$fieldHtml['html'].='<a data-type="tag-1"href="javascript:;">' . $product_group_list[$v['id']]['group_name'] . '</a>';
							}
							$ii++;
						}
						
						$fieldHtml['html'].='</div><div class="js-product_group_2">';
						if ($fieldContent['size'] == 0) {
							$i = 1;
							$ii = 0;
							foreach ($fieldContent['goods'] as $key => $value) {
								if (!isset($product_group_list[$value['id']])) {
									continue;
								}
								
								if ($ii == 0) {
									$fieldHtml['html'].='<ul class="js-goods-list sc-goods-list pic clearfix size-0">';
								} else {
									$fieldHtml['html'].='<ul class="js-goods-list sc-goods-list pic clearfix size-0" style="display:none">';
								}
								$ii++;
								$product_list = M('Product_to_group')->getProducts($value['id']);
								$product_arr = array();
								foreach ($product_list as $v) {
									array_push($product_arr, $v['product_id']);
								}

								$product_list = M('Product')->getSelling(array('product_id' => array('in', $product_arr), 'store_id' => $store_id), '', '', 0, 1000);

								//$product_list = M('Product')->getSelling(array('group_id' => $value['id']), '', '', 0, 10);
								if (empty($product_list)) {
									$fieldHtml['html'].='<center>暂时没有商品</cemter>';
								} else {
									foreach ($product_list as $k => $v) {

										//商品类型
										$type = 0;
										if (!empty($v['wholesale_product_id'])) {
											$type = 1;
										}

										if ($drp_level > 0 && !empty($v['unified_price_setting']) && $v['is_fx']) {
											$price = $v['drp_level_' . $drp_level . '_price'];
										} else {
											$price = $v['price'];
										}

										//商品限购
										$buyer_quota = !empty($v['buyer_quota']) ? $v['buyer_quota'] : 0;

										$url = 'good.php?id=' . $v["product_id"] . '&store_id=' . $seller_id;
										$fieldHtml['html'].='<li class="goods-card goods-list big-pic ' . ($fieldContent['size_type'] == 2 ? 'normal' : 'card') . '">';
										$fieldHtml['html'].='<a href="' . $url . '" class="js-goods link clearfix" target="_blank" title="' . htmlspecialchars_decode($v['name'], ENT_QUOTES) . '">';
										$fieldHtml['html'].='<div class="photo-block" style="height:auto;">';
										$fieldHtml['html'].='<img class="goods-photo js-goods-lazy" src="' . getAttachmentUrl($v['image']) . '" style="display:inline;">';
										$fieldHtml['html'].='</div>';
										$fieldHtml['html'].='<div class="info clearfix ' . ($fieldContent['show_title'] == 1 ? 'info-title ' : 'info-no-title ') . ($fieldContent['price'] == 1 ? 'info-price ' : 'info-no-price ') . 'btn' . $fieldContent['buy_btn_type'] . ($fieldContent['show_title'] == 1 || $fieldContent['price'] == 1 ? '' : ' hide') . '">';
										$fieldHtml['html'].='<p class="goods-title">' . htmlspecialchars_decode($v['name'], ENT_QUOTES) . '</p>';
										$fieldHtml['html'].='<p class="goods-sub-title c-black hide"></p>';
										if ($fieldContent['price']) {
											$fieldHtml['html'].='<p class="goods-price">';
											if($fx_store_info['is_point_mall'] == 1) {
												$fieldHtml['html'].='<em><span class="point_ico"></span>' . $price . '</em>';
											} else {
												 $fieldHtml['html'].='<em>￥' . $price . '</em>';
											}
											$fieldHtml['html'].='</p>';
										}
										$fieldHtml['html'].='<p class="goods-price-taobao hide"></p>';
										$fieldHtml['html'].='</div>';
										$fieldHtml['html'].='</a>';
										if ($fieldContent['buy_btn'] && $fieldContent['size_type'] == 0) {
											$fieldHtml['html'].='<div class="js-goods-buy buy-response" data-id="' . $value['id'] . '"></div>';
											$fieldHtml['html'].='<a href="javascript:void(0)" class="js-goods-cart-btn" data-buyer-quota="' . $buyer_quota . '" data-id="' . $v["product_id"] . '" data-store_id="' . $seller_id . '" data-type="' . $type . '"><div class="goods-buy btn' . $fieldContent['buy_btn_type'] . ' info-no-title"></div></a>';
										}
										$fieldHtml['html'].='</li>';
										$i++;
									}
								}
								$fieldHtml['html'].='</ul>';
							}
						} else if ($fieldContent['size'] == 1) {
							if ($fieldContent['size_type'] != 1) {
								$i = 1;
								$ii = 0;
								foreach ($fieldContent['goods'] as $key => $value) {
									if (!isset($product_group_list[$value['id']])) {
										continue;
									}
									if ($ii == 0) {
										$fieldHtml['html'].='<ul class="js-goods-list sc-goods-list pic clearfix size-1">';
									} else {
										$fieldHtml['html'].='<ul class="js-goods-list sc-goods-list pic clearfix size-1" style="display:none">';
									}
									$ii++;
									$product_list = M('Product_to_group')->getProducts($value['id']);
									$product_arr = array();
									foreach ($product_list as $v) {
										array_push($product_arr, $v['product_id']);
									}

									$product_list = M('Product')->getSelling(array('product_id' => array('in', $product_arr), 'store_id' => $store_id), '', '', 0, 1000);

									if (empty($product_list)) {
										$fieldHtml['html'].='<center>暂时没有商品</cemter>';
									} else {
										foreach ($product_list as $k => $v) {

											//商品类型
											$type = 0;
											if (!empty($v['wholesale_product_id'])) {
												$type = 1;
											}

											if ($drp_level > 0 && !empty($v['unified_price_setting']) && $v['is_fx']) {
												$price = $v['drp_level_' . $drp_level . '_price'];
											} else {
												$price = $v['price'];
											}

											//商品限购
											$buyer_quota = !empty($v['buyer_quota']) ? $v['buyer_quota'] : 0;
											
											$url = 'good.php?id=' . $v["product_id"] . '&store_id=' . $seller_id;
											$fieldHtml['html'].='<li class="goods-card goods-list small-pic ' . ($fieldContent['size_type'] == 2 ? 'normal' : 'card') . '">';
											$fieldHtml['html'].='<a href="' . $url . '" class="js-goods link clearfix" target="_blank" title="' . htmlspecialchars_decode($v['name'], ENT_QUOTES) . '">';
											$fieldHtml['html'].='<div class="photo-block">';
											$fieldHtml['html'].='<img class="goods-photo js-goods-lazy" src="' . getAttachmentUrl($v['image']) . '" style="display:inline;"/>';
											$fieldHtml['html'].='</div>';
											$fieldHtml['html'].='<div class="info clearfix ' . ($fieldContent['show_title'] == 1 ? 'info-title ' : 'info-no-title ') . ($fieldContent['price'] == 1 ? 'info-price ' : 'info-no-price ') . 'btn' . $fieldContent['buy_btn_type'] . ($fieldContent['show_title'] == 1 || $fieldContent['price'] == 1 ? '' : ' hide') . '">';
											$fieldHtml['html'].='<p class="goods-title">' . htmlspecialchars_decode($v['name'], ENT_QUOTES) . '</p>';
											$fieldHtml['html'].='<p class="goods-sub-title c-black hide"></p>';

											if ($fieldContent['price']) {
												$fieldHtml['html'].='<p class="goods-price">';
												if($fx_store_info['is_point_mall'] == 1) {
													$fieldHtml['html'].='<em><span class="point_ico"></span>' . $price . '</em>';
												} else {
													$fieldHtml['html'].='<em>￥' . $price . '</em>';
												}
												$fieldHtml['html'].='</p>';
											}

											$fieldHtml['html'].='<p class="goods-price-taobao hide"></p>';
											$fieldHtml['html'].='</div>';
											$fieldHtml['html'].='</a>';
											if ($fieldContent['buy_btn'] && $fieldContent['size_type'] == 0) {
												$fieldHtml['html'].='<div class="js-goods-buy buy-response" data-id="' . $v['id'] . '"></div>';
												$fieldHtml['html'].='<a href="javascript:void(0)" class="js-goods-cart-btn" data-buyer-quota="' . $buyer_quota . '" data-id="' . $v["product_id"] . '" data-store_id="' . $seller_id . '" data-typ="' . $type . '"><div class="goods-buy btn' . $fieldContent['buy_btn_type'] . ' info-no-title"></div></a>';
											}
											$fieldHtml['html'].='</li>';
											$i++;
										}
									}$fieldHtml['html'].='</ul>';
								}
							} else {
								$i = 1;
								$ii = 0;
								foreach ($fieldContent['goods'] as $key => $value) {
									if (!isset($product_group_list[$value['id']])) {
										continue;
									}
									
									
									$product_list = M('Product_to_group')->getProducts($value['id']);
									$product_arr = array();
									foreach ($product_list as $v) {
										array_push($product_arr, $v['product_id']);
									}

									$product_list = M('Product')->getSelling(array('product_id' => array('in', $product_arr), 'store_id' => $store_id), '', '', 0, 1000);

									$fieldHtml['html'].='<ul class="js-goods-list sc-goods-list pic clearfix size-1 waterfall">';
									foreach ($product_list as $key => $product) {

										//商品类型
										$type = 0;
										if (!empty($product['wholesale_product_id'])) {
											$type = 1;
										}

										if ($drp_level > 0 && !empty($product['unified_price_setting']) && $product['is_fx']) {
											$price = $product['drp_level_' . $drp_level . '_price'];
										} else {
											$price = $product['price'];
										}

										//商品限购
										$buyer_quota = !empty($product['buyer_quota']) ? $product['buyer_quota'] : 0;
					
										$url = 'good.php?id=' . $product['product_id'] . '&store_id=' . $seller_id;
										$fieldHtml['html'].='<li class="goods-card goods-list small-pic waterfall" style="width:155px;">';
										$fieldHtml['html'].='<a href="' . $url . '" class="js-goods link clearfix" target="_blank" title="' . htmlspecialchars_decode($product['name'], ENT_QUOTES) . '">';
										$fieldHtml['html'].='<div class="photo-block">';
										$fieldHtml['html'].='<img class="goods-photo js-goods-lazy" src="' . getAttachmentUrl($product['image']) . '" style="display:inline;">';
										$fieldHtml['html'].='</div>';
										$fieldHtml['html'].='<div class="info clearfix ' . ($fieldContent['show_title'] == 1 ? 'info-title ' : 'info-no-title ') . ($fieldContent['price'] == 1 ? 'info-price ' : 'info-no-price ') . 'btn' . $fieldContent['buy_btn_type'] . ($fieldContent['show_title'] == 1 || $fieldContent['price'] == 1 ? '' : ' hide') . '">';
										$fieldHtml['html'].='<p class="goods-title">' . htmlspecialchars_decode($product['name'], ENT_QUOTES) . '</p>';
										$fieldHtml['html'].='<p class="goods-sub-title c-black hide"></p>';
										if ($fieldContent['price']) {
											$fieldHtml['html'].='<p class="goods-price">';
											if($fx_store_info['is_point_mall'] == 1) {
												$fieldHtml['html'].='<em><span class="point_ico"></span>' . $price . '</em>';
											} else {
												$fieldHtml['html'].='<em>￥' . $price . '</em>';
											}
											$fieldHtml['html'].='</p>';
										}
										$fieldHtml['html'].='<p class="goods-price-taobao hide"></p>';
										$fieldHtml['html'].='</div>';
										$fieldHtml['html'].='</a>';
										if ($fieldContent['buy_btn'] && ($fieldContent['size_type'] == 0 || $fieldContent['size_type'] == 1)) {
											$fieldHtml['html'].='<div class="js-goods-buy buy-response" data-id="' . $value['id'] . '"></div>';
											$fieldHtml['html'].='<a href="javascript:void(0)" class="js-goods-cart-btn" data-buyer-quota="' . $buyer_quota . '" data-id="' . $product["product_id"] . '" data-store_id="' . $seller_id . '" data-type="' . $type . '"><div class="goods-buy btn' . $fieldContent['buy_btn_type'] . ' info-no-title"></div></a>';
										}
										$fieldHtml['html'].='</li>';
										$i++;
									}
									$fieldHtml['html'].='</ul>';
								}
							}
						} else if ($fieldContent['size'] == 2) {
							$ii = 0;
							foreach ($fieldContent['goods'] as $key => $value) {
								if (!isset($product_group_list[$value['id']])) {
									continue;
								}
								$i = 1;
								if ($ii == 0) {
									$fieldHtml['html'].='<ul class="js-goods-list sc-goods-list pic clearfix size-2">';
								} else {
									$fieldHtml['html'].='<ul class="js-goods-list sc-goods-list pic clearfix size-2" style="display:none">';
								}
								$ii++;

								$product_list = M('Product_to_group')->getProducts($value['id']);
								$product_arr = array();
								foreach ($product_list as $v) {
									array_push($product_arr, $v['product_id']);
								}

								$product_list = M('Product')->getSelling(array('product_id' => array('in', $product_arr), 'store_id' => $store_id), '', '', 0, 1000);
								if (empty($product_list)) {
									$fieldHtml['html'].='<center>暂时没有商品</cemter>';
								} else {
									foreach ($product_list as $k => $v) {

										//商品类型
										$type = 0;
										if (!empty($v['wholesale_product_id'])) {
											$type = 1;
										}

										if ($drp_level > 0 && !empty($v['unified_price_setting']) && $v['is_fx']) {
											$price = $v['drp_level_' . $drp_level . '_price'];
										} else {
											$price = $v['price'];
										}

										//商品限购
										$buyer_quota = !empty($v['buyer_quota']) ? $v['buyer_quota'] : 0;

										$url = 'good.php?id=' . $v["product_id"] . '&store_id=' . $seller_id;
										$fieldHtml['html'] .='<li class="goods-card goods-list ' . ($i % 3 == 1 ? 'big-pic' : 'small-pic') . ($fieldContent['size_type'] == 2 ? ' normal' : ' card') . '">';
										$fieldHtml['html'].='<a href="' . $url . '" class="js-goods link clearfix" target="_blank" title="' . htmlspecialchars_decode($v['name'], ENT_QUOTES) . '">';
										$fieldHtml['html'].='<div class="photo-block">';
										$fieldHtml['html'].='<img class="goods-photo js-goods-lazy" src="' . getAttachmentUrl($v['image']) . '" style="display:inline;">';
										$fieldHtml['html'].='</div>';
										$fieldHtml['html'].='<div class="info clearfix ' . ($fieldContent['show_title'] == 1 ? 'info-title ' : 'info-no-title ') . ($fieldContent['price'] == 1 ? 'info-price ' : 'info-no-price ') . '' . $fieldContent['buy_btn_type'] . (($fieldContent['show_title'] == 1 || $fieldContent['price'] == 1) ? '' : ' hide') . '">';
										$fieldHtml['html'].='<p class="goods-title">' . htmlspecialchars_decode($v['name'], ENT_QUOTES) . '</p>';
										$fieldHtml['html'].='<p class="goods-sub-title c-black hide"></p>';
										if ($fieldContent['price']) {
											$fieldHtml['html'].='<p class="goods-price">';
											if($fx_store_info['is_point_mall'] == 1) {
												$fieldHtml['html'].='<em><span class="point_ico"></span>' . $price . '</em>';
											} else {
												$fieldHtml['html'].='<em>￥' . $price . '</em>';
											}
											$fieldHtml['html'].='</p>';
										}
										$fieldHtml['html'].='<p class="goods-price-taobao hide"></p>';
										$fieldHtml['html'].='</div>';
										$fieldHtml['html'].='</a>';
										if ($fieldContent['buy_btn'] && $fieldContent['size_type'] == 0) {
											$fieldHtml['html'].='<div class="js-goods-buy buy-response" data-id="' . $value['id'] . '"></div>';
											$fieldHtml['html'].='<a href="javascript:void(0)" class="js-goods-cart-btn" data-buyer-quota="' . $buyer_quota . '" data-id="' . $v["product_id"] . '" data-store_id="' . $seller_id . '" data-type="' . $type . '"><div class="goods-buy btn' . $fieldContent['buy_btn_type'] . ' info-no-title"></div></a>';
										}
										$fieldHtml['html'].='</li>';
										$i++;
									}
								}
								$fieldHtml['html'].='</ul>';
							}
						} else if ($fieldContent['size'] == 3) {
							$i = 1;
							$ii = 0;
							foreach ($fieldContent['goods'] as $key => $value) {
								if (!isset($product_group_list[$value['id']])) {
									continue;
								}
								if ($ii == 0) {
									$fieldHtml['html'].='<ul class="js-goods-list sc-goods-list clearfix list size-3">';
								} else {
									$fieldHtml['html'].='<ul class="js-goods-list sc-goods-list clearfix list size-3" style="display:none">';
								}
								$ii++;

								$product_list = M('Product_to_group')->getProducts($value['id']);
								$product_arr = array();
								foreach ($product_list as $v) {
									array_push($product_arr, $v['product_id']);
								}

								$product_list = M('Product')->getSelling(array('product_id' => array('in', $product_arr), 'store_id' => $store_id), '', '', 0, 1000);
								//$product_list = M('Product')->getSelling(array('group_id' => $value['id']), '', '', 0, 10);
								if (empty($product_list)) {
									$fieldHtml['html'].='<center>暂时没有商品</cemter>';
								} else {
									foreach ($product_list as $k => $v) {

										//商品类型
										$type = 0;
										if (!empty($v['wholesale_product_id'])) {
											$type = 1;
										}

										if ($drp_level > 0 && !empty($v['unified_price_setting']) && $v['is_fx']) {
											$price = $v['drp_level_' . $drp_level . '_price'];
										} else {
											$price = $v['price'];
										}

										//商品限购
										$buyer_quota = !empty($v['buyer_quota']) ? $v['buyer_quota'] : 0;

										$url = 'good.php?id=' . $v["product_id"] . '&store_id=' . $seller_id;
										$fieldHtml['html'].='<li class="goods-card goods-list ' . ($i % 3 == 1 ? 'big-pic' : 'small-pic') . ($fieldContent['size_type'] == 2 ? ' normal' : ' card') . '">';
										$fieldHtml['html'].='<a href="' . $url . '" class="js-goods link clearfix" target="_blank" title="' . htmlspecialchars_decode($v['name'], ENT_QUOTES) . '">';
										$fieldHtml['html'].='<div class="photo-block">';
										$fieldHtml['html'].='<img class="goods-photo js-goods-lazy" src="' . getAttachmentUrl($v['image']) . '" style="display:block;">';
										$fieldHtml['html'].='</div>';
										$fieldHtml['html'].='<div class="info">';
										$fieldHtml['html'].='<p class="goods-title">' . htmlspecialchars_decode($v['name'], ENT_QUOTES) . '</p>';
										if ($fieldContent['price']) {
											$fieldHtml['html'].='<p class="goods-price">';
											if($fx_store_info['is_point_mall'] == 1) {
												$fieldHtml['html'].='<em><span class="point_ico"></span>' . $price . '</em>';
											} else {
												$fieldHtml['html'].='<em>￥' . $price . '</em>';
											}
											$fieldHtml['html'].='</p>';
										}
										$fieldHtml['html'].='</div>';
										$fieldHtml['html'].='</a>';
										if ($fieldContent['buy_btn']) {
											$fieldHtml['html'].='<div class="js-goods-buy buy-response" data-id="' . $v['id'] . '"></div>';
											$fieldHtml['html'].='<a href="javascript:void(0)" class="js-goods-cart-btn" data-buyer-quota="' . $buyer_quota . '" data-id="' . $v["product_id"] . '" data-store_id="' . $seller_id . '" data-type="' . $type . '"><div class="goods-buy btn' . $fieldContent['buy_btn_type'] . ' info-no-title"></div></a>';
										}
										$fieldHtml['html'].='</li>';
										$i++;
									}
									$fieldHtml['html'].='</ul>';
								}
							}
						}
						$fieldHtml['html'].='</div>';
					}
					break;
					case 'article' :
						//$custom_field = D('Custom_field')->where(array('field_type'=>'article','store_id'=>$_SESSION['store']['store_id']))->find();
						//$content = unserialize($custom_field['content']);
						$contents = $fieldContent['activity_arr'];
						// 动态
						$aids = array();
						foreach($contents as $con_article){
							if(!in_array($con_article['id'],$aids)){
								$aids[] = $con_article['id'];
							}
						}
						$article_list = D('Article')->where(array('status'=>1, 'platform_status'=>1,'id'=>array('in',$aids)))->select();
						if(!$article_list){
							break;
						}
						$articles = false;
						$on_aids = array();
						foreach($article_list as $_article){
							$_article['product'] = D('Product')->field('product_id,name,image')->where(array('product_id'=>$_article['product_id']))->find();
							$collects = D('My_collection_article')->field('count(*) as count')->where(array('article_id'=>$_article['id']))->find();
							$mycollected = D('My_collection_article')->where(array('article_id'=>$_article['id'],'uid'=>$_SESSION['wap_user']['uid']))->find();
							$_article['collect'] = $collects['count'];
							$_article['mycollected'] = $mycollected ? 1 : 0;
							$articles[$_article['id']] = $_article;
							$on_aids[] = $_article['id'];
						}
						
						$js = '<script src="'.STATIC_URL.'js/layer_mobile/layer.m.js"></script><script type="text/javascript">

							// 查看更多
							function get_more(obj){
								var aid = $(obj).attr("aid");
								var store_id = $(obj).attr("store_id");
								var target = "' . option('config.site_url') . '/wap/article.php?action=index&store_id="+store_id+"&aid="+aid;
								window.location.href=target;
							}

							// 添加/删除收藏
							function collections(obj){
								var aid = $(obj).attr("aid");
								var store_id = $(obj).attr("store_id");
								$.post("' . option('config.site_url') . '/wap/article.php?action=collect",{"aid":aid,"store_id":store_id},function(response){
									if(response.err_code>0){
										layer.open({title:["系统提示","background-color:#FF6600;color:#fff;"],content: response.err_msg});
										return;
									}
									var isactive = $(obj).find("i").hasClass("active");
									var collect_count = parseInt($(obj).find("span").text());
									var flag = response.err_msg;
									if(isactive){
										if(flag=="add"){
											$(obj).find("span").text(collect_count+1);
										}else{
											$(obj).find("i").removeClass("active");
											$(obj).find("span").text(collect_count-1);
										}
									}else{
										if(flag=="add"){
											$(obj).find("i").addClass("active");
											$(obj).find("span").text(collect_count+1);
										}else{
											$(obj).find("span").text(collect_count-1);
										}
									}

								},"json");
							}

							</script>';
						$current_store = current($articles);
						$shop_name_my = $fieldContent['name']?$fieldContent['name']:'店铺动态';
						$html  = '<div class="shopIndex  swiper-container swiper-container-horizontal">';
						$html .= '  <div class="title"><span>'.$shop_name_my.'</span><span><a href="javascript:;" onclick="get_more(this)" id="a_get_more" aid="'.$contents[0]['id'].'" store_id="'.$current_store['store_id'].'">查看更多</a><i></i></span></div>';
						$html .= '  <ul class="swiper-wrapper" id="shop_article">';
						foreach($contents as $_item){

							if (!in_array($_item['id'], $on_aids)) {
								continue;
							}

							$html .= '      <li class="swiper-slide swiper-slide-active" aid="'.$_item['id'].'" store_id="'.$articles[$_item['id']]['store_id'].'">';
							$html .= '          <div class="shopInfo clearfix">';
							$html .= '              <div class="shopImg">';
							$html .= '                  <a href="' . option('config.site_url') . '/wap/good.php?id='.$articles[$_item['id']]['product_id'].'&platform=1"><img src="'.getAttachmentUrl($articles[$_item['id']]['product']['image']).'" width=84 height=84></a>';
							$html .= '              </div>';
							$html .= '              <div class="shopTxt">';
							$html .= '                  <h2>'.$_item['title'].'</h2>';
							$html .= '                  <p>'.getHumanTime(time()-$articles[$_item['id']]['dateline']).'前'.'</p>';
							$html .= '              </div>';
							$iscollected = $articles[$_item['id']]['mycollected']?'active':'';  // 是否收藏过
							$html .= '              <button onclick="collections(this)" aid='.$_item['id'].'  store_id='.$articles[$_item['id']]['store_id'].'><i class="'.$iscollected.'"></i><span>'.$articles[$_item['id']]['collect'].'</span></button>';
							$html .= '          </div>';
							$html .= '          <ul class="shopList">';
							$html .= '              <li class="">';
							$html .= '                  <p>'.$articles[$_item['id']]['desc'].'</p>';
							$html .= '                  <ul class="clearfix ">';
														$imgs = explode(',',$articles[$_item['id']]['pictures']);
														foreach($imgs as $___key => $img){
															if($___key<=2){
							$html .= '                      <li><img src="'.getAttachmentUrl($img).'" width=110 height=110></li>';
															}
														}
							$html .= '                  </ul>';
							$html .= '              </li>';
							$html .= '          </ul>';
							$html .= '      </li>';
						}
						$html .= '  </ul>';
						$html .= '<ul class="shopSpot swiper-pagination  swiper-pagination-clickable swiper-pagination-bullets"><span class="swiper-pagination-bullet swiper-pagination-bullet-active"></span><span class="swiper-pagination-bullet"></span><span class="swiper-pagination-bullet"></span></ul>
							</div>';

						$html .= $js;
					$fieldHtml['html'] .= $html;
					break;

				case 'cube': //魔方 
						$fieldHtml['html'] = '<!-- 魔方 --><div style="clear:both"></div>';

						$cube_html = '<table class="custom-cube2-table js-custom-cube2-table" style="visibility: visible;">';
						$cube_html .= ' <tbody>';
						
						$megre_td = array();
						for ($r = 0; $r < 4; $r++) {
							$cube_html .= '<tr>';
							for ($c = 0; $c < 4; $c++) {
								$_selected = false;
								$_colspan = 1;
								$_rowspan = 1;
								$_index = 0;
								$_image = '';
								$_url = '';
								foreach ($fieldContent['content'] as $i => $cube_data) {
									if ($cube_data['x'] == $c && $cube_data['y'] == $r) {
										$_selected = true;
										$_colspan = $cube_data['colspan'];
										$_rowspan = $cube_data['rowspan'];
										$_index = $i;
										$_image = !empty($cube_data['image']) ? $cube_data['image'] : '';
										$_url = !empty($cube_data['url']) ? $cube_data['url'] : '';
									}
								}

								//修改url中store_id参数，若没有则添加
								if (!empty($_url)) {
									$tmp_url = redirect_store($seller_id, $_url);
									if (!empty($tmp_url)) {
										$_url = $tmp_url;
									}
								}

								if ($_rowspan > 1) {
									for ($r2 = 1; $r2 < $_rowspan; $r2++) {
										for ($c2 = 0; $c2 < $_colspan; $c2++) {
											$megre_td[] = ($c + $c2) . ':' . ($r + $r2);
										}
									}
								}
								if ($_colspan > 1) {
									$c += ($_colspan - 1);
								}
								if (!in_array(($c . ':' . $r), $megre_td)) {
									if ($_selected) {
										$cube_html .= '<td class="not-empty cols-' . $_colspan . ' rows-' . $_rowspan . ' index-' . $_index . '" colspan="' . $_colspan . '" rowspan="' . $_rowspan . '" data-index="' . $_index . '">';
										if (!empty($_image)) {
											if (!empty($_url)) {
												$cube_html .= '<a href="' . $_url . '"><img src="' . $_image . '"></a>';
											} else {
												$cube_html .= '  <img src="' . $_image . '">';
											}
										} else {
											$cube_html .= '';
										}
										$cube_html .= '</td>';
									} else {
										$cube_html .= '<td class="empty" data-x="' . $c . '" data-y="' . $r . '"></td>';
									}   
								}
							}
							$cube_html .= '</tr>';
						}
						$cube_html .= '</body>';
						$cube_html .= '</table>';
						$fieldHtml['html'] .= $cube_html;
						$fieldHtml['html'] .= '<div style="clear:both"></div>';
					case 'map':
						$fieldHtml['html'] = '<div><div class="custom-title text-left" style="margin-bottom:0;background:none;"><h2 class="title">店铺地图</h2></div></div><div class="edit-map-page"><div class="js-map-box" id="map-'.$key.'" data-title="'.C('now_store.name').'" data-lng="'.C('now_store.long').'" data-lat="'.C('now_store.lat').'" data-adress="'.C('now_store.adress').'"></div></div>';
					break;
			}
			$fieldHtmlArr[] = $fieldHtml;
		}
		return $fieldHtmlArr;
	}

	/**
	 * app 微页面
	 */
	public function getFields($store_id, $module_name, $module_id, $seller_id = 0, $drp_level = 0, $drp_diy_store = 1, $uid = 0) {
		if (empty($seller_id)) {
			$seller_id = $store_id;
		}
		if ($drp_level > 3) {
			$drp_level = 3;
		}
		
		// 查找所有自定义模块
		$field_list = $this->get_field($store_id, $module_name, $module_id,$seller_id);
		if (empty($field_list)) {
			return array();
		}
		
		$fx_store = array();
		$supplier_store = D('Store')->where(array('store_id' => $store_id))->find();
		if ($store_id == $seller_id) {
			$fx_store = $supplier_store;
		} else {
			$fx_store = D('Store')->where(array('store_id' => $seller_id))->find();
		}
		
		$return = array();
		// 对自定义数据模块进行处理
		foreach ($field_list as $key => $value) {
			if (empty($value['content']) && !in_array($value['field_type'], array('line', 'search', 'store', 'attention_collect'))) {
				continue;
			}
			unset($value['module_name']);
			
			$field = array();
			$field['field_type'] = $value['field_type'];
			$content = $value['content'];
			
			switch ($value['field_type']) {
				case 'rich_text' :
					$field['content'] = htmlspecialchars_decode($content['content'], ENT_QUOTES);
					$field['bgcolor'] = $content['bgcolor'] ? $content['bgcolor'] : '';
					$field['screen'] = $content['screen'] ? $content['screen'] : 0;
					break;
				case 'goods' :
					$product_list = array();
					
					foreach ($content['goods'] as $goods) {
						$product = M('Shop_goods')->where(array('goods_id' => $goods['id'], 'status' => 1))->find();
						if (empty($product)) {
							continue;
						}
						
						$is_reservation = $product['is_reservation'];

						$price = $product['price'];
						
						
						$tmp = array();
						$tmp['product_id'] = $product['product_id'];
						$tmp['image'] = $product['image'];
						$tmp['price'] = $price;
						$tmp['name'] = htmlspecialchars_decode($product['name'], ENT_QUOTES);
						$tmp['url'] = url('goods:index', array('id' => $product['product_id'], 'store_id' => $seller_id));
						$tmp['store_id'] = $seller_id;

						$product_list[] = $tmp;
						unset($product);
					}
					
					if (!isset($value['content']['size'])) {
						$value['content']['size'] = 0;
					}
					
					if (!isset($value['content']['size_type'])) {
						$value['content']['size_type'] = 0;
					}
					
					if (!isset($value['content']['show_title'])) {
						$value['content']['show_title'] = 0;
					}
					
					if (!isset($value['content']['price'])) {
						$value['content']['price'] = 0;
					}
					
					if (!isset($value['content']['buy_btn'])) {
						$value['content']['buy_btn'] = 0;
					}
					
					unset($value['content']['goods']);
					$value['content']['product_list'] = $product_list;
					$field = $value;
					break;
				case 'title' :
					if (!isset($value['content']['show_method'])) {
						$value['content']['show_method'] = 0;
					}
					if (!isset($value['content']['bgcolor'])) {
						$value['content']['bgcolor'] = '#FFF';
					}
					$field = $value;
					break;
				case 'text_nav' :
					$content = array();
					if (is_array($value['content'])) {
						foreach ($value['content'] as $text_nav) {
							unset($text_nav['name']);
							unset($text_nav['prefix']);
							if (!empty($text_nav['url']) && stripos($text_nav['url'], option('config.site_url')) !== false) {
								$text_nav['url'] = redirect_store($seller_id, $text_nav['url']);
							} else if (empty($text_nav['url'])) {
								$text_nav['url'] = 'javascript: void(0)';
							}
							$content[] = $text_nav;
						}
					}
					
					$field['content'] = $content;
					break;
				case 'link' :
					$link_list = array();
					if (is_array($value['content'])) {
						foreach ($value['content'] as $link) {
							
							
							if ($link['widget'] == 'pagecat') {
								$wei_page_list = M('Wei_page')->getCategoryPageNumberList($link['id'], $link['number']);
								foreach ($wei_page_list as $wei_page) {
									$tmp = array();
									$url = '';
									if (empty($drp_diy_store) && stripos($link['url'], option('config.site_url')) !== false) {
										$url = option('config.wap_site_url') . '/page.php?id=' . $wei_page['page_id'] . '&store_id=' . $seller_id;
									} else {
										$url = option('config.wap_site_url') . '/page.php?id=' . $wei_page['page_id'];
									}
									
									$tmp['name'] = htmlspecialchars_decode($wei_page['page_name'], ENT_QUOTES);
									$tmp['url'] = $url;
									
									$link_list[] = $tmp;
								}
							} else if ($link['widget'] == 'goodcat') {
								$tmp['name'] = htmlspecialchars_decode($link['name'], ENT_QUOTES);
								$tmp['url'] = option('config.wap_site_url') . '/goodcat.php?id=' . $link['id']. '&store_id=' . $seller_id;
								
								$link_list[] = $tmp;
							} else if ($link['type'] == 'link') {
								$tmp['name'] = $link['name'];
								$tmp['url'] = $link['url'];
								
								$link_list[] = $tmp;
							}
						}
					}
					$field['link_list'] = $link_list;
					break;
				case 'search' :
					break;
				case 'line' :
					break;
				case 'white' :
					$field['height'] = $value['content']['height'];
					break;
				case 'component' :
					$field = $this->getFields($store_id, 'custom_page', $value['content']['id'], $seller_id, $drp_level, $drp_diy_store, $uid);
					break;
				case 'store' :
					$field['name'] = C('now_store.name');
					$field['url'] = C('now_store.url');
					break;
				case 'notice' :
					$field['content'] = $value['content']['content'];
					break;
				case 'goods_group1' :
					$goods_group_list = array();
					foreach ($value['content']['goods_group1'] as $goods_group) {
						$product_group = D('Product_group')->where(array('group_id' => $goods_group['id']))->find();
						if (empty($product_group)) {
							continue;
						}
						
						$order = '';
						if ($product_group['first_sort'] == 1) {
							$order = 'p.pv DESC';
						} else {
							$order = 'p.sort DESC';
						}
						
						if ($product_group['second_sort'] == 0) {
							$order .= ',`p`.`date_added` DESC';
						} else if ($product_group['second_sort'] == 1) {
							$order .= ',`p`.`date_added` ASC';
						} else if ($product_group['first_sort'] != 1) {
							$order .= ',`p`.`pv` DESC';
						}
						
						$group = array();
						$group['title'] = $product_group['group_name'];
						$limit = 200;
						if ($goods_group['show_num']) {
							$limit = $goods_group['show_num'];
						}
						
						$product_list = D('Product_to_group as pg')->join('Product as p ON p.product_id = pg.product_id')->where("pg.group_id = '" . $goods_group['id'] . "' AND p.store_id != 0 AND p.status = 1")->field('p.product_id, p.name, p.price, p.image, p.unified_price_setting, p.is_fx, p.drp_level_1_price, p.drp_level_2_price, p.drp_level_3_price, p.is_reservation')->limit($limit)->order($order)->select();
						
						if (empty($product_list)) {
							continue;
						}
						
						$product_tmp_list = array();
						foreach ($product_list as $product) {
							$price = $product['price'];
							$is_reservation = $product['is_reservation'];
							if ($drp_level > 0 && !empty($product['unified_price_setting']) && $product['is_fx']) {
								$price = $product['drp_level_' . $drp_level . '_price'];
								$is_reservation = 0;
							}
							
							$tmp = array();
							$tmp['product_id'] = $product['product_id'];
							$tmp['name'] = $product['name'];
							$tmp['image'] = getAttachmentUrl($product['image']);
							$tmp['price'] = $price;
							$tmp['url'] = option('config.wap_site_url') .'/good.php?id=' . $product['product_id'] . '&store_id=' . $seller_id;
							$tmp['is_reservation'] = $is_reservation;
							
							$product_tmp_list[] = $tmp;
						}
						
						
						$group['product_list'] = $product_tmp_list;
						$goods_group_list[] = $group;
					}
					
					$field['goods_group_list'] = $goods_group_list;
					break;
				case 'goods_group2' :
					$content = array();
					$content['size'] = $value['content']['size'] + 0;
					$content['size_type'] = $value['content']['size_type'] + 0;
					$content['buy_btn'] = $value['content']['buy_btn'] + 0;
					$content['buy_btn_type'] = $value['content']['buy_btn_type'] + 0;
					$content['show_title'] = $value['content']['show_title'] + 0;
					$content['price'] = $value['content']['price'] + 0;
					
					$goods_group_list = array();
					foreach ($value['content']['goods'] as $goods_group) {
						$product_group = D('Product_group')->where(array('group_id' => $goods_group['id']))->find();
						if (empty($product_group)) {
							continue;
						}
						
						$order = '';
						if ($product_group['first_sort'] == 1) {
							$order = 'p.pv DESC';
						} else {
							$order = 'p.sort DESC';
						}
						
						if ($product_group['second_sort'] == 0) {
							$order .= ',`p`.`date_added` DESC';
						} else if ($product_group['second_sort'] == 1) {
							$order .= ',`p`.`date_added` ASC';
						} else if ($product_group['first_sort'] != 1) {
							$order .= ',`p`.`pv` DESC';
						}
						
						$group = array();
						$group['title'] = $product_group['group_name'];
						$limit = 1000;
						$product_list = D('Product_to_group as pg')->join('Product as p ON p.product_id = pg.product_id')->where("pg.group_id = '" . $goods_group['id'] . "' AND p.store_id != 0 AND p.status = 1")->field('p.product_id, p.name, p.price, p.image, p.unified_price_setting, p.is_fx, p.drp_level_1_price, p.drp_level_2_price, p.drp_level_3_price, p.is_reservation')->order($order)->limit($limit)->select();
						
						if (empty($product_list)) {
							continue;
						}
					
						$product_tmp_list = array();
						foreach ($product_list as $product) {
							$price = $product['price'];
							$is_reservation = $product['is_reservation'];
							if ($drp_level > 0 && !empty($product['unified_price_setting']) && $product['is_fx']) {
								$price = $product['drp_level_' . $drp_level . '_price'];
								$is_reservation = 0;
							}
								
							$tmp = array();
							$tmp['product_id'] = $product['product_id'];
							$tmp['name'] = $product['name'];
							$tmp['image'] = getAttachmentUrl($product['image']);
							$tmp['price'] = $price;
							$tmp['url'] = option('config.wap_site_url') .'/good.php?id=' . $product['product_id'] . '&store_id=' . $seller_id;
							$tmp['is_reservation'] = $is_reservation;
								
							$product_tmp_list[] = $tmp;
						}
						
						$group['product_list'] = $product_tmp_list;
						$goods_group_list[] = $group;
					}
					$content['goods_group_list'] = $goods_group_list;
					$field['content'] = $content;
					break;
				case 'goods_group3' :
					$goods_group_list = array();
					foreach ($value['content']['goods_group3'] as $goods_group) {
						$product_group = D('Product_group')->where(array('group_id' => $goods_group['id']))->find();
						if (empty($product_group)) {
							continue;
						}
				
						$order = '';
						if ($product_group['first_sort'] == 1) {
							$order = 'p.pv DESC';
						} else {
							$order = 'p.sort DESC';
						}
				
						if ($product_group['second_sort'] == 0) {
							$order .= ',`p`.`date_added` DESC';
						} else if ($product_group['second_sort'] == 1) {
							$order .= ',`p`.`date_added` ASC';
						} else if ($product_group['first_sort'] != 1) {
							$order .= ',`p`.`pv` DESC';
						}
				
						$group = array();
						$group['title'] = $product_group['group_name'];
						$limit = 200;
						if ($goods_group['show_num']) {
							$limit = $goods_group['show_num'];
						}
				
						$product_list = D('Product_to_group as pg')->join('Product as p ON p.product_id = pg.product_id')->where("pg.group_id = '" . $goods_group['id'] . "' AND p.store_id != 0 AND p.status = 1 AND p.supplier_id = 0")->field('p.product_id, p.name, p.price, p.image, p.unified_price_setting, p.is_fx, p.drp_level_1_price, p.drp_level_2_price, p.drp_level_3_price, p.is_reservation')->limit($limit)->order($order)->select();
				
						if (empty($product_list)) {
							continue;
						}
				
						$product_tmp_list = array();
						foreach ($product_list as $product) {
							$price = $product['price'];
							$is_reservation = $product['is_reservation'];
							if ($drp_level > 0 && !empty($product['unified_price_setting']) && $product['is_fx']) {
								$price = $product['drp_level_' . $drp_level . '_price'];
								$is_reservation = 0;
							}
								
							$tmp = array();
							$tmp['product_id'] = $product['product_id'];
							$tmp['name'] = $product['name'];
							$tmp['image'] = getAttachmentUrl($product['image']);
							$tmp['price'] = $price;
							$tmp['url'] = option('config.wap_site_url') .'/good.php?id=' . $product['product_id'] . '&store_id=' . $seller_id;
							$tmp['is_reservation'] = $is_reservation;
								
							$product_tmp_list[] = $tmp;
						}
				
				
						$group['product_list'] = $product_tmp_list;
						$goods_group_list[] = $group;
					}
					
					$field['show_type'] = $value['content']['show_type'] == 2 ? false : true;
					$field['goods_group_list'] = $goods_group_list;
					break;
				case 'tpl_shop' :
					if (!isset($value['content']['shop_head_bg_img'])) {
						$value['content']['shop_head_bg_img'] = '';
					} else {
						$value['content']['shop_head_bg_img'] = getAttachmentUrl($value['content']['shop_head_bg_img']);
					}
					
					if (!isset($value['content']['bgcolor'])) {
						$value['content']['bgcolor'] = '#FFFFFF';
					}
					
					//全部已经上架的商品
					$count = M('Shop_goods')->where(array('store_id' => $store_id,'status'=>1))->count();
					
					if (empty($drp_diy_store)) {
						$store = D('Store')->where(array('store_id' => $seller_id))->field("collect, logo, name")->find();
					} else {
						$store = D('Store')->where(array('store_id' => $store_id))->field("collect, logo, name")->find();
					}
					$collect = $store['collect'];
					
					$logo = getAttachmentUrl($store['logo']) ? getAttachmentUrl($store['logo']) : getAttachmentUrl('/images/default_shop_2.jpg', false);
					$count = ($count > 9999) ? '9999+' : $count;
					$collect = ($collect > 9999) ? '9999+' : $collect;
					
					$value['content']['shop_head_logo_img'] = $logo;
					$value['content']['product_count'] = $count;
					$value['content']['collect'] = $collect;
					$value['content']['title'] = $value['content']['title'] ? $value['content']['title'] : $store['name'];
					$value['content']['physical_title'] = '';
					if ($_SESSION['physical_id']) {
						$store_physical = D('Store_physical')->where(array('pigcms_id' => $_SESSION['physical_id']))->find();
						$value['content']['physical_title'] = $store_physical['name'];
					}
					
					
					$field = $value;
					break;
				case 'tpl_shop1' :
					if (!isset($value['content']['shop_head_bg_img'])) {
						$value['content']['shop_head_bg_img'] = '';
					} else {
						$value['content']['shop_head_bg_img'] = getAttachmentUrl($value['content']['shop_head_bg_img']);
					}
					
					if (!isset($value['content']['bgcolor'])) {
						$value['content']['bgcolor'] = '#FFFFFF';
					}
					
					if (empty($drp_diy_store)) {
						$store = D('Store')->where(array('store_id' => $seller_id))->field("attention_num as atten_num, logo, name")->find();
					} else {
						$store = D('Store')->where(array('store_id' => $store_id))->field("attention_num as atten_num, logo, name")->find();
					}
					
					$logo = getAttachmentUrl($store['logo']) ? getAttachmentUrl($store['logo']) : getAttachmentUrl('/images/default_shop_2.jpg', false);
					$value['content']['shop_head_logo_img'] = $logo;
					$value['content']['title'] = $value['content']['title'] ? $value['content']['title'] : $store['name'];
					$value['content']['physical_title'] = '';
					if ($_SESSION['physical_id']) {
						$store_physical = D('Store_physical')->where(array('pigcms_id' => $_SESSION['physical_id']))->find();
						$value['content']['physical_title'] = $store_physical['name'];
					}
					
					$field = $value;
					break;
				case 'attention_collect' :
					$where['store_id'] = $seller_id;
					$store = D('Store')->field('collect, attention_num')->where($where)->find();
					
					$field['collect'] = $store['collect'] + 0;
					$field['attention_num'] = $store['attention_num'] + 0;
					break;
				case 'image_nav' :
					$content = array();
					foreach ($value['content'] as $key => $tmp) {
						unset($tmp['name']);
						unset($tmp['prefix']);
						if (!empty($tmp['url']) && stripos($tmp['url'], option('config.site_url')) !== false) {
							$tmp['url'] = redirect_store($seller_id, $tmp['url']);
						} else if (empty($tmp['url'])) {
							$tmp['url'] = 'javascript: void(0)';
						}
						
						$content[] = $tmp;
					}
					$field['content'] = $content;
					break;
				case 'image_ad' :
					$value['content']['image_type'] += 0;
					$value['content']['image_size'] += 0;
					foreach ($value['content']['nav_list'] as &$nav) {
						unset($nav['name']);
						unset($nav['prefix']);
						
						if (!empty($nav['url']) && stripos($nav['url'], option('config.site_url')) !== false) {
							$nav['url'] = redirect_store($seller_id, $nav['url']);
						} else if (empty($nav['url'])) {
							$nav['url'] = 'javascript: void(0)';
						}
						
					}
					$field = $value;
					break;
				case 'article' :
					$article_arr = $value['content']['activity_arr'];
					
					$article_list = array();
					$store_list = array();
					foreach ($article_arr as $tmp) {
						$article = D('Article')->where(array('id' => $tmp['id'], 'status' => 1))->find();
						if (empty($article)) {
							continue;
						}
						
						if (!isset($store_list[$article['store_id']])) {
							$store_tmp = D('Store')->where(array('store_id' => $article['store_id']))->field('store_id, name, logo')->find();
							if (empty($store_tmp)) {
								continue;
							}
							$store_tmp['logo'] = $store_tmp['logo'] ? getAttachmentUrl($store_tmp['logo']) : getAttachmentUrl('images/default_shop.png', false);
							$store_list[$store['store_id']] = $store_tmp;
						}
						
						$product = D('Product')->where(array('product_id' => $article['product_id'], 'store_id' => $article['store_id'], 'status' => 1))->field('product_id, name, image, price')->find();
						if (empty($product)) {
							continue;
						}
						
						$article_data = array();
						$article_data['pigcms_id'] = $article['id'];
						$article_data['store_id'] = $article['store_id'];
						$article_data['product_id'] = $article['product_id'];
						$article_data['product_name'] = $product['name'];
						$article_data['product_image'] = getAttachmentUrl($product['image']);
						$article_data['store_name'] = $store_list[$article['store_id']]['name'];
						$article_data['store_logo'] = $store_list[$article['store_id']]['logo'];
						$article_data['time'] = getHumanTime(time() - $article['dateline']) . '前';
						$count = D('My_collection_article')->where(array('article_id' => $tmp['id']))->count('id');
						$is_collect = D('My_collection_article')->where(array('article_id' => $tmp['id'], 'uid' => $uid))->find();
						$article_data['collect'] = $count;
						$article_data['mycollected'] = $is_collect ? 1 : 0;
						if (!empty($article['pictures'])) {
							$article_data['picture_list'] = explode(',', $article['pictures']);
							foreach ($article_data['picture_list'] as &$picture) {
								$picture = getAttachmentUrl($picture);
							}
						} else {
							$article_data['picture_list'] = array();
						}
						
						$article_list[] = $article_data;
					}
					
					$value['content']['activity_arr'] = $article_list;
					$field['content'] = $value['content'];
					break;
				case 'coupons' :
					$content = array();
					foreach ($value['content']['coupon_arr'] as $coupon) {
						$coupon['coupon_id'] = $coupon['id'];
						unset($coupon['id']);
						$content[] = $coupon;
					}
					$field['content'] = $content;
					break;
				case 'subject_menu' :
					$content = array();
					foreach ($value['content']['subtype_list'] as $subtype) {
						$subtype['url'] = option('config.wap_site_url') . '/subtype.php?id=' . $seller_id . '&sid=' . $subtype['id'];
						$subtype['sid'] = $subtype['id'];
						unset($subtype['id']);
						$content[] = $subtype;
					}
					$field['content'] = $content;
					break;
				case 'subject_display' :
					$first_hour = max(0, $value['content']['hour']);
					$pre_hour = max(1, $value['content']['update_hour']);
					$day = max(3, $value['content']['day']);
					$number = max(1, $value['content']['number']);
					
					$hour = date('G');
					$content = array();
					$next_hour = 0;
					if ($hour < $first_hour) {
						$next_hour = $first_hour;
					} else {
						$add_step = 0;
						if (($hour - $first_hour) % $pre_hour == 0) {
							$add_step = 1;
						}
						
						$next_hour = max(1, ceil(($hour - $first_hour) / $pre_hour) + $add_step) * $pre_hour + $first_hour;
						if ($next_hour > 24) {
							$next_hour = $first_hour;
						}
					}
					
					$content['next_update'] = $next_hour . ':00';
					$week_arr = array("日", "一", "二", "三", "四", "五", "六");
					
					$subject_list = array();
					
					$time1 = mktime(0, 0, 0, date('m'), date('d'));
					$time2 = mktime(23, 59, 59, date('m'), date('d'));
					
					for($i = 0; $i < $day; $i++) {
						$start_time = $time1 - $i * 24 * 3600;
						$end_time = $time2 - $i * 24 * 3600;
						
						$data = array();
						$data['date'] = date('m-d', $start_time) . ' 星期' . $week_arr[date('w', $start_time)];
						
						$subject_tmp_list = D('Subject AS s')->join("Store_subject_data AS ssd ON s.id = ssd.subject_id AND ssd.store_id = '" . $seller_id . "'")->field('s.*, ssd.dz_count')->where("s.show_index = 1 AND s.store_id = '" . $store_id . "' AND timestamp >= '" . $start_time . "' AND timestamp <= '" . $end_time . "'")->limit($number)->order('s.id DESC')->select();
						if (empty($subject_tmp_list)) {
							continue;
						}
						
						foreach ($subject_tmp_list as $subject) {
							$tmp = array();
							$tmp['sid'] = $subject['id'];
							$tmp['name'] = $subject['name'];
							$tmp['pic'] = getAttachmentUrl($subject['pic']);
							$tmp['url'] = option('config.wap_site_url') . '/subinfo.php?store_id=' . $seller_id . '&subject_id=' . $subject['id'];
							$tmp['dz_count'] = $subject['dz_count'] + 0;
							$tmp['is_dz'] = 0;
							$tmp['store_id'] = $seller_id;
							if ($uid) {
								$count = D('User_collect')->where(array('type' => 3, 'dataid' => $subject['id'], 'store_id' => $seller_id))->count('collect_id');
								if ($count) {
									$tmp['is_dz'] = 1;
								}
							}
							
							$data['subject_list'][] = $tmp;
						}
						$content['subject_group_list'][] = $data;
					}
					
					$field['content'] = $content;
					break;
				case 'new_activity_module':
					$content = array();
					
					// atype 1、预售，2、团购，3、夺宝，4、砍价，5、秒杀，6、众筹，7、降价拍
					foreach ($value['content']['activity_arr'] as $activity) {
						if (empty($activity['id'])) {
							continue;
						}
						if ($activity['atype'] == 1) {
							$presale = D('Presale')->where("id = '" . $activity['id'] . "'")->find();
							
							if (empty($presale)) {
								continue;
							}
							
							$product = D('Product')->where("product_id = '" . $presale['product_id'] . "'")->find();
							$image = getAttachmentUrl($product['image']);
							
							$order_count = M('Order')->getActivityOrderCount(7, $activity['id']);
							
							$tmp = array();
							$tmp['pigcms_id'] = $presale['id'];
							$tmp['type'] = 1;
							$tmp['store_id'] = $presale['store_id'];
							$tmp['image'] = $image;
							$tmp['name'] = $presale['name'];
							$tmp['dingjin'] = $presale['dingjin'];
							$tmp['presale_person'] = $presale['presale_person'] + $order_count;
							$tmp['tips'] = '预售';
							$tmp['wap_url']  = option('config.wap_site_url') . '/presale.php?id='. $presale['id'];
							
							$content[] = $tmp;
						} else if ($activity['atype'] == 2) {
							$tuan = D('Tuan')->where("id = '" . $activity['id'] . "'")->find();
							
							if (empty($tuan)) {
								continue;
							}
							
							$product = D('Product')->where("product_id = '".$tuan['product_id']."'")->find();
							$tuan_person = M('Order')->getActivityOrderCount(6, $tuan['id']);
							$image = getAttachmentUrl($product['image']);
							
							$tmp = array();
							$tmp['pigcms_id'] = $tuan['id'];
							$tmp['type'] = 2;
							$tmp['store_id'] = $tuan['store_id'];
							$tmp['image'] = $image;
							$tmp['name'] = $tuan['name'];
							$tmp['start_price'] = $tuan['start_price'];
							$tmp['price'] = $tuan['price'];
							$tmp['person'] = $tuan_person;
							$tmp['tips'] = '团购';
							$tmp['wap_url']  = option('config.site_url') . '/webapp/groupbuy/#/details/' . $tuan['id'];
							
							$content[] = $tmp;
						} else if ($activity['atype'] == 3) {
							$unitary = D('Unitary')->where("id= '" . $activity['id'] . "'")->find();
							if (!empty($unitary)) {
								$product = D('Product')->where("product_id='" . $unitary['product_id'] . "'")->find();
								$unitary_person = D('Unitary_lucknum')->where(array('unitary_id' => $unitary['id']))->count('id');
								$image = getAttachmentUrl($product['image']);
								
								$per = round($unitary_person / $unitary['total_num'] * 100, 2);
								
								$tmp = array();
								$tmp['pigcms_id'] = $unitary['id'];
								$tmp['type'] = 3;
								$tmp['store_id'] = $unitary['store_id'];
								$tmp['image'] = $image;
								$tmp['name'] = $unitary['name'];
								$tmp['item_price'] = $unitary['item_price'];
								$tmp['price'] = $product['price'];
								$tmp['per'] = $per;
								$tmp['person'] = $tuan_person;
								$tmp['tips'] = '夺宝';
								$tmp['wap_url']  = option('config.site_url') . '/webapp/snatch/#/main/' . $activity['id'];
								
								$content[] = $tmp;
							}
						} else if ($activity['atype'] == 4) {
							$bargain = D('')-> table("Bargain b")->join("Product p On b.product_id = p.product_id", "LEFT")-> where("b.pigcms_id='" . $activity['id'] . "'")-> field("b.*, p.name as product_name, p.image")-> find();
							if (empty($bargain)) {
								continue;
							}
							
							$image = getAttachmentUrl($bargain['image']);
							$bargain_person = D('Bargain_kanuser')->where(array('bargain_id' => $activity['id']))->field('count(DISTINCT(token)) AS count')->find();
							
							$tmp = array();
							$tmp['pigcms_id'] = $bargain['pigcms_id'];
							$tmp['type'] = 4;
							$tmp['store_id'] = $bargain['store_id'];
							$tmp['image'] = $image;
							$tmp['name'] = $bargain['product_name'];
							$tmp['min_price'] = $unitary['minimum'] / 100;
							$tmp['price'] = $product['price'] / 100;
							$tmp['person'] = $bargain_person['count'];
							$tmp['tips'] = '砍价';
							$tmp['wap_url']  = option('config.wap_site_url') . '/bargain.php?action=detail&id=' . $bargain['pigcms_id'] . '&store_id=' . $bargain['store_id'];
							
							$content[] = $tmp;
						} else if ($activity['atype'] == 5) {
							$seckill = D('Seckill')->where(array('pigcms_id' => $activity['id']))->find();
							if (empty($seckill)) {
								continue;
							}
							
							if ($seckill['sku_id']) {
								$seckill = D('')->table("Seckill b")
								->join("Product p On b.product_id = p.product_id", "LEFT")
								->join("Product_sku sku On b.sku_id = sku.sku_id", "LEFT")
								->where("b.pigcms_id = '" . $activity['id'] . "'")
								->field("b.*, p.name as product_name, p.image, p.price as product_price")
								->find();
							} else if (empty($seckill['sku_id'])) {
								$seckill = D('')->table("Seckill b")
								->join("Product p On b.product_id = p.product_id", "LEFT")
								->where("b.pigcms_id = '" . $activity['id'] . "'")
								->field("b.*, p.name as product_name, p.image, p.price as product_price")
								->find();
							}
							
							$image = getAttachmentUrl($seckill['image']);
							$seckill_price = $seckill['seckill_price'] ? $seckill['seckill_price'] : $seckill['product_price'];
							$seckill_person = D('Seckill_user')->where(array('seckill_id' => $activity['id']))->count('pigcms_id');
							
							$tmp = array();
							$tmp['pigcms_id'] = $seckill['pigcms_id'];
							$tmp['type'] = 5;
							$tmp['store_id'] = $seckill['store_id'];
							$tmp['image'] = $image;
							$tmp['name'] = $seckill['name'];
							$tmp['seckill_price'] = $seckill_price;
							$tmp['price'] = $seckill['product_price'];
							$tmp['person'] = $seckill_person;
							$tmp['tips'] = '秒杀';
							$tmp['wap_url']  = option('config.wap_site_url') . '/seckill.php?seckill_id=' . $activity['id'];
							
							$content[] = $tmp;
						} else if ($activity['atype'] == 6) {
							$zc_product = D('Zc_product')->where("product_id='" . $activity['id'] . "'")->find();
							if (!empty($zc_product)) {
								$image = getAttachmentUrl($zc_product['productImageMobile']);
							
								$per = 100;
								if (!empty($zc_product['amount'])) {
									$per = min(100, round($zc_product['collect'] / $zc_product['amount'] * 100, 2));
								}
								
								$tmp = array();
								$tmp['pigcms_id'] = $zc_product['product_id'];
								$tmp['type'] = 6;
								$tmp['store_id'] = $zc_product['store_id'];
								$tmp['image'] = $image;
								$tmp['name'] = $zc_product['productName'];
								$tmp['collect'] = $zc_product['collect'];
								$tmp['per'] = $per;
								$tmp['tips'] = '众筹';
								$tmp['wap_url']  = option('config.site_url') . '/webapp/chanping/index.html#/view/' . $activity['id'];
								
								$content[] = $tmp;
							}
						} else if ($activity['atype'] == 7) {
							$where = array();
							$where['pigcms_id'] = $activity['id'];
							$where['starttime'] = array('<=', time());
							$where['endtime'] = array('>=', time());
							$where['state'] = 0;
							$cutprice = D('Cutprice')->where($where)->find();
							
							if ($cutprice) {
								// 降价拍
								$product = D('Product')->where(array('product_id' => $cutprice['product_id']))->field('product_id,name,image,info')->find();
								$images = getAttachmentUrl($product['image']);
								
								// 已有多少人参与
								$part_in = D('Cutprice_record')->where(array('cutprice_id' => $cutprice['pigcms_id']))->count('id');
									
								$cha = time() - $cutprice['starttime'];
								$cutprice['nowprice'] = $cutprice['startprice'];
								if($cha > 0){
									$chaprice = (floor($cha / 60 / $cutprice['cuttime'])) * $cutprice['cutprice'];
									if($cutprice['inventory'] > 0 && ($cutprice['startprice'] - $chaprice) > $cutprice['stopprice']){
										$cutprice['nowprice'] = $cutprice['startprice'] - $chaprice;
									}
								}
								
								$tmp = array();
								$tmp['pigcms_id'] = $cutprice['pigcms_id'];
								$tmp['name'] = $cutprice['active_name'];
								$tmp['image'] = $images;
								$tmp['start_price'] = $cutprice['nowprice'];
								$tmp['price'] = $cutprice['original'];
								$tmp['presale_person'] = $part_in;
								$tmp['tips'] = '降价拍';
								$tmp['type'] = 7;
								$tmp['wap_url'] = option('config.site_url') . '/wap/cutprice.php?action=detail&store_id=' . $cutprice['store_id'] . '&id=' . $cutprice['pigcms_id'];
								
								$content[] = $tmp;
							}
						}
					}
					
					if (count($content) > 0 && count($content) % 2 == 1) {
						$content = array_merge($content, $content);
					}
					
					$field['name'] = $value['content']['name'];
					$field['content'] = $content;
					$field['display'] = empty($value['content']['display']) ? 1 : 0;
					break;
				case 'cube':
					$content = array();
					if (is_array($value['content']['content'])) {
						$k = 0;
						$y = 0;
						foreach ($value['content']['content'] as $key => $tmp) {
							unset($tmp['type']);
							unset($tmp['prefix']);
							unset($tmp['title']);
							
							if ($tmp['y'] > $y) {
								$y = $tmp['y'];
								$k = $key;
							}
							
							$tmp['image'] = getAttachmentUrl($tmp['image']);
							if (!empty($tmp['url']) && stripos($tmp['url'], option('config.site_url')) !== false) {
								$tmp['url'] = redirect_store($seller_id, $tmp['url']);
							} else if (empty($tmp['url'])) {
								$tmp['url'] = 'javascript: void(0)';
							}
							
							$content[] = $tmp;
						}
						
						$botton_content = $content[$k];
						unset($content[$k]);
						$content[] = $botton_content;
					}
					$content = array_merge($content);
					
					$field['content'] = $content;
					break;
				case 'map':
					$store_contact = D('Store_contact')->where(array('store_id' => $store_id))->find();
					if (empty($store_contact)) {
						continue;
					}
					
					$area = new area();
					
					$content = array();
					$content['address'] = $store_contact['address'];
					$content['lng'] = $store_contact['long'];
					$content['lat'] = $store_contact['lat'];
					$content['province'] = $area->get_name($store_contact['province']);
					$content['city'] = $area->get_name($store_contact['city']);
					$content['area'] = $area->get_name($store_contact['county']);
					$content['store_name'] = $store_contact['entity_name'];// $fx_store['name'];
					$content['entity_name'] = $store_contact['entity_name'];
					
					$field['content'] = $content;
					break;
				default :
					$field = $value;
			}
			
			if ($value['field_type'] == 'component') {
				$return = array_merge_recursive ($return, $field);
			} else {
				$return[] = $field;
			}
		}
		
		$return_data = array();
		$goods_group_3 = array();
		foreach ($return as $tmp) {
			if ($tmp['field_type'] == 'goods_group3') {
				$goods_group_3 = $tmp;
			} else {
				$return_data[] = $tmp;
			}
		}
		if (!empty($goods_group_3)) {
			$return_data[] = $goods_group_3;
		}
		
		return $return_data;
	}
}

/*富文本内容，url替换*/
function richTextFormat($matches, $store_id) {
	return 'href="' . $matches . '"';
}