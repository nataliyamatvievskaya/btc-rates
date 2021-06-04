# test task for perfect panel

## Задание 1

SELECT 
    U.id as ID,
    CONCAT(U.first_name, ' ', U.last_name) as Name,
    B.author as Author,
    GROUP_CONCAT(DISTINCT B.name
        ORDER BY B.id ASC
        SEPARATOR ', ') AS books
FROM
    `user_books` as UB
        left JOIN
    users as U ON UB.user_id = U.id
        LEFT JOIN
    `books` as B ON UB.book_id = B.id
WHERE
    U.age = 17
GROUP BY UB.user_id , B.autor
HAVING count(B.author) = 2

## Задание 2

описание проекта
Стек
 - PHP 7.2
 - Redis
 - используемые библиотеки и фреймворки: Slim(маршрутизация приложения), Guzzle(обработка запросов и ответов), predis (работа c Redis)
 
 Структура приложения
  - web/index.php - точка входа
  - app - основная реализация
  - app/Actions - бизнес логика
  - app/Cache - механизм(ы) кэширования
  - app/Config - конфигурационные файлы
  - app/ControllerV1 - контроллер(ы)
  - app/Rates - получение данных от внешнего API
  - app/Store - модели

Кеширование курсов включается в настройках - app/Config/bootstrap.php и кеширует курсы от API на 60 секунд
