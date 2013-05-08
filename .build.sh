#!/bin/bash

# Run php lint test
php .lint.php

# Run coding standard check
php .style.php

# Install dependencies
composer install

# Run unit tests
phpunit
