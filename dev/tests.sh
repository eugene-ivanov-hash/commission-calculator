#!/usr/bin/env bash

set -e

echo -n 'Start Integration tests ... '
vendor/bin/phpunit --testsuite integrations
echo -e "\e[32mOK\e[0m"

echo -n 'Start Unit tests ... '
vendor/bin/phpunit --testsuite units
echo -e "\e[32mOK\e[0m"
