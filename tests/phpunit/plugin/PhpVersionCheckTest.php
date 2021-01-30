<?php
declare(strict_types=1);

namespace phpunit\plugin;

use PHPUnit\Framework\TestCase;
use pocketmine\plugin\PluginManager;
use function array_filter;
use function implode;
use function max;
use const PHP_MAJOR_VERSION;
use const PHP_MINOR_VERSION;
use const PHP_RELEASE_VERSION;

class PhpVersionCheckTest extends TestCase{

	/**
	 * @return mixed[][]
	 * @phpstan-return list<array{array{0: int, 1: int|null, 2: int|null}, bool}>
	 */
	public function phpVersionCheckProvider() : array{
		return [
			//same exact version is ok
			[[PHP_MAJOR_VERSION, PHP_MINOR_VERSION, PHP_RELEASE_VERSION], true],

			//previous patch is ok
			[[PHP_MAJOR_VERSION, PHP_MINOR_VERSION, max(0, PHP_RELEASE_VERSION - 1)], true],
			[[PHP_MAJOR_VERSION, PHP_MINOR_VERSION, null], true],

			//next patch is not ok
			[[PHP_MAJOR_VERSION, PHP_MINOR_VERSION, PHP_RELEASE_VERSION + 1], false],

			//previous minor is not ok
			[[PHP_MAJOR_VERSION, max(0, PHP_MINOR_VERSION - 1), 0], false],
			[[PHP_MAJOR_VERSION, max(0, PHP_MINOR_VERSION - 1), null], false],

			//next minor is not ok
			[[PHP_MAJOR_VERSION, PHP_MINOR_VERSION + 1, 0], false],
			[[PHP_MAJOR_VERSION, PHP_MINOR_VERSION + 1, null], false],

			//previous major is not ok
			[[max(0, PHP_MINOR_VERSION - 1), 0, 0], false],
			[[max(0, PHP_MINOR_VERSION - 1), 0, null], false],
			[[max(0, PHP_MINOR_VERSION - 1), null, null], false],

			//next major is not ok
			[[max(0, PHP_MINOR_VERSION + 1), 0, 0], false],
			[[max(0, PHP_MINOR_VERSION + 1), 0, null], false],
			[[max(0, PHP_MINOR_VERSION + 1), null, null], false],
		];
	}

	/**
	 * @var mixed[] $requiredVersion
	 * @phpstan-param  array{0: int, 1: int|null, 2: int|null} $requiredVersion
	 *
	 * @dataProvider phpVersionCheckProvider
	 */
	public function testPhpVersionCheck(array $requiredVersion, bool $expected) : void{
		self::assertEquals($expected, PluginManager::isCompatiblePhp(implode(".", array_filter($requiredVersion, "\is_int"))));
	}
}