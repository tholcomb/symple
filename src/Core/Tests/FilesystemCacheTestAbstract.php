<?php

namespace Tholcomb\Symple\Core\Tests;

use PHPUnit\Framework\TestCase;
use Tholcomb\Symple\Core\Cache\SympleCacheInterface;
use function Tholcomb\Symple\Core\recursive_rm;

abstract class FilesystemCacheTestAbstract extends TestCase {
	protected $path;

	public function __construct()
	{
		parent::__construct();
		$this->path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('fscachetest_');
	}

	protected function setUp(): void
	{
		mkdir($this->path);
	}

	protected function tearDown(): void
	{
		if (is_dir($this->path)) {
			recursive_rm($this->path);
		}
	}

	private function getDirCount(): int
	{
		return count(glob($this->path . DIRECTORY_SEPARATOR . '{,.}*', GLOB_BRACE));
	}

	public function testCache()
	{
		$cache = $this->getCache();
		if ($this->getDirCount() !== 2) {
			throw new \RuntimeException("Creation of directory '{$this->path}' appears to have failed.");
		}
		$cache->warmCache();
		$this->assertGreaterThan(2, $this->getDirCount(), 'Cache was not warmed.');
		$cache->clearCache();
		$this->assertTrue(is_dir($this->path) === false || $this->getDirCount() === 2, 'Cache was not cleared.');
	}

	abstract protected function getCache(): SympleCacheInterface;
}