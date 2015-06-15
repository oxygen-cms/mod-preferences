@extends(Config::get('oxygen/core::layout'))

@section('content')

<?php

use Oxygen\Core\Html\Form\Label;use Oxygen\Core\Html\Form\Row;use Oxygen\Core\Html\Header\Header;
    use Oxygen\Core\Html\Form\Footer;
    use Oxygen\Core\Html\Form\EditableField;use Oxygen\Core\Html\Toolbar\ButtonToolbarItem;use Oxygen\Core\Html\Toolbar\SubmitToolbarItem;

$header = Header::fromBlueprint(
        $blueprint,
        Lang::get('oxygen/preferences::ui.update.title', ['name' => $schema->getTitle()])
    );

    $header->setBackLink(URL::route($blueprint->getRouteName('getView'), Preferences::parentGroup($schema->getKey())));

?>

<!-- =====================
            HEADER
     ===================== -->

<div class="Block">
    {{ $header->render()}}
</div>

<?php

    echo Form::open([
        'route' => [$blueprint->getRouteName('putUpdate'), $schema->getKey()],
        'method' => 'PUT',
        'class' => 'Form--sendAjax Form--warnBeforeExit Form--submitOnKeydown Form--themes'
    ]);

    $themes = Theme::all();
?>

@if(empty($themes))
    <div class="Block">
        <h3 class="heading-gamma margin-large">@lang('oxygen/preferences::ui.theme.choose.empty')</h3>
    </div>
@endif

<div class="Row--layout Row--equalCells">
    <?php
        foreach($themes as $theme):
            $itemHeader = new Header($theme->getName(), ['span' => 'oneThird'], Header::TYPE_BLOCK);
            $itemHeader->addClass('Link-cursor');
            if($theme == Theme::current()) {
                $itemHeader->setSubtitle('(current)');
            }
            $itemHeader->setIndex($theme->getKey());
            if($theme->hasImage()) {
                $itemHeader->setContent('<img src="' . $theme->getImage() . '">');
            } else {
                $itemHeader->setContent('<div class="Icon-container"><span class="Icon Icon--gigantic Icon--light Icon-picture-o"></span></div>');
            }
            echo $itemHeader->render();
        endforeach;
    ?>
</div>

<div class="Block js-hide">
    <?php
        $field = $schema->getField('theme');
        if($field->editable) {
            $editableField = new EditableField($field, Theme::getCurrentKey());
            $label = new Label($field->getMeta());
            $row = new Row([$label, $editableField]);
            echo $row->render();
        }
    ?>
</div>

<div class="Block js-hide">
    <?php
        if(!isset($footer)) {
            $footer = new Row([
                new ButtonToolbarItem(Lang::get('oxygen/preferences::ui.update.close'), $blueprint->getRouteName('getView')),
                new SubmitToolbarItem(Lang::get('oxygen/preferences::ui.update.submit'))
            ]);
            $footer->isFooter = true;
        }
        echo $footer->render();
    ?>
</div>

<?php echo Form::close(); ?>

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
