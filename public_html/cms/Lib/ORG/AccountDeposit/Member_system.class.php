<?php
/*
 * 通联云
 */

class Member_system {
    public function __construct()
    {

    }

    //创建会员
    function createMember($client) {
        $param["bizUserId"] = "cyx";
        $param["memberType"] = "3";
        $param["source"] = "1";
        $result = $client->request("MemberService", "createMember", $param);
        print_r($result);
    }

    //实名认证
    function setRealName($client, $privateKey, $privateKey) {
        $param["bizUserId"] = "cyx";
        $param["name"] = "cyx";
        $param["identityType"] = "1";
        $param["identityNo"] = rsaEncrypt("330227198805284412", $privateKey, $privateKey);

        $result = $client->request("MemberService", "setRealName", $param);
        print_r($result);
    }


}

