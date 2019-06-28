<?php declare(strict_types=1);

namespace Surda\LanguageSwitcher\DI;

use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Surda\LanguageSwitcher\LanguageSwitcherFactory;

class LanguageSwitcherExtension extends CompilerExtension
{
    /** @var array */
    public $defaults = [
        'default' => NULL,
        'locales' => [],
        'locale' => NULL,
        'useAjax' => FALSE,
        'template' => NULL,
        'templates' => [],
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

    public function getConfigSchema(): Schema
    {
        return Expect::structure([
            'default' => Expect::string()->nullable()->default(NULL),
            'locales' => Expect::array()->default([]),
            'locale' => Expect::string()->nullable()->default(NULL),
            'useAjax' => Expect::bool(FALSE),
            'template' => Expect::string()->nullable()->default(NULL),
            'templates' => Expect::array()->default([]),

            // Mapping language ISO 639-1 code and ISO 3166-1 Alpha-2 code
            // https://en.wikipedia.org/wiki/ISO_3166-1
            // https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
            'locale2Country' => Expect::array()->default([
                'en' => 'us',
                'cs' => 'cz',
            ]),
        ]);
    }

    public function loadConfiguration(): void
    {
        $builder = $this->getContainerBuilder();
        $config = $this->config;

        $itemsPerPageFactory = $builder->addFactoryDefinition($this->prefix('factory'))
            ->setImplement(LanguageSwitcherFactory::class);

        $itemsPerPageDefinition = $itemsPerPageFactory->getResultDefinition();

        $itemsPerPageDefinition->addSetup($config->useAjax === TRUE ? 'enableAjax' : 'disableAjax');

        if ($config->locales !== []) {
            $itemsPerPageDefinition->addSetup('setLocales', [$config->locales]);
        }

        if ($config->default !== NULL) {
            $itemsPerPageDefinition->addSetup('setDefaultLocale', [$config->default]);
        }

        if ($config->locale !== NULL) {
            $itemsPerPageDefinition->addSetup('setLocale', [$config->locale]);
        }

        $templates = $config->templates === [] ? $this->templates : $config->templates;
        foreach ($templates as $type => $templateFile) {
            $itemsPerPageDefinition->addSetup('setTemplateByType', [$type, $templateFile]);
        }

        if ($config->template !== NULL) {
            $itemsPerPageDefinition->addSetup('setTemplate', [$config->template]);
        }

        if ($config->locale2Country !== []) {
            $itemsPerPageDefinition->addSetup('setLocale2Country', [$config->locale2Country]);
        }
    }
}