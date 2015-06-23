<?php

namespace OxygenModule\Preferences\Controller;

use Exception;

use Oxygen\Preferences\Schema;
use View;
use Input;
use Lang;
use Response;
use Validator;
use Preferences;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Oxygen\Core\Blueprint\BlueprintManager as BlueprintManager;
use Oxygen\Core\Controller\BlueprintController;
use Oxygen\Core\Http\Notification;

class PreferencesController extends BlueprintController {

    /**
     * Constructs the AuthController.
     *
     * @param BlueprintManager $manager
     */
    public function __construct(BlueprintManager $manager) {
        parent::__construct($manager, 'Preferences');
    }

    /**
     * Lists preferences for that group.
     *
     * @param string $group
     * @return Response
     */
    public function getView($group = null) {
        $title = Preferences::isRoot($group) ? '' : Preferences::group($group) . ' ';
        $title .= Lang::get('oxygen/mod-preferences::ui.home.title');

        return View::make('oxygen/mod-preferences::list', [
            'group' => $group,
            'title' => $title
        ]);
    }

    /**
     * Form to update a preferences.
     *
     * @return Response
     */
    public function getUpdate($key) {
        $schema = $this->getSchema($key);

        $view = $schema->hasView() ? $schema->getView() : 'oxygen/mod-preferences::update';

        return View::make($view, [
            'schema' => $schema,
            'title' => Lang::get('oxygen/mod-preferences::ui.update.title', ['name' => $schema->getTitle()]) . ' ' . Lang::get('oxygen/mod-preferences::ui.home.title')
        ]);
    }

    /**
     * Updates the preferences
     *
     * @return Response
     */
    public function putUpdate($key) {
        $schema = $this->getSchema($key);

        $input = $this->preferencesFromInput(Input::except('_method', '_token'));
        $validator = Validator::make($input, $schema->getValidationRules());
        if($validator->fails()) {
            return Response::notification(
                new Notification($validator->messages()->first(), Notification::FAILED)
            );
        }

        $schema->getRepository()->fill($input);
        $schema->storeRepository();

        return Response::notification(
            new Notification(Lang::get('oxygen/mod-preferences::messages.updated')),
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
        if(!Preferences::has($key)) {
            throw new NotFoundHttpException();
        }

        $schema = Preferences::get($key);
        $schema->loadRepository();
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