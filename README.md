# Symple PHP Framework 
Yet Another Symfony-based PHP Framework

[![Build Status](https://travis-ci.com/tholcomb/symple.svg?branch=master)](https://travis-ci.com/tholcomb/symple)
[![Latest Stable Version](https://poser.pugx.org/tholcomb/symple/v)](//packagist.org/packages/tholcomb/symple)
[![Latest Unstable Version](https://poser.pugx.org/tholcomb/symple/v/unstable)](//packagist.org/packages/tholcomb/symple)
[![License](https://poser.pugx.org/tholcomb/symple/license)](//packagist.org/packages/tholcomb/symple)

## Install

Quick install: `composer require tholcomb/symple`. 

It is recommended to require the individual components instead of this package. e.g. `composer require tholcomb/symple-http tholcomb/symple-twig`


## Usage
* Pimple is used for dependency injection: [Docs](https://pimple.symfony.com/)
* Controllers use Symfony's annotation-based routing: [Examples](https://symfony.com/doc/5.0/routing.html#creating-routes-as-annotations)

## Example:
```php
<?php
// ./src/MyController.php

namespace MyNamespace;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Tholcomb\Symple\Http\AbstractController;

/** @Route("/", name="my-", methods={"GET","HEAD"}) */
class MyController extends AbstractController {
  private $log;

  public function __construct(LoggerInterface $log)
  {
    $this->log = $log;
  }

  /** @Route("/", name="home") */
  public function home(): Response
  {
    $params = [
      'api_url' => $this->url('my-api'),
    ]; 

    return $this->renderToResponse('my-template.html.twig', $params);
  }
  
  /** @Route("/api", name="api", methods={"POST"}) */
  public function api(Request $req): JsonResponse
  {
    return $this->json(['status' => 'success']);
  }
}
```
```php
<?php
// ./src/MyProvider.php

namespace MyNamespace;

use Doctrine\Common\Cache\FilesystemCache;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tholcomb\Symple\Http\HttpProvider;
use Tholcomb\Symple\Logger\LoggerProvider;
use Tholcomb\Symple\Twig\TwigProvider;

class MyProvider implements ServiceProviderInterface {
  public function register(Container $c)
  {
    // Register the providers. In theory, these could be registered in any order
    $c->register(new LoggerProvider(), [
      'logger.path' => 'path/To/My/symple.log', // Optional - defaults to tmp_dir/symple.log
    ]);
    $c->register(new HttpProvider());
    $c->register(new TwigProvider(), [
      'twig.enable_routing' => true, // Optional - Enables generating URLs in templates
      'twig.cache_path' => 'path/To/My/Cache/', // Optional - Enables caching for Twig
    ]);
    
    $c['http.annotation_cache'] = function () { // Optional - Enable annotation cache
      return new FilesystemCache('path/To/My/Annot/Cache/'); // Must implement Doctrine\Common\Cache\Cache
    };
    
    HttpProvider::addController($c, MyController::class, function ($c) {
      return new MyController(LoggerProvider::getLogger($c, 'controller'));
    });
    TwigProvider::addTemplateDir($c, 'path/To/My/Templates/', 'optional_namespace');
  }
}
```
```php
<?php
// ./bootstrap.php

namespace MyNamespace;

use Tholcomb\Symple\Core\Symple;

require_once __DIR__ . '/vendor/autoload.php';

Symple::boot(); // Enables Symfony's ErrorHandler
Symple::registerEnv('path/To/My/.env'); // Optional - Uses symfony/dotenv
Symple::enableDebug(); // Optional - Force debug mode, automatically enabled if environment var APP_ENV === 'dev'
```
```php
<?php
// ./public/index.php

namespace MyNamespace;

require_once __DIR__ . '/../bootstrap.php';

use Pimple\Container;
use Tholcomb\Symple\Http\HttpProvider;

$c = new Container();
$c->register(new MyProvider());

HttpProvider::run($c);
```
```php
<?php
// ./bin/console

namespace MyNamespace;

require_once __DIR__ . '/../bootstrap.php';

use Pimple\Container;
use Tholcomb\Symple\Console\ConsoleProvider;

$c = new Container();
// The ConsoleProvider must be registered first for integration with other components.
$c->register(new ConsoleProvider(), [
  'console.app.name' => 'MyName', // Optional - Set console name
  'console.app.version' => 'v0.0', // Optional - Set console version
]);
ConsoleProvider::addBuiltinCommands($c);
$c->register(new MyProvider());

ConsoleProvider::getConsole($c)->run();
```