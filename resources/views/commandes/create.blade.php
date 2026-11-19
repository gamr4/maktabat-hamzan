@extends('layouts.app')

@section('title', 'Nouvelle commande')
@section('eyebrow', 'Bon de commande')

@section('content')
    @include('commandes._form', [
        'action' => route('commandes.store'),
        'method' => 'POST',
        'commande' => null,
    ])
@endsection
