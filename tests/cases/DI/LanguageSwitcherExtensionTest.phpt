<?php declare(strict_types=1);

namespace Tests\Surda\LanguageSwitcher;

use Nette\DI\Container;
use Surda\LanguageSwitcher\LanguageSwitcherFactory;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
class LanguageSwitcherExtensionTest extends TestCase
{
    public function testRegistration()
    {
        /** @var Container $container */
        $container = (new ContainerFactory())->create([
            'languageSwitcher' => [
                'locales' => [
                    'cs' => 'Czech',
                    'en' => 'English',
                ],
                'default' => 'en',
            ]
        ]);

        $service = $container->getService('languageSwitcher.factory');
        Assert::true($service instanceof LanguageSwitcherFactory);

        $service = $container->getByType(LanguageSwitcherFactory::class);
        Assert::true($service instanceof LanguageSwitcherFactory);
    }
}

(new LanguageSwitcherExtensionTest())->run();