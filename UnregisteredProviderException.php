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

class UnregisteredProviderException extends \LogicException {
	public function __construct(string $class)
	{
		parent::__construct("Provider '{$class}' has not been registered");
	}
}