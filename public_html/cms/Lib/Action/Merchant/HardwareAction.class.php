<?php
/**
 * 硬件设置
 **/
class HardwareAction extends BaseAction
{
	public function index()
	{
	    $where = array('mer_id' => $this->merchant_session['mer_id']);
	    if ($this->merchant_session['store_id']) {
	        $where['store_id'] = $this->merchant_session['store_id'];
	    }
		$list = D('Orderprinter')->field(true)->where($where)->select();
		$stores = D('Merchant_store')->field('store_id, name')->where($where)->select();
		$tmp = array();
		foreach ($stores as $s) {
			$tmp[$s['store_id']] = $s;
		}
		$names = array('下单成功后打印', '支付成功后打印', '验证消费后打印', '退款成功后打印', '打印总单', '接单成功后打印（快店）');
		foreach ($list as &$o) {
			if ($o['is_main']) {
				$o['name'] .= '(主打印机)';
			} else {
				$o['name'] = $o['name'] ? $o['name'] : '打印机-' . $o['pigcms_id'];
			}
			$o['str'] = '';
			if ($o['paid'] != '') {
				$o['paid'] = explode(',', $o['paid']);
				foreach ($o['paid'] as $id) {
					$o['str'] .= '【' . $names[$id] . '】';
				}
			}
			
			$o['store_name'] = isset($tmp[$o['store_id']]['name']) ? $tmp[$o['store_id']]['name'] : '';
		}
		$this->assign('list', $list);
		$this->display();		
	}
	
	public function addprint()
	{
		
		$pigcms_id = isset($_REQUEST['pigcms_id']) ? intval($_REQUEST['pigcms_id']) : 0;
		$pigcms_id && $orderprint = D('Orderprinter')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'pigcms_id' => $pigcms_id))->find();
		if (IS_POST) {
		    if ($this->merchant_session['store_id']) {
		        $_POST['store_id'] = $this->merchant_session['store_id'];
		    }
			$data['print_type'] = isset($_POST['print_type']) ? intval($_POST['print_type']) : 0;
			$data['feie_ukey'] = isset($_POST['feie_ukey']) ? $_POST['feie_ukey'] : '';
			$data['feie_user'] = isset($_POST['feie_user']) ? $_POST['feie_user'] : '';
			$data['store_id'] = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
			$data['username'] = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
			$data['name'] = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
			$data['mp'] = isset($_POST['mp']) ? htmlspecialchars($_POST['mp']) : '';
			$data['mcode'] = isset($_POST['mcode']) ? htmlspecialchars($_POST['mcode']) : '';
			$data['mkey'] = isset($_POST['mkey']) ? htmlspecialchars($_POST['mkey']) : '';
			$data['qrcode'] = isset($_POST['qrcode']) ? $_POST['qrcode'] : '';
			$data['count'] = isset($_POST['count']) ? intval($_POST['count']) : 1;
			$data['is_main'] = isset($_POST['is_main']) ? intval($_POST['is_main']) : 0;
			$data['image'] = isset($_POST['image']) ? intval($_POST['image']) : 0;
			$data['paper'] = isset($_POST['paper']) ? intval($_POST['paper']) : 0;
			$data['is_big'] = isset($_POST['is_big']) ? intval($_POST['is_big']) : 0;
			$data['paid'] = isset($_POST['paid']) ? $_POST['paid'] : '';
			$data['mer_id'] = intval($this->merchant_session['mer_id']);
			if ($data['count'] < 1 || $data['count'] > 100) {
			    $this->error('份数在1~100之间的数字');
			}
			if ($data['paid']) $data['paid'] = implode(',', $data['paid']);
			//if ($tobj = D('Orderprinter')->field(true)->where("`mer_id`='{$this->merchant_session['mer_id']}' AND `store_id`='{$data['store_id']}' AND `pigcms_id`<>'{$pigcms_id}'")->find()) $this->error('该店铺已经添加了打印机');
			if ($orderprint) {
				D('Orderprinter')->where(array('mer_id' => $this->merchant_session['mer_id'], 'pigcms_id' => $pigcms_id))->save($data);
			} else {
				D('Orderprinter')->add($data);
			}
			$this->success('操作成功', U('Hardware/index'));
		} else {
		    $where = array('mer_id' => $this->merchant_session['mer_id'], 'status' => 1);
		    if ($this->merchant_session['store_id']) {
		        $where['store_id'] = $this->merchant_session['store_id'];
		    }
	        $stores = D('Merchant_store')->field('store_id, name')->where($where)->select();
			
			$orderprint['paid'] != '' && $orderprint['paid'] = explode(',', $orderprint['paid']);
			$this->assign('orderprint', $orderprint);
			$this->assign('stores', $stores);
			$this->display();
		}
	}
	
	public function print_status()
	{
		$pigcms_id = intval($_POST['id']);
		$database = D('Orderprinter');
		if (!($orderprint = $database->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'pigcms_id' => $pigcms_id))->find())){
			exit('0');
		}
		
		$condition['pigcms_id'] = $pigcms_id;
		$data['is_main'] = $_POST['type'] == 'open' ? 1 : 0;
		if($database->where($condition)->data($data)->save()){
			exit('1');
		}else{
			exit;
		}
	}
	
	public function delprint()
	{
		$pigcms_id = isset($_GET['pigcms_id']) ? intval($_GET['pigcms_id']) : 0;
		if ($orderprint = D('Orderprinter')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'pigcms_id' => $pigcms_id))->find()) {
			D('Orderprinter')->where(array('mer_id' => $this->merchant_session['mer_id'], 'pigcms_id' => $pigcms_id))->delete();
			$this->success('打印机删除设置成功', U('Hardware/index'));
		} else {
			$this->error('不合法的参数请求', U('Hardware/index'));
		}
	}
	
	/* 微信WIFI */
	public function wifi(){
		$this->display();
	}
}
?>