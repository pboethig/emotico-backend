##Install and start container
run ./start_composer.sh

##start websocket server
this starts the websocketserver. Leave the terminal open.
run ./start_app.sh

## test sample websocketclient
open <root>/ClientApp/index.html in a browser
run tests inside the app
```sh
./vendor/bin/phpunit src/MittaxMediaConverterBundle/
```
this will push convertermessages to the sampleclient 


if you stuck on mongodb during composer install use this composer require:
```sh
composer require jenssegers/mongodb --ignore-platform-reqs
```

RabbitMQ Panel:
http://localhost:15672

Assets public url:
http://localhost:8181/assets

Export public url:
http://localhost:8181/exports