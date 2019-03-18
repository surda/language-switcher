<?php declare(strict_types=1);

namespace Surda\LanguageSwitcher\DI;

use Nette\DI\CompilerExtension;
use Nette\Utils\Validators;
use Surda\LanguageSwitcher\LanguageSwitcherFactory;

class LanguageSwitcherExtension extends CompilerExtension
{
    /** @var array */
    public $defaults = [
        'locales' => [],
        'default' => NULL,
        'locale' => NULL,
        'template' => NULL,
        'templates' => [],
        'useAjax' => FALSE,
        // Mapping language ISO 639-1 code and ISO 3166-1 Alpha-2 code
        // https://en.wikipedia.org/wiki/ISO_3166-1
        // https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
        'locale2Country' => [
            'en' => 'us',
            'cs' => 'cz',
        ],
    ];

    /** @var array */
    private $templates = [
        'default' => __DIR__ . '/../Templates/bootstrap4.dropdown.latte',
        'default-sm' => __DIR__ . '/../Templates/bootstrap4.dropdown.sm.latte',
        'nav-item' => __DIR__ . '/../Templates/bootstrap4.nav-item.latte',
        'nav-item-flag' => __DIR__ . '/../Templates/bootstrap4.nav-item.flag.latte',
    ];

    public function loadConfiguration(): void
    {
        $builder = $this->getContainerBuilder();
        $config = $this->validateConfig($this->defaults);

        $this->validate($config);

        $languageSwitcher = $builder->addDefinition($this->prefix('languageSwitcher'))
            ->setImplement(LanguageSwitcherFactory::class)
            ->addSetup($config['useAjax'] === TRUE ? 'enableAjax' : 'disableAjax');

        if ($config['locales'] !== []) {
            $languageSwitcher->addSetup('setLocales', [$config['locales']]);
        }

        if ($config['default'] !== NULL) {
            $languageSwitcher->addSetup('setDefaultLocale', [$config['default']]);
        }

        if ($config['locale'] !== NULL) {
            $languageSwitcher->addSetup('setLocale', [$config['locale']]);
        }

        $templates = $config['templates'] === [] ? $this->templates : $config['templates'];
        foreach ($templates as $type => $templateFile) {
            $languageSwitcher->addSetup('setTemplateByType', [$type, $templateFile]);
        }

        if ($config['template'] !== NULL) {
            $languageSwitcher->addSetup('setTemplate', [$config['template']]);
        }

        if ($config['locale2Country'] !== []) {
            $languageSwitcher->addSetup('setLocale2Country', [$config['locale2Country']]);
        }
    }

    /**
     * @param array $config
     */
    private function validate(array $config): void
    {
        Validators::assertField($config, 'locales', 'array');
        Validators::assertField($config, 'default', 'string');
        Validators::assertField($config, 'locale', 'string|null');
        Validators::assertField($config, 'useAjax', 'bool');
        Validators::assertField($config, 'template', 'string|null');
        Validators::assertField($config, 'templates', 'array');
        Validators::assertField($config, 'locale2Country', 'array');
    }
}