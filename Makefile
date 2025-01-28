# Variables
PHP = php
REMOVE_FILE = rm
ADD_FILE = touch
ADD_FOLDER = mkdir
COMPOSER = composer
SYMFONY = symfony
COMPOSER_INSTALL = $(COMPOSER) require
SYMFONY_CONSOLE = $(APP_FOLDER) $(PHP) bin/console

## —— 🔥 App ——
init: ## Initialiser un projet Symfony complet
		$(MAKE) composer-install-twig
		$(MAKE) composer-install-doctrine
		$(MAKE) composer-install-maker
		$(MAKE) composer-install-fixtures
		$(MAKE) composer-install-security
		$(MAKE) composer-install-form
		$(MAKE) composer-install-validator
		$(MAKE) composer-install-asset
		$(ADD_FOLDER) public/assets
		$(ADD_FOLDER) public/assets/css
		$(ADD_FOLDER) public/assets/js
		$(ADD_FOLDER) public/assets/js/class
		$(ADD_FOLDER) public/assets/images
		$(ADD_FOLDER) public/assets/icons
		$(ADD_FILE) public/assets/css/style.css
		$(ADD_FILE) public/assets/js/main.js
		@$(call "The application is available at: http://127.0.0.1:8000/.")

add-data: ## Création de la base de donnée, gestion des migrations & fixtures
		$(COMPOSER) install
		$(SYMFONY_CONSOLE) doctrine:database:create
		$(SYMFONY_CONSOLE) doctrine:schema:update --force
		$(SYMFONY_CONSOLE) d:f:l --no-interaction
		
## —— 🎻 Composer ——
composer-install-twig: ## Installation de twig
		$(COMPOSER_INSTALL) twig

composer-install-doctrine: ## Installation de doctrine
		$(COMPOSER_INSTALL) doctrine --no-interaction
		$(REMOVE_FILE) compose.override.yaml
		$(REMOVE_FILE) compose.yaml

composer-install-maker: ## Installation de maker
		$(COMPOSER_INSTALL) maker --dev

composer-install-fixtures: ## Installation de orm-fixtures
		$(COMPOSER_INSTALL) orm-fixtures

composer-install-security: ## Installation de security
		$(COMPOSER_INSTALL) security

composer-install-form: ## Installation de symfony form
		$(COMPOSER_INSTALL) form

composer-install-validator: ## Installation de validator
		$(COMPOSER_INSTALL) validator

composer-install-asset: ## Installation de validator
		$(COMPOSER_INSTALL) asset

## —— 🎶 Symfony ——

migration: ## Effectuer tous le processus de migration
		$(MAKE) make-migration
		$(MAKE) migration-migrate

make-migration: ## Créer une migration
		$(SYMFONY_CONSOLE) make:migration --no-interaction

migration-migrate: ## Migrer la migration
		$(SYMFONY_CONSOLE) doctrine:migrations:migrate --no-interaction

## —— 🛠️ Others ——
help: ## List of commands
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'