# region
省市区联动数据

## 安装

`composer require Myischen/region`

在 app.php 中加入服务提供者

```
/*
 * Package Service Providers...
 */
Myischen\Region\RegionServiceProvider::class,
```

生成 migration 文件

`php artisan vendor:publish --provider="Myischen\Region\RegionServiceProvider"`

在 `2018_01_01_000000_create_regions_table.php` 可以根据自身需求与结构修改表名或字段名

执行迁移

`php artisan migrate`

### 默认方法

```php
use Myischen\Region\Region;

$region = new Region();

$region->allProvinces(); // 全部省份
$region->allCities(); // 全部城市
$region->allAreas(); // 全部区
$region->allStreets()//所有乡镇
$region->nest($id = null); // 展示全部子区域，可指定某个省或市id
$region->nestFromChild($id); // 根据市或区id展示其所有父结构
```


数据来自 <https://github.com/modood/Administrative-divisions-of-China>
