Drilex [![License](https://poser.pugx.org/atphp/drilex/license.png)](https://packagist.org/packages/atphp/drilex)
====

Drilex is a Silex starter project which use Drupal as backend to manage user and content. This is being developed, not ready for production yet.

## Install

```bash
git clone git@github.com:atdrupal/drilex.git drilex
cd drilex
composer install
# Update config.php to include Drupal connection params
```

## Usage

1. Start the testing server

    `php -S localhost:8888 -t public/`

2. Then you can login with your account in Drupal CMS: /user/login
3. The entity is accessible: /node/1
4. Logout: /user/logout
