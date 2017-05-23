# Local Food Nodes

### Get started with the dev environment
* Install homebrew (http://brew.sh/index.html)
* Install virtualbox (https://www.virtualbox.org)
* Install docker and composer
`brew install docker docker-machine docker-compose homebrew/php/composer`.

#### Project
* Clone git repo
* Run `composer install` in project root directory

#### Docker
* Create the docker machine for local food nodes.
`docker-machine create --driver virtualbox local-food-nodes`

* Bind the machine environment variables to your terminal window.
`eval $(docker-machine env local-food-nodes)`

* Build and start docker containers.
`docker-compose build --no-cache`
`docker-compose up -d` or `docker-compose up` if you want to see the php error logs printed to the terminal

Nginx, PHP, MySQL, Redis is now up and running, now we need to configure the project.
* Run `docker-machine ls` to get the *docker-machine ip*.
* Get .env from David
* Open .env and update *DB_HOST* and *REDIS_HOST* with the *docker-machine ip*
* Run `php artisan migrate` to create the db tables.

### Build assets
Elixir...

#### All done!
Site is now up and running on http://[docker-machine ip]:8080

#### Problem with storage write permission
* php artisan cache:clear
* chmod -R 777 storage
* composer dump-autoload

# Testing
php artisan migrate --database='phpunit'
php artisan db:seed --database='phpunit'
