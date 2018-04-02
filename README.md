# region
省市区联动数据

## 安装

`composer require cblink/region`

在 app.php 中加入服务提供者

```
/*
 * Package Service Providers...
 */
Cblink\Region\RegionServiceProvider::class,
```

生成 migration 文件

`php artisan vendor:publish --provider="Cblink\Region\RegionServiceProvider"`

在 `2018_01_01_000000_create_regions_table.php` 可以根据自身需求与结构修改表名或字段名

执行迁移

`php artisan migrate`
