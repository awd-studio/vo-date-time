#!make

# ———————————————————————— 🔧 Environment Imports ———————————————————————————————
-include .env.dist
-include .env
-include .env.dev
-include .env.override
export

# ———————————————————————— 🧩 Variables —————————————————————————————————————————
MERGED_FILE := .env.merged
ENV_SOURCES := $(wildcard .env.dist .env .env.$(APP_ENV) .env.override)

# Docker command helpers
DOCKER = docker
MAKE_SILENT = $(MAKE) --no-print-directory

# Pre-check for .env.merged generation
define DOCKER_PREP
@$(MAKE_SILENT) env-merge
endef

# Docker Compose with auto env-merge
DOCKER_COMPOSE = $(MAKE_SILENT) env-merge && docker compose --env-file .env.merged

# Log formatting helpers
GREEN = /bin/echo -e "\x1b[32m\#\# $1\x1b[0m"
RED = /bin/echo -e "\x1b[31m\#\# $1\x1b[0m"

# PHP container interaction
EXEC = $(DOCKER) exec -it $(DOCKER_SERVICE_NAME_PHP)
PHP = $(EXEC) php
COMPOSER = $(EXEC) composer

## ————————————————— 🔥 Project Lifecycle ———————————————————————————————————————
.PHONY: init
init: ## Init the project
	$(MAKE_SILENT) env-merge
	$(MAKE_SILENT) build
	$(MAKE_SILENT) start
	$(COMPOSER) install --prefer-dist
	$(COMPOSER) dev-tools-setup
	@$(call GREEN,"The application installed successfully.")

.PHONY: rebuild
rebuild: ## Rebuild all Docker containers
	$(MAKE_SILENT) stop
	$(DOCKER_COMPOSE) up -d --no-deps --build

.PHONY: php
php: ## Open PHP container shell
	$(DOCKER_COMPOSE) up -d php-fpm
	$(MAKE_SILENT) php-bash

.PHONY: php-bash
php-bash:
	$(DOCKER_COMPOSE) exec php-fpm bash -l

## ————————————————— 🏁 Runtime Control —————————————————————————————————————————
.PHONY: start
start: ## Start the application
	$(DOCKER_COMPOSE) up -d
	@$(call GREEN,"The application is available at: $(HOST).")

.PHONY: stop
stop: ## Stop the app
	$(DOCKER_COMPOSE) stop
	@$(call GREEN,"The containers are now stopped.")

.PHONY: down
down: ## Completely remove all containers
	$(DOCKER_COMPOSE) down
	@$(call GREEN,"The containers are now down.")

## ————————————————— ✅️ Quality & Testing ———————————————————————————————————————
.PHONY: test
test: ## Run all tests
	$(DOCKER_PREP)
	$(COMPOSER) test

.PHONY: code-fix
code-fix: ## Runs quality tools to fix common code issues
	$(DOCKER_PREP)
	$(COMPOSER) code-fix

## ————————————————— 🎻 Composer ————————————————————————————————————————————————
.PHONY: composer-install
composer-install: ## Install composer dependencies
	$(COMPOSER) install

.PHONY: composer-update
composer-update: ## Update composer dependencies
	$(COMPOSER) update

.PHONY: composer-clear-cache
composer-clear-cache: ## Clear composer cache
	$(COMPOSER) clear-cache

## ————————————————— 🛠️ Utilities ——————————————————————————————————————————————
.PHONY: env-merge
env-merge: ## Generate .env.merged from all env layers
	@NEW_ENV=$$(cat /dev/null \
		$(shell [ -f .env.dist ] && echo .env.dist) \
		$(shell [ -f .env ] && echo .env) \
		$(shell [ -f .env.dev ] && echo .env.dev) \
		$(shell [ -f .env.override ] && echo .env.override) \
		| grep -v '^#' | grep -v '^\s*$$' | awk -F= '!seen[$$1]++'); \
	OLD_ENV=$$(cat .env.merged 2>/dev/null || echo ""); \
	if [ "$$NEW_ENV" != "$$OLD_ENV" ]; then \
		echo "$$NEW_ENV" > .env.merged; \
		echo "🔄 Regenerated .env.merged"; \
	else \
		echo "✅ .env.merged is up to date."; \
	fi

## ————————————————— 📚 Help ————————————————————————————————————————————————————
.PHONY: help
help: ## Show all commands
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' Makefile | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'
