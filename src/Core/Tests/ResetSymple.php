<?php
/*
 * This file is part of the Symple framework
 *
 * Copyright (c) Tyler Holcomb <tyler@tholcomb.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tholcomb\Symple\Core\Tests;

use Tholcomb\Symple\Core\Symple;

class ResetSymple extends Symple {
	public static function reset(): void
	{
		static::$booted = false;
		static::$debug = false;
	}
}