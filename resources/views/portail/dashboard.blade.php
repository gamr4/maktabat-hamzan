@extends('layouts.portail')

@section('title', 'Tableau de bord Client')
@section('eyebrow', 'Vue d\'ensemble')

@section('actions')
    <a href="{{ route('dashboard') }}" class="btn btn-ghost">
        <i class="ti ti-layout-dashboard" aria-hidden="true"></i> Tableau de bord
    </a>
@endsection

@section('content')
    @if (auth()->user()->isClient())
        <div class="alert alert-info">
            <i class="ti ti-info-circle" aria-hidden="true"></i>
            <span>Votre historique de commandes apparaît maintenant dans votre tableau de bord principal. Vous y trouverez vos 5 commandes les plus récentes, le total de vos achats et le détail de chaque commande.</span>
        </div>
    @endif

    <a href="{{ route('dashboard') }}" class="btn btn-primary">Continuer vers le tableau de bord</a>
@endsection