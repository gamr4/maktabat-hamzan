@extends('layouts.app')

@section('title', 'Ajouter un fournisseur')
@section('eyebrow', 'Répertoire')

@section('content')
    @include('fournisseurs._form', [
        'action' => route('fournisseurs.store'),
        'method' => 'POST',
        'fournisseur' => null,
    ])
@endsection
