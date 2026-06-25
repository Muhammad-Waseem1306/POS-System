@props([
    'title' => null,
    'description' => null,
])

<section {{ $attributes->merge(['class' => 'form-section']) }}>
    @if ($title)
        <div class="form-section__header">
            <h3 class="form-section__title">{{ $title }}</h3>
            @if ($description)
                <p class="form-section__description">{{ $description }}</p>
            @endif
        </div>
    @endif
    <div class="form-section__grid row">
        {{ $slot }}
    </div>
</section>
