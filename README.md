# 0. Что использовалось
* Аккаунт на github.com;
* MySQL версии 5.7 или выше;
* Apache 2.4;
* Git;
* Composer;

# 1. Первоначальная установка и настройка
Установил laravel 8 по инструкции
https://laravel.com/docs/8.x/installation

Загрузил фреймворк:
`composer global require laravel/installer`
Вместо bash использую zsh. В текстовый файл .zshrc добавил путь
`export PATH=~/.config/composer/vendor/bin:$PATH`
Установил ларавель:
`laravel new testbase`

Создал базу данных:
`CREATE DATABASE testbase CHARACTER SET utf8 COLLATE utf8_general_ci;`

В .env файл добавил параметры соединения с БД.
`DB_CONNECTION=mysql`
`DB_HOST=127.0.0.1`
`DB_PORT=3306`
`DB_DATABASE=testbase`
`DB_USERNAME=root`
`DB_PASSWORD=123qwe+++`
Вуаля, площадка для разработки готова.

# 2. Шаблон для интерфейса
Нашел такой шаблон [Material Dashboard Laravel](https://material-dashboard-laravel.creative-tim.com/docs/getting-started/laravel-setup.html?_ga=2.61746732.1802414693.1603789826-2091948728.1603789826)
Установил его по инструкции
1. Переходим в директорию с проектом
2. Выполняем `composer require laravel/ui`
3. Выполняем `php artisan ui vue --auth`
4. Устанавливаем сам шаблон `composer require laravel-frontend-presets/material-dashboard`
5. Накатываем шаблон `php artisan ui material`
6. Восстанавливаем список всех классов которые нужно включить в проект `composer dump-autoload`
7. Запускаем миграции и сиды `php artisan migrate --seed`

Данный шаблон уже имеет возможность регистрации/авторизации/восстановление пароля
![Регистрация / Авторизация / Восстановление пароля](http://remondom.ru/testbase/00_reg_aut_forget.png)

# 3. Настройка лары для работы с почтой
По [https://medium.com/@agavitalis/how-to-send-an-email-in-laravel-using-gmail-smtp-server-53d962f01a0c](этой) инструкции заставим приложение отправлять письма через gmail.

`MAIL_DRIVER=smtp`

`MAIL_HOST=smtp.googlemail.com`

`MAIL_PORT=465`

`MAIL_USERNAME=ENTER_YOUR_EMAIL_ADDRESS(GMAIL)`

`MAIL_PASSWORD=` Здесь указываем не пароль от почты, а пароль приложения

`MAIL_ENCRYPTION=ssl`

![Здесь получим пароль для нашего приложения](http://remondom.ru/testbase/01_password.png)

# 4. Установка ssl-сертификата для локального хоста
У большинства серверов используется https. Чтобы наша локальная машина не отличалась от прода поставим сертификат по [инструкции](https://stackoverflow.com/questions/25946170/how-can-i-install-ssl-on-localhost-in-ubuntu)
![Так будет выглядить настройки виртуального хоста для апача](http://remondom.ru/testbase/08_ssl_apache.png)

# 5. Личный кабинет
Личный кабинет был уже реализован в ![шаблогне](http://remondom.ru/testbase/02_room.jpg)

# 6. Переключение языка интерфейса русский/английский (выбранный язык запоминается у зарегистрированного пользователя)

Создадим миграцию. Поле в таблице где будем хранить выбранный язык пользователя.
![](http://remondom.ru/testbase/03_migration_language.png)

Добавим это поле в модель для массового заполнения.
![](http://remondom.ru/testbase/04_fillable.jpg)

Маршрут для сохранения выбранного языка.
![](http://remondom.ru/testbase/05_route_language.jpg)

Сохранение языка в переменную сессии и в базу данных.
![](http://remondom.ru/testbase/06_save_language_session_db.jpg)

Язык готов.
![](http://remondom.ru/testbase/07_language_done.jpg)

# 7. Возможность поделиться реферальной ссылкой
Для создания реферальной системы воспользуемся советами из этой [https://dev.to/simioluwatomi/let-s-build-a-super-simple-referral-system-with-laravel-1o3h](статьи.)

Создадим миграцию которая добавит поле в таблицу для хранения значения реферальной ссылки.
![](http://remondom.ru/testbase/09_unique%20referal.png)

Создадим еще одну миграцию для поля refferal_id и внешний ключ на поле id таблице users. В referral_id будет хранится id пользователя от которого получили реферальную ссылку. Такая схем, с замыканием на себя, позволяет хранить деревья бесконечного уровня вложенности.
![](http://remondom.ru/testbase/10_referral_id.png)

Напишем хелпер который возвращает уникальное имя (некую сигнатуру), используя email пользователя, для формирования реферальной ссылки.

![](http://remondom.ru/testbase/11_helper_get_sign_refferal.png)

Зарегистрируем наш хелпер в composer.json

![](http://remondom.ru/testbase/12_composer_json.png)

Определим связи в модели User. Отношение "один ко многим" будет использоваться для того чтобы взять пользователя и получить всех людей которые зарегистрировались по его реферальной ссылки. Отношение "многие ко одному" позволет узнать от кого зарегистрировался пользователь

![](http://remondom.ru/testbase/13_belongth_many.png)

В личном кабинете будем выводить реферальную ссылку. Которую пользователь может передать другим людям, чтоб они регистрировались по его ссылки. Плюс я добавил кнопку копирования. Котоорая скопирует реферальную ссылку в буфер обмена.
![](http://remondom.ru/testbase/14_room_link_button.png)

JS вызываемый при нажатии на кнопку скопировать. Сначала положим содержимое в скрытый тег. Выделим содержимое тега. А потом выполним нативную команду js скопировать.

![](http://remondom.ru/testbase/15_button_js.png)

Изменим наш контролер RegisterController.php
Поскольку он использует трэйт Illuminate\Foundation\Auth\RegistersUsers То переопределим метод showRegistrationForm. В нем мы смотрим если в гет запросе пришла переменная ref и она при этом не пустая то положем ее содержимое в переменную сессии. Это означает, что пользователь регистрируется по ссылке.
А при создании пользователя, проверим есть ли в сессии переменная реферал. Если есть то мы удалим ее из сесси. И попытемся по ней найти пользователя в таблице users. И если новый пользователь пришел по ссылке и пользователь дал правильную ссылку то мы запишем id от кого он пришел.

![](http://remondom.ru/testbase/16_trait.png)

# 8. В личном кабинете страница вывода дерева рефералов до 5-го уровня

При открытии профиля сделаем выборку всех рефералов текущего пользователя используя активную загрузку. Что позволит уменьшить количество запросов к БД.
![](http://remondom.ru/testbase/17_activerecord.png)

![](http://remondom.ru/testbase/18_basepattern.png)

![](http://remondom.ru/testbase/19_childpattern.png)

Один шаблон используется для вывода первого уровня дерева. Если в элементе есть подчиненные элементы то подключаем другой шаблон который выведет эти подчиненные элементы. А если и там есть дети то шаблон подключит себя еще раз. Чтобы остановить эту рекурсию на 5-ой глубине вложенности, добавим счетчик глубины.

![](http://remondom.ru/testbase/20_done.png)
Вот и все. Готово.
