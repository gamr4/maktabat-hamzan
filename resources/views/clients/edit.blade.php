@extends('layouts.app')

@section('title', 'Modifier un client')
@section('eyebrow', 'Répertoire')

@section('content')
    @include('clients._form', [
        'action' => route('clients.update', $client->code_client),
        'method' => 'PUT',
        'client' => $client,
    ])
@endsection
