<?php
class plan_eleme_refresh_token extends plan_base
{
	public function runTask()
	{
		$time = time() - 600;//提前十分钟刷新token
		
		$elemeShop = M('Eleme_shop');
		
		$shops = $elemeShop->field(true)->where(array('expires_in' => array('lt', $time)))->group('userId')->limit(10)->select();
		if (empty($shops)) {
		    return true;
		}
		import('@.ORG.Eleme');
		$eleme = new Eleme();
		foreach ($shops as $shop) {
		    $token = $eleme->getTokenByRefreshToken($shop['refresh_token']);
		    if (isset($token['access_token']) && $token['access_token']) {
		        $data = array();
		        $data['access_token'] = $token['access_token'];
		        $data['refresh_token'] = $token['refresh_token'];
		        $data['expires_in'] = $token['expires_in'] + time();
		        $elemeShop->where(array('userId' => $shop['userId']))->save($data);
		    }
		}
		return true;
	}
}
?>