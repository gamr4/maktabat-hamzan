fs=require('fs');let d=fs.readFileSync('C:/Users/Asus Rog/github/maktabat-hamzan/resources/views/dashboard.blade.php','utf8');d+='\n\n@section('title', “Tableau de bord\")
@section('eyebrow', “Vue d'ensemble\')
@section('actions')
  <a class=\"btn btn-primary\" href=\"{{ route(\"commandes.create\") }}\">
    <i class=\"ti ti-plus\" aria-hidden=\"true\"></i> Nouvelle commande
  </a>
_
@endsection\n\n@section('content')
  {{-- Workspace Hero with enhanced interactivity --}}
  <section class=\"workspace-hero\" aria-labelledby=\"workspace-title\">
    <div>
      <p class=\"workspace-hero__eyebrow\"><span class=\"status-dot status-dot--pulse\" aria-hidden=\"true\"></span> Espace de travail</p>
      <h2 id=\"workspace-title\">Votre activité, claire et préte é avancer.</h2>
      <p>Suivez le rythme de la papeterie, addez: une commande ou ouvers directement la bnne collection.</p>
    </div>
    <div class=\"workspace-hero__links\" aria-label=\"Accés rapides\">
      <a href=\"{{ route(\"produits.index\") }}\" class=\"hero-link\">
        <i class=\"ti ti-box\" aria-hidden=\"true\"></i>
        <span>Catalogue</span>
        <i class=\"ti ti-arrow-right\" aria-hidden=\"true\"></i>
      </a>
      <a href=\"{{ route(\"clients.index\") }}\" class=\"hero-link\">
        <i class=\"ti ti-users\" aria-hidden=\"true\"></i>
        <span>Clients</span>
        <i class=\"ti ti-arrow-right\" aria-hidden=\"true\"></i>
      </a>
    </div>
  </section>