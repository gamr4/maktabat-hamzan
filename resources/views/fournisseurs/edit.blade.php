@extends('layouts.app')

@section('title', 'Modifier un fournisseur')
@section('eyebrow', 'Répertoire')

@section('content')
    @include('fournisseurs._form', [
        'action' => route('fournisseurs.update', $fournisseur->code_fournisseur),
        'method' => 'PUT',
        'fournisseur' => $fournisseur,
    ])
@endsection
