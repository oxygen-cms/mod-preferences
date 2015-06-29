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
    $form->addContent('<div class="Block">');

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
            $editable = new EditableField($field, app('request'), $schema->getRepository()->get($field->name));
            $label = new Label($field);
            $row = new Row([$label, $editable]);
            $form->addContent($row);
        }
    }

    $form->addContent('</div>');
}

if(!isset($footer)) {
    $footer = new Row([
            new ButtonToolbarItem(Lang::get('oxygen/mod-preferences::ui.update.close'), $blueprint->getAction('getView')),
            new SubmitToolbarItem(Lang::get('oxygen/mod-preferences::ui.update.submit'))
    ]);
    $footer->isFooter = true;
}

$form->addContent('<div class="Block">')->addContent($footer)->addContent('</div>');

echo $form->render();

