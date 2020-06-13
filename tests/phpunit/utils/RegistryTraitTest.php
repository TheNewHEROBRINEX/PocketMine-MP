<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\utils;

use PHPUnit\Framework\Exception;
use PHPUnit\Framework\TestCase;
use const LC_ALL;

class RegistryTraitTest extends TestCase{

	/**
	 * @phpstan-return \Generator<int, array{string, \Closure() : \stdClass}>, void, void>
	 */
	public function localeBugProvider() : \Generator{
		yield ['tr_TR.UTF-8', function() : \stdClass{
			return TestRegistry::INNER();
		}];
	}

	/**
	 * @doesNotPerformAssertions
	 * @dataProvider localeBugProvider
	 * @phpstan-param \Closure() : \stdClass $testFunc
	 */
	public function testLocaleBug(string $locale, \Closure $testFunc) : void{
		try{
			$this->setLocale(LC_ALL, $locale);
		}catch(Exception $e){
			self::markTestSkipped($e->getMessage());
		}
		$testFunc();
	}
}
