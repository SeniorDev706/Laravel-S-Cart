@php
/*
$layout_page = shop_page
**Variables:**
- $page: no paginate
*/ 
@endphp

@extends($sc_templatePath.'.layout')

@section('block_main')
<section class="section section-sm section-first bg-default text-md-left">
    <div class="container">
        <div class="row">
            <div class="col-12">
                {!! sc_html_render($page->content) !!}
            </div>
        </div>
    </div>
</section>
@endsection

{{-- breadcrumb --}}
@section('breadcrumb')
@php
$bannerBreadcrumb = $modelBanner->start()->getBreadcrumb()->getData()->first();
@endphp
<section class="breadcrumbs-custom">
  <div class="parallax-container" data-parallax-img="{{ asset($bannerBreadcrumb['image'] ?? '') }}">
    <div class="material-parallax parallax">
      <img src="{{ asset($bannerBreadcrumb['image'] ?? '') }}" alt="" style="display: block; transform: translate3d(-50%, 83px, 0px);">
    </div>
    <div class="breadcrumbs-custom-body parallax-content context-dark">
      <div class="container">
        <h2 class="breadcrumbs-custom-title">{{ $title ?? '' }}</h2>
      </div>
    </div>
  </div>
  <div class="breadcrumbs-custom-footer">
    <div class="container">
      <ul class="breadcrumbs-custom-path">
        <li><a href="{{ sc_route('home') }}">{{ trans('front.home') }}</a></li>
        <li class="active">{{ $title ?? '' }}</li>
      </ul>
    </div>
  </div>
</section>
@endsection
{{-- //breadcrumb --}}

@push('styles')
{{-- Your css style --}}
@endpush

@push('scripts')
{{-- Your scripts --}}
@endpush