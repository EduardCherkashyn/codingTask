inside task_php container:
````
1) Create database:

vendor/bin/doctrine orm:schema-tool:create
````
````
2) Run migrations:

./vendor/bin/doctrine-migrations migrate
