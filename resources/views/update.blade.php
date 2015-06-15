@extends(Config::get('oxygen/core::layout'))

@section('content')

<?php

    use Oxygen\Core\Html\Header\Header;

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

@include('oxygen/preferences::updateForm', ['blueprint' => $blueprint, 'schema' => $schema])

@stop
