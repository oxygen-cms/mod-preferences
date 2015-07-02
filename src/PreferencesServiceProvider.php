<?php

namespace OxygenModule\Preferences;

use Illuminate\Support\ServiceProvider;

use Oxygen\Core\Blueprint\BlueprintManager;
use Oxygen\Core\Html\Navigation\Navigation;
use Oxygen\Preferences\Loader\ConfigLoader;
use Oxygen\Preferences\PreferencesManager;

class PreferencesServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */

	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */

	public function boot() {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'oxygen/mod-preferences');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'oxygen/mod-preferences');

        $this->app[BlueprintManager::class]->loadDirectory(__DIR__ . '/../resources/blueprints');

        $this->app[PreferencesManager::class]->extendSchema('appearance.themes', function($schema) {
            $schema->setView('oxygen/mod-preferences::themes.choose');
        });

        $this->addNavigationItems();
	}

	/**
	 * Adds items the the admin navigation.
	 *
	 * @return void
	 */

	public function addNavigationItems() {
		$blueprint = $this->app[BlueprintManager::class]->get('Preferences');
		$nav = $this->app[Navigation::class];

		$nav->add($blueprint->getToolbarItem('getView'));
	}

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {

    }
}
