<?php
/**
 * This file is part of the Symple framework
 *
 * Copyright (c) Tyler Holcomb <tyler@tholcomb.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tholcomb\Symple\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use Psr\Log\LoggerInterface;
use Tholcomb\Symple\Core\AbstractProvider;

class LoggerProvider extends AbstractProvider {
	protected const NAME = 'logger';

	public function register(Container $c)
	{
		parent::register($c);
		$c['logger'] = function ($c) {
			$path = $c['logger.path'];
			if (!is_resource($path)) {
				if (!is_dir(dirname($path))) mkdir(dirname($path));
				$path = fopen($path, 'a');
			}

			return new Logger($c['logger.name'], [
				new StreamHandler($path),
			]);
		};
		$c['logger.name'] = 'symple-logger';
		$c['logger.path'] = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'symple.log';
	}

	public static function getLogger(Container $c, ?string $name = null): LoggerInterface
	{
		static::isRegistered($c, true);
		$log = $c['logger'];
		if ($log instanceof Logger && $name !== null) return $log->withName($name);

		return $log;
	}
}