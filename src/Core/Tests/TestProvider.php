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

use Pimple\Container;
use Psr\Log\LoggerInterface;
use Tholcomb\Symple\Core\AbstractProvider;

class TestProvider extends AbstractProvider {
	protected const NAME = 'test';
}