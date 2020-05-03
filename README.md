Laravel Terraform
====================
A bridge between Laravel and the [Terraform PHP API library](https://github.com/sdwru/terraform-v2).

## Installation

Laravel Terraform requires [PHP](https://php.net) 7.2-7.4 but I have only tried using it with PHP 7.3. This particular version probably supports Laravel 6-7 but I have only tried using it on Laravel 7.

Add the following dependencies to laravel composer.json
```
"require": {
    "sdwru/terraform-v2": "dev-master",
    "sdwru/laravel-terraform": "dev-master"
},
"repositories": [
    { "type": "git", "url": "https://github.com/sdwru/terraform-v2.git" },
    { "type": "git", "url": "https://github.com/sdwru/laravel-terraform.git" }
],
```
And run `composer update` from cli.

## Configuration

Laravel Terraform requires connection configuration.

To get started, you'll need to publish all vendor assets:

```bash
$ php artisan vendor:publish
```
and select `Provider: Sdwru\Terraform\TerraformServiceProvider`

This will create a `config/terraform.php` file in your app that you can modify to set your configuration. Also, make sure you check for changes to the original config file in this package between releases.

The following options should not be changed.

##### Default Connection Name

This option (`'default'`) is from the upstream code for changing http clients that this package is based on. We only use guzzle in this package so the default value for this setting should be left at `'main'`.

##### Terraform Connections

This option (`'connections'`) is where each of the connections are setup for your application if we were to support more than one http client. For now, we only use guzzle in this application.  We left this in this package for now.  It's possible we will support other clients in the future such as the Laravel v7 http client (also based on guzzle).

#### API

Gets the API configuration from the Laravel .env file


## Usage

##### TerraformManager

This is the class of most interest. It is bound to the ioc container as `'terraform'` and can be accessed using the `Facades\Terraform` facade. This class implements the `ManagerInterface` by extending `AbstractManager`. The interface and abstract class are both part of my [Laravel Manager](https://github.com/GrahamCampbell/Laravel-Manager) package, so you may want to go and checkout the docs for how to use the manager class over at [that repo](https://github.com/GrahamCampbell/Laravel-Manager#usage). Note that the connection class returned will always be an instance of `\Terraform\Client`.

##### Facades\Terraform

This facade will dynamically pass static method calls to the `'terraform'` object in the ioc container which by default is the `TerraformManager` class.

##### TerraformServiceProvider

This class contains no public methods of interest. This class uses automatic package discovery and therefore does NOT need to be added to the providers array in `config/app.php`. This class will setup ioc bindings.

##### Real Examples

Here you can see an example of just how simple this package is to use. Out of the box, the default adapter is `main`. After you enter your authentication details in the `.env` file, it should just work:

```php
use Sdwru\Terraform\Facades\Terraform;
// you can alias this in config/app.php if you like or add alias auto discover in the composer.json file.
// https://laravel-news.com/package-auto-discovery

Terraform::user()->getById(1);
// we're done here - how easy was that, it just works!

Terraform::job()->getAll();
// this example is simple, and there are far more methods available
```

The Terraform manager will behave like it is a `\TerraformV2\TerraformV2` class. If you want to call specific connections, you can do with the `connection` method:

```php
use Sdwru\Terraform\Facades\Terraform;

// the alternative connection is the other example provided in the default config
Terraform::connection('alternative')->rateLimit()->getRateLimit()->remaining;

// let's check how long we have until the limit will reset
Terraform::connection('alternative')->rateLimit()->getRateLimit()->reset;
```

With that in mind, note that:

```php
use Sdwru\Terraform\Facades\Terraform;

// writing this:
Terraform::connection('main')->region()->getAll();

// is identical to writing this:
Terraform::region()->getAll();

// and is also identical to writing this:
Terraform::connection()->region()->getAll();

// this is because the main connection is configured to be the default
Terraform::getDefaultConnection(); // this will return main

// we can change the default connection
Terraform::setDefaultConnection('alternative'); // the default is now alternative
```

If you prefer to use dependency injection over facades then you can easily inject the manager like so:

```php
use Sdwru\Terraform\TerraformManager;
use Illuminate\Support\Facades\App; // you probably have this aliased already

class Foo
{
    protected $Terraform;

    public function __construct(TerraformManager $Terraform)
    {
        $this->Terraform = $Terraform;
    }

    public function bar()
    {
        $this->Terraform->region()->getAll();
    }
}

App::make('Foo')->bar();
```

For more information on how to use the `\TerraformV2\TerraformV2` class we are calling behind the scenes here, check out the docs at https://github.com/toin0u/DigitalOceanV2#action, and the manager class at https://github.com/GrahamCampbell/Laravel-Manager#usage.

##### Further Information

There are other classes in this package that are not documented here. This is because they are not intended for public use and are used internally by this package.


## Security

If you discover a security vulnerability within this package, please send an email to Graham Campbell at graham@alt-three.com. All security vulnerabilities will be promptly addressed. You may view our full security policy [here](https://github.com/GrahamCampbell/Laravel-Terraform/security/policy).


## License

Laravel Terraform is licensed under [The MIT License (MIT)](LICENSE).


---

<div align="center">
	<b>
		<a href="https://tidelift.com/subscription/pkg/packagist-graham-campbell-Terraform?utm_source=packagist-graham-campbell-Terraform&utm_medium=referral&utm_campaign=readme">Get professional support for Laravel Terraform with a Tidelift subscription</a>
	</b>
	<br>
	<sub>
		Tidelift helps make open source sustainable for maintainers while giving companies<br>assurances about security, maintenance, and licensing for their dependencies.
	</sub>
</div>
