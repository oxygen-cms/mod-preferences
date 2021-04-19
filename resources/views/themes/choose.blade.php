@extends(app('oxygen.layout'))

@section('content')

    <?php

    use Oxygen\Core\Html\Form\Form;
    use Oxygen\Core\Html\Form\Label;
    use Oxygen\Core\Html\Form\Row;
    use Oxygen\Core\Html\Header\Header;
    use Oxygen\Core\Html\Form\EditableField;
    use Oxygen\Core\Html\Toolbar\ButtonToolbarItem;
    use Oxygen\Core\Html\Toolbar\SubmitToolbarItem;
    use Oxygen\Theme\ThemeManager;
    use Oxygen\Theme\ThemeNotFoundException;

    $header = Header::fromBlueprint(
        $blueprint,
        __('oxygen/mod-preferences::ui.update.title', ['name' => $schema->getTitle()])
    );

    $header->setBackLink(URL::route($blueprint->getRouteName('getView'), Preferences::getParentGroupName($schema->getKey())));

    $themeManager = app(ThemeManager::class);
    $themes = $themeManager->all();

    ?>

    <!-- =====================
                HEADER
         ===================== -->

    <div class="Block">
        {!! $header->render() !!}
    </div>

    @if(empty($themes))
        <div class="Block">
            <h3 class="heading-gamma margin-large">@lang('oxygen/mod-preferences::ui.theme.choose.empty')</h3>
        </div>
    @endif

    <div class="Row--equalCells Row--wrap ThemeChooser">
        <?php

            try {
                $currentTheme = $themeManager->current();
            } catch (ThemeNotFoundException $e) {
                $currentTheme = null;
            }

            foreach($themes as $theme) {
                $itemHeader = new Header($theme->getName(), ['span' => 'oneThird'], Header::TYPE_BLOCK);
                $itemHeader->addClass('Link-cursor');
                if($theme == $currentTheme) {
                    $itemHeader->setSubtitle('(current)');
                }
                $itemHeader->setIndex($theme->getKey());
                if($theme->hasImage()) {
                    $itemHeader->setContent('<img src="' . $theme->getImage() . '">');
                } else {
                    $itemHeader->setContent('<div class="Icon-container"><span class="fa Icon--gigantic Icon--light fa-picture-o"></span></div>');
                }
                echo $itemHeader->render();
            }
        ?>
    </div>

    <?php

        $form = new Form($blueprint->getAction('putUpdate'));
        $form->setAsynchronous(true)->setWarnBeforeExit(true)->setSubmitOnShortcutKey(true);
        $form->setRouteParameterArguments(['schema' => $schema]);
        $form->addClass('Form--themes');

        $field = $schema->getField('theme');
        $editableField = new EditableField($field, $themeManager->getLoader()->getCurrentTheme());
        $label = new Label($field);
        $row = new Row([$label, $editableField]);

        $form->addContent('<div class="Block js-hide">' . $row->render() . '</div>');

        if(!isset($footer)) {
            $footer = new Row([
                    new ButtonToolbarItem(__('oxygen/mod-preferences::ui.update.close'), $blueprint->getAction('getView')),
                    new SubmitToolbarItem(__('oxygen/mod-preferences::ui.update.submit'))
            ]);
            $footer->isFooter = true;
        }

        $form->addContent('<div class="Block js-hide">' . $footer->render() . '</div>');

        echo $form->render();

    ?>

@stop
