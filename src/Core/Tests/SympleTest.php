<?php
/**
 * This file is part of the Symple framework
 *
 * Copyright (c) Tyler Holcomb <tyler@tholcomb.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tholcomb\Symple\Core\Tests;

use PHPUnit\Framework\TestCase;
use Tholcomb\Symple\Core\Symple;

class SympleTest extends TestCase {
	private const ENV_FILE = __DIR__ . '/env/.env';

	protected function setUp(): void
	{
		if (isset($_SERVER[Symple::ENV_KEY])) unset($_SERVER[Symple::ENV_KEY]);
		$_SERVER[Symple::DISABLE_ERROR_HANDLER_KEY] = true;
	}

	public function testDotenvDefault(): void
	{
		Symple::registerEnv(self::ENV_FILE);
		$this->assertTrue(isset($_SERVER['TEST_VAR']), 'TEST_VAR not set');
		$this->assertEquals(123, $_SERVER['TEST_VAR'] ?? null);
		$this->assertEquals('dev', $_SERVER['TEST_ENV'] ?? null);
	}

	public function testDotenvProd(): void
	{
		$_SERVER[Symple::ENV_KEY] = 'prod';
		Symple::registerEnv(self::ENV_FILE);
		$this->assertEquals(456, $_SERVER['TEST_VAR'] ?? null);
		$this->assertEquals('prod', $_SERVER['TEST_ENV'] ?? null);
	}

	public function testAutoDebugMode(): void
	{
		ResetSymple::reset();
		Symple::boot();
		$this->assertFalse(Symple::isDebug(), 'Debug was set without dev APP_ENV');

		ResetSymple::reset();
		$_SERVER[Symple::ENV_KEY] = 'dev';
		Symple::boot();
		$this->assertTrue(Symple::isDebug(), 'Debug was not enabled with dev APP_ENV');
	}

	public function testDoubleBoot(): void
	{
		ResetSymple::reset();
		$this->expectException(\LogicException::class);
		$this->expectExceptionMessageMatches('/once/');

		Symple::boot();
		Symple::boot();
	}

	public function testDebug(): void
	{
		ResetSymple::reset();
		Symple::enableDebug();
		$this->assertTrue(Symple::isDebug(), 'Debug was not set');
	}
}

class ResetSymple extends Symple {
	public static function reset(): void
	{
		static::$booted = false;
		static::$debug = false;
	}
}