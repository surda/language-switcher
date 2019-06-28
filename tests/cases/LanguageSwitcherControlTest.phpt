<?php declare(strict_types=1);

namespace Tests\Surda\LanguageSwitcher;

use Nette\DI\Container;
use Surda\LanguageSwitcher\LanguageSwitcherControl;
use Surda\LanguageSwitcher\LanguageSwitcherFactory;
use Tester\TestCase;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class LanguageSwitcherControlTest extends TestCase
{
    /**
     * @return Container
     */
    protected function createContainer(): Container
    {
        $config = [
            'languageSwitcher' => [
                'locales' => [
                    'cs' => 'Czech',
                    'en' => 'English',
                ],
                'default' => 'en',
                'locale' => 'cs',
                'template' => __DIR__ . '/files/default.latte',
                'templates' => [
                    'locale' => __DIR__ . '/files/locale.latte',
                    'locale2Country' => __DIR__ . '/files/locale2Country.latte',
                ],
            ],
        ];

        return (new ContainerFactory())->create($config);
    }

    public function testControl()
    {
        /** @var LanguageSwitcherFactory $factory */
        $factory = $this->createContainer()->getService('languageSwitcher.factory');

        /** @var LanguageSwitcherControl $control */
        $control = $factory->create();

        Assert::same('cs', $control->getLocale());
        Assert::same('en', $control->getDefaultLocale());
        Assert::same(['cs' => 'Czech', 'en' => 'English'], $control->getLocales());

        Assert::same(__DIR__ . '/files/default.latte', $control->getTemplateByType('default'));
        Assert::same(__DIR__ . '/files/locale.latte', $control->getTemplateByType('locale'));
        Assert::same(__DIR__ . '/files/locale2Country.latte', $control->getTemplateByType('locale2Country'));
    }
}

(new LanguageSwitcherControlTest())->run();