# `Commission Calculator`

## Description
Symfony command to calculate commission for transactions from file.

File should contain transactions in JSON format. Example of file:

```
{"bin":"45717360","amount":"100.00","currency":"EUR"}
{"bin":"45417360","amount":"10000.00","currency":"JPY"}
{"bin":"41417360","amount":"130.00","currency":"USD"}
{"bin":"4745030","amount":"2000.00","currency":"GBP"}
```

## Basic usage
```shell
php bin/console app:calculate:commissions <path_to_transcations_file>
```
Arguments:

`filePath`              Path to file with transactions

Options:

`-h, --help`            Display help for the given command. When no command is given display help for the list command

`-q, --quiet`           Do not output any message

`-V, --version`         Display this application version

`--ansi|--no-ansi`  Force (or disable --no-ansi) ANSI output

`-n, --no-interaction`  Do not ask any interactive question

`-e, --env=ENV`         The Environment name. [default: "dev"]

`--no-debug`        Switch off debug mode.

`--profile`         Enables profiling (requires debug).

`-v|vv|vvv, --verbose`  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

## Setup natively

### Requirements
1. PHP 8.2 or higher;
2. Composer;
3. Make;

### Initial setup
```shell
make setup
```
```shell
composer install
```

### Run application
```shell
php bin/console app:calculate:commissions <path_to_transcations_file>
```

## Setup with docker

### Requirements
1. Docker;
2. Make;

### Initial setup
```shell
make setup
```
```shell
docker build --target php-prod -t commission-calculator .
```

### Run application
```shell
docker run --rm -v <path_to_file>:/srv/app/transactions.txt commission-calculator
```

Example:
```shell
docker run --rm -v ./tests/Integration/Command/fixtures/transactions_positive.txt:/srv/app/transactions.txt commission-calculator
```


## Setup for development

### Requirements
1. Docker;
2. Docker Compose;
3. Make;

### Initial setup
```shell
make setup
```

### Run application
```shell
make docker-up
```

### Debug
Use `XDEBUG_ENABLE` environment variable to enable (`1`) or disable (`0`) Xdebug. To apply these changes, run
```shell
make docker-up
```

### Testing
```shell
make tests
```
