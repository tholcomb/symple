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
use function Tholcomb\Symple\Core\class_implements_interface;
use function Tholcomb\Symple\Core\isset_and_true;

class UtilsTest extends TestCase {
	public function testClassImplements()
	{
		$interface = \ArrayAccess::class;
		$classA = Container::class;
		$classB = \stdClass::class;
		$this->assertTrue(class_implements_interface($classA, $interface));
		$this->assertFalse(class_implements_interface($classB, $interface));
		$caught = false;
		try {
			class_implements_interface($classB, $interface, true);
		} catch (\LogicException $e) {
			$caught = true;
		}
		$this->assertTrue($caught);
	}

	public function testIssetAndTrue()
	{
		$arr = [
			'a' => true,
			'b' => false,
		];
		$this->assertTrue(isset_and_true('a', $arr));
		$this->assertFalse(isset_and_true('b', $arr));
		$this->assertFalse(isset_and_true('c', $arr));

		$container = new Container();
		$container['a'] = true;
		$container['b'] = false;
		$this->assertTrue(isset_and_true('a', $container));
		$this->assertFalse(isset_and_true('b', $container));
	}
}