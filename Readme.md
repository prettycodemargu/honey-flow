###### Запуск проекта локально

1. Прописать в /etc/hosts

    ```
    192.168.210.2   honey-flow.local
    192.168.210.3   api.honey-flow.local
    ```

1. Запустить docker-контейнеры

    ```
    cd honey-flow &&
    docker-compose up
    ```

1. Установить пакеты бэкенда

    ```
    cd honey-flow/app-engine &&
    composer install  
    ```

1. Установить пакеты фронтенда

    ```
    cd honey-flow/app-front &&
    npm install
    ```

1. Собрать билд для фронтенда

    ```
    npm run build
    ```

1. Выполнить миграции (см. Readme в папке миграций)

1. Открыть в браузере http://honey-flow.local

