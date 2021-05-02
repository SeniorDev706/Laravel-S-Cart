@php
/*
$layout_page = shop_cart
**Variables:**
- $cart: no paginate
- $shippingMethod: string
- $paymentMethod: string
- $totalMethod: array
- $dataTotal: array
- $shippingAddress: array
- $countries: array
- $attributesGroup: array
*/
@endphp

@extends($sc_templatePath.'.layout')

@section('block_main')
<section class="section section-xl bg-default text-md-left">
    <div class="container">
        <div class="row">
            @if (count($cart) ==0)

            <div class="col-md-12">
                {!! sc_language_render('front.data_notfound') !!}!
            </div>

            @else

            {{-- Item cart detail --}}
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table box table-bordered">
                        <thead>
                            <tr style="background: #eaebec">
                                <th style="width: 50px;">No.</th>
                                <th style="width: 100px;">{{ sc_language_render('product.sku') }}</th>
                                <th>{{ sc_language_render('product.name') }}</th>
                                <th>{{ sc_language_render('product.price') }}</th>
                                <th>{{ sc_language_render('product.quantity') }}</th>
                                <th>{{ sc_language_render('product.subtal') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $item)
                                @php
                                    $n = (isset($n)?$n:0);
                                    $n++;
                                    // Check product in cart
                                    $product = $modelProduct->start()->getDetail($item->id, null, $item->storeId);
                                    if(!$product) {
                                        continue;
                                    }
                                    // End check product in cart
                                @endphp
                            <tr class="row_cart form-group {{ session('arrErrorQty')[$product->id] ?? '' }}{{ (session('arrErrorQty')[$product->id] ?? 0) ? ' has-error' : '' }}">
                                <td>{{ $n }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>
                                    <a href="{{$product->getUrl() }}" class="row_cart-name">
                                        <img width="100" src="{{sc_file($product->getImage())}}"
                                            alt="{{ $product->name }}">
                                        <span>
                                            {{ $product->name }}<br />

                                            {{-- Go to store --}}
                                            @if (sc_config_global('MultiVendorPro') && config('app.storeId') == SC_ID_ROOT)
                                            <div class="store-url">
                                                <a href="{{ $product->goToStore() }}"><i class="fa fa-shopping-bag" aria-hidden="true"></i> {{ sc_language_render('front.store').' '. $product->store_id  }}</a>
                                            </div>
                                            @endif
                                            {{-- End go to store --}}
                                            
                                            {{-- Process attributes --}}
                                            @if ($item->options->count())
                                            @foreach ($item->options as $groupAtt => $att)
                                            <b>{{ $attributesGroup[$groupAtt] }}</b>: {!! sc_render_option_price($att) !!}
                                            @endforeach
                                            @endif
                                            {{-- //end Process attributes --}}
                                        </span>
                                    </a>
                                </td>

                                <td>{!! $product->showPrice() !!}</td>

                                <td class="cart-col-qty">
                                    <div class="cart-qty">
                                        <input style="width: 150px; margin: 0 auto" type="number" data-id="{{ $item->id }}"
                                            data-rowid="{{$item->rowId}}" data-store_id="{{$product->store_id}}" onChange="updateCart($(this));"
                                            class="item-qty form-control" name="qty-{{$item->id}}" value="{{$item->qty}}">
                                    </div>
                                    <span class="text-danger item-qty-{{$item->id}}" style="display: none;"></span>
                                    @if (session('arrErrorQty')[$product->id] ?? 0)
                                    <span class="help-block">
                                        <br>{{ sc_language_render('cart.minimum_value', ['value' => session('arrErrorQty')[$product->id]]) }}
                                    </span>
                                    @endif
                                </td>

                                <td align="right">
                                    {{sc_currency_render($item->subtotal)}}
                                </td>

                                <td align="center">
                                    <a onClick="return confirm('Confirm?')" title="Remove Item" alt="Remove Item"
                                        class="cart_quantity_delete"
                                        href="{{ sc_route("cart.remove", ['id'=>$item->rowId, 'instance' => 'cart']) }}">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- //Item cart detail --}}

            {{-- Button backshop, clear cart --}}
            <div class="col-md-12">
                <div class="pull-left">
                    <button class="btn btn-default btn-back" type="button"
                        onClick="location.href='{{ sc_route('home') }}'"><i class="fa fa-arrow-left"></i>
                        {{ sc_language_render('cart.back_to_shop') }}</button>
                </div>
                <div class="pull-right">
                    <button class="btn btn-delete-all" type="button"
                        onClick="if(confirm('Confirm ?')) window.location.href='{{ sc_route('cart.clear', ['instance' => 'cart']) }}';">
                        <i class="fa fa-window-close" aria-hidden="true"></i>
                        {{ sc_language_render('cart.remove_all') }}</button>
                </div>
            </div>
            {{--// Button backshop, clear cart --}}

            <div class="col-md-12">
                <form class="sc-shipping-address" id="form-process" role="form" method="POST" action="{{ sc_route('checkout.prepare') }}">
                    {{-- Required csrf for secirity --}}
                    @csrf
                    {{--// Required csrf for secirity --}}
                    <div class="row">
                        <div class="col-md-6">

                            {{-- Select address if customer login --}}
                            @if (auth()->user())
                                <div class="">
                                    <select class="form-control" name="address_process" style="width: 100%;" id="addressList">
                                        <option value="">{{ sc_language_render('cart.change_address') }}</option>
                                        @foreach ($addressList as $k => $address)
                                        <option value="{{ $address->id }}" {{ (old('address_process') ==  $address->id) ? 'selected':''}}>- {{ $address->first_name. ' '.$address->last_name.', '.$address->address1.' '.$address->address2.' '.$address->address3 }}</option>
                                        @endforeach
                                        <option value="new" {{ (old('address_process') ==  'new') ? 'selected':''}}>{{ sc_language_render('cart.add_new_address') }}</option>
                                    </select>
                                </div>
                            @endif
                            {{--// Select address if customer login --}}
                            
                            {{-- Render address shipping --}}
                            <table class="table table-borderless table-responsive">
                                <tr width=100%>
                                    @if (sc_config('customer_lastname'))
                                        <td class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                            <label for="phone" class="control-label"><i class="fa fa-user"></i>
                                                {{ sc_language_render('order.first_name') }}:</label>
                                            <input class="form-control" name="first_name" type="text"
                                                placeholder="{{ sc_language_render('order.first_name') }}"
                                                value="{{old('first_name', $shippingAddress['first_name'])}}">
                                            @if($errors->has('first_name'))
                                            <span class="help-block">{{ $errors->first('first_name') }}</span>
                                            @endif
                                        </td>
                                        <td class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                            <label for="phone" class="control-label"><i class="fa fa-user"></i>
                                                {{ sc_language_render('order.last_name') }}:</label>
                                            <input class="form-control" name="last_name" type="text" placeholder="{{ sc_language_render('order.last_name') }}"
                                                value="{{old('last_name',$shippingAddress['last_name'])}}">
                                            @if($errors->has('last_name'))
                                            <span class="help-block">{{ $errors->first('last_name') }}</span>
                                            @endif
                                        </td>
                                    @else
                                        <td colspan="2"
                                            class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                            <label for="phone" class="control-label"><i class="fa fa-user"></i>
                                                {{ sc_language_render('order.name') }}:</label>
                                            <input class="form-control" name="first_name" type="text" placeholder="{{ sc_language_render('order.name') }}"
                                                value="{{old('first_name',$shippingAddress['first_name'])}}">
                                            @if($errors->has('first_name'))
                                            <span class="help-block">{{ $errors->first('first_name') }}</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>

                                @if (sc_config('customer_name_kana'))
                                    <tr>
                                    <td class="form-group{{ $errors->has('first_name_kana') ? ' has-error' : '' }}">
                                        <label for="phone" class="control-label"><i class="fa fa-user"></i>
                                            {{ sc_language_render('order.first_name_kana') }}:</label>
                                        <input class="form-control" name="first_name_kana" type="text"
                                            placeholder="{{ sc_language_render('order.first_name_kana') }}"
                                            value="{{old('first_name_kana', $shippingAddress['first_name_kana'])}}">
                                        @if($errors->has('first_name_kana'))
                                        <span class="help-block">{{ $errors->first('first_name_kana') }}</span>
                                        @endif
                                    </td>
                                    <td class="form-group{{ $errors->has('last_name_kana') ? ' has-error' : '' }}">
                                        <label for="phone" class="control-label"><i class="fa fa-user"></i>
                                            {{ sc_language_render('order.last_name_kana') }}:</label>
                                        <input class="form-control" name="last_name_kana" type="text" placeholder="{{ sc_language_render('order.last_name_kana') }}"
                                            value="{{old('last_name_kana',$shippingAddress['last_name_kana'])}}">
                                        @if($errors->has('last_name_kana'))
                                        <span class="help-block">{{ $errors->first('last_name_kana') }}</span>
                                        @endif
                                    </td>
                                    </tr>
                                @endif

                                <tr>
                                    @if (sc_config('customer_phone'))
                                        <td class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                            <label for="email" class="control-label"><i class="fa fa-envelope"></i>
                                                {{ sc_language_render('order.email') }}:</label>
                                            <input class="form-control" name="email" type="text" placeholder="{{ sc_language_render('order.email') }}"
                                                value="{{old('email', $shippingAddress['email'])}}">
                                            @if($errors->has('email'))
                                                <span class="help-block">{{ $errors->first('email') }}</span>
                                            @endif
                                        </td>
                                        <td class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                                            <label for="phone" class="control-label"><i class="fa fa-phone"
                                                    aria-hidden="true"></i> {{ sc_language_render('order.phone') }}:</label>
                                            <input class="form-control" name="phone" type="text" placeholder="{{ sc_language_render('order.phone') }}"
                                                value="{{old('phone',$shippingAddress['phone'])}}">
                                            @if($errors->has('phone'))
                                                <span class="help-block">{{ $errors->first('phone') }}</span>
                                            @endif
                                        </td>
                                    @else
                                        <td colspan="2" class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                            <label for="email" class="control-label"><i class="fa fa-envelope"></i>
                                                {{ sc_language_render('order.email') }}:</label>
                                            <input class="form-control" name="email" type="text" placeholder="{{ sc_language_render('order.email') }}"
                                                value="{{old('email',$shippingAddress['email'])}}">
                                            @if($errors->has('email'))
                                                <span class="help-block">{{ $errors->first('email') }}</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>


                                @if (sc_config('customer_country'))
                                <tr>
                                    <td colspan="2" class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
                                        <label for="country" class="control-label"><i class="fas fa-globe"></i>
                                            {{ sc_language_render('order.country') }}:</label>
                                        @php
                                            $ct = old('country',$shippingAddress['country']);
                                        @endphp
                                        <select class="form-control country " style="width: 100%;" name="country">
                                            <option value="">__{{ sc_language_render('order.country') }}__</option>
                                            @foreach ($countries as $k => $v)
                                            <option value="{{ $k }}" {{ ($ct ==$k) ? 'selected':'' }}>{{ $v }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('country'))
                                            <span class="help-block">
                                                {{ $errors->first('country') }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endif


                                <tr>
                                    @if (sc_config('customer_postcode'))
                                        <td class="form-group {{ $errors->has('postcode') ? ' has-error' : '' }}">
                                            <label for="postcode" class="control-label"><i class="fa fa-tablet"></i>
                                                {{ sc_language_render('order.postcode') }}:</label>
                                            <input class="form-control" name="postcode" type="text" placeholder="{{ sc_language_render('order.postcode') }}"
                                                value="{{ old('postcode',$shippingAddress['postcode'])}}">
                                            @if($errors->has('postcode'))
                                                <span class="help-block">{{ $errors->first('postcode') }}</span>
                                            @endif
                                        </td>
                                    @endif

                                    @if (sc_config('customer_company'))
                                        <td class="form-group{{ $errors->has('company') ? ' has-error' : '' }}">
                                            <label for="company" class="control-label"><i class="fa fa-university"></i>
                                                {{ sc_language_render('order.company') }}</label>
                                            <input class="form-control" name="company" type="text" placeholder="{{ sc_language_render('order.company') }}"
                                                value="{{ old('company',$shippingAddress['company'])}}">
                                            @if($errors->has('company'))
                                                <span class="help-block">{{ $errors->first('company') }}</span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>

                                @if (sc_config('customer_address1'))
                                <tr>
                                        <td colspan="2"
                                            class="form-group {{ $errors->has('address1') ? ' has-error' : '' }}">
                                            <label for="address1" class="control-label"><i class="fa fa-list-ul"></i>
                                                {{ sc_language_render('order.address') }}:</label>
                                            <input class="form-control" name="address1" type="text" placeholder="{{ sc_language_render('order.address1') }}"
                                                value="{{ old('address1',$shippingAddress['address1'])}}">
                                            @if($errors->has('address1'))
                                                <span class="help-block">{{ $errors->first('address1') }}</span>
                                            @endif
                                        </td>
                                </tr>
                                @endif

                                @if (sc_config('customer_address2'))
                                <tr>
                                        <td colspan="2"
                                            class="form-group {{ $errors->has('address2') ? ' has-error' : '' }}">
                                            <label for="address2" class="control-label"><i class="fa fa-list-ul"></i>
                                                {{ sc_language_render('order.address') }}:</label>
                                            <input class="form-control" name="address2" type="text" placeholder="{{ sc_language_render('order.address2') }}"
                                                value="{{ old('address2',$shippingAddress['address2'])}}">
                                            @if($errors->has('address2'))
                                                <span class="help-block">{{ $errors->first('address2') }}</span>
                                            @endif
                                        </td>
                                </tr>
                                @endif

                                @if (sc_config('customer_address3'))
                                <tr>
                                        <td colspan="2"
                                            class="form-group {{ $errors->has('address3') ? ' has-error' : '' }}">
                                            <label for="address3" class="control-label"><i class="fa fa-list-ul"></i>
                                                {{ sc_language_render('order.address') }}:</label>
                                            <input class="form-control" name="address3" type="text" placeholder="{{ sc_language_render('order.address3') }}"
                                                value="{{ old('address3',$shippingAddress['address3'])}}">
                                            @if($errors->has('address3'))
                                                <span class="help-block">{{ $errors->first('address3') }}</span>
                                            @endif
                                        </td>
                                </tr>
                                @endif

                                <tr>
                                    <td colspan="2">
                                        <label class="control-label"><i class="fa fa-calendar-o"></i>
                                            {{ sc_language_render('cart.note') }}:</label>
                                        <textarea class="form-control" rows="5" name="comment"
                                            placeholder="{{ sc_language_render('cart.note') }}....">{{ old('comment','')}}</textarea>
                                    </td>
                                </tr>

                            </table>
                            {{--// Render address shipping --}}
                        </div>

                        <div class="col-md-6">
                            {{-- Total --}}
                            <div class="row">
                                <div class="col-md-12">
                                    {{-- Data total --}}
                                    @if (view()->exists($sc_templatePath.'.common.render_total'))
                                        @include($sc_templatePath.'.common.render_total')
                                    @endif
                                    {{-- Data total --}}

                                    {{-- Total method --}}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div
                                                class="form-group {{ $errors->has('totalMethod') ? ' has-error' : '' }}">
                                                @if($errors->has('totalMethod'))
                                                    <span class="help-block">{{ $errors->first('totalMethod') }}</span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                @foreach ($totalMethod as $key => $plugin)
                                                    @if (view()->exists($plugin['pathPlugin'].'::render'))
                                                        @include($plugin['pathPlugin'].'::render')
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    {{-- //Total method --}}

@if (!sc_config('shipping_off'))
                                    {{-- Shipping method --}}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div
                                                class="form-group {{ $errors->has('shippingMethod') ? ' has-error' : '' }}">
                                                <h3 class="control-label"><i class="fa fa-truck" aria-hidden="true"></i>
                                                    {{ sc_language_render('order.shipping_method') }}:<br></h3>
                                                @if($errors->has('shippingMethod'))
                                                <span class="help-block">{{ $errors->first('shippingMethod') }}</span>
                                                @endif
                                            </div>

                                            <div class="form-group">
                                                @foreach ($shippingMethod as $key => $shipping)
                                                <div>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="shippingMethod"
                                                            value="{{ $shipping['key'] }}"
                                                            {{ (old('shippingMethod') == $key)?'checked':'' }}
                                                            style="position: relative;"
                                                            {{ ($shipping['permission'])?'':'disabled' }}>
                                                        {{ $shipping['title'] }}
                                                    </label>
                                                </div>

                                                {{-- Render view --}}
                                                @if (view()->exists($shipping['pathPlugin'].'::render'))
                                                    @include($shipping['pathPlugin'].'::render')
                                                @endif
                                                {{-- //Render view --}}

                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    {{-- //Shipping method --}}
@endif

@if (!sc_config('payment_off'))
                                    {{-- Payment method --}}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div
                                                class="form-group {{ $errors->has('paymentMethod') ? ' has-error' : '' }}">
                                                <h3 class="control-label"><i class="fa fa-credit-card-alt"></i>
                                                    {{ sc_language_render('order.payment_method') }}:<br></h3>
                                                @if($errors->has('paymentMethod'))
                                                <span class="help-block">{{ $errors->first('paymentMethod') }}</span>
                                                @endif
                                            </div>
                                            <div class="form-group cart-payment-method">
                                                @foreach ($paymentMethod as $key => $payment)
                                                <div>
                                                    <label class="radio-inline">
                                                        <input type="radio" name="paymentMethod"
                                                            value="{{ $payment['key'] }}"
                                                            {{ (old('shippingMethod') == $key)?'checked':'' }}
                                                            style="position: relative;"
                                                            {{ ($payment['permission'])?'':'disabled' }}>
                                                            <label class="radio-inline" for="payment-{{ $payment['key'] }}">
                                                                <img title="{{ $payment['title'] }}"
                                                                    alt="{{ $payment['title'] }}"
                                                                    src="{{ sc_file($payment['image']) }}">
                                                            </label>
                                                    </label>
                                                </div>

                                                {{-- Render view --}}
                                                @if (view()->exists($payment['pathPlugin'].'::render'))
                                                    @include($payment['pathPlugin'].'::render')
                                                @endif
                                                {{-- //Render view --}}

                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    {{-- //Payment method --}}
@endif

                                </div>
                                
                            </div>
                            {{-- End total --}}

                            {{-- Button checkout --}}
                            <div class="row" style="padding-bottom: 20px;">
                                <div class="col-md-12 text-center">
                                    <div class="pull-right">
                                        {!! $viewCaptcha ?? ''!!}
                                        <button class="button button-lg button-secondary" type="submit" id="button-form-process">{{ sc_language_render('checkout.title') }}</button>
                                    </div>
                                </div>
                            </div>
                            {{-- Button checkout --}}

                        </div>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
</section>

{{-- Render include view --}}
@if (!empty($layout_page && $includePathView = config('sc_include_view.'.$layout_page, [])))
@foreach ($includePathView as $view)
  @if (view()->exists($view))
    @include($view)
  @endif
@endforeach
@endif
{{--// Render include view --}}


@endsection


{{-- breadcrumb --}}
@section('breadcrumb')
<section class="breadcrumbs-custom">
    <div class="breadcrumbs-custom-footer">
        <div class="container">
          <ul class="breadcrumbs-custom-path">
            <li><a href="{{ sc_route('home') }}">{{ sc_language_render('front.home') }}</a></li>
            <li class="active">{{ $title ?? '' }}</li>
          </ul>
        </div>
    </div>
</section>
@endsection
{{-- //breadcrumb --}}


@push('scripts')

{{-- Render script from total method --}}
@foreach ($totalMethod as $key => $plugin)
@if (view()->exists($plugin['pathPlugin'].'::script'))
    @include($plugin['pathPlugin'].'::script')
@endif
@endforeach
{{--// Render script from total method --}}

{{-- Render script from shipping method --}}
@foreach ($shippingMethod as $key => $plugin)
@if (view()->exists($plugin['pathPlugin'].'::script'))
    @include($plugin['pathPlugin'].'::script')
@endif
@endforeach
{{--// Render script from shipping method --}}

{{-- Render script from payment method --}}
@foreach ($paymentMethod as $key => $plugin)
@if (view()->exists($plugin['pathPlugin'].'::script'))
    @include($plugin['pathPlugin'].'::script')
@endif
@endforeach
{{--// Render script from payment method --}}

{{-- Render include script --}}
@if (!empty($layout_page) && $includePathScript = config('sc_include_script.'.$layout_page, []))
@foreach ($includePathScript as $script)
  @if (view()->exists($script))
    @include($script)
  @endif
@endforeach
@endif
{{--// Render include script --}}

<script type="text/javascript">
    function updateCart(obj){
        let new_qty = obj.val();
        let storeId = obj.data('store_id');
        let rowid = obj.data('rowid');
        let id = obj.data('id');
        $.ajax({
            url: '{{ sc_route('cart.update') }}',
            type: 'POST',
            dataType: 'json',
            async: false,
            cache: false,
            data: {
                id: id,
                rowId: rowid,
                new_qty: new_qty,
                storeId: storeId,
                _token:'{{ csrf_token() }}'},
            success: function(data){
                error= parseInt(data.error);
                if(error ===0)
                {
                    window.location.replace(location.href);
                }else{
                    $('.item-qty-'+id).css('display','block').html(data.msg);
                }

                }
        });
    }

    function buttonQty(obj, action){
        var parent = obj.parent();
        var input = parent.find(".item-qty");
        if(action === 'reduce'){
            input.val(parseInt(input.val()) - 1);
        }else{
            input.val(parseInt(input.val()) + 1);
        }
        updateCart(input)
    }

    $('#button-form-process').click(function(){
        $('#form-process').submit();
        $(this).prop('disabled',true);
    });

    $('#addressList').change(function(){
        var id = $('#addressList').val();
        if(!id) {
            return;   
        } else if(id == 'new') {
            $('#form-process [name="first_name"]').val('');
            $('#form-process [name="last_name"]').val('');
            $('#form-process [name="phone"]').val('');
            $('#form-process [name="postcode"]').val('');
            $('#form-process [name="company"]').val('');
            $('#form-process [name="country"]').val('');
            $('#form-process [name="address1"]').val('');
            $('#form-process [name="address2"]').val('');
            $('#form-process [name="address3"]').val('');
        } else {
            $.ajax({
            url: '{{ sc_route('customer.address_detail') }}',
            type: 'GET',
            dataType: 'json',
            async: false,
            cache: false,
            data: {
                id: id,
            },
            success: function(data){
                error= parseInt(data.error);
                if(error === 1)
                {
                    alert(data.msg);
                }else{
                    $('#form-process [name="first_name"]').val(data.first_name);
                    $('#form-process [name="last_name"]').val(data.last_name);
                    $('#form-process [name="phone"]').val(data.phone);
                    $('#form-process [name="postcode"]').val(data.postcode);
                    $('#form-process [name="company"]').val(data.company);
                    $('#form-process [name="country"]').val(data.country);
                    $('#form-process [name="address1"]').val(data.address1);
                    $('#form-process [name="address2"]').val(data.address2);
                    $('#form-process [name="address3"]').val(data.address3);
                }

                }
        });
        }
    });

</script>
@endpush

@push('styles')
{{-- Your css style --}}
@endpush