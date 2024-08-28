ifneq ("$(wildcard ./.env)","")
	include .env
	export $(shell sed 's/=.*//' .env)
endif

.PHONY: setup snapshot tests

setup:
	@dev/setup.sh

docker-up:
	@docker compose build
	@docker compose run --rm php dev/install.sh
	@docker compose up --detach

docker-php-cli:
	@docker compose exec php zsh

docker-down:
	@docker compose down

tests:
	@docker compose exec php dev/tests.sh
