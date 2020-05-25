.PHONY: build up down restart migrate help docs

help: ## Show this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build: ## Build base php-fpm image & npm & composer
	sh build.sh

up: ## Run services
	docker-compose up -d

down: ## Stop services and remove containers
	docker-compose down --remo

restart: ## Restart all services
	@make -s down
	@make -s up

logs: ## Shortcut to get inside app container
	docker-compose logs -f

migrate:
	docker-compose run --rm php-fpm php bin/console doctrine:migrations:migrate
	docker-compose run --rm php-fpm php bin/console doctrine:fixtures:load

test:
	docker-compose run --rm php-fpm php bin/phpunit
