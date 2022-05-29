symfony console doctrine:database:create --env=test
symfony console doctrine:migrations:migrate -n --env=test
./bin/phpunit tests/testProducts.php 
exit
