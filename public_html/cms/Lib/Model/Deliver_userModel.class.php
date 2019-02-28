<?php
class Deliver_userModel extends Model
{
    /**
     * 检测是否有配送员可接单
     * @param float $lat
     * @param float $lng
     * @return boolean
     */
    public function hasUser($lat, $lng)
    {
        $where = "`group`=1 AND `status`=1 AND `is_notice`=0 AND ((`delivery_range_type`=0 AND ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`lat`*PI()/180)*POW(SIN(({$lng}*PI()/180-`lng`*PI()/180)/2),2)))*1000) <= `range`*1000)";
        $where .= " OR (`delivery_range_type`=1 AND MBRContains(PolygonFromText(`delivery_range_polygon`),PolygonFromText('Point({$lng} {$lat})'))>0))";
        $list = $this->field(true)->where($where)->select();
        return $list;
    }
}