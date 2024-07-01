# - Variables
# Project name
PROJECT = "ayyure"
# Docker-compose Dev file
DOCKER_COMPOSE_DEV_FILE = "docker-compose.dev.yml"
# Use bash as the shell
SHELL := /usr/bin/env bash

# - Mandatory
all: help

.PHONY: all help help-md autophony up down dev

# - Misc.
help: ## Show this help.
	@grep "##" $(MAKEFILE_LIST) | grep -v "grep" | sed 's/:.*##\s*/:@\t/g' | column -t -s "@"

help-md: ## Show this help but in a markdown styled way.
	@grep "##" $(MAKEFILE_LIST) | grep -v "grep" | sed -E 's/([^:]*):.*##\s*/- ***\1***:@\t/g' | column -t -s "@"

autophony: ## Generate a .PHONY rule for your Makefile using all rules in the Makefile(s).
	@grep -oE "^[a-zA-Z-]*\:" $(MAKEFILE_LIST) | sed "s/://g" | xargs echo ".PHONY:"

# - Basic
up: ## Up the needed containers.
	@docker compose -f $(DOCKER_COMPOSE_DEV_FILE) --project-name $(PROJECT) up --remove-orphans --build -d

down: ## Down the needed containers.
	@docker compose -f $(DOCKER_COMPOSE_DEV_FILE) --project-name $(PROJECT) down --remove-orphans

dev: ## Run the developpement server.
	@symfony server:start
