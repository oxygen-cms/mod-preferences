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

    $header = Header::fromBlueprint(
        $blueprint,
        __('oxygen/mod-preferences::ui.update.title', ['name' => $schema->getTitle()])
    );

    $header->setBackLink(URL::route($blueprint->getRouteName('getView'), Preferences::getParentGroupName($schema->getKey())));

    $themes = Theme::all();

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

    <div class="Row--layout Row--equalCells">
        <?php

            try {
                $currentTheme = Theme::current();
            } catch (\Oxygen\Theme\ThemeNotFoundException $e) {
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
                    $itemHeader->setContent('<div class="Icon-container"><span class="Icon Icon--gigantic Icon--light Icon-picture-o"></span></div>');
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
        $editableField = new EditableField($field, app('request'), Theme::getLoader()->getCurrentTheme());
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

<?php Event::listen('oxygen.layout.page.after', function() { ?>

<script>
    var Oxygen = Oxygen || {};
    Oxygen.load = Oxygen.load || [];
    Oxygen.load.push(function() {
        $("[data-index]").on("click", function() {
            $('[name="theme"]').val($(event.currentTarget).attr("data-index"));
            $(".Form--themes").submit();
        });
    });
</script>

<?php }); ?>
