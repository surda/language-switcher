<?php declare(strict_types=1);

namespace Surda\LanguageSwitcher;

trait TLanguageSwitcher
{
    /** @persistent */
    public $locale = 'cs';

    /** @var LanguageSwitcherFactory */
    protected $languageSwitcherFactory;

    /**
     * @param LanguageSwitcherFactory $LanguageSwitcherFactory
     */
    public function injectLanguageSwitcherFactory(LanguageSwitcherFactory $LanguageSwitcherFactory)
    {
        $this->languageSwitcherFactory = $LanguageSwitcherFactory;

        $this->onStartup[] = function () {
            $this->template->locale = $this->locale;
        };
    }

    /**
     * @return LanguageSwitcherControl
     */
    protected function createComponentLanguageSwitcher(): LanguageSwitcherControl
    {
        $control = $this->languageSwitcherFactory->create();
        $control->setLocale($this->locale);

        $control->onChange[] = function (LanguageSwitcherControl $control, string $locale): void {
            $this->redirect('this', ['locale' => $locale]);
        };

        return $control;
    }
}