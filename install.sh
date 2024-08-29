#!/bin/bash

#Build docker image
docker compose up --build -d \
&& echo -e "\n${PURPLE} Please wait while installing composer Packages ... \n ${NC}" \
&&  sleep 5 && docker exec tours composer install --ignore-platform-reqs \
&& echo -e "\n${PURPLE} Generating application keys \n ${NC}" \
&&  docker exec flavors php artisan key:generate \
&& echo -e "\n${PURPLE} Running database migrations \n ${NC}" \
&&  docker exec flavors php artisan migrate \
&& echo -e "\n${PURPLE} seed data to database \n ${NC}" \
&&  docker exec tours php artisan db:seed 
&& echo -e "\n${PURPLE} create jwt secrets \n ${NC}" \
&&  docker exec tours php artisan jwt:secret
&& echo -e "\n${PURPLE} Obtaining the necessary permissions for the sessions and the local storage ... \n ${NC}" \
&&  sudo chmod -R 777 storage/framework/* \
&&  sudo chmod -R 777 storage/* \
&&  sudo chmod -R 777 storage/app/public/images/* \
&& echo -e "\n${GREEN}  Everything done \n ${NC}" \
