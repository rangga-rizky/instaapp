run:
	php artisan serve
build:
	docker build . -t ranggarizky/instaapp -f ./Dockerfile
build-prod:
	docker build . -t ranggarizky/instaapp  -f ./Dockerfile --platform linux/amd64
push:
	$(MAKE) build-prod
	docker push ranggarizky/instaapp
test:
	php artisan test
