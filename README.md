This library has similar features of gson/jackson. Its purpose is to easily handle the conversion between PHP objects and JSON or XML.

## 使用示例

### 类成员为基础数据类型

```php
class SimpleMapBean
{
    public ?string $vNull = null;
    public ?string $vString = '';
    public ?bool $vBool = false;
    public ?bool $vTrue = true;
    public ?bool $vFalse = false;
    public ?bool $vBoolean = true;
    public ?int $vInt = 10;
    public ?int $vInteger = -1;
    public ?float $vFloat = 1.234;
    public ?float $vDouble = 1.3456789;
    public ?array $vArray = [1, 2, 3];
    public ?object $vObject = null;
    public ?stdClass $vStdClass = null;
}

$jsonStr =<<<JSON
{"vNull":null,"vString":"","vBool":false,"vTrue":true,"vFalse":false,"vBoolean":true,"vInt":10,"vInteger":-1,"vFloat":1.234,"vDouble":1.3456789,"vArray":[1,2,3],"vObject":{"a":"a","b":"b"},"vStdClass":{"m1":"m1","m2":"m2"}}
JSON;

// simpleMapObject 数据类型为 SimpleMapBean，并且 PHPStorm 中会有相应的代码提示，能识别到数据类型 
$simpleMapObject = JSON::parseObj($jsonString, SimpleMapBean::class);
```

### 类成员为自定义的类

```php
class OrderBean
{
    public string $orderNo;
    public OrderInfoBean $orderInfo;

    /**
     * 此处通过注释写明数据类型，有利于 PHPStorm 分析数据类型
     * @var $goodsList GoodsInfoBean[]
     * 使用注解 ListPropertyType 明确数组内的数据类型
     */
    #[ListPropertyType(GoodsInfoBean::class)]
    public array $goodsList;
}

class OrderInfoBean
{
    public ?int $goodsCount;
    public ?bool $isCod;
    public ?float $amount;
    public ?string $ownerNo;
}

class GoodsInfoBean
{
    public ?string $specNo;
    public ?float $goodsCount;
}

$jsonStr = <<<JSON
{"orderNo":"orderNo:12345","orderInfo":{"goodsCount":123,"isCod":true,"amount":1.2345,"ownerNo":"ownerNo"},"goodsList":[{"specNo":"specNo","goodsCount":123},{"specNo":"specNo","goodsCount":123}]}
JSON;
$orderBean = JSON::parseObj($jsonString, OrderBean::class);
```

### 使用注解声明类成员别名

```php
class PropertyAliasBean
{
    #[PropertyAlias("spec_no")]
    public ?string $specNo;
    #[PropertyAlias("goods_count")]
    public ?float $goodsCount;
}

$jsonStr = <<<JSON
{"spec_no":"spec_no","goods_count":123}
JSON;

$propertyAliasBean = JSON::parseObj($jsonString, PropertyAliasBean::class);
```

### 使用数据校验器 Validator 在序列化同时校验数据
```php
class ValidationTestBean
{
    #[AssertFalse]
    public bool $assertFalse = false;
    #[AssertTrue]
    public bool $assertTrue = true;
    #[Future]
    public string $future = "9999-02-23 15:10:23";
    #[Length(10)]
    public string $length = "123456789";
    #[MustNotNull]
    public string $mustNotNull = "111";
    #[MustNull]
    public ?string $mustNull = null;
    #[NotBlank]
    public string $notBlank = "12345";
    #[Past]
    public string $past = "2000-02-23 15:11:20";
    #[Pattern("/\d{11}/")]
    public string $pattern = "01234567891";
}
```

- The getter/setter name should be 'camelCase'; support set{camelCase}, get{camelCase}, is{camelCase} methods.
- 建议都加上默认值，或者有 getter 方法，内部用 isset() 或者 ?? 处理，返回默认值，PHP8 要求对象必须初始化（initialized）后才能使用
- 所有的对象嵌套，都允许为 null，便于使用
- bean class 不能有含参数的构造函数
- 考虑字段不传的情况，需要支持 null，比如更新类型的接口
- 不能显示声明构造函数，或者只能有非必填参数的构造函数
- 都加上 ? 声明
- 别名（alias）：优先级低。