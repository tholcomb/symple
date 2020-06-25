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
use function Tholcomb\Symple\Core\exists_and_registered;
use function Tholcomb\Symple\Core\isset_and_true;
use function Tholcomb\Symple\Core\recursive_rm;

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

	public function testRecursiveRm()
	{
		$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('testRecursiveRm');
		mkdir($dir);
		$dir .= DIRECTORY_SEPARATOR;
		touch($dir . 'testA');
		mkdir($dir . 'testB');
		touch($dir . 'testB' . DIRECTORY_SEPARATOR . 'testC');

		$this->assertTrue(is_dir($dir));
		recursive_rm($dir);
		$this->assertFalse(is_dir($dir));
	}

	public function testExistsAndRegistered()
	{
		$c = new Container();
		$this->assertFalse(exists_and_registered(uniqid('NonExistentClass'), $c), 'Nonexistent class');
		$this->assertFalse(exists_and_registered(TestProvider::class, $c), 'Unregistered class');
		$c->register(new TestProvider());
		$this->assertTrue(exists_and_registered(TestProvider::class, $c), 'Registered class');
	}
}