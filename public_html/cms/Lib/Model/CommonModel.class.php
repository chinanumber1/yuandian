<?php
class CommonModel extends Model{
    public function LbsTranform($url,$module_name = MODULE_NAME , $action_name = ACTION_NAME ){
        if(stripos($url , 'LBS://')!==FALSE){
            $url = parse_url($url);
            $long_lat = explode(',',$url['host']);
            $long = $long_lat[0];
            $lat = $long_lat[1];
            $url = U($module_name.'/'.$action_name,array('long'=>$long,'lat'=>$lat));
        }
        return $url;
    }
}
?>