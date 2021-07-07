<?php
/**
 * This file is part of the Symple framework
 *
 * Copyright (c) Tyler Holcomb <tyler@tholcomb.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tholcomb\Symple\Core;

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\ErrorHandler\ErrorHandler;

abstract class Symple {
	public const ENV_KEY = 'APP_ENV';
	public const DISABLE_ERROR_HANDLER_KEY = 'SYMPLE_DISABLE_ERROR_HANDLER';

	protected static $booted = false;
	protected static $debug = false;

	final public static function boot(): void
	{
		if (self::$booted === true) throw new \LogicException(__METHOD__ . ' should only be called once.');

		if (!isset($_SERVER[self::DISABLE_ERROR_HANDLER_KEY])) {
			ErrorHandler::register();
		}
		$env = $_SERVER[self::ENV_KEY] ?? null;
		if ($env === 'dev') {
			self::enableDebug();
		}
		self::$booted = true;
	}

	final public static function registerEnv(string $envFile): void
	{
		(new Dotenv())->loadEnv($envFile, self::ENV_KEY);
	}

	final public static function enableDebug(): void
	{
		if (self::$debug === true) return;

		if (!isset($_SERVER[self::DISABLE_ERROR_HANDLER_KEY])) {
			Debug::enable();
		}
		self::$debug = true;
	}

	final public static function isDebug(): bool
	{
		return self::$debug === true;
	}
}