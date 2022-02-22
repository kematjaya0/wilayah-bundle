# wilayah-bundle
Symfony bundle untuk database wilayah di indonesia
1. instalasi
```
composer require kematjaya/wilayah-bundle
```
2. update bundles.php
```
// config/bundles.php
...
Kematjaya\WilayahBundle\WilayahBundle::class => ['all' => true]
...
```
3. update database schema
```
php bin/console doctrine:schema:update --force
```
4. insert data
```
// all data
php bin/console wilayah:insert

// provinsi saja
php bin/console wilayah:insert --data=provinsi

// provinsi dan kabupaten
php bin/console wilayah:insert --data=provinsi --data=kabupaten
```
