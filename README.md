# Laravel Route Report

A Laravel package that provides a command to analyze route definitions and detect duplicate routes and non-existent actions.

## Installation

To install this package, run the following command in your terminal:

```bash
composer require sbuicodev/laravel-route-report
```

## Usage

To run the route report command, use the following artisan command:

```bash
php artisan route:report
```

This command will display two tables:

*   **Duplicate Routes**: A list of routes that are defined more than once, along with their corresponding actions.
*   **Non-Existent Actions**: A list of routes that point to non-existent actions.

## Features

*   Detects duplicate routes and displays them in a table format.
*   Identifies non-existent actions and displays them in a table format.
*   Provides a clear and concise report to help you optimize your route definitions.

## Requirements

*   Laravel 10.x or higher
*   PHP 8.1 or higher

## Contributing

Contributions are welcome! If you find any issues or have suggestions for improvement, please submit a pull request.

## License

This package is licensed under the MIT License.

## TODO
- Implement tests

## Author

Stefano Buico (sbuicodev)