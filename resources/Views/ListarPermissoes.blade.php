@extends('layouts.base')

@section('content')

    <div id="app">
      <listar-permissoes
        :title="'{{$title}}'"
        :permissoes="{{$permissoes}}"
        :roles="{{$roles}}"
      />
    </div>
  <script type="text/javascript" src="/js/app.js"></script>

@endsection
