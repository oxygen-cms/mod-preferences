@extends(app('oxygen.layout'))

@section('content')

<?php

    use Oxygen\Core\Html\Header\Header;

    $header = Header::fromBlueprint(
        $blueprint,
        Preferences::isRootKey($group) ? __('oxygen/mod-preferences::ui.home.title') : Preferences::getGroupName($group)
    );

    if(!Preferences::isRootKey($group)) {
        $header->setBackLink(URL::route($blueprint->getRouteName('getView'), Preferences::getParentGroupName($group)));
    }

?>

<!-- =====================
            HEADER
     ===================== -->

<div class="Block">
    {!! $header->render() !!}
</div>

<!-- =====================
            HEADER
     ===================== -->

<div class="Block">
    <?php
        foreach(Preferences::getSchema($group) as $key => $item):
            $key = $group . ($group === null ? '' : '.') . $key;

            if(is_array($item)):
                $header = Header::fromBlueprint(
                    $blueprint,
                    Preferences::getGroupName($key),
                    ['group' => $key],
                    Header::TYPE_SMALL,
                    'group'
                );

                echo $header->render();
            else:
                $header = Header::fromBlueprint(
                    $blueprint,
                    $item->getTitle(),
                    ['schema' => $item],
                    Header::TYPE_SMALL,
                    'item'
                );

                echo $header->render();
            endif;
        endforeach;
    ?>

    @if(empty(Preferences::getSchema($group)))
        <h2 class="heading-gamma margin-large">
            @lang('oxygen/mod-preferences::ui.home.noItems')
        </h2>
    @endif
</div>

@stop
