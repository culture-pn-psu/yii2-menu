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
