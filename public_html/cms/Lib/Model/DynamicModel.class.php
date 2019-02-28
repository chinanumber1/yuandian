<?php
class DynamicModel extends Model{
    public $nameCache = array();
    //protected $tableName = 'circle_dynamic'; 
	/*得到所有区域*/
	public function dynamicList(){
	   $Model = new Model();
	   $sql="select a.content,a.latitude,a.longitude,a.add_time,b.name,c.nick from pigcms_circle_dynamic as a left join pigcms_circle as b on a.circle_id=b.id left join pigcms_user as c on a.uid=c.id where a.status=0 ";
	   return $Model->query($sql);
	}
	
}
?>