<?php
/**
 * This file is part of the Symple framework
 *
 * Copyright (c) Tyler Holcomb <tyler@tholcomb.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tholcomb\Symple\Logger\Tests;

use PHPUnit\Framework\TestCase;
use Pimple\Container;
use Tholcomb\Symple\Logger\LoggerProvider;

class LoggerProviderTest extends TestCase {
	public function testLog()
	{
		if (!is_writable('/tmp/')) {
			$this->addWarning('/tmp/ is not writable. skipping.');
			return;
		}
		$c = new Container();
		$c->register(new LoggerProvider());
		$file = '/tmp/' . bin2hex(random_bytes(32)) . '/.test';
		$c['logger.path'] = $file;

		$l = LoggerProvider::getLogger($c);
		$msg = bin2hex(random_bytes(32));
		$l->debug($msg);
		$this->assertFileExists($file, 'log file not created');
		$this->assertStringContainsString($msg, file_get_contents($file));

		$name = bin2hex(random_bytes(32));
		$l = LoggerProvider::getLogger($c, $name);
		$l->debug($msg);
		$this->assertStringContainsString($name, file_get_contents($file));

		unlink($file);
		rmdir(dirname($file));
	}
}