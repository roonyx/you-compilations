#!make
export $(shell sed 's/=.*//' ./src/.env)
RED='\033[0;31m'         #  ${RED}
GREEN='\033[0;32m'       #  ${GREEN}
YELLOW='\033[0;33m'      #  ${GREEN}
BOLD='\033[1;m'          #  ${BOLD}
WARNING='\033[37;1;41m'  #  ${WARNING}
END_COLOR='\033[0m'      #  ${END_COLOR}

.PHONY: rebuild up stop down restart status console help

rebuild: stop
	@echo ${BOLD}"\nRebuilding containers...\n" ${END_COLOR}
	docker-compose build --no-cache

up:
	@echo ${BOLD}"\nSpinning up containers...\n" ${END_COLOR}
	docker-compose up -d
	@$(MAKE) --no-print-directory status

up-build:
	@echo ${BOLD}"\nSpinning up containers...\n" ${END_COLOR}
	docker-compose up -d --build
	@$(MAKE) --no-print-directory status

stop:
	@echo ${BOLD}"\nHalting containers..." ${END_COLOR}
	@docker-compose stop
	@$(MAKE) --no-print-directory status

down:
	@echo ${BOLD}"\nRemoving containers..." ${END_COLOR}
	@docker-compose down
	@$(MAKE) --no-print-directory status

restart:
	@echo ${BOLD}"\nRestarting containers...\n" ${END_COLOR}
	@docker-compose stop
	@$(MAKE) up

status:
	@echo ${BOLD}"\nContainers statuses\n" ${END_COLOR}
	@docker-compose ps

console-php:
	@docker-compose exec php bash
console-db:
	@docker-compose exec db bash

logs:
	@docker-compose logs --tail=100 -f
logs-db:
	@docker-compose logs --tail=100 -f db
logs-php:
	@docker-compose logs --tail=100 -f php