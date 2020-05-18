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