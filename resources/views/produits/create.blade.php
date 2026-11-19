@extends('layouts.app')

@section('title', 'Ajouter un produit')
@section('eyebrow', 'Catalogue')

@section('content')
    @include('produits._form', [
        'action' => route('produits.store'),
        'method' => 'POST',
        'produit' => null,
    ])
@endsection
