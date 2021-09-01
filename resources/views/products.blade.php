@extends('layouts.main')

@section('title', 'Produtos')

@section('content')

<p>Página de produtos</p>
@if ($search)
<p>O usuário está buscando por: {{ $search }}</p>
@endif

@endsection