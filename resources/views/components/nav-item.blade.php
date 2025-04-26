@props(['href', 'class', 'navname','active'])
<li class="nav-item ">
    <a {{ $attributes->merge(['href' => $href, 'class'=>"nav-link $active "]) }} >
        <i {{ $attributes->merge(['class' => 'nav-icon fas ' . $class]) }}></i>
        <p>
            {{ $navname  }}
        </p>
    </a>
</li>
