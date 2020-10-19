@switch($kind)
    @case(SC_PRODUCT_GROUP)
        <span class="sc-new-price">{!! trans('product.price_group_chose') !!}</span>
        @break
    @default
        @if ($price == $priceFinal)
            <span class="sc-new-price">{!! sc_currency_render($price) !!}</span>
        @else
            <span class="sc-new-price">{!! sc_currency_render($priceFinal) !!}</span>
            <span class="sc-old-price">{!!  sc_currency_render($price) !!}</span>
        @endif
        @if (sc_config('product_display_price_include_tax'))
            ({!! trans('front.price_include_tax') !!})
        @else
            ({!! trans('front.price_without_tax') !!})
        @endif
        
@endswitch