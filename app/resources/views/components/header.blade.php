@props(['style' => null])

<header {{ $attributes->bem('header', $style) }}>
    <x-wrapper class="header__wrapper">
        <a class="header__logotype" href="{{ url('') }}">
            {{ __('components.header.logotype') }}
        </a>
        <nav class="header__nav">
            <ul>
                <li>
                    <a href="{{ url('/votes') }}">
                        {{ __('voting-lists.title') }}
                    </a>
                </li>
                <li>
                    <a href="{{ url('/pages/about') }}">
                        {{ __('components.header.nav.about') }}
                    </a>
                </li>
            </ul>
        </nav>
    </x-wrapper>
</header>
