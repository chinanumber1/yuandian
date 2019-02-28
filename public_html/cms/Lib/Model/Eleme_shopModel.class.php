<?php
class Eleme_shopModel extends Model
{
    public function getElemeObj($storeId)
    {
        $shop = $this->field(true)->where(array('store_id' => $storeId))->find();
        if (empty($shop)) {
            return false;
        }
        
        import('@.ORG.Eleme');
        $eleme = new Eleme();
        if ($shop['expires_in'] - time() < 600) {
            $token = $eleme->getTokenByRefreshToken($shop['refresh_token']);
            if (isset($token['access_token']) && $token['access_token']) {
                $data = array();
                $data['access_token'] = $token['access_token'];
                $data['refresh_token'] = $token['refresh_token'];
                $data['expires_in'] = $token['expires_in'] + time();
                $shop['access_token'] = $token['access_token'];
                $this->where(array('userId' => $shop['userId']))->save($data);
            }
        }
        $eleme->setToken($shop['access_token']);
        return $eleme;
    }
}
?>