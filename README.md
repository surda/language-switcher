# Language switcher

## Installation

The recommended way to is via Composer:

```
composer require surda/language-switcher
```

After that you have to register extension in config.neon:

```yaml
extensions:
    languageSwitcher: Surda\LanguageSwitcher\DI\LanguageSwitcherExtension
```

## Minimal configuration

```yaml
languageSwitcher:
    locales:
        cs: Čeština
        en: English
    default: cs
```

List of all configuration options:

```yaml
languageSwitcher:
    locales:
        cs: Čeština
        en: English
    default: cs
    locale: cs
    useAjax: false
    locale2Country:
        en: us
        cs: cz
    template: path/to/your/latte/file.latte
    # or
    templates:
        default: path/to/your/latte/file.latte
        navbar: path/to/your/latte/navbar.latte
```

## Usage

Inject language switcher + <code>$locale</code> persistent property 

```php
use Nette\Application\UI\Presenter;
use Surda\LanguageSwitcher\LanguageSwitcher;

class BasePresenter extends Presenter
{
    use LanguageSwitcher;
}
```

Inject language switcher without <code>$locale</code> persistent property 

```php
use Nette\Application\UI\Presenter;
use Surda\LanguageSwitcher\LanguageSwitcherWithoutLocaleProperty as LanguageSwitcher;

class BasePresenter extends Presenter
{
    use LanguageSwitcher;
    
    /** @persistent */
    public $locale = 'cs';
}
```

Custom 

```php
use Nette\Application\UI\Presenter;
use Surda\LanguageSwitcher\LanguageSwitcherFactory;
use Surda\LanguageSwitcher\LanguageSwitcherControl;

class BasePresenter extends Presenter
{
    /** @var LanguageSwitcherFactory */
    private $languageSwitcherFactory;

    /** @persistent */
    public $locale = 'cs';

    public function injectLanguageSwitcher(LanguageSwitcherFactory $LanguageSwitcherFactory)
    {
        $this->languageSwitcherFactory = $LanguageSwitcherFactory;
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
```

Template

```html
{control languageSwitcher} or {control languageSwitcher default}  
```

Set control template by type of template (see config.neon)

```html
{control languageSwitcher navbar}  
```

## Custom component options

```php
class ProductPresenter extends Presenter
{
    /**
    * @return LanguageSwitcherControl
    */
    protected function createComponentLanguageSwitcher(): LanguageSwitcherControl
    {
        // Init items per page component
        $control = $this->languageSwitcherFactory->create();
        
        // All allowed locales 
        $control->setLocales(['cs' => 'Czech', 'en' => 'English']);

        // Default locale
        $control->setDefaultLocale('cs');

        // Current locale
        $control->setLocale('cs');

        // To use your own template (default type)
        $control->setTemplate('path/to/your/latte/file.latte');

        // To use your own template
        $control->setTemplates([
            'default' => 'path/to/your/latte/file.latte',
            'navbar' => 'path/to/your/latte/navbar.latte',
        ]);

        // Enable ajax (defult is disable)
        $control->enableAjax();
        
        // Disable ajax
        $control->disableAjax();
        
        return $control;
    }
}
```