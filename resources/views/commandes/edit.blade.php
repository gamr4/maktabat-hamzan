@extends('layouts.app')

@section('title', 'Modifier la commande ' . $commande->numero_commande)
@section('eyebrow', 'Bon de commande')

@section('content')
    @include('commandes._form', [
        'action' => route('commandes.update', $commande->numero_commande),
        'method' => 'PUT',
        'commande' => $commande,
    ])
@endsection
