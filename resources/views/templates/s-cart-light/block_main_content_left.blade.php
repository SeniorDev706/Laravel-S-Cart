    <div class="aside row row-30 row-md-50 justify-content-md-between">
      <!--Module left -->
      @isset ($sc_blocksContent['left'])
        @foreach ( $sc_blocksContent['left'] as $layout)
          @php
          $arrPage = explode(',', $layout->page)
          @endphp
          @if (empty($layout->page) || $layout->page == '*' || (isset($layout_page) && in_array($layout_page, $arrPage)))
            @if ($layout->type =='html')
            {!! $layout->text !!}
            @elseif($layout->type =='view')
              @includeIf($sc_templatePath.'.block.'.$layout->text)
            @endif
          @endif
        @endforeach
      @endisset
      <!--//Module left -->

    </div>