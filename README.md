Menu Manager for Yii 2
======================
ระบบจัดการเมนู


Installation ติดตั้ง
-----------------

### Install With Composer

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require firdows/yii2-menu "@dev"
```
Or, you may add

```
"firdows/yii2-menu" : "@dev"
```

### ตั้งค่าใน main.php


```php
return [
    ...
    'modules' => [
        'menu' => [
            'class' => 'firdows\menu\Module',
        ],
    ],
    'components'=>[...]
    ...
];
```
### Migrations
```
yii migrate --migrationPath=@firdows/menu/migrations
```


### ตั้งค่า RBAC
[Basic Configuration](https://github.com/mdmsoft/yii2-admin/blob/master/docs/guide/configuration.md)
```
yii migrate --migrationPath=@mdm/admin/migrations
```

### Use การเรียกใช้
การป้อนรหัสหมวดหมู่เมนูเข้าไปในฟังค์ชั่นจะได้ข้อมูล Array อ่าน
```php
$nav = new firdows\menu\models\Navigate();
$menu = $nav->menu(menu_cate_id);
```

#### การนำไปใช้กับ Widget
Ex.
```php
dmstr\widgets\Menu::widget([
    'options' => ['class' => 'sidebar-menu'],
    'items' => $menu,
]);
```



Example
-------
<img src="http://ikhlasservice.com/uploads/menu.png" width="400"/>