SHELL := /bin/bash

# Essentials
init:
	@make init-dev
	@make generate-keys
	@make start
	@sleep 30
	make doctrine-init
	@echo "Initialization completed."

# Enviroment Initialization
init-dev:
	@if [ -f .env.dist ]; then cp .env.dist .env; fi;

generate-keys:
	@if [ ! -d config/jwt ]; then mkdir config/jwt; fi;
	openssl genrsa -out config/jwt/private.pem -aes256 4096
	openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem

# Development
start:
	@docker-compose up -d
	@make update
	@echo; echo "Done."

stop:
	@docker-compose stop
	@echo; echo "Done."

update:
	@docker-compose exec -T php composer install
	@make fix-permissions

restart:
	make stop
	make start

setup-permissions:
	@echo Setting up permissions...
	@docker-compose exec php chown -R www-data:www-data /app || true
	@docker-compose exec php chmod -R 777 /app/var || true

# Linux related
fix-permissions:
	sudo find config -type f -exec chmod 666 {} +
	sudo find . -type d -exec chmod 777 {} +

# Other symfony related
doctrine-init:
	@docker-compose exec php sh -c 'php bin/console doctrine:database:create --if-not-exists --no-interaction'
	@docker-compose exec php sh -c 'php bin/console doctrine:schema:create --no-interaction'

doctrine-update:
	@docker-compose exec php sh -c 'php bin/console doctrine:schema:update --force'

run-tests:
	@docker-compose exec php sh -c "php bin/behat"
