@extends('admin.layout')

@section('main')

<div class="row">
  <div class="col-md-12">

    <div class="card card-primary card-outline card-outline-tabs">
      <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin_template.index') }}" >{{ trans('template.local') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="#" >{{ trans('template.online') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" target=_new  href="{{ route('admin_template.import') }}" ><span><i class="fas fa-save"></i> {{ trans('plugin.import_data', ['data' => 'template']) }}</span></a>
          </li>
          <li class="btn-group float-right m-2">
            {!! trans('template.template_more') !!}
          </li>
        </ul>
      </div>

      <div class="card-header">
        <div class="float-right" >
          <div class="filter-api">
          <input name="sort_download" id="sort_download" data-name="sort_download" type="checkbox"  {{ $sort_download? 'checked':'' }} class="input">
          <label class="checkbox-inline form-check-label" for="sort_download">
            {{ trans('plugin.libraries.sort_download') }}
          </label>
          </div>
          <div class="filter-api">
          <input name="sort_rating" id="sort_rating" data-name="sort_rating" type="checkbox"  {{ $sort_rating? 'checked':'' }} class="input">
          <label class="checkbox-inline form-check-label" for="sort_rating">
            {{ trans('plugin.libraries.sort_rating') }}
          </label>
          </div>
          <div class="filter-api">
          <input name="sort_price_asc" id="sort_price_asc" data-name="sort_price_asc" type="checkbox"  {{ $sort_price_asc? 'checked':'' }} class="input">
          <label class="checkbox-inline form-check-label" for="sort_price_asc">
            {{ trans('plugin.libraries.sort_price_asc') }}
          </label>
          </div>
          <div class="filter-api">
          <input name="sort_price_desc" id="sort_price_desc" data-name="sort_price_desc" type="checkbox"  {{ $sort_price_desc? 'checked':'' }} class="input">
          <label class="checkbox-inline form-check-label" for="sort_price_desc">
            {{ trans('plugin.libraries.sort_price_desc') }}
          </label>
          </div>

          <div class="filter-api">
          <input name="only_free" id="only_free" data-name="only_free" type="checkbox"  {{ $only_free? 'checked':'' }} class="input">
          <label class="checkbox-inline form-check-label" for="only_free">
            {{ trans('plugin.libraries.only_free') }}
          </label> 
          </div>
          <div class="filter-api">
          <input name="all_version" id="all_version" data-name="all_version" type="checkbox"  {{ $all_version? 'checked':'' }} class="input">
          <label class="checkbox-inline form-check-label" for="all_version">
            {{ trans('plugin.libraries.all_version') }}
          </label>  
          </div>  
          <input class="form-control-sm filter-search" name="search_keyword" data-name="search_keyword" type="text" value="{{ $search_keyword ?? '' }}" placeholder="{{ trans('plugin.libraries.enter_search_keyword') }}">
          <button title="Filter" class="btn btn-flat filter-button"  id="filter-button"><i class="fa fa-filter" aria-hidden="true"></i></button>
        </div>
        <a class="link-filter" href=""></a>
      </div>

      <div class="card-body" id="pjax-container">
        <div class="tab-content" id="custom-tabs-four-tabContent">
          <table class="table table-hover text-nowrap table-bordered">
            <thead>
              <tr>
                <th>{{ trans('template.image') }}</th>
                <th>{{ trans('template.code') }}</th>
                <th>{{ trans('template.name') }}</th>
                <th>{{ trans('template.version') }}</th>
                <th>{{ trans('template.compatible') }}</th>
                <th>{{ trans('template.auth') }}</th>
                <th>{{ trans('template.image_demo') }}</th>
                <th>{{ trans('template.price') }}</th>
                <th>{{ trans('template.rated') }}</th>
                <th><i class="fa fa-download" aria-hidden="true"></i></th>
                <th>{{ trans('template.date') }}</th>
                <th>{{ trans('template.action') }}</th>
              </tr>
            </thead>
            <tbody>
              @if (!$arrTemplateLibrary)
                <tr>
                  <td colspan="12" style="text-align: center;color: red;">
                    {{ trans('template.empty') }}
                  </td>
                </tr>
              @else
                @foreach ($arrTemplateLibrary as  $template)
  @php
  $scVersion = explode(',', $template['scart_version']);
  $scRenderVersion = implode(' ',array_map(
  function($version){
  return '<span title="SCart version '.$version.'" class="badge badge-primary">'.$version.'</span>';
  },$scVersion)
  );
  
  if (array_key_exists($template['key'], $arrTemplateLocal)) 
  {
  $templateAction = trans('template.located');
  } elseif(!in_array(config('scart.version'), $scVersion)) {
  $templateAction = '';
  } else {
  if(($template['is_free'] || $template['price_final'] == 0)) {
  $templateAction = '<span onClick="installTemplate($(this),\''.$template['key'].'\', \''.$template['file'].'\');" title="'.trans('template.install').'" type="button" class="btn btn-flat btn-success"><i class="fa fa-plus-circle"></i></span>';
  } else {
  $templateAction = '';
  }
  }
  @endphp
                  <tr>
                    <td>{!! sc_image_render($template['image'],'50px', '', $template['name']) !!}</td>
                    <td>{{ $template['key'] }}</td>
                    <td>{{ $template['name'] }} <span data-toggle="tooltip" title="{!! $template['description'] !!}"><i class="fa fa-info-circle" aria-hidden="true"></i></span></td>
                    <td>{{ $template['version']??'' }}</td>
                    <td><b>SC:</b> {!! $scRenderVersion !!}</td>
                    <td>{{ $template['username']??'' }}</td>
                    <td class="pointer" onclick="imagedemo('{{ $template['image_demo']??'' }}')"><a>{{ trans('template.click_here') }}</a></td>
                    <td>
                      @if ($template['is_free'] || $template['price_final'] == 0)
                        <span class="badge badge-success">{{ trans('template.free') }}</span>
                      @else
                          @if ($template['price_final'] != $template['price'])
                              <span class="sc-old-price">{{ number_format($template['price']) }}</span><br>
                              <span class="sc-new-price">${{ number_format($template['price_final']) }}</span>
                          @else
                            <span class="sc-new-price">${{ number_format($template['price_final']) }}</span>
                          @endif
                      @endif
                    </td>
                    <td>
                      @php
                      $vote = $template['points'];
                      $vote_times = $template['times'];
                      $cal_vote = number_format($template['rated'], 1);
                      @endphp
                      <span title="{{ $cal_vote }}" style="color:#e66c16">
                        @for ($i = 1; $i <= $cal_vote; $i++) 
                        <i class="fa fa-star voted" aria-hidden="true"></i>
                        @endfor
                        @if ($cal_vote == round($cal_vote))
                          @for ($k = 1; $k <= (5-$cal_vote); $k++) 
                          <i class="fa fa-star-o" aria-hidden="true"></i>
                          @endfor
                        @else
                           <i class="fa fa-star-half-o voted" aria-hidden="true"></i>
                           @for ($k = 1; $k <= (5-$cal_vote); $k++) 
                           <i class="fa fa-star-o" aria-hidden="true"></i>
                           @endfor
                        @endif
                     </span>
                     <span class="sum_vote">
                      ({{ $vote }}/{{ $vote_times }})
                    </span>
  
                    </td>
                    <td>{{ $template['download']??'' }}</td>
                    <td>{{ $template['date']??'' }}</td>
                    <td>
                      {!! $templateAction ?? '' !!}
                      <a href="{{ $template['link'] }}" title="Link home">
                        <span class="btn btn-flat btn-default" type="button">
                        <i class="fa fa-chain-broken" aria-hidden="true"></i> {!! trans('template.link') !!}
                        </span>
                      </a>
                    </td>                        
                  </tr>
                @endforeach
              @endif
            </tbody>
          </table>

        </div>

        <div class="block-pagination clearfix m-10">
          <div class="ml-3 float-left">
            {!! $resultItems??'' !!}
          </div>
          <ul class="pagination pagination-sm mr-3 float-right">
            <!-- Previous Page Link -->
            @if ($dataApi['current_page'] > 1)
            <li class="page-item"><a class="page-link pjax-container" href="{{ route('admin_template_online') }}?page={{ $dataApi['current_page'] - 1}}" rel="prev">«</a></li>
            @endif
            @for ($i = 1; $i < $dataApi['last_page']; $i++)
                @if ( $dataApi['current_page'] == $i)
                <li class="page-item active"><span class="page-link pjax-container">{{ $i }}</span></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ route('admin_template_online') }}?page={{ $i }}">{{ $i }}</a></li>
                @endif
            @endfor
            @if ($dataApi['current_page'] < $dataApi['last_page'])
            <li class="page-item"><a class="page-link pjax-container" href="{{ route('admin_template_online') }}?page={{ $dataApi['current_page'] + 1}}" rel="next">»</a></li>
            @endif
          </ul>
        </div>

      </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
{{-- //Pjax --}}
<script src="{{ asset('admin/plugin/jquery.pjax.js')}}"></script>


<script type="text/javascript">
  function installTemplate(obj,key, path) {
      $('#loading').show()
      obj.button('loading');
      $.ajax({
        type: 'POST',
        dataType:'json',
        url: '{{ route('admin_template_online.install') }}',
        data: {
          "_token": "{{ csrf_token() }}",
          "key":key,
          "path":path,
        },
        success: function (response) {
          console.log(response);
              if(parseInt(response.error) ==0){
                alertJs('success', response.msg);
              location.reload();
              }else{
                alertMsg('error', response.msg, 'You clicked the button!');
              }
              $('#loading').hide();
              obj.button('reset');
        }
      });
  }

    $(document).on('pjax:send', function() {
      $('#loading').show()
    })
    $(document).on('pjax:complete', function() {
      $('#loading').hide()
    })

    $(document).ready(function(){
    // does current browser support PJAX
      if ($.support.pjax) {
        $.pjax.defaults.timeout = 2000; // time in milliseconds
      }
    });
    // tag a
    $(function(){
      $(document).pjax('a.page-link, a.link-filter', '#pjax-container')
    })

    $(document).on('ready pjax:end', function(event) {
      //
    })

    $('[data-toggle="tooltip"]').tooltip();

    function imagedemo(image) {
      Swal.fire({
        title: '{{  trans('template.image_demo') }}',
        text: '',
        imageUrl: image,
        imageWidth: 800,
        imageHeight: 800,
        imageAlt: 'Image demo',
      })
    }

</script>

<script>
  $('.input').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '20%' /* optional */
  });

  $('#filter-button').click(function(){
    var urlNext = '{{ url()->current() }}';
    var all_version = $('[name="all_version"]:checked').val();
    var only_free = $('[name="only_free"]:checked').val();
    var sort_rating = $('[name="sort_rating"]:checked').val();
    var sort_price_asc = $('[name="sort_price_asc"]:checked').val();
    var sort_price_desc = $('[name="sort_price_desc"]:checked').val();
    var sort_download = $('[name="sort_download"]:checked').val();
    var search_keyword = $('[name="search_keyword"]').val();

    var urlString = "";
    if(all_version) {
      urlString +="&all_version=1";
    }
    if(only_free) {
      urlString +="&only_free=1";
    }
    if(sort_rating) {
      urlString +="&sort_rating=1";
    }
    if(sort_price_asc) {
      urlString +="&sort_price_asc=1";
      $('[name="sort_price_desc"]').prop("checked", false);
    }
    if(sort_price_desc && !sort_price_asc) {
      urlString +="&sort_price_desc=1";
    }
    if(sort_download) {
      urlString +="&sort_download=1";
    }
    if(search_keyword){
      urlString +="&search_keyword="+search_keyword;
    }
      urlString = urlString.substr(1);
      urlNext = urlNext+"?"+urlString;
      $('.link-filter').attr('href', urlNext);
      $('.link-filter').trigger('click');
  });
</script>

@endpush
