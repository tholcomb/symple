Symple PHP Framework - Yet Another Symfony-based PHP Framework

__USAGE__

Quick install: `composer require tholcomb/symple`. 

It is recommended to require the individual components instead of this package. e.g. `composer require tholcomb/symple-http tholcomb/symple-twig`

Controllers use Symfony's annotation-based routing.


Front controller example:
```php
<?php
// ./public/index.php

namespace MyNamespace;

use Doctrine\Common\Cache\FilesystemCache;
use Pimple\Container;
use Tholcomb\Rw\Core\Symple;
use Tholcomb\Rw\Http\HttpProvider;
use Tholcomb\Rw\Logger\LoggerProvider;
use Tholcomb\Rw\Twig\TwigProvider;

Symple::boot(); // Enables Symfony's ErrorHandler
Symple::registerEnv('path/To/My/.env'); // Optional - Uses symfony/dotenv
Symple::enableDebug(); // Optional - Force debug mode, automatically enabled if environment var APP_ENV === 'dev'

$c = new Container();

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
  return new MyController($c['dep1'], $c['dep2']);
});
TwigProvider::addTemplateDir($c, 'path/To/My/Templates/', 'optional_namespace');

HttpProvider::run($c);
```