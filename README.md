# Installation

Добавить в kernel

    Evrinoma\NomenclatureBundle\EvrinomaNomenclatureBundle::class => ['all' => true],

Добавить в routes

    nomenclature:
        resource: "@EvrinomaNomenclatureBundle/Resources/config/routes.yml"

Добавить в composer

    composer config repositories.dto vcs https://github.com/evrinoma/DtoBundle.git
    composer config repositories.dto-common vcs https://github.com/evrinoma/DtoCommonBundle.git
    composer config repositories.utils vcs https://github.com/evrinoma/UtilsBundle.git

# Configuration

преопределение штатного класса сущности

    nomenclature:
        db_driver: orm модель данных
        factory_nomenclature: App\Nomenclature\Factory\NomenclatureFactory фабрика для создания объектов номенклатуры,
                 недостающие значения можно разрешить только на уровне Mediator
        factory_item: App\Nomenclature\Factory\ItemFactory фабрика для создания объектов элементов номенклатуры,
                 недостающие значения можно разрешить только на уровне Mediator
        entity_nomenclature: App\Nomenclature\Entity\Nomenclature сущность номенклатуры
        entity_item: App\Nomenclature\Entity\Item сущность элемента номенклатуры
        constraints: Вкл/выкл проверки полей сущности по умолчанию 
        dto_nomenclature: App\Nomenclature\Dto\NomenclatureDto класс dto с которым работает сущность номенклатуры
        dto_item: App\Nomenclature\Dto\ItemDto класс dto с которым работает сущность элемента номенклатуры
        decorates:
          command_nomenclature - декоратор mediator команд номенклатуры
          query_nomenclature - декоратор mediator запросов номенклатуры
          command_item - декоратор mediator команд элемента номенклатуры
          query_item - декоратор mediator запросов элемента номенклатуры
        services:
          pre_validator_nomenclature - переопределение сервиса валидатора номенклатуры
          handler_nomenclature - переопределение сервиса обработчика сущностей номенклатуры
          pre_validator_item - переопределение сервиса валидатора элемента номенклатуры
          handler_item - переопределение сервиса обработчика сущностей элемента номенклатуры
          file_system - переопределение сервиса сохранения файла

# CQRS model

Actions в контроллере разбиты на две группы
создание, редактирование, удаление данных

        1. putAction(PUT), postAction(POST), deleteAction(DELETE)
получение данных

        2. getAction(GET), criteriaAction(GET)

каждый метод работает со своим менеджером

        1. CommandManagerInterface
        2. QueryManagerInterface

При переопределении штатного класса сущности, дополнение данными осуществляется декорированием, с помощью MediatorInterface


группы  сериализации

    1. API_GET_NOMENCLATURE, API_CRITERIA_NOMENCLATURE - получение номенклатуры
    2. API_POST_NOMENCLATURE - создание номенклатуры
    3. API_PUT_NOMENCLATURE -  редактирование номенклатуры

    4. API_GET_NOMENCLATURE_ITEM, API_CRITERIA_NOMENCLATURE_ITEM - получение элемента
    5. API_POST_NOMENCLATURE_ITEM - создание элемента
    6. API_PUT_NOMENCLATURE_ITEM -  редактирование элемента

# Статусы:

    создание:
        номенклатура создана HTTP_CREATED 201
    обновление:
        номенклатура обновлена HTTP_OK 200
    удаление:
        номенклатура удалена HTTP_ACCEPTED 202
    получение:
        номенклатура найдена HTTP_OK 200
    ошибки:
        если номенклатура не найдена NomenclatureNotFoundException возвращает HTTP_NOT_FOUND 404
        если номенклатура не уникальна UniqueConstraintViolationException возвращает HTTP_CONFLICT 409
        если номенклатура не прошла валидацию NomenclatureInvalidException возвращает HTTP_UNPROCESSABLE_ENTITY 422
        если номенклатура не может быть сохранена NomenclatureCannotBeSavedException возвращает HTTP_NOT_IMPLEMENTED 501
        все остальные ошибки возвращаются как HTTP_BAD_REQUEST 400
    
    создание:
        элемента номенклатуры создан HTTP_CREATED 201
    обновление:
        элемента номенклатуры обновлен HTTP_OK 200
    удаление:
        элемента номенклатуры удален HTTP_ACCEPTED 202
    получение:
        элемента номенклатуры найден HTTP_OK 200
    ошибки:
        если элемент номенклатуры не найден ContactNotFoundException возвращает HTTP_NOT_FOUND 404
        если элемент номенклатуры не уникален UniqueConstraintViolationException возвращает HTTP_CONFLICT 409
        если элемент номенклатуры не пройти валидацию ContactInvalidException возвращает HTTP_UNPROCESSABLE_ENTITY 422
        если элемент номенклатуры не может быть сохранен ContactCannotBeSavedException возвращает HTTP_NOT_IMPLEMENTED 501
        все остальные ошибки возвращаются как HTTP_BAD_REQUEST 400

# Constraint

Для добавления проверки поля сущности nomenclature нужно описать логику проверки реализующую интерфейс Evrinoma\UtilsBundle\Constraint\Property\ConstraintInterface и зарегистрировать сервис с этикеткой evrinoma.nomenclature.constraint.property

    evrinoma.nomenclature.constraint.property.custom:
        class: App\Nomenclature\Constraint\Property\Custom
        tags: [ 'evrinoma.nomenclature.constraint.property' ]

## Description
Формат ответа от сервера содержит статус код и имеет следующий стандартный формат
```text
    [
        TypeModel::TYPE => string,
        PayloadModel::PAYLOAD => array,
        MessageModel::MESSAGE => string,
    ];
```
где
TYPE - типа ответа

    ERROR - ошибка
    NOTICE - уведомление
    INFO - информация
    DEBUG - отладка

MESSAGE - от кого пришло сообщение
PAYLOAD - массив данных

## Notice

показать проблемы кода

```bash
vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --verbose --diff --dry-run
```

применить исправления

```bash
vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php
```

# Тесты:

    composer install --dev

### run all tests

    /usr/bin/php vendor/phpunit/phpunit/phpunit --bootstrap src/Tests/bootstrap.php --configuration phpunit.xml.dist src/Tests --teamcity

### run personal test for example testPost

    /usr/bin/php vendor/phpunit/phpunit/phpunit --bootstrap src/Tests/bootstrap.php --configuration phpunit.xml.dist src/Tests/Functional/Controller/ApiControllerTest.php --filter "/::testPost( .*)?$/" 

## Thanks

## Done

## License
    PROPRIETARY
   