@extends(app('oxygen.layout'))

@section('content')

<?php

    use Oxygen\Core\Html\Header\Header;

    $header = Header::fromBlueprint(
        $blueprint,
        Lang::get('oxygen/mod-preferences::ui.update.title', ['name' => $schema->getTitle()])
    );

    $header->setBackLink(URL::route($blueprint->getRouteName('getView'), Preferences::getParentGroupName($schema->getKey())));

?>

<!-- =====================
            HEADER
     ===================== -->

<div class="Block">
    {!! $header->render() !!}
</div>

@include('oxygen/mod-preferences::updateForm', ['blueprint' => $blueprint, 'schema' => $schema])

@stop
