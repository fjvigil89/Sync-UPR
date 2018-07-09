#!/bin/bash

ENV=${1:-master}
echo Deploying $ENV
#ssh -i /root/.ssh/id_rsa root@10.2.24.192 'cd /home/Sync-UPR/master && sudo ./vendor/bin/swagger app/Http --output public/api/'

