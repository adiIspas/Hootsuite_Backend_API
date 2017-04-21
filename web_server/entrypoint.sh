#!/bin/bash
rm -rf /var/www/app/cache/*
exec php -S 0.0.0.0:8000 # This will run a web-server on port 8000