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
use Pimple\Container;
use Tholcomb\Symple\Core\UnregisteredProviderException;

class AbstractProviderTest extends TestCase {
	public function testIsRegistered()
	{
		$this->expectException(UnregisteredProviderException::class);
		$this->expectExceptionMessageMatches('/' . preg_quote(TestProvider::class, '/') . '/');

		$c = new Container();
		$this->assertFalse(TestProvider::isRegistered($c), 'Provider reports registered but is not');
		$c->register(new TestProvider());
		$this->assertTrue(TestProvider::isRegistered($c), 'Provider does not report registered');

		$c = new Container();
		TestProvider::isRegistered($c, true);
	}
}