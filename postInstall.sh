sleep(15)
docker exec -it php74-container bash
symfony console doctrine:database:create --env=test
symfony console doctrine:migrations:migrate -n --env=test
exit