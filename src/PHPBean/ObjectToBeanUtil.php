<?php /** @noinspection PhpUndefinedClassInspection */

/** @noinspection PhpDeprecationInspection */

/** @noinspection PhpDeprecationInspection */

namespace PHPBean;

use Exception;
use PHPBean\Attributes\Alias;
use PHPBean\Attributes\ParamType;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

/**
 * @deprecated
 */
class ObjectToBeanUtil
{
    /**
     * @template T
     * @param string $string
     * @param T $beanClassName
     * @return T[]
     * @throws ReflectionException | Exception
     */
    public static function parseJsonArray(string $string, $beanClassName): array
    {
        $string = trim($string);
        if ($string == "") {
            throw new Exception("字符串不能为空");
        }

        $jsonArrList = json_decode($string, true);
        if (!$jsonArrList) {
            throw new Exception("非合法 json:" . json_last_error_msg());
        }

        $arrValue = [];
        foreach ($jsonArrList as $jsonArr) {
            $arrValue[] = self::parseJsonValue($jsonArr, $beanClassName);
        }
        return $arrValue;
    }

    /**
     * @template T
     * @param string $string
     * @param T $beanClassName
     * @return T
     * @throws ReflectionException | Exception
     */
    public static function parseJsonObject(string $string, $beanClassName): object
    {
        $string = trim($string);
        if ($string == "") {
            throw new Exception("字符串不能为空");
        }

        $jsonArr = json_decode($string, true);
        if (!$jsonArr) {
            throw new Exception("非合法 json:" . json_last_error_msg());
        }

        return self::parseJsonValue($jsonArr, $beanClassName);
    }

    /**
     * @template T
     * @param array $jsonArr
     * @param T $beanClassName
     * @return T
     * @throws ReflectionException | Exception
     */
    private static function parseJsonValue(array $jsonArr, $beanClassName)
    {
        $targetBeanInstance = new $beanClassName();
        $reflectionClass = new ReflectionClass($beanClassName);
        $classPropertiesList = self::getPropInfoList($reflectionClass);

        self::checkPropsInfo($classPropertiesList);

        // 不能仅以 classPropertiesList（必须优先，因为外部数据是不可靠的） 处理，否则类成员类型是当前类，则无限递归了或者需要对 null 进行处理，毕竟 json 数据不是无限的。
        // 转换数据
        foreach ($classPropertiesList as $propName => $propDetailArr) {
            $alias = $propDetailArr['alias'];   // 字段的别名

            // 从格式化后的数据中获取成员的值，优先识别别名
            $value = null;
            if (isset($jsonArr[$alias])) {
                $value = $jsonArr[$alias];
            } elseif (isset($jsonArr[$propName])) {
                $value = $jsonArr[$propName];
            }
            if ($value === null) {
                continue;
            }

            self::setPropertyValue($targetBeanInstance, $propName, $propDetailArr, $value);
        }

        return $targetBeanInstance;
    }

    /**
     * @param object $targetBeanInstance
     * @param string $propName
     * @param string[] $propDetailArr
     * @param mixed $value
     * @return void
     * @throws Exception
     */
    private static function setPropertyValue(object $targetBeanInstance, string $propName, array $propDetailArr, mixed $value): void
    {
        $isInArray = $propDetailArr['isInArray'];
        $paramType = trim($propDetailArr['paramType']);
        $setterMethodName = $propDetailArr['setterMethodName'];

        // 暂时不加，example 内的示例不完善
        // if ($paramType == "") {
        //     throw new PHPBeanException("bean 转换失败：类型不可为空");
        // }
        $paramTypeForCheck = strtolower($paramType);
        if ($paramTypeForCheck == "null") {
            throw new Exception("bean 转换失败：类型不可为 null");
        }
        if ($paramTypeForCheck == "resource") {
            throw new Exception("bean 转换失败：类型不可为 Resource");
        }

        $isBaseType = match ($paramTypeForCheck) {
            "string", "integer", "int", "float", "double", "bool", "boolean", "array", "object" => true,
            default => false,
        };

        // 基础类型，直接回写值
        if ($isBaseType) {
            // 是一个 list
            if ($isInArray) {
                $valueList = [];
                foreach ($value as $oneValue) {
                    $valueList[] = $oneValue;
                }

                self::setValue($targetBeanInstance, $propName, $valueList, $setterMethodName);
            } else {
                self::setValue($targetBeanInstance, $propName, $value, $setterMethodName);
            }

            return;
        }

        // 是一个 list
        if ($isInArray) {
            $valueList = [];
            foreach ($value as $oneValue) {
                $subTargetBeanInstance = self::parseJsonValue($oneValue, $paramType);
                $valueList[] = $subTargetBeanInstance;
            }

            self::setValue($targetBeanInstance, $propName, $valueList, $setterMethodName);
            return;
        }

        // 自定义对象
        $subTargetBeanInstance = self::parseJsonValue($value, $paramType);
        self::setValue($targetBeanInstance, $propName, $subTargetBeanInstance, $setterMethodName);
    }

    /**
     * @param object $targetBeanInstance
     * @param string $propName
     * @param mixed $value
     * @param string $setterMethodName
     * @return void
     */
    private static function setValue(object $targetBeanInstance, string $propName, mixed $value, string $setterMethodName): void
    {
        if ($setterMethodName <> "") {
            $targetBeanInstance->$setterMethodName($value);
        } else {
            $targetBeanInstance->$propName = $value;
        }
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @return array
     * @throws ReflectionException
     * @throws Exception
     */
    private static function getPropInfoList(ReflectionClass $reflectionClass): array
    {
        $classPropertiesList = [];

        // get all properties
        $defaultProperties = $reflectionClass->getDefaultProperties();
        $reflectionPropertiesList = $reflectionClass->getProperties();
        $defaultPropertiesKeys = array_keys($defaultProperties);
        $allPropertiesList = array_combine($defaultPropertiesKeys, $defaultPropertiesKeys);

        foreach ($reflectionPropertiesList as $reflectionPropertiesListInfo) {
            $name = $reflectionPropertiesListInfo->name;
            $allPropertiesList[$name] = $name;
        }

        // 遍历所有属性
        foreach ($allPropertiesList as $propName => $propDefaultValue) {
            $reflectionProperty = $reflectionClass->getProperty($propName);

            $paramType = "";
            $isInArray = false;
            self::getPropertyParamTypeInfo($reflectionProperty, $paramType, $isInArray);

            $alias = self::getPropertyAlias($reflectionProperty);

            // var_dump($alias);
            // var_dump($paramType);
            // var_dump($isInArray);

            // 对于私有成员，需要同时有 getter 和 setter 才能玩得转
            $setterMethodName = "set" . ucfirst($propName);  // 首字母大写
            $setterMethodName = self::hasPublicSetterGetterMethod($reflectionClass, $propName) ? $setterMethodName : "";

            $classPropertiesList[$propName] = array(
                'alias'            => $alias,   // 字段的别名
                'isInArray'        => $isInArray,
                'paramType'        => $paramType,
                'propModifiers'    => $reflectionProperty->getModifiers(),   // 修饰符，public private 等
                'setterMethodName' => $setterMethodName,
            );
        }

        return $classPropertiesList;
    }

    /**
     * @throws Exception
     */
    public static function checkPropsInfo($classPropertiesList): void
    {
        foreach ($classPropertiesList as $propName => $propDetailArr) {
            $propModifiers = $propDetailArr['propModifiers'];
            $setterMethodName = $propDetailArr['setterMethodName'];

            if (ReflectionProperty::IS_STATIC & $propModifiers) {
                throw new Exception("数据转换失败：成员 $propName 校验失败，属性不能为 static");
            }
            if (ReflectionProperty::IS_PRIVATE & $propModifiers && "" == $setterMethodName) {
                throw new Exception("数据转换失败：成员 $propName 校验失败，无可 setter/getter 方法");
            }
        }
    }

    /**
     * @throws Exception
     */
    private static function getPropertyParamTypeInfo(ReflectionProperty $reflectionProperty, &$paramType, &$isInArray): void
    {
        $paramType = "";
        $isInArray = false;
        $paramTypeAttributeList = $reflectionProperty->getAttributes(ParamType::class);
        if (empty($paramTypeAttributeList)) {
            return;
        }

        if (isset($paramTypeAttributeList[1])) {
            $propName = $reflectionProperty->name;
            $beanClassName = $reflectionProperty->getDeclaringClass();
            throw new Exception("类 $beanClassName 的属性 $propName 注解错误，不允许存在多个 ParamType 注解");
        }
        $paramTypeAttribute = $paramTypeAttributeList[0];

        /**
         * @var ParamType $paramTypeInstance 实例化是为了在注解内部做校验，校验失败抛出异常
         */
        $paramTypeInstance = $paramTypeAttribute->newInstance();
        $paramType = $paramTypeInstance->getType();
        $isInArray = $paramTypeInstance->getIsInArray();
    }

    /**
     * 获取 bean 类成员的 Alias 注解内容
     *
     * @throws Exception
     */
    private static function getPropertyAlias(ReflectionProperty $reflectionProperty): string
    {
        $alias = "";
        $aliasAttributeList = $reflectionProperty->getAttributes(Alias::class);
        if (empty($aliasAttributeList)) {
            return $alias;
        }

        if (isset($aliasAttributeList[1])) {
            $propName = $reflectionProperty->name;
            $beanClassName = $reflectionProperty->getDeclaringClass();
            throw new Exception("类 $beanClassName 的属性 $propName 注解错误，不允许存在多个 Alias 注解");
        }
        $aliasAttribute = $aliasAttributeList[0];

        /**
         * @var Alias $aliasInstance 实例化是为了在注解内部做校验，校验失败抛出异常
         */
        $aliasInstance = $aliasAttribute->newInstance();
        return $aliasInstance->getAlias();
    }

    /**
     * 判断是否同时存在 setter getter 方法
     *
     * @param ReflectionClass $reflectionClass
     * @param string $propName
     * @return bool
     * @throws ReflectionException
     */
    private static function hasPublicSetterGetterMethod(ReflectionClass $reflectionClass, string $propName): bool
    {
        $propName = ucfirst($propName);
        $setterMethodName = "set" . $propName;
        $getterMethodName = "get" . $propName;

        $exist = $reflectionClass->hasMethod($setterMethodName) && $reflectionClass->hasMethod($getterMethodName);
        if (!$exist) {
            return false;
        }

        $isPublicSetter = $reflectionClass->getMethod($setterMethodName)->getModifiers() & ReflectionMethod::IS_PUBLIC;
        $isPublicGetter = $reflectionClass->getMethod($getterMethodName)->getModifiers() & ReflectionMethod::IS_PUBLIC;

        return $isPublicSetter && $isPublicGetter;
    }
}
