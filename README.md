# Local Food Nodes

### Get started with the dev environment
* Install VirtualBox (https://www.virtualbox.org)
* Install Composer
* Install Laravel Homestead

#### Project
* Clone git repo
* Run `composer install` in project root directory

### Setup dev environment
* Follow the Homestead documentation

### Elasticsearch
* Make sure the docker-machine is running.
* SSH to docker-machine with `docker-machine ssh` and run `sudo sysctl -w vm.max_map_count=262144` to increase value.
* Run `docker-compose up -d` to start elasticsearch container

### Build assets
We're using elixir, just run `gulp watch` in terminal.

#### Problem with storage write permission
* php artisan cache:clear
* chmod -R 777 storage
* composer dump-autoload

# Testing
php artisan migrate --database='phpunit'
php artisan db:seed --database='phpunit'

# Renew certificate
letsencrypt_webfaction --letsencrypt_account_email info@localfoodnodes.org --domains localfoodnodes.org --public ~/webapps/localfoodnodes_2/public --username [ssh username] --password [ssh password]
