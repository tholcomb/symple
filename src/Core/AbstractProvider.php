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
use Pimple\ServiceProviderInterface;

abstract class AbstractProvider implements ServiceProviderInterface {
	protected const NAME = 'default';

	public function register(Container $c)
	{
		$c[static::NAME . '.registered'] = true;
	}

	public static function isRegistered(Container $c, bool $throw = false): bool
	{
		$reg = isset($c[static::NAME . '.registered']);
		if ($reg === false && $throw === true) throw new UnregisteredProviderException(static::class);

		return $reg;
	}
}