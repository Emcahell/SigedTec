@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Paginación') }}">

        <div class="flex gap-2 items-center justify-between sm:hidden">

            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center gap-1 px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-blue-100 cursor-not-allowed leading-5 rounded-md">
                    <x-icon-chevron-left />
                    {{ __('Anterior') }}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center gap-1 px-4 py-2 text-sm font-medium text-blue-700 bg-white border border-blue-200 leading-5 rounded-md hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <x-icon-chevron-left />
                    {{ __('Anterior') }}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center gap-1 px-4 py-2 text-sm font-medium text-blue-700 bg-white border border-blue-200 leading-5 rounded-md hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    {{ __('Siguiente') }}
                    <x-icon-chevron-right />
                </a>
            @else
                <span class="inline-flex items-center gap-1 px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-blue-100 cursor-not-allowed leading-5 rounded-md">
                    {{ __('Siguiente') }}
                    <x-icon-chevron-right />
                </span>
            @endif

        </div>

        <div class="hidden sm:flex-1 sm:flex sm:gap-2 sm:items-center sm:justify-between">

            <div>
                <p class="text-sm text-gray-600 leading-5">
                    {{ __('Mostrando') }}
                    @if ($paginator->firstItem())
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        {{ __('a') }}
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    {{ __('de') }}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {{ __('resultados') }}
                </p>
            </div>

            <div>
                <span class="inline-flex rtl:flex-row-reverse rounded-md">

                    {{-- Enlace a la página anterior --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('Anterior') }}">
                            <span class="inline-flex items-center px-2 py-2 text-sm font-medium text-gray-400 bg-white border border-blue-100 cursor-not-allowed rounded-l-md leading-5" aria-hidden="true">
                                <x-icon-chevron-left />
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-2 py-2 text-sm font-medium text-blue-600 bg-white border border-blue-200 rounded-l-md leading-5 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" aria-label="{{ __('Anterior') }}">
                            <x-icon-chevron-left />
                        </a>
                    @endif

                    {{-- Elementos de paginación --}}
                    @foreach ($elements as $element)
                        {{-- Separador --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-blue-100 cursor-default leading-5">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Enlaces de página --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-blue-700 border border-blue-700 cursor-default leading-5">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-blue-700 bg-white border border-blue-200 leading-5 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" aria-label="{{ __('Ir a la página :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Enlace a la página siguiente --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-blue-600 bg-white border border-blue-200 rounded-r-md leading-5 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" aria-label="{{ __('Siguiente') }}">
                            <x-icon-chevron-right />
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('Siguiente') }}">
                            <span class="inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-400 bg-white border border-blue-100 cursor-not-allowed rounded-r-md leading-5" aria-hidden="true">
                                <x-icon-chevron-right />
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
