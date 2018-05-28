#!/bin/bash

ENV=${1:-master}
echo Deploying $ENV
ssh -i /root/.ssh/id_rsa root@10.2.24.193 'cd /home/Sync-UPR/master && sudo cp .env.example .env && sudo php artisan key:generate && sudo chmod -R 777 storage'


