## Пакет реализующий интеграцию приёма платежей с Яндекс.Касса
### Laravel package that provides integration with Yandex.Kassa([README.EN](https://github.com/CawaKharkov/yandex-kassa/blob/master/README.EN.md))

## *В настоящее время работает только с Laravel> 5.3, для 5.2 будет создана отдельная ветка*

### Утановка

- Подключить пакет с помощь композера: ``` composer require cawakharkov/yandex-kassa:dev-master ```
- Если вы получили ошибку о том, что ваш проект не совместим с дев версией пакета, необходимо понизить требования minimum-stability - https://getcomposer.org/doc/04-schema.md#minimum-stability
- Настроить пакет [CawaKharkov/laravel-balance](https://github.com/CawaKharkov/laravel-balance)
- Подключить сервис провайдер `config/app.php` -> ``` \CawaKharkov\YandexKassa\YandexKassaServiceProvider::class, ```
- Опубликовать всё файлы необходимые для работы пакета ``` php artisan vendor:publish ```
  - Миграции
  - Файл конфигурации(config/yandex_kassa.php)
- Запустить миграции для пакета ```php  artisan migrate --path=database/migrations/yandex_kassa ```

### Настройки

- Внести в кофигурационный файл свои данные(config/yandex_kassa.php)


### Использование

- Форма оплаты http://domain/payment/form, её можно переопределить в настройках
- CheckUrl http://domain/payment/check
- AvisoUrl http://domain/payment/payment
- При создании платежа будет запущено событие ``` \CawaKharkov\YandexKassa\Events\PaymentCreated ```


