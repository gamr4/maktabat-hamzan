<!-- Slim single-line greeting bar -->
<section @class(['greeting-bar', 'greeting-bar--admin' => ($variant ?? null) === 'admin']) aria-labelledby="greeting-title">
    <div class="greeting-bar__left">
        <span class="status-indicator-dot" aria-hidden="true"></span>
        <h2 id="greeting-title" class="greeting-bar__title">
            Bonjour, <strong>{{ trim($name) }}</strong>
            <span class="greeting-bar__sub">{{ $subtext }}</span>
        </h2>
    </div>
    @if (!empty($actions))
        <div class="greeting-bar__actions" aria-label="Accès rapides">
            {!! $actions !!}
        </div>
    @endif
</section>
