#!/bin/bash

composer install

cp .env.example .env

./vendor/bin/sail npm install
./vendor/bin/sail npm run production

./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate:fresh --seed
