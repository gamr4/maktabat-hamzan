@extends('layouts.app')

@section('title', 'Ajouter un client')
@section('eyebrow', 'Répertoire')

@section('content')
    @include('clients._form', [
        'action' => route('clients.store'),
        'method' => 'POST',
        'client' => null,
    ])
@endsection
