<?php

Blueprint::make('Preferences', function($blueprint) {
    $blueprint->setController('Oxygen\Preferences\Controller\PreferencesController');
    $blueprint->setIcon('cog');

    $blueprint->setToolbarOrders([
        'section' => [],
        'group' => ['getViewSubgroup'],
        'item' => ['getUpdate']
    ]);

    $blueprint->makeAction([
        'name' => 'getView',
        'pattern' => '{group?}',
        'routeParametersCallback' => function($action, array $options) {
            return isset($options['group'])
                ? [$options['group']]
                : [];
        }
    ]);
    $blueprint->makeToolbarItem([
        'action' => 'getView',
        'label' => 'Preferences',
        'icon' => 'cog'
    ]);
    $blueprint->makeToolbarItem([
        'action' => 'getView',
        'identifier' => 'getViewSubgroup',
        'label' => 'Open',
        'icon' => 'folder-open'
    ]);

    $blueprint->makeAction([
        'name' => 'getUpdate',
        'pattern' => '{key}/update',
        'routeParametersCallback' => function($action, array $options) {
            return [
                $options['schema']->getKey()
            ];
        }
    ]);
    $blueprint->makeToolbarItem([
        'action' => 'getUpdate',
        'label' => 'Edit',
        'icon' => 'pencil'
    ]);

    $blueprint->makeAction([
        'name' => 'putUpdate',
        'pattern' => '{key}',
        'method' => 'PUT',
        'routeParametersCallback' => function($action, array $options) {
            return [
                $options['schema']->getKey()
            ];
        }
    ]);
});