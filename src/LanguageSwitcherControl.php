<?php declare(strict_types=1);

namespace Surda\LanguageSwitcher;

use Nette\Application\UI;
use Surda\LanguageSwitcher\Exception\InvalidArgumentException;
use Surda\UI\Control\ThemeableControls;

class LanguageSwitcherControl extends UI\Control
{
    use ThemeableControls;

    /** @var array */
    protected $locales = [];

    /** @var string */
    protected $defaultLocale;

    /** @var string|null */
    protected $locale;

    /** @var bool */
    protected $useAjax = FALSE;

    /** @var array */
    protected $locale2Country = [];

    /** @var array */
    public $onChange;

    public function render(string $templateType = 'default'): void
    {
        /** @var \Nette\Bridges\ApplicationLatte\Template $template */
        $template = $this->template;

        $template->setFile($this->getTemplateByType($templateType));
        $template->addFilter('locale2Country', function (string $locale): string {
            if (array_key_exists($locale, $this->locale2Country)) {
                return $this->locale2Country[$locale];
            }

            return $locale;
        });

        $template->locales = $this->getLocales();
        $template->locale = $this->getLocale();
        $template->useAjax = $this->useAjax;

        $template->render();
    }

    /**
     * @param string $locale
     */
    public function handleChange(string $locale): void
    {
        $this->setLocale($locale);

        if ($this->useAjax) {
            $this->redrawControl('LanguageSwitcherSnippet');
        }

        $this->onChange($this, $this->locale);
    }

    /**
     * @param string $locale
     */
    public function setLocale(string $locale): void
    {
        if (!array_key_exists($locale, $this->locales)) {
            throw new InvalidArgumentException(sprintf('Invalid "%s" locale.', $locale));
        }

        $this->locale = $locale;
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        if ($this->locale === NULL) {
            return $this->getDefaultLocale();
        }

        return $this->locale;
    }

    /**
     * @return string
     */
    public function getDefaultLocale(): string
    {
        return $this->defaultLocale;
    }

    /**
     * @param string $defaultLocale
     */
    public function setDefaultLocale(string $defaultLocale): void
    {
        if (!array_key_exists($defaultLocale, $this->locales)) {
            throw new InvalidArgumentException(sprintf('Invalid "%s" default locale.', $defaultLocale));
        }

        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @return array
     */
    public function getLocales(): array
    {
        return $this->locales;
    }

    /**
     * @param array $locales
     */
    public function setLocales(array $locales): void
    {
        $this->locales = $locales;
    }

    /**
     * @param array $locale2Country
     */
    public function setLocale2Country(array $locale2Country): void
    {
        $this->locale2Country = $locale2Country;
    }

    public function enableAjax(): void
    {
        $this->useAjax = TRUE;
    }

    public function disableAjax(): void
    {
        $this->useAjax = FALSE;
    }
}