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

use Pimple\Container;

function class_implements_interface(string $class, string $interface, bool $throw = false): bool
{
	if (!class_exists($class)) throw new \LogicException("Class '{$class}' does not exist");
	if (in_array($interface, class_implements($class))) {
		return true;
	} else {
		if ($throw === true) {
			throw new \LogicException("Class '{$class}' does not implement interface '{$interface}'");
		}
		return false;
	}
}

/**
 * @param mixed $key
 * @param array|\ArrayAccess $arr
 * @return bool
 */
function isset_and_true($key, $arr): bool
{
	if (!$arr instanceof \ArrayAccess && !is_array($arr)) {
		throw new \InvalidArgumentException(sprintf('Invalid type: %s', gettype($arr)));
	}
	return (isset($arr[$key]) && $arr[$key] === true);
}

function recursive_rm(string $dir): void
{
	if (!is_dir($dir)) throw new \InvalidArgumentException("Directory '$dir' does not exist");
	$pattern = $dir . DIRECTORY_SEPARATOR . '{,.}*';
	foreach (glob($pattern, GLOB_BRACE) as $i) {
		if (in_array(basename($i), ['.', '..'])) continue;
		if (is_file($i) || is_link($i)) {
			unlink($i);
		} elseif (is_dir($i)) {
			recursive_rm($i);
		}
	}
	rmdir($dir);
}

function exists_and_registered(string $class, Container $c): bool
{
	$callable = [$class, 'isRegistered'];

	return (class_exists($class) === true && call_user_func($callable, $c) === true);
}