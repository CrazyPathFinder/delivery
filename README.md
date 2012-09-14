Delivery
========

Project for delivery company

Documentation
-------------

Дамп БД в корне, файл **delivery.sql**

Пример файла настроек в корне, файл **parameters.yml.example**
Этот файл должен находиться в **app/config** и должен называться **parameters.yml**

Файл **composer.phar** находиться в директории **bin**
Пример вызова **bin/composer.phar update**

Шаблоны отправляемых писем находяться в директории **src/System/UserBundle/Resources/views/**
Там находяться поддиректории для разных контроллеров, файлы шаблонов имеют в названии (в конце названия) **Email.html.twig** за исключением файла **checkEmail.html.twig** - это не файл шаблона.

Installation
------------ 

После развертывания и обновления (установки) вендеров, проверить наличие директорий **app/cache и app/logs**, при отсутствии создать.

Дать разрешения на директории **app/logs и app/cache 777**