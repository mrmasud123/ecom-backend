@props(['pageTitle' => []])

@php
    $breadcrumbs = is_array($pageTitle) ? $pageTitle : [$pageTitle];
@endphp

<div class="flex flex-wrap items-center justify-between gap-3 mb-6">

    <!-- Page Title (Last Item) -->
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white/90">
        {{ end($breadcrumbs)['name'] }}
    </h2>

    <!-- Breadcrumb -->
    <nav>
        <ol class="flex items-center gap-1.5 flex-wrap">

            <!-- Home -->
            <li class="flex items-center gap-1.5">
                <a class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
                   href="{{ url('/') }}">
                    Home
                </a>
                <span class="text-gray-400">/</span>
            </li>

            <!-- Dynamic Breadcrumbs -->
            @foreach($breadcrumbs as $index => $item)
                @if ($loop->last)
                    <li class="flex items-center gap-1.5">
                        @if ($index != count($breadcrumbs) - 1)
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $item['name'] }}
                        </span>
                            <span class="text-gray-400">/</span>
                        @else
                            <!-- Active -->
                            <span class="text-sm text-gray-800 dark:text-white/90 font-medium">
                            {{ $item['name'] }}
                        </span>
                        @endif

                    </li>
                @else
                    <a class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
                       href="{{ url($item['link']) }}">
                        <li class="flex items-center gap-1.5">
                            @if ($index != count($breadcrumbs) - 1)
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $item['name'] }}
                        </span>
                                <span class="text-gray-400">/</span>
                            @else
                                <!-- Active -->
                                <span class="text-sm text-gray-800 dark:text-white/90 font-medium">
                            {{ $item['name'] }}
                        </span>
                            @endif

                        </li>
                    </a>

                @endif

            @endforeach

        </ol>
    </nav>
</div>
