docker-stop:
	docker compose -f dev/docker-compose.yml stop

docker-build:
	docker compose -f dev/docker-compose.yml down -v
	docker compose -f dev/docker-compose.yml build

docker-up:
	docker compose -f dev/docker-compose.yml up
