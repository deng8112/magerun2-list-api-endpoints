# magerun2 plugin: List all API endpoints

[![Build Status](https://travis-ci.org/bitExpert/magerun2-list-api-endpoints.svg?branch=master)](https://travis-ci.org/bitExpert/magerun2-list-api-endpoints)

This is a plugin for [netz98 Magerun2](https://github.com/netz98/n98-magerun2) to list all API endpoints.

## Installation

### For your OS-user

n98-magerun2 will automatically load the plugins laying under `~/.n98-magerun2/modules` (create this folder if it does not exist). 

To add this command, simply clone the repository.

```
git clone git@github.com:bitExpert/magerun2-list-api-endpoints.git ~/.n98-magerun2/modules/magerun2-list-api-endpoints
```

### For specific projects

The preferred way of installing `bitexpert/magerun2-list-api-endpoints` is through Composer.
Simply add `bitexpert/magerun2-list-api-endpoints` as a dev dependency:

```
composer.phar require --dev bitexpert/magerun2-list-api-endpoints
```

## Usage

This plugin adds the `api:list:endpoints` command to magerun2.

You are able to filter routes by their respective HTTP methods. To only
see `GET` routes, run magerun2 like this:

```
magerun2 api:list:endpoints --method=get
```

To list all `GET` and `POST` routes, pass a comma-separated list as method argument:

```
magerun2 api:list:endpoints --method=get,post
```

You are able to filter routes by their url. To only see `customers` routes,
run magerun2 like this:

```
magerun2 api:list:endpoints --route=customers
```

Both filters can be combined, to show only `customers` routes with the `GET`
method, run magerun2 like this:

```
magerun2 api:list:endpoints --route=customers --method=get
```

## Contribute

Please feel free to fork and extend existing or add new features and send
a pull request with your changes! To establish a consistent code quality,
please provide unit tests for all your changes and adapt the documentation.

## Want To Contribute?

If you feel that you have something to share, then we’d love to have you.
Check out [the contributing guide](CONTRIBUTING.md) to find out how, as
well as what we expect from you.

## License

This plugin is released under the Apache 2.0 license.
