<?php
/**
 * This file is part of the Symple framework
 *
 * Copyright (c) Tyler Holcomb <tyler@tholcomb.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tholcomb\Symple\Core\Cache;

class SympleCacheContainer {
	/** @var SympleCacheInterface */
	private $caches = [];

	public function addCache(SympleCacheInterface $cache): void
	{
		if (in_array($cache,  $this->caches)) {
			throw new \LogicException(sprintf("Cache '%s' already added", get_class($cache)));
		}
		$this->caches[] = $cache;
	}

	/** @return SympleCacheInterface[] */
	public function getCaches(): array
	{
		return $this->caches;
	}
}