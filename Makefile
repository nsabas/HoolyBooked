start:
	docker-compose up -d
	docker-compose exec php composer install

init_db:
	docker-compose exec php bin/console doctrine:database:create --if-not-exists
	docker-compose exec php bin/console doctrine:migrations:migrate -n

tests:
	docker-compose exec php php bin/phpunit

run_remind_worker:
	docker-compose exec php php bin/console messenger:consume scheduler_remind_booking

run_manual_remind:
	docker-compose exec php php bin/console app:remind_booking
