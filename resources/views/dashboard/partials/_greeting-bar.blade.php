<!-- Slim single-line greeting bar -->
<section class="greeting-bar" aria-labelledby="greeting-title">
    <div class="greeting-bar__left">
        <span class="status-indicator-dot" aria-hidden="true"></span>
        <h2 id="greeting-title" class="greeting-bar__title">
            Bonjour, <strong>{{ trim($name) }}</strong>
            <span class="greeting-bar__sub">{{ $subtext }}</span>
        </h2>
    </div>
    <div class="greeting-bar__actions" aria-label="Accès rapides">
        {!! $actions !!}
    </div>
</section>