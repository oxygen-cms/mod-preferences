<?php

namespace OxygenModule\Preferences;

use Illuminate\Support\ServiceProvider;

use Oxygen\Core\Blueprint\BlueprintManager;
use Oxygen\Core\Html\Navigation\Navigation;
use Oxygen\Preferences\Loader\ConfigLoader;

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
		$this->package('oxygen/preferences', 'oxygen/preferences', __DIR__ . '/../resources');

        $this->app[BlueprintManager::class]->loadDirectory(__DIR__ . '/../resources/blueprints');

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
        // TODO: Implement register() method.
    }
}
