<?php declare(strict_types=1);

namespace Tests\Surda\LanguageSwitcher;

use Nette\DI\Container;
use Surda\LanguageSwitcher\LanguageSwitcherControl;
use Surda\LanguageSwitcher\LanguageSwitcherFactory;
use Testbench\TComponent;
use Tester\TestCase;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/../bootstrap.testbench.php';

/**
 * @testCase
 * @skip
 */
class LanguageSwitcherControlRenderTest extends TestCase
{
    use TComponent;

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

    public function testComponentRender()
    {
        /** @var LanguageSwitcherFactory $factory */
        $factory = $this->createContainer()->getService('languageSwitcher.languageSwitcher');

        /** @var LanguageSwitcherControl $control */
        $control = $factory->create();

        $this->checkRenderOutput($control, 'default');
        $this->checkRenderOutput($control, 'default', ['default']);

        $control->setLocale('cs');
        $this->checkRenderOutput($control, 'cs-Czech', ['locale']);
        $control->setLocale('en');
        $this->checkRenderOutput($control, 'en-English', ['locale']);
    }

    public function testControlRenderConvertLocaleToCountry()
    {
        /** @var LanguageSwitcherFactory $factory */
        $factory = $this->createContainer()->getService('languageSwitcher.languageSwitcher');

        /** @var LanguageSwitcherControl $control */
        $control = $factory->create();

        $control->setLocale('cs');
        $this->checkRenderOutput($control, 'flag-icon-cz', ['locale2Country']);
        $control->setLocale('en');
        $this->checkRenderOutput($control, 'flag-icon-us', ['locale2Country']);
    }
}

(new LanguageSwitcherControlRenderTest())->run();