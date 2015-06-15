@extends(Config::get('oxygen/core::layout'))

@section('content')

<?php

    use Oxygen\Core\Html\Header\Header;

    $header = Header::fromBlueprint(
        $blueprint,
        Preferences::isRoot($group) ? Lang::get('oxygen/preferences::ui.home.title') : Preferences::group($group)
    );

    if(!Preferences::isRoot($group)) {
        $header->setBackLink(URL::route($blueprint->getRouteName('getView'), Preferences::parentGroup($group)));
    }

?>

<!-- =====================
            HEADER
     ===================== -->

<div class="Block">
    {{ $header->render()}}
</div>

<!-- =====================
            HEADER
     ===================== -->

<div class="Block">
    <?php
        foreach(Preferences::get($group) as $key => $item):
            $key = $group . ($group === null ? '' : '.') . $key;

            if(is_array($item)):
                $header = Header::fromBlueprint(
                    $blueprint,
                    Preferences::group($key),
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

    @if(empty(Preferences::get($group)))
        <h2 class="heading-gamma margin-large">
            @lang('oxygen/preferences::ui.home.noItems')
        </h2>
    @endif
</div>

@stop
