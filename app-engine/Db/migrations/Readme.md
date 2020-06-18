Для выполнения миграции локально

1. Войти в контейнер

    ```
    docker exec -it honey-flow_php_1 bash
    ```

1. Перейти в директорию с файлами миграции

    ```
    cd /app-engine/Db/migrations
    ```

1. Запустить файл миграции с флагом up для наката и down для отката

    ```
    php migration_001.php up
    ```

