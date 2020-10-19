<!--Module bottom -->
@isset ($sc_blocksContent['bottom'])
    @foreach ( $sc_blocksContent['bottom'] as $layout)
        @php
        $arrPage = explode(',', $layout->page)
        @endphp
        @if ($layout->page == '*' || (isset($layout_page) && in_array($layout_page, $arrPage)))
            @if ($layout->type =='html')
                {!! $layout->text !!}
            @elseif($layout->type =='view')
                @if (view()->exists($sc_templatePath.'.block.'.$layout->text))
                    @include($sc_templatePath.'.block.'.$layout->text)
                @endif
            @endif
        @endif
    @endforeach
@endisset
<!--//Module bottom -->