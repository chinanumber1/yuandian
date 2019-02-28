<?php
'SELECT s.*, m.*, mm.isverify,mm.logo as `merchant_logo`,(CASE
	        WHEN (`s`.`open_1`=\'00:00:00\' and `s`.`open_2`=\'00:00:00\' and `s`.`open_3`=\'00:00:00\' and `s`.`close_1`=\'00:00:00\' and `s`.`close_2`=\'00:00:00\' and `s`.`close_3`=\'00:00:00\') then 2
	        WHEN (`m`.`is_reserve` = 1) then 1
	        WHEN ((`s`.`open_1`<\'10:31:38\' and `s`.`close_1`>\'10:31:38\') OR (`s`.`open_2`<\'10:31:38\' and `s`.`close_2`>\'10:31:38\') OR (`s`.`open_3`<\'10:31:38\' and `s`.`close_3`>\'10:31:38\')) then 2
	        ELSE 0
	        END) as `t_sort`, ROUND(6378.137 * 2 * ASIN(SQRT(POW(SIN((28.016968170201135*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS(28.016968170201135*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN((120.66580149108428*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) AS juli FROM pigcms_merchant_store AS s INNER JOIN pigcms_merchant_store_shop AS m ON m.store_id=s.store_id LEFT JOIN pigcms_merchant AS mm ON mm.mer_id = s.mer_id WHERE s.status=1 AND s.store_id=m.store_id AND s.have_shop=1 AND ((m.is_close_shop=0 AND m.store_theme=1) OR m.store_theme=0) AND s.auth>2 AND (`m`.`deliver_type` IN (2, 3, 4, 5) OR (`m`.`delivery_range_type`=0 AND `m`.`deliver_type` IN (0, 1) AND ROUND(6378.137 * 2 * ASIN(SQRT(POW(SIN((28.016968170201135*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS(28.016968170201135*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN((120.66580149108428*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) < `m`.`delivery_radius`*1000) OR (`m`.`delivery_range_type`=1 AND MBRContains(PolygonFromText(`m`.`delivery_range_polygon`),PolygonFromText(\'Point(120.66580149108428 28.016968170201135)\'))>0)) AND mm.status = 1 ORDER BY `t_sort` DESC, `m`.`is_close` ASC, `m`.`sort` DESC, juli ASC LIMIT 0, 20'