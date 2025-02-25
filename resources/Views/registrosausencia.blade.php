@extends('layouts.base')

@section('content')
<div id="app">
    <registrosausencia
        :title="'{{$title}}'"
        :periodos_ausencia="{{$periodos_ausencia}}"
        :nome_usuarios="{{$nome_usuarios}}"
        :id_usuarios="{{$id_usuarios}}"
        :nome_professores="{{$nome_professores}}"
        :nome_tecnicos="{{$nome_tecnicos}}"
    />
</div>

<script type="text/javascript" src="/js/app.js"></script>

@endsection
