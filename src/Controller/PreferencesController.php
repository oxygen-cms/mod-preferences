<?php

namespace OxygenModule\Preferences\Controller;

use Illuminate\Validation\Factory;
use Illuminate\View\View;
use Oxygen\Preferences\PreferencesManager;
use Oxygen\Preferences\Schema;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Oxygen\Core\Blueprint\BlueprintManager;
use Oxygen\Core\Controller\BlueprintController;
use Oxygen\Core\Http\Notification;
use Illuminate\Http\Response;
use Oxygen\Preferences\Facades\Preferences;
use Illuminate\Http\Request;

class PreferencesController extends BlueprintController {
    /**
     * @var PreferencesManager
     */
    private $preferences;

    /**
     * Constructs the AuthController.
     *
     * @param BlueprintManager $manager
     * @throws \Oxygen\Core\Blueprint\BlueprintNotFoundException
     * @throws \ReflectionException
     */
    public function __construct(BlueprintManager $manager, PreferencesManager $preferences) {
        parent::__construct($manager->get('Preferences'));
        $this->preferences = $preferences;
    }

    /**
     * Lists preferences for that group.
     *
     * @param string $group
     * @return View
     */
    public function getView($group = null) {
        $title = $this->preferences->isRootKey($group) ? '' : $this->preferences->getGroupName($group) . ' ';
        $title .= __('oxygen/mod-preferences::ui.home.title');

        return view('oxygen/mod-preferences::list', [
            'group' => $group,
            'title' => $title
        ]);
    }

    /**
     * Form to update a preferences.
     *
     * @return View
     */
    public function getUpdate($key) {
        $schema = $this->getSchema($key);

        $view = $schema->hasView() ? $schema->getView() : 'oxygen/mod-preferences::update';

        return view($view, [
            'schema' => $schema,
            'title' => __('oxygen/mod-preferences::ui.update.title', ['name' => $schema->getTitle()]) . ' ' . __('oxygen/mod-preferences::ui.home.title')
        ]);
    }

    /**
     * Updates the preferences
     *
     * @param string $key
     * @param Factory $validationFactory
     * @return Response
     */
    public function putUpdate($key, Factory $validationFactory, Request $request) {
        $schema = $this->getSchema($key);

        $input = $this->preferencesFromInput($request->except('_method', '_token'));
        $validator = $validationFactory->make($input, $schema->getValidationRules());
        if($validator->fails()) {
            return notify(
                new Notification($validator->messages()->first(), Notification::FAILED)
            );
        }

        $schema->getRepository()->fill($input);
        $schema->storeRepository();

        return notify(
            new Notification(__('oxygen/mod-preferences::messages.updated')),
            ['refresh' => true, 'hardRedirect' => true]
        );
    }

    /**
     * Gets the schema for the specified key.
     *
     * @param string $key
     * @return Schema
     */

    protected function getSchema($key) {
        if(!$this->preferences->hasSchema($key)) {
            throw new NotFoundHttpException();
        }

        $schema = $this->preferences->getSchema($key);
        return $schema;
    }

    /**
     * Returns an array of preferences keys and values ready for validation.
     *
     * @param array $input
     * @return array
     */
    protected function preferencesFromInput(array $input) {
        $return = [];

        foreach($input as $key => $value) {
            $key = str_replace('_', '.', $key);

            if($value === 'false') {
                $value = false;
            } else if($value === 'true') {
                $value = true;
            }

            $return[$key] = $value;
        }

        return $return;
    }

}
