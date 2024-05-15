.PHONY: tree
tree:
	tree -I 'node_modules|vendor|.git' > directory_structure.txt

.PHONY: up
up:
	docker-compose up -d

.PHONY: up-build
up-build:
	docker-compose up -d --build

.PHONY: down
down:
	docker-compose down

