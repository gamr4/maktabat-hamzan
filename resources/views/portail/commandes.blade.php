@extends('layouts.portail')

@section('title', 'Mes Commandes')
@section('eyebrow', 'Historique & Suivi')

@section('breadcrumbs')
    <a href="{{ route('dashboard') }}">Portail</a>
    <span class="sep">/</span>
    <span>Mes Commandes</span>
@endsection

@section('actions')
    <a href="{{ route('dashboard') }}" class="btn btn-ghost">
        <i class="ti ti-arrow-left" aria-hidden="true"></i> Tableau de bord
    </a>
@endsection

@section('content')
    <div class="alert alert-info">
        <i class="ti ti-info-circle" aria-hidden="true"></i>
        <span>Votre historique de commandes apparaît maintenant dans votre tableau de bord principal. Accédez-y depuis le menu latéral ou le lien "Mes commandes" dans votre tableau de bord.</span>
    </div>

    <a href="{{ route('dashboard') }}" class="btn btn-primary">Aller au tableau de bord</a>
@endsection