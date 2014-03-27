
# Updater

Update utility that can be called by a deployment script, able to perform installation specific configuration, database updates and/or rollbacks.

It performs the following tasks:

- Determine the next version to update to by scanning a directory structure
- Perform MySQL operations required to update and/or rollback the database of a project's installation to a newer version
- Update and/or rollback configuration files


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

### Pushing Notifications

    $ app/console update


## Developer Guide

### Dependencies

This project requires PHP 5.3+. All external libraries can be installed
using Composer:

    $ php composer.phar install # Install all dependencies to your machine
    
Remember to periodically run the following command to update vendor software:

    $ php composer.phar update

### Tests

Run the tests using PHPUnit. Make sure to use the `phpunit.xml` config
file in the `app` directory, using the `-c` option:

    $ phpunit -c app

## Troubleshooting

Updated whenever problems and solutions arise.