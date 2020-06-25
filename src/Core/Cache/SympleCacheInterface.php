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

interface SympleCacheInterface {
	public function clearCache(): void;

	public function warmCache(): void;

	public function getCacheLocation(): ?string;
}