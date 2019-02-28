<?php

/*
 * 数据修正控制器
 *
 * @  Writers    LiHongShun
 * @  BuildTime  2015/06/13
 * 一定要先运行 /index.php?g=Index&&c=Ureply11&a=lhsrrx
 * 再运行 /index.php?g=Index&&c=Ureply11&a=merscore
 * 再运行 /index.php?g=Index&&c=Ureply11&a=merstoreismain
 */

class Ureply11Action extends BaseAction {

    public function index() {
        echo "";
    }

    /*     * ****要先运行lhsrrx方法在运行 merscore方法  不然运行merscore时 会没值********* */

    public function lhsrrx() {
        /* ini_set('max_execution_time', '120'); */
        set_time_limit('60');
        $Db = new Model();
        $DB_PREFIX = C('DB_PREFIX');
        /*         * ***更新团够评论的强大 sql********
          update pigcms_reply,pigcms_group_order set pigcms_reply.mer_id=pigcms_group_order.mer_id ,  pigcms_reply.store_id=pigcms_group_order.store_id
          where pigcms_reply.order_id  = pigcms_group_order.order_id AND pigcms_reply.order_type='0'** */
        $sql1 = "update {$DB_PREFIX}reply,{$DB_PREFIX}group_order set {$DB_PREFIX}reply.mer_id={$DB_PREFIX}group_order.mer_id , {$DB_PREFIX}reply.store_id={$DB_PREFIX}group_order.store_id
		where {$DB_PREFIX}reply.order_id  = {$DB_PREFIX}group_order.order_id AND {$DB_PREFIX}reply.order_type='0'";
        echo "开始执行评论表团够部分数据订正<br/>";
        echo "===================================================================<br/>";
        $Db->query($sql1);
        //echo $Db->getLastSql();
        echo "程序睡两秒=============<br/>";
        sleep(2);
        echo "程序醒来继续执行============<br/>";
        //echo $sql1;
        /*         * ***更新订餐评论的强大 sql********
          update pigcms_reply, pigcms_meal_order set pigcms_reply.mer_id= pigcms_meal_order.mer_id ,  pigcms_reply.store_id= pigcms_meal_order.store_id
          where pigcms_reply.order_id  =  pigcms_meal_order.order_id AND pigcms_reply.order_type='1'** */
        $sql2 = "update {$DB_PREFIX}reply, {$DB_PREFIX}meal_order set {$DB_PREFIX}reply.mer_id= {$DB_PREFIX}meal_order.mer_id ,  {$DB_PREFIX}reply.store_id= {$DB_PREFIX}meal_order.store_id
		where {$DB_PREFIX}reply.order_id  =  {$DB_PREFIX}meal_order.order_id AND {$DB_PREFIX}reply.order_type='1'";
        //echo '<br/>'.$sql2;
        echo "开始执行评论表订餐部分数据订正<br/>";
        echo "===================================================================<br/>";
        $Db->query($sql2);
        echo "<br/>";
        echo "数据订正完成，谢谢您的执行。再见！<br/>";
    }

    public function merscore() {
        set_time_limit('60');
        $Db = new Model();
        $DB_PREFIX = C('DB_PREFIX');
        $sql1 = "SELECT `mer_id` , `store_id` , sum( score ) AS ts, count( pigcms_id ) AS tt FROM {$DB_PREFIX}reply GROUP BY `store_id`";
        $ret = $Db->query($sql1);
        $inser_Db = D('Merchant_score');
        if (!empty($ret)) {
            foreach ($ret as $vv) {
                $tmp = array('parent_id' => $vv['store_id'], 'type' => 2, 'score_all' => $vv['ts'], 'reply_count' => $vv['tt']);
                $inser_Db->add($tmp);
            }
        }

        $sql2 = "SELECT `mer_id` , `store_id` , sum( score ) AS ts, count( pigcms_id ) AS tt FROM {$DB_PREFIX}reply GROUP BY `mer_id`";
        $rets = $Db->query($sql2);
        if (!empty($rets)) {
            foreach ($rets as $vm) {
                $tmpe = array('parent_id' => $vm['mer_id'], 'type' => 1, 'score_all' => $vm['ts'], 'reply_count' => $vm['tt']);
                $inser_Db->add($tmpe);
            }
        }

        echo "数据订正完成，谢谢您的执行。再见！<br/>";
    }

    /*     * ********跑主店信息***************** */

    public function merstoreismain() {
        set_time_limit('60');
        /*         * *强大的 替换 SQL 更新语句
          UPDATE `pigcms_merchant_store` SET `ismain` = '1' ,`weixin` = 'hefei_live', `qq` = '800022936',   `permoney` = ROUND(RAND() * 20 + 30), `feature` = '以满足客户需求为主', `trafficroute` = '公交车路线： 18路 43路 64路 129路 502路到翠澜站下' where store_id in( SELECT store_id FROM `pigcms_merchant_store_tmp` group by `mer_id` order by mer_id ASC ,`store_id` ASC)** */
        $Db = new Model();
        $DB_PREFIX = C('DB_PREFIX');

		$sql2 = "SELECT mer_id FROM {$DB_PREFIX}merchant_store where ismain=1 group by `mer_id`";
        $tmpret = $Db->query($sql2);
		$mer_tmp=array();
		if(!empty($tmpret)){
			foreach($tmpret as $mvv){
		      $mer_tmp[]=$mvv['mer_id'];
			}
		}
		$sql1 = "SELECT * FROM {$DB_PREFIX}merchant_store  group by `mer_id` order by mer_id ASC ,`store_id` ASC";
        $ret = $Db->query($sql1);
		$m=$e=0;
        if (!empty($ret)) {
			$store_tmpDb=M('Merchant_store');
            foreach ($ret as $vv) {
                if (!($vv['ismain'] == 1) && !in_array($vv['mer_id'],$mer_tmp)) {
                    $updatearr = array('ismain' => 1, 'weixin' => 'hefei_live', 'qq' => '800022936', 'permoney' => rand(30, 50), 'feature' => '以满足客户需求为主', 'trafficroute' => '公交车路线： 18路 43路 64路 129路 502路到翠澜站下');
                    $flage = $store_tmpDb->where(array('store_id' => $vv['store_id'], 'mer_id' => $vv['mer_id']))->save($updatearr);
                    if ($flage) {
                        echo "成功 store_id => " . $vv['store_id'].'<br/>';
						$m++;
                    } else {
                        echo "失败 store_id => " . $vv['store_id'].'<br/>';
						$e++;
                    }
                }
            }
			echo '成功 '.$m.' 条<br/>';
			echo '失败 '.$e.' 条<br/>';
        }
    }

}
