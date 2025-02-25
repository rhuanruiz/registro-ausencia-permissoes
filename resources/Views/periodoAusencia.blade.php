@extends('layouts.base')

@section('content')
<div id="app">
    <periodos-ausencia
        :title="'{{$title}}'"
        :periodos_ausencia="{{$periodos_ausencia}}"
    />
</div>

<script type="text/javascript" src="/js/app.js"></script>

@endsection
