#!/bin/bash

#$ -N dewikinews-rss-update
#$ -o /data/project/dewikinews-rss/update.log
#$ -e /data/project/dewikinews-rss/update.log
#$ -l h_vmem=500M,release=stretch

php /data/project/dewikinews-rss/php/dewikinews-update.php
