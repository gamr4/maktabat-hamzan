@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('eyebrow', 'Vue d’ensemble')

@section('actions')
    @if ($role === 'admin' || $role === 'gestionnaire')
        <a class="btn btn-primary" href="{{ route('commandes.create') }}">
            <i class="ti ti-plus" aria-hidden="true"></i> Nouvelle commande
        </a>
    @endif
@endsection

@section('content')
    @if ($role === 'admin')
        @include('dashboard.partials.admin')
    @elseif ($role === 'gestionnaire')
        @include('dashboard.partials.gestionnaire')
    @elseif ($role === 'client')
        @include('dashboard.partials.client')
    @else
        @include('dashboard.partials.admin')
    @endif
@endsection
