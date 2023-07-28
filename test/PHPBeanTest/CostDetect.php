<?php

namespace PHPBeanTest;

$start = microtime(true);
const ROOT_DIR = "E:/0_auto_sync_file/workingSpaceHelp/php/component/php-bean";

$spend = (microtime(true) - $start) * 1000;
// logx("init require spend:" . $spend);

$load = 1;
if ($load) {
    init_require(ROOT_DIR);
}

require ROOT_DIR . "/test/PHPBeanTest/Data/OrderBean.php";
require ROOT_DIR . "/test/PHPBeanTest/Data/OrderInfoBean.php";
require ROOT_DIR . "/test/PHPBeanTest/Data/GoodsInfoBean.php";

use PHPBean\JSON;
use PHPBeanTest\Data\OrderBean;

// $orderBeanExpect = $this->getOrderBean();
$orderBeanExpect = array(
    'order_no'  => 'orderNo 别名，order_no',
    // 'orderNo'   => '订单号',
    'orderInfo' => array(
        'goodsCount' => 2,
        'isCod'      => 'Y',
        'amount'     => 1.123,
        'ownerNo'    => 'ownerNo',
    ),
    'goodsList' => array(
        ['specNo' => '商家编码0', 'num' => 0,],
        ['specNo' => '商家编码1', 'num' => 1,],
    ),
    'snList'    => array('sn0', 'sn1', 'sn2',),
);

$count = 1;
$str = json_encode($orderBeanExpect, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
// logx("json 字符串:" . $str);
// logx("json_decode 结果:" . print_r(json_decode($str), true));
while ($count--) {
    $orderBeanResult = JSON::parseObj($str, OrderBean::class);
}
$spend = (microtime(true) - $start) * 1000;
logx("JSON::parseObj spend:" . $spend);
// logx("PHPBean 工具的 JSON::parseObj 解析结果:" . print_r($orderBeanResult, true));
// logx($orderBeanResult);
// logx($str);

function logx($msg): void
{
    if ($msg === null) {
        $msg = 'null';
    }
    fwrite(STDOUT, print_r($msg, true) . "\r\n");
}

function init_require($ROOT_DIR): void
{
    $start = microtime(true);

    require "{$ROOT_DIR}/src/PHPBean/JSON.php";
    require "{$ROOT_DIR}/src/PHPBean/ObjectToBean.php";
    require "{$ROOT_DIR}/src/PHPBean/Utils/ClassUtil.php";
    require "{$ROOT_DIR}/src/PHPBean/Utils/ClassInfoCache.php";
    require "{$ROOT_DIR}/src/PHPBean/Enum/TypeName.php";
    require "{$ROOT_DIR}/src/PHPBean/Utils/ClassPropertyInfo.php";
    require "{$ROOT_DIR}/src/PHPBean/Attributes/ExtensionBeforeHandle.php";
    require "{$ROOT_DIR}/src/PHPBean/Attributes/PropertyAlias.php";
    require "{$ROOT_DIR}/src/PHPBean/Attributes/ListPropertyType.php";
    require "{$ROOT_DIR}/src/PHPBean/Deserializer/Deserializer.php";
    require "{$ROOT_DIR}/src/PHPBean/Deserializer/MixValueDeserializer.php";
    require "{$ROOT_DIR}/src/PHPBean/Deserializer/StringDeserializer.php";
    require "{$ROOT_DIR}/src/PHPBean/Attributes/ExtensionAfterHandle.php";
    require "{$ROOT_DIR}/src/PHPBean/Deserializer/BeanClassDeserializer.php";
    require "{$ROOT_DIR}/src/PHPBean/Deserializer/IntDeserializer.php";
    require "{$ROOT_DIR}/src/PHPBean/Deserializer/BoolDeserializer.php";
    require "{$ROOT_DIR}/src/PHPBean/Deserializer/FloatDeserializer.php";
    require "{$ROOT_DIR}/src/PHPBean/Deserializer/ListDeserializer.php";

    $spend = (microtime(true) - $start) * 1000;
    logx("init require spend:" . $spend);
}