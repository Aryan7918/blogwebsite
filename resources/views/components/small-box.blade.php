@props(['number', 'text', 'class', 'color','href'])

<div class="col-lg-3 col-6">
    <div class="small-box bg-{{ $color }}">
        <div class="inner">
            <h3>{{ $number }}</h3>
            <p>{{ $text }}</p>
        </div>
        <div class="icon">
            <i {{ $attributes->merge(['class' => 'ion ion-' . $class]) }}></i>
        </div>
        <a href="{{ $href }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
</div>
