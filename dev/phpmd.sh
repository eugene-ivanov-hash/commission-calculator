#!/usr/bin/env bash

set -e

./vendor/bin/phpmd src/ text phpmd.xml
