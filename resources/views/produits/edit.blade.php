@extends('layouts.app')

@section('title', 'Modifier un produit')
@section('eyebrow', 'Catalogue')

@section('content')
    @include('produits._form', [
        'action' => route('produits.update', $produit->code_produit),
        'method' => 'PUT',
        'produit' => $produit,
    ])
@endsection
