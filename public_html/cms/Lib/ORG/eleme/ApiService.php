<?php
require 'RpcService.php';

/**
 * 商户服务
 */
class ApiService extends RpcService
{

    /** 获取商户账号信息
    
     * @return mixed
     */
    public function get_user()
    {
        return $this->client->call("eleme.user.getUser", array());
    }

    /** 获取当前授权账号的手机号,特权接口仅部分帐号可以调用
    
     * @return mixed
     */
    public function get_phone_number()
    {
        return $this->client->call("eleme.user.getPhoneNumber", array());
    }
    
    /** 查询店铺信息
     * @param $shop_id 店铺Id
     * @return mixed
     */
    public function get_shop($shop_id)
    {
        return $this->client->call("eleme.shop.getShop", array("shopId" => $shop_id));
    }
    
    /** 更新店铺基本信息
     * @param $shop_id 店铺Id
     * @param $properties 店铺属性
     * @return mixed
     */
    public function update_shop($shop_id, $properties)
    {
        return $this->client->call("eleme.shop.updateShop", array("shopId" => $shop_id, "properties" => $properties));
    }
    
    /** 批量获取店铺简要
     * @param $shop_ids 店铺Id的列表
     * @return mixed
     */
    public function mget_shop_status($shop_ids)
    {
        return $this->client->call("eleme.shop.mgetShopStatus", array("shopIds" => $shop_ids));
    }
    
    /** 设置送达时间
     * @param $shop_id 店铺Id
     * @param $delivery_basic_mins 配送基准时间(单位分钟)
     * @param $delivery_adjust_mins 配送调整时间(单位分钟)
     * @return mixed
     */
    public function set_delivery_time($shop_id, $delivery_basic_mins, $delivery_adjust_mins)
    {
        return $this->client->call("eleme.shop.setDeliveryTime", array("shopId" => $shop_id, "deliveryBasicMins" => $delivery_basic_mins, "deliveryAdjustMins" => $delivery_adjust_mins));
    }
    
    /** 设置是否支持在线退单
     * @param $shop_id 店铺Id
     * @param $enable 是否支持
     * @return mixed
     */
    public function set_online_refund($shop_id, $enable)
    {
        return $this->client->call("eleme.shop.setOnlineRefund", array("shopId" => $shop_id, "enable" => $enable));
    }
    
    /** open_a_p_i 查询近2周的评论
     * @param $shop_id 店铺Id
     * @param $offset 分页偏移
     * @param $limit 单页数据
     * @return mixed
     */
    public function query_order_comments($shop_id, $offset, $limit)
    {
        return $this->client->call("eleme.ugc.queryOrderComments", array("shopId" => $shop_id, "offset" => $offset, "limit" => $limit));
    }
    
    /** open_a_p_i 查询近2周的评论数量
     * @param $shop_id 店铺Id
     * @return mixed
     */
    public function count_order_comments($shop_id)
    {
        return $this->client->call("eleme.ugc.countOrderComments", array("shopId" => $shop_id));
    }
    
    /** open_a_p_i 回复评论接口
     * @param $shop_id 店铺Id
     * @param $comment_id 评论id
     * @param $content 回复内容
     * @param $replier_name 回复人
     * @return mixed
     */
    public function reply_order_comment($shop_id, $comment_id, $content, $replier_name)
    {
        return $this->client->call("eleme.ugc.replyOrderComment", array("shopId" => $shop_id, "commentId" => $comment_id, "content" => $content, "replierName" => $replier_name));
    }
    
    /** 查询店铺商品分类
     * @param $shop_id 店铺Id
     * @return mixed
     */
    public function get_shop_categories($shop_id)
    {
        return $this->client->call("eleme.product.category.getShopCategories", array("shopId" => $shop_id));
    }
    
    /** 查询店铺商品分类，包含二级分类
     * @param $shop_id 店铺Id
     * @return mixed
     */
    public function get_shop_categories_with_children($shop_id)
    {
        return $this->client->call("eleme.product.category.getShopCategoriesWithChildren", array("shopId" => $shop_id));
    }
    
    /** 查询商品分类详情
     * @param $category_id 商品分类Id
     * @return mixed
     */
    public function get_category($category_id)
    {
        return $this->client->call("eleme.product.category.getCategory", array("categoryId" => $category_id));
    }
    
    /** 查询商品分类详情，包含二级分类
     * @param $category_id 商品分类Id
     * @return mixed
     */
    public function get_category_with_children($category_id)
    {
        return $this->client->call("eleme.product.category.getCategoryWithChildren", array("categoryId" => $category_id));
    }
    
    /** 添加商品分类
     * @param $shop_id 店铺Id
     * @param $name 商品分类名称，长度需在50字以内
     * @param $description 商品分类描述，长度需在50字以内
     * @return mixed
     */
    public function create_category($shop_id, $name, $description)
    {
        return $this->client->call("eleme.product.category.createCategory", array("shopId" => $shop_id, "name" => $name, "description" => $description));
    }
    
    /** 添加商品分类，支持二级分类
     * @param $shop_id 店铺Id
     * @param $name 商品分类名称，长度需在50字以内
     * @param $parent_id 父分类ID，如果没有可以填0
     * @param $description 商品分类描述，长度需在50字以内
     * @return mixed
     */
    public function create_category_with_children($shop_id, $name, $parent_id, $description)
    {
        return $this->client->call("eleme.product.category.createCategoryWithChildren", array("shopId" => $shop_id, "name" => $name, "parentId" => $parent_id, "description" => $description));
    }
    
    /** 更新商品分类
     * @param $category_id 商品分类Id
     * @param $name 商品分类名称，长度需在50字以内
     * @param $description 商品分类描述，长度需在50字以内
     * @return mixed
     */
    public function update_category($category_id, $name, $description)
    {
        return $this->client->call("eleme.product.category.updateCategory", array("categoryId" => $category_id, "name" => $name, "description" => $description));
    }
    
    /** 更新商品分类，包含二级分类
     * @param $category_id 商品分类Id
     * @param $name 商品分类名称，长度需在50字以内
     * @param $parent_id 父分类ID，如果没有可以填0
     * @param $description 商品分类描述，长度需在50字以内
     * @return mixed
     */
    public function update_category_with_children($category_id, $name, $parent_id, $description)
    {
        return $this->client->call("eleme.product.category.updateCategoryWithChildren", array("categoryId" => $category_id, "name" => $name, "parentId" => $parent_id, "description" => $description));
    }
    
    /** 删除商品分类
     * @param $category_id 商品分类Id
     * @return mixed
     */
    public function remove_category($category_id)
    {
        return $this->client->call("eleme.product.category.removeCategory", array("categoryId" => $category_id));
    }
    
    /** 设置分类排序
     * @param $shop_id 饿了么店铺Id
     * @param $category_ids 需要排序的分类Id
     * @return mixed
     */
    public function set_category_positions($shop_id, $category_ids)
    {
        return $this->client->call("eleme.product.category.setCategoryPositions", array("shopId" => $shop_id, "categoryIds" => $category_ids));
    }
    
    /** 设置二级分类排序
     * @param $shop_id 饿了么店铺Id
     * @param $category_with_children_ids 需要排序的父分类Id，及其下属的二级分类ID
     * @return mixed
     */
    public function set_category_positions_with_children($shop_id, $category_with_children_ids)
    {
        return $this->client->call("eleme.product.category.setCategoryPositionsWithChildren", array("shopId" => $shop_id, "categoryWithChildrenIds" => $category_with_children_ids));
    }
    
    /** 查询商品后台类目
     * @param $shop_id 店铺Id
     * @return mixed
     */
    public function get_back_category($shop_id)
    {
        return $this->client->call("eleme.product.category.getBackCategory", array("shopId" => $shop_id));
    }
    
    /** 上传图片，返回图片的hash值
     * @param $image 文件内容base64编码值
     * @return mixed
     */
    public function upload_image($image)
    {
        return $this->client->call("eleme.file.uploadImage", array("image" => $image));
    }
    
    /** 通过远程_u_r_l上传图片，返回图片的hash值
     * @param $url 远程Url地址
     * @return mixed
     */
    public function upload_image_with_remote_url($url)
    {
        return $this->client->call("eleme.file.uploadImageWithRemoteUrl", array("url" => $url));
    }
    
    /** 获取上传文件的访问_u_r_l，返回文件的_url地址
     * @param $hash 图片hash值
     * @return mixed
     */
    public function get_uploaded_url($hash)
    {
        return $this->client->call("eleme.file.getUploadedUrl", array("hash" => $hash));
    }
    
    /** 获取一个分类下的所有商品
     * @param $category_id 商品分类Id
     * @return mixed
     */
    public function get_items_by_category_id($category_id)
    {
        return $this->client->call("eleme.product.item.getItemsByCategoryId", array("categoryId" => $category_id));
    }
    
    /** 查询商品详情
     * @param $item_id 商品Id
     * @return mixed
     */
    public function get_item($item_id)
    {
        return $this->client->call("eleme.product.item.getItem", array("itemId" => $item_id));
    }
    
    /** 批量查询商品详情
     * @param $item_ids 商品Id的列表
     * @return mixed
     */
    public function batch_get_items($item_ids)
    {
        return $this->client->call("eleme.product.item.batchGetItems", array("itemIds" => $item_ids));
    }
    
    /** 添加商品
     * @param $category_id 商品分类Id
     * @param $properties 商品属性
     * @return mixed
     */
    public function create_item($category_id, $properties)
    {
        return $this->client->call("eleme.product.item.createItem", array("categoryId" => $category_id, "properties" => $properties));
    }
    
    /** 批量添加商品
     * @param $category_id 商品分类Id
     * @param $items 商品属性的列表
     * @return mixed
     */
    public function batch_create_items($category_id, $items)
    {
        return $this->client->call("eleme.product.item.batchCreateItems", array("categoryId" => $category_id, "items" => $items));
    }
    
    /** 更新商品
     * @param $item_id 商品Id
     * @param $category_id 商品分类Id
     * @param $properties 商品属性
     * @return mixed
     */
    public function update_item($item_id, $category_id, $properties)
    {
        return $this->client->call("eleme.product.item.updateItem", array("itemId" => $item_id, "categoryId" => $category_id, "properties" => $properties));
    }
    
    /** 批量置满库存
     * @param $spec_ids 商品及商品规格的列表
     * @return mixed
     */
    public function batch_fill_stock($spec_ids)
    {
        return $this->client->call("eleme.product.item.batchFillStock", array("specIds" => $spec_ids));
    }
    
    /** 批量沽清库存
     * @param $spec_ids 商品及商品规格的列表
     * @return mixed
     */
    public function batch_clear_stock($spec_ids)
    {
        return $this->client->call("eleme.product.item.batchClearStock", array("specIds" => $spec_ids));
    }
    
    /** 批量上架商品
     * @param $spec_ids 商品及商品规格的列表
     * @return mixed
     */
    public function batch_on_shelf($spec_ids)
    {
        return $this->client->call("eleme.product.item.batchOnShelf", array("specIds" => $spec_ids));
    }
    
    /** 批量下架商品
     * @param $spec_ids 商品及商品规格的列表
     * @return mixed
     */
    public function batch_off_shelf($spec_ids)
    {
        return $this->client->call("eleme.product.item.batchOffShelf", array("specIds" => $spec_ids));
    }
    
    /** 删除商品
     * @param $item_id 商品Id
     * @return mixed
     */
    public function remove_item($item_id)
    {
        return $this->client->call("eleme.product.item.removeItem", array("itemId" => $item_id));
    }
    
    /** 批量删除商品
     * @param $item_ids 商品Id的列表
     * @return mixed
     */
    public function batch_remove_items($item_ids)
    {
        return $this->client->call("eleme.product.item.batchRemoveItems", array("itemIds" => $item_ids));
    }
    
    /** 批量更新商品库存
     * @param $spec_stocks 商品以及规格库存列表
     * @return mixed
     */
    public function batch_update_spec_stocks($spec_stocks)
    {
        return $this->client->call("eleme.product.item.batchUpdateSpecStocks", array("specStocks" => $spec_stocks));
    }
    
    /** 设置商品排序
     * @param $category_id 商品分类Id
     * @param $item_ids 商品Id列表
     * @return mixed
     */
    public function set_item_positions($category_id, $item_ids)
    {
        return $this->client->call("eleme.product.item.setItemPositions", array("categoryId" => $category_id, "itemIds" => $item_ids));
    }
    
    /** 批量沽清库存并在次日2:00开始置满
     * @param $clear_stocks 店铺Id及商品Id的列表
     * @return mixed
     */
    public function clear_and_timing_max_stock($clear_stocks)
    {
        return $this->client->call("eleme.product.item.clearAndTimingMaxStock", array("clearStocks" => $clear_stocks));
    }
    
    /** 根据商品扩展码获取商品
     * @param $shop_id 店铺Id
     * @param $extend_code 商品扩展码
     * @return mixed
     */
    public function get_item_by_shop_id_and_extend_code($shop_id, $extend_code)
    {
        return $this->client->call("eleme.product.item.getItemByShopIdAndExtendCode", array("shopId" => $shop_id, "extendCode" => $extend_code));
    }
    
    /** 根据商品条形码获取商品
     * @param $shop_id 店铺Id
     * @param $bar_code 商品条形码
     * @return mixed
     */
    public function get_items_by_shop_id_and_bar_code($shop_id, $bar_code)
    {
        return $this->client->call("eleme.product.item.getItemsByShopIdAndBarCode", array("shopId" => $shop_id, "barCode" => $bar_code));
    }
    
    /** 批量修改商品价格
     * @param $shop_id 店铺Id
     * @param $spec_prices 商品Id及其下SkuId和价格对应Map(限制最多50个)
     * @return mixed
     */
    public function batch_update_prices($shop_id, $spec_prices)
    {
        return $this->client->call("eleme.product.item.batchUpdatePrices", array("shopId" => $shop_id, "specPrices" => $spec_prices));
    }
    
    /** 查询活动商品
     * @param $shop_id 店铺Id
     * @return mixed
     */
    public function get_item_ids_has_activity_by_shop_id($shop_id)
    {
        return $this->client->call("eleme.product.item.getItemIdsHasActivityByShopId", array("shopId" => $shop_id));
    }
    
    /** 查询店铺当前生效合同类型
     * @param $shop_id 店铺id
     * @return mixed
     */
    public function get_effect_service_pack_contract($shop_id)
    {
        return $this->client->call("eleme.packs.getEffectServicePackContract", array("shopId" => $shop_id));
    }
    
    /** 获取订单
     * @param $order_id 订单Id
     * @return mixed
     */
    public function get_order($order_id)
    {
        return $this->client->call("eleme.order.getOrder", array("orderId" => $order_id));
    }
    
    /** 批量获取订单
     * @param $order_ids 订单Id的列表
     * @return mixed
     */
    public function mget_orders($order_ids)
    {
        return $this->client->call("eleme.order.mgetOrders", array("orderIds" => $order_ids));
    }
    
    /** 确认订单(推荐)
     * @param $order_id 订单Id
     * @return mixed
     */
    public function confirm_order_lite($order_id)
    {
        return $this->client->call("eleme.order.confirmOrderLite", array("orderId" => $order_id));
    }
    
    /** 确认订单
     * @param $order_id 订单Id
     * @return mixed
     */
    public function confirm_order($order_id)
    {
        return $this->client->call("eleme.order.confirmOrder", array("orderId" => $order_id));
    }
    
    /** 取消订单(推荐)
     * @param $order_id 订单Id
     * @param $type 取消原因
     * @param $remark 备注说明
     * @return mixed
     */
    public function cancel_order_lite($order_id, $type, $remark)
    {
        return $this->client->call("eleme.order.cancelOrderLite", array("orderId" => $order_id, "type" => $type, "remark" => $remark));
    }
    
    /** 取消订单
     * @param $order_id 订单Id
     * @param $type 取消原因
     * @param $remark 备注说明
     * @return mixed
     */
    public function cancel_order($order_id, $type, $remark)
    {
        return $this->client->call("eleme.order.cancelOrder", array("orderId" => $order_id, "type" => $type, "remark" => $remark));
    }
    
    /** 同意退单/同意取消单(推荐)
     * @param $order_id 订单Id
     * @return mixed
     */
    public function agree_refund_lite($order_id)
    {
        return $this->client->call("eleme.order.agreeRefundLite", array("orderId" => $order_id));
    }
    
    /** 同意退单/同意取消单
     * @param $order_id 订单Id
     * @return mixed
     */
    public function agree_refund($order_id)
    {
        return $this->client->call("eleme.order.agreeRefund", array("orderId" => $order_id));
    }
    
    /** 不同意退单/不同意取消单(推荐)
     * @param $order_id 订单Id
     * @param $reason 商家不同意退单原因
     * @return mixed
     */
    public function disagree_refund_lite($order_id, $reason)
    {
        return $this->client->call("eleme.order.disagreeRefundLite", array("orderId" => $order_id, "reason" => $reason));
    }
    
    /** 不同意退单/不同意取消单
     * @param $order_id 订单Id
     * @param $reason 商家不同意退单原因
     * @return mixed
     */
    public function disagree_refund($order_id, $reason)
    {
        return $this->client->call("eleme.order.disagreeRefund", array("orderId" => $order_id, "reason" => $reason));
    }
    
    /** 获取订单配送记录
     * @param $order_id 订单Id
     * @return mixed
     */
    public function get_delivery_state_record($order_id)
    {
        return $this->client->call("eleme.order.getDeliveryStateRecord", array("orderId" => $order_id));
    }
    
    /** 批量获取订单最新配送记录
     * @param $order_ids 订单Id列表
     * @return mixed
     */
    public function batch_get_delivery_states($order_ids)
    {
        return $this->client->call("eleme.order.batchGetDeliveryStates", array("orderIds" => $order_ids));
    }
    
    /** 配送异常或者物流拒单后选择自行配送(推荐)
     * @param $order_id 订单Id
     * @return mixed
     */
    public function delivery_by_self_lite($order_id)
    {
        return $this->client->call("eleme.order.deliveryBySelfLite", array("orderId" => $order_id));
    }
    
    /** 配送异常或者物流拒单后选择自行配送
     * @param $order_id 订单Id
     * @return mixed
     */
    public function delivery_by_self($order_id)
    {
        return $this->client->call("eleme.order.deliveryBySelf", array("orderId" => $order_id));
    }
    
    /** 配送异常或者物流拒单后选择不再配送(推荐)
     * @param $order_id 订单Id
     * @return mixed
     */
    public function no_more_delivery_lite($order_id)
    {
        return $this->client->call("eleme.order.noMoreDeliveryLite", array("orderId" => $order_id));
    }
    
    /** 配送异常或者物流拒单后选择不再配送
     * @param $order_id 订单Id
     * @return mixed
     */
    public function no_more_delivery($order_id)
    {
        return $this->client->call("eleme.order.noMoreDelivery", array("orderId" => $order_id));
    }
    
    /** 订单确认送达(推荐)
     * @param $order_id 订单ID
     * @return mixed
     */
    public function received_order_lite($order_id)
    {
        return $this->client->call("eleme.order.receivedOrderLite", array("orderId" => $order_id));
    }
    
    /** 订单确认送达
     * @param $order_id 订单ID
     * @return mixed
     */
    public function received_order($order_id)
    {
        return $this->client->call("eleme.order.receivedOrder", array("orderId" => $order_id));
    }
    
    /** 回复催单
     * @param $remind_id 催单Id
     * @param $type 回复类别
     * @param $content 回复内容,如果type为custom,content必填,回复内容不能超过30个字符
     * @return mixed
     */
    public function reply_reminder($remind_id, $type, $content)
    {
        return $this->client->call("eleme.order.replyReminder", array("remindId" => $remind_id, "type" => $type, "content" => $content));
    }
    
    /** 获取指定订单菜品活动价格.
     * @param $order_id 订单Id
     * @return mixed
     */
    public function get_commodities($order_id)
    {
        return $this->client->call("eleme.order.getCommodities", array("orderId" => $order_id));
    }
    
    /** 批量获取订单菜品活动价格
     * @param $order_ids 订单Id列表
     * @return mixed
     */
    public function mget_commodities($order_ids)
    {
        return $this->client->call("eleme.order.mgetCommodities", array("orderIds" => $order_ids));
    }
    
    /** 获取订单退款信息
     * @param $order_id 订单Id
     * @return mixed
     */
    public function get_refund_order($order_id)
    {
        return $this->client->call("eleme.order.getRefundOrder", array("orderId" => $order_id));
    }
    
    /** 批量获取订单退款信息
     * @param $order_ids 订单Id列表
     * @return mixed
     */
    public function mget_refund_orders($order_ids)
    {
        return $this->client->call("eleme.order.mgetRefundOrders", array("orderIds" => $order_ids));
    }
    
    /** 取消呼叫配送
     * @param $order_id 订单Id
     * @return mixed
     */
    public function cancel_delivery($order_id)
    {
        return $this->client->call("eleme.order.cancelDelivery", array("orderId" => $order_id));
    }
    
    /** 呼叫配送
     * @param $order_id 订单Id
     * @param $fee 小费,1-8之间的整数
     * @return mixed
     */
    public function call_delivery($order_id, $fee)
    {
        return $this->client->call("eleme.order.callDelivery", array("orderId" => $order_id, "fee" => $fee));
    }
    
    /** 获取店铺未回复的催单
     * @param $shop_id 店铺id
     * @return mixed
     */
    public function get_unreply_reminders($shop_id)
    {
        return $this->client->call("eleme.order.getUnreplyReminders", array("shopId" => $shop_id));
    }
    
    /** 查询店铺未处理订单
     * @param $shop_id 店铺id
     * @return mixed
     */
    public function get_unprocess_orders($shop_id)
    {
        return $this->client->call("eleme.order.getUnprocessOrders", array("shopId" => $shop_id));
    }
    
    /** 查询店铺未处理的取消单
     * @param $shop_id 店铺id
     * @return mixed
     */
    public function get_cancel_orders($shop_id)
    {
        return $this->client->call("eleme.order.getCancelOrders", array("shopId" => $shop_id));
    }
    
    /** 查询店铺未处理的退单
     * @param $shop_id 店铺id
     * @return mixed
     */
    public function get_refund_orders($shop_id)
    {
        return $this->client->call("eleme.order.getRefundOrders", array("shopId" => $shop_id));
    }
    
    /** 查询全部订单
     * @param $shop_id 店铺id
     * @param $page_no 页码。取值范围:大于零的整数最大限制为100
     * @param $page_size 每页获取条数。最小值1，最大值50。
     * @param $date 日期,默认当天,格式:yyyy-MM-dd
     * @return mixed
     */
    public function get_all_orders($shop_id, $page_no, $page_size, $date)
    {
        return $this->client->call("eleme.order.getAllOrders", array("shopId" => $shop_id, "pageNo" => $page_no, "pageSize" => $page_size, "date" => $date));
    }
    
    /** 获取未到达的推送消息
     * @param $app_id 应用ID
     * @return mixed
     */
    public function get_non_reached_messages($app_id)
    {
        return $this->client->call("eleme.message.getNonReachedMessages", array("appId" => $app_id));
    }
    
    /** 获取未到达的推送消息实体
     * @param $app_id 应用ID
     * @return mixed
     */
    public function get_non_reached_o_messages($app_id)
    {
        return $this->client->call("eleme.message.getNonReachedOMessages", array("appId" => $app_id));
    }
    
    /** 查询商户余额,返回可用余额和总余额
     * @param $shop_id 饿了么店铺Id
     * @return mixed
     */
    public function query_balance($shop_id)
    {
        return $this->client->call("eleme.finance.queryBalance", array("shopId" => $shop_id));
    }
    
    /** 查询余额流水,有流水改动的交易
     * @param $request 查询条件
     * @return mixed
     */
    public function query_balance_log($request)
    {
        return $this->client->call("eleme.finance.queryBalanceLog", array("request" => $request));
    }

}