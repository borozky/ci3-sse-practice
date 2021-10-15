# Experimenting Codeigniter 3 with Server-Sent events

## Setup
Assuming you have docker installed and have cloned the directory

```sh
# Create .env file.
# IMPORTANT: Adjust your values
cp .env.example .env

# Copy .env credentials into application/config/development/constants.php file
# We're going to use docker, therefore the mysql host will be 'mysql'
CONST_DIR=application/config/development 
mkdir -p $CONST_DIR 
cat .env | grep 'DB_DATABASE' | tail -1 | awk -F '=' '{ print $2 }' | xargs printf "<?php\n\ndefined('DB_DATABASE') OR define('DB_DATABASE', '%s');\n" >> $CONST_DIR/constants.php
cat .env | grep 'DB_USERNAME' | tail -1 | awk -F '=' '{ print $2 }' | xargs printf "defined('DB_USERNAME') OR define('DB_USERNAME', '%s');\n" >> $CONST_DIR/constants.php
cat .env | grep 'DB_PASSWORD' | tail -1 | awk -F '=' '{ print $2 }' | xargs printf "defined('DB_PASSWORD') OR define('DB_PASSWORD', '%s');\n" >> $CONST_DIR/constants.php
printf "defined('DB_HOST') OR define('DB_HOST', 'mysql');\n\n" >> $CONST_DIR/constants.php

# Verify
cat $CONST_DIR/constants.php

# Build image and start
docker-compose build --no-cache
docker-compose up -d

# Install dependencies
docker-compose exec --user $USER:$USER web bash -c "composer install"
```

Then browse the site on `localhost`
