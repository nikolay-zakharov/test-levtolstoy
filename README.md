# Тестовое задание "Лев Толстой - не простой"

### Запуск
1. `cp .env.example .env`
1. `docker-compose up --build -d`
1. Подключиться к `lytican_levtolstoy.database`, создать чистую БД, выполнить миграции.

### API
1. Файл `api.http` содержит все методы с предустановленными параметрами. Для него env-файлом является `http-client.env.json`.
