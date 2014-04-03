
# Updater

PHP command line utility that can be called by a deployment script, able to perform installation specific configuration, database updates and/or rollbacks.

It performs the following tasks:

- Perform database operations using PDO, required to update and/or rollback the database of a project's installation to a newer version

In the future the following tasks will be supported:

- Process custom PHP update scripts
- Rollback/update multiple versions in a chain
- Surgical rollback by keeping track of the individual steps in an update that failed

## Config

### Application Config

Copy and customize the following files (removing the `.dist` extension):

- `app/config/config.php.dist`

### Install PHP composer
You can download Composer using:

    $ curl -s https://getcomposer.org/installer | php
    
This will install a composer.phar script in the current directory. This file can be added to .gitignore, so you can keep it locally.

## Usage

Run the app from the command line using `app/console`. Use the following to see a list of commands:

    $ app/console list

### Run update

    $ app/console update <version_number> <path/to/updates/directory>
    
### Perform rollback

    $ app/console rollback <version_number> <path/to/updates/directory>


## Developer Guide

### Dependencies

This project requires PHP 5.3+. All external libraries can be installed
using Composer:

    $ php composer.phar install # Install all dependencies to your machine
    
Remember to periodically run the following command to update vendor software:

    $ php composer.phar update

### Tests

Run the tests using PHPUnit:

    $ phpunit
    
Generate code coverage HTML:

    $ phpunit --coverage-html ./phpunit-reports

## Troubleshooting

Updated whenever problems and solutions arise.