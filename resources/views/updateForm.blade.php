<?php

use Oxygen\Core\Html\Form\Form;
use Oxygen\Core\Html\Form\EditableField;
use Oxygen\Core\Html\Form\Label;
use Oxygen\Core\Html\Form\Row;
use Oxygen\Core\Html\Toolbar\ButtonToolbarItem;
use Oxygen\Core\Html\Toolbar\SubmitToolbarItem;

$form = new Form($blueprint->getAction('putUpdate'));
$form->setAsynchronous(true)->setWarnBeforeExit(true)->setSubmitOnShortcutKey(true);
$form->setRouteParameterArguments(['schema' => $schema]);

foreach($schema->getFields() as $groupName => $groupItems) {
    if($groupName !== '') {
        $form->addContent('<div class="Row">
        <h2 class="heading-beta">' . e($groupName) . '</h2>
        </div>');
    }

    foreach($groupItems as $subgroupName => $subgroupItems) {
        if($subgroupName !== '') {
            $form->addContent('<div class="Row">
                <h2 class="heading-gamma">' . e($subgroupName) . '</h2>
            </div>');
        }
        foreach($subgroupItems as $field) {
            if(!$field->editable) {
                continue;
            }
            try {
                $themeValue = app(\Oxygen\Preferences\ThemeSpecificPreferencesFallback::class)->getPreferenceValue($schema->getKey() . '::' . $field->name);
                $field->placeholder = $themeValue . ' (default for current theme)';
            } catch(\Oxygen\Preferences\PreferenceNotFoundException $e) {
                // do nothing
            }
            $field->attributes['class'] = 'Form-input--fullWidth';
            $editable = new EditableField($field, $schema->getRepository()->get($field->name));
            $label = new Label($field);
            $row = new Row([$label, $editable]);
            $form->addContent($row);
        }
    }
}

if(!isset($footer)) {
    $footer = new Row([
            new ButtonToolbarItem(__('oxygen/mod-preferences::ui.update.close'), $blueprint->getAction('getView')),
            new SubmitToolbarItem(__('oxygen/mod-preferences::ui.update.submit'))
    ]);
    $footer->isFooter = true;
}

$form->addContent($footer);

echo $form->render();

