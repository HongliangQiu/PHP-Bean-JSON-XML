<?php

namespace PHPBean\Deserializer;

use PHPBean\Utils\ClassPropertyInfo;
use SimpleXMLElement;

class BoolDeserializer extends Deserializer
{
    private const TRUE_VALUE_LIST = array(
        'true' => true,
        'True' => true,
        'tRue' => true,
        'trUe' => true,
        'truE' => true,
        'TRue' => true,
        'TrUe' => true,
        'TruE' => true,
        'tRUe' => true,
        'tRuE' => true,
        'trUE' => true,
        'TRUe' => true,
        'TRuE' => true,
        'TrUE' => true,
        'tRUE' => true,
        'TRUE' => true,
        'yes'  => true,
        'Yes'  => true,
        'yEs'  => true,
        'yeS'  => true,
        'YEs'  => true,
        'YeS'  => true,
        'yES'  => true,
        'YES'  => true,
        '1'    => true,
        't'    => true,
        'T'    => true,
        'y'    => true,
        'Y'    => true,
    );

    public static function deserialize(mixed $targetValue, ClassPropertyInfo $classPropertyInfo, int $listDimension = 0): ?bool
    {
        if (is_bool($targetValue)) {
            return $targetValue;
        }

        if ($targetValue instanceof SimpleXMLElement) {
            $targetValue = $targetValue->__toString();
        }

        if (!is_scalar($targetValue)) {
            return null;
        }

        // For the efficiency consideration, the 'switch' or 'match' is not used.
        return array_key_exists($targetValue, self::TRUE_VALUE_LIST);
    }
}