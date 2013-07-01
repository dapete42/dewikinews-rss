#!/bin/bash

#$ -N dewikinews-rss-update
#$ -o /data/project/dewikinews-rss/update.log
#$ -e /data/project/dewikinews-rss/update.log
#$ -l h_vmem=500M

php /data/project/dewikinews-rss/public_html/dewikinews-update.php
