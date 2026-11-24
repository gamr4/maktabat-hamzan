@extends('layouts.app')

@section('title', 'Nouvelle commande')
@section('eyebrow', 'Bon de commande')

@section('content')
    @include('commandes._form', [
        'action' => route('commandes.store'),
        'method' => 'POST',
        'commande' => null,
        'lockedClient' => $lockedClient ?? null,
        'cancelRoute' => $cancelRoute ?? route('commandes.index'),
        'submitLabel' => $submitLabel ?? 'Enregistrer',
        'showAdminLinks' => $showAdminLinks ?? true,
    ])
@endsection
