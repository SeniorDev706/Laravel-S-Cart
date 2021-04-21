@php
/*
$layout_page = shop_profile
** Variables:**
- $statusOrder
- $orders
*/ 
@endphp

@extends($sc_templatePath.'.layout')

@section('block_main')
<section class="section section-sm section-first bg-default text-md-left">
<div class="container">
  <div class="row">
    <div class="col-12 col-sm-12 col-md-3">
      @include($sc_templatePath.'.account.nav_customer')
    </div>
    <div class="col-12 col-sm-12 col-md-9 min-height-37vh">
      <h6 class="aside-title">{{ $title }}</h6>
      @if (count($orders) ==0)
      <div class="text-danger">
        {{ sc_language_render('front.data_notfound') }}
      </div>
      @else
      <table class="table box table-bordered table-responsive" width="100%">
        <thead>
          <tr>
            <th style="width: 50px;">No.</th>
            <th style="width: 100px;">ID</th>
            <th>{{ sc_language_render('order.total') }}</th>
            <th>{{ sc_language_render('order.order_status') }}</th>
            <th>{{ sc_language_render('other.created_at') }}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach($orders as $order)
          @php
          $n = (isset($n)?$n:0);
          $n++;
          @endphp
          <tr>
            <td><span class="item_21_id">{{ $n }}</span></td>
            <td><span class="item_21_sku">#{{ $order->id }}</span></td>
            <td align="right">
              {{ number_format($order->total) }}
            </td>
            <td>{{ $statusOrder[$order->status]}}</td>
            <td>{{ $order->created_at }}</td>
            <td>
              <a href="{{ sc_route('customer.order_detail', ['id' => $order->id ]) }}"><i class="fa fa-indent" aria-hidden="true"></i> {{ sc_language_render('order.detail') }}</a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @endif
    </div>
  </div>
</div>
</section>
@endsection

{{-- breadcrumb --}}
@section('breadcrumb')
<section class="breadcrumbs-custom">
    <div class="breadcrumbs-custom-footer">
        <div class="container">
          <ul class="breadcrumbs-custom-path">
            <li><a href="{{ sc_route('home') }}">{{ sc_language_render('front.home') }}</a></li>
            <li><a href="{{ sc_route('customer.index') }}">{{ sc_language_render('front.my_account') }}</a></li>
            <li class="active">{{ $title ?? '' }}</li>
          </ul>
        </div>
    </div>
</section>
@endsection
{{-- //breadcrumb --}}