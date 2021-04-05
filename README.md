# PS5 Stock Checker

Symfony application which checks different sources for Playstation 5 stock and sends an email notification

## Requirements

* PHP >= 7.4

## Installation

1. Clone this repository
2. Install dependencies with `composer install`
3. Set the `MAILER_DSN` and `EMAIL_RECIPIENT` environment variables. The simplest way to do this is to create a `.env.local` file and add them there

## Usage

```sh
bin/console app:process
```

Pass in the `-h` flag to see what arguments and options you may use

```sh
bin/console app:process -h
```

To ensure that email notifications are working, use the `app:test` command:

```sh
bin/console app:test
```

## Cron Job

Add the following to your cron table to run the checker every 5 minutes:

```
*/5 * * * * php /path/to/ps5-stock-checker/bin/console app:process >/dev/null 2>/dev/null
```

## Sources

The application currently checks the following sources:

* [Argos](https://www.argos.co.uk/)
* [Box](https://www.box.co.uk/)
* [Game](https://www.game.co.uk/)
* [Smyths](https://www.smythstoys.com/uk/en-gb)

PRs are welcome if you'd like to add more, just make sure they implement the [SiteInterface](src/Component/SiteInterface.php)

## License

Released under the [MIT license](LICENSE)