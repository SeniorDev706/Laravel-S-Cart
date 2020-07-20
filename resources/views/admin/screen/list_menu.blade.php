@extends('admin.layout')

@section('main')
@php
    $id = empty($id) ? 0 : $id;
@endphp

<div class="row">

  <div class="col-md-6">

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">
          <div class="btn-group">
            <a class="btn btn-warning btn-flat btn-sm menu-sort-save" title="Save"><i class="fa fa-save"></i><span class="hidden-xs">&nbsp;Save</span></a>
          </div>
        </h3>
      </div>

      <div class="box-body table-responsive box-primary">

<div class="dd" id="menu-sort">
    <ol class="dd-list">
@php
  $menus = Admin::getMenu();
  // print_r($menus);
@endphp
{{-- Level 0 --}}
        @foreach ($menus[0] as $level0)
          @if ($level0->type ==1)
            <li class="dd-item " data-id="{{ $level0->id }}">
                <div class="dd-handle header-fix  {{ ($level0->id == $id)? 'active-item' : '' }}">
                  {!! sc_language_render($level0->title) !!}
                  <span class="pull-right dd-nodrag">
                      <a href="{{ route('admin_menu.edit',['id'=>$level0->id]) }}"><i class="fa fa-edit fa-2x"></i></a>
                      &nbsp; 
                      <a data-id="{{ $level0->id }}" class="remove_menu"><i class="fa fa-trash fa-2x"></i></a>
                  </span>
                </div>
            </li>
          @elseif($level0->uri)
            <li class="dd-item" data-id="{{ $level0->id }}">
                <div class="dd-handle {{ ($level0->id == $id)? 'active-item' : '' }}">
                  <i class="fa {{ $level0->icon }}"></i> {!! sc_language_render($level0->title) !!}
                  <span class="pull-right dd-nodrag">
                      <a href="{{ route('admin_menu.edit',['id'=>$level0->id]) }}"><i class="fa fa-edit fa-edit fa-2x"></i></a>
                      &nbsp; 
                      <a data-id="{{ $level0->id }}" class="remove_menu"><i class="fa fa-trash fa-edit fa-2x"></i></a>
                  </span>
                </div>
            </li>
          @else
            <li class="dd-item " data-id="{{ $level0->id }}">
              <div class="dd-handle {{ ($level0->id == $id)? 'active-item' : '' }}">
                <i class="fa {{ $level0->icon }}"></i> {!! sc_language_render($level0->title) !!}
                  <span class="pull-right dd-nodrag">
                      <a href="{{ route('admin_menu.edit',['id'=>$level0->id]) }}"><i class="fa fa-edit fa-edit fa-2x"></i></a>
                      &nbsp; 
                      <a data-id="{{ $level0->id }}" class="remove_menu"><i class="fa fa-trash fa-edit fa-2x"></i></a>
                  </span>
              </div>
    {{-- Level 1 --}}
            @if (isset($menus[$level0->id]) && count($menus[$level0->id]))
              <ol class="dd-list">
                @foreach ($menus[$level0->id] as $level1)
                  @if($level1->uri)
                    <li class="dd-item" data-id="{{ $level1->id }}">
                        <div class="dd-handle {{ ($level1->id == $id)? 'active-item' : '' }}">
                          <i class="fa {{ $level1->icon }}"></i> {!! sc_language_render($level1->title) !!}
                          <span class="pull-right dd-nodrag">
                              <a href="{{ route('admin_menu.edit',['id'=>$level1->id]) }}"><i class="fa fa-edit fa-edit fa-2x"></i></a>
                              &nbsp; 
                              <a data-id="{{ $level1->id }}" class="remove_menu"><i class="fa fa-trash fa-edit fa-2x"></i></a>
                          </span>
                        </div>
                    </li>
                  @else
                  <li class="dd-item" data-id="{{ $level1->id }}">
                    <div class="dd-handle {{ ($level1->id == $id)? 'active-item' : '' }}">
                      <i class="fa {{ $level1->icon }}"></i> {!! sc_language_render($level1->title) !!}
                      <span class="pull-right dd-nodrag">
                          <a href="{{ route('admin_menu.edit',['id'=>$level1->id]) }}"><i class="fa fa-edit fa-edit fa-2x"></i></a>
                          &nbsp; 
                          <a data-id="{{ $level1->id }}" class="remove_menu"><i class="fa fa-trash fa-edit fa-2x"></i></a>
                      </span>
                    </div>
            {{-- LEvel 2  --}}
                        @if (isset($menus[$level1->id]) && count($menus[$level1->id]))
                          <ol class="dd-list dd-collapsed">
                            @foreach ($menus[$level1->id] as $level2)
                              @if($level2->uri)
                                <li class="dd-item" data-id="{{ $level2->id }}">
                                    <div class="dd-handle {{ ($level2->id == $id)? 'active-item' : '' }}">
                                      <i class="fa {{ $level2->icon }}"></i> {!! sc_language_render($level2->title) !!}
                                      <span class="pull-right dd-nodrag">
                                          <a href="{{ route('admin_menu.edit',['id'=>$level2->id]) }}"><i class="fa fa-edit fa-edit fa-2x"></i></a>
                                          &nbsp; 
                                          <a data-id="{{ $level2->id }}" class="remove_menu"><i class="fa fa-trash fa-edit fa-2x"></i></a>
                                      </span>
                                    </div>
                                </li>
                              @else
                              <li class="dd-item" data-id="{{ $level2->id }}">
                                <div class="dd-handle {{ ($level2->id == $id)? 'active-item' : '' }}">
                                  <i class="fa {{ $level2->icon }}"></i> {!! sc_language_render($level2->title) !!}
                                  <span class="pull-right dd-nodrag">
                                      <a href="{{ route('admin_menu.edit',['id'=>$level2->id]) }}"><i class="fa fa-edit fa-edit fa-2x"></i></a>
                                      &nbsp; 
                                      <a data-id="{{ $level2->id }}" class="remove_menu"><i class="fa fa-trash fa-edit fa-2x"></i></a>
                                  </span>
                                </li>
                              @endif
                            @endforeach
                          </ol>
                        @endif
                        {{--  end level 2 --}}
                    </li>
                  @endif
                 @endforeach
              </ol>
            @endif
              {{-- end level 1 --}}
            </li>
          @endif

        @endforeach
      {{-- end level 0 --}}

    </ol>
</div>

      </div>
    </div>
  </div>

  <div class="col-md-6">

    <div class="box box-primary">   
              <div class="box-header with-border">
                <h3 class="box-title">{!! $title_form !!}</h3>
                @if ($layout == 'edit')
                <div class="box-tools">
                    <div class="btn-group pull-right" style="margin-right: 5px">
                        <a href="{{ route('admin_menu.index') }}" class="btn  btn-flat btn-default" title="List"><i class="fa fa-list"></i><span class="hidden-xs"> {{trans('admin.back_list')}}</span></a>
                    </div>
                </div>
                @endif
              </div>
   
                <form action="{{ $url_action }}" method="post" accept-charset="UTF-8" class="form-horizontal" id="form-main"  enctype="multipart/form-data">

                    <div class="box-body">
                        <div class="fields-group">

                            <div class="form-group   {{ $errors->has('parent_id') ? ' has-error' : '' }}">
                                <label for="name" class="col-sm-2 col-form-label">{{ trans('menu.admin.parent') }}</label>
                                <div class="col-sm-8">
                                    <select class="form-control parent select2" style="width: 100%;" name="parent_id" >
                                        <option value=""></option>
                                        <option value="0" {{ (old('parent',$menu['parent']??'') ==0) ? 'selected':'' }}>== ROOT ==</option>
                                        @foreach ($treeMenu as $k => $v)
                                            <option value="{{ $k }}" {{ (old('parent',$menu['parent_id']??'') ==$k) ? 'selected':'' }}>{!! $v !!}</option>
                                        @endforeach
                                    </select>
                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                {{ $errors->first('parent_id') }}
                                            </span>
                                        @endif
                                </div>
                            </div>

                            <div class="form-group   {{ $errors->has('title') ? ' has-error' : '' }}">
                                <label for="title" class="col-sm-2 col-form-label">{{ trans('menu.admin.title') }}</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text"   id="title" name="title" value="{{ old('title',$menu['title']??'') }}" class="form-control title" placeholder="" />
                                    </div>
                                        @if ($errors->has('title'))
                                            <span class="help-block">
                                                {{ $errors->first('title') }}
                                            </span>
                                        @endif
                                </div>
                            </div>

                          <div class="form-group">
                              <label for="icon" class="col-sm-2 col-form-label">{{ trans('menu.admin.icon') }}</label>
                              <div class="col-sm-8">
                                  <div class="input-group">
                                      <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                      <input required="1" style="width: 140px" type="text" id="icon" name="icon" value="{{ old('icon',$menu['icon']??'fa-bars') }}" class="form-control icon" placeholder="Input Icon" />
                                  </div>
                              </div>
                          </div>

                            <div class="form-group   {{ $errors->has('uri') ? ' has-error' : '' }}">
                                <label for="uri" class="col-sm-2 col-form-label">{{ trans('menu.admin.uri') }}</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="text"   id="uri" name="uri" value="{{ old('uri',$menu['uri']??'') }}" class="form-control uri" placeholder="" />
                                    </div>
                                        @if ($errors->has('uri'))
                                            <span class="help-block">
                                                {{ $errors->first('uri') }}
                                            </span>
                                        @endif
                                </div>
                            </div>

                            <div class="form-group    {{ $errors->has('sort') ? ' has-error' : '' }}">
                                <label for="sort" class="col-sm-2 col-form-label">{{ trans('menu.admin.sort') }}</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-pencil fa-fw"></i></span>
                                        <input type="number" style="width: 100px;"  id="sort" name="sort" value="{!! old('sort',$menu['sort']??0)??0 !!}" class="form-control input-sm sort" placeholder="" />
                                    </div>
                                        @if ($errors->has('sort'))
                                            <span class="help-block">
                                                <i class="fa fa-info-circle"></i> {{ $errors->first('sort') }}
                                            </span>
                                        @endif
                                </div>
                            </div>
                          </div>
                        </div>

                    <!-- /.box-body -->

                    <div class="box-footer">
                            @csrf
                        <div class="col-md-2">
                        </div>

                        <div class="col-md-8">
                            <div class="btn-group pull-right">
                                <button type="submit" class="btn btn-primary">{{ trans('admin.submit') }}</button>
                            </div>

                            <div class="btn-group pull-left">
                                <button type="reset" class="btn btn-warning">{{ trans('admin.reset') }}</button>
                            </div>
                        </div>
                    </div>

                    <!-- /.box-footer -->

                      </form>

    </div>
  </div>
</div>

@endsection


@push('styles')
<!-- Ediable -->
<link rel="stylesheet" href="{{ asset('admin/plugin/nestable/jquery.nestable.min.css')}}">
<link rel="stylesheet" href="{{ asset('admin/plugin/iconpicker/fontawesome-iconpicker.min.css')}}">
@endpush

@push('scripts')
<!-- Ediable -->

<script src="{{ asset('admin/plugin/nestable/jquery.nestable.min.js')}}"></script>
<script src="{{ asset('admin/plugin/iconpicker/fontawesome-iconpicker.min.js')}}"></script>


<script type="text/javascript">
$('.remove_menu').click(function(event) {
  var id = $(this).data('id');
  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-success',
      cancelButton: 'btn btn-danger'
    },
    buttonsStyling: true,
  })

  swalWithBootstrapButtons.fire({
    title: '{{ trans('admin.confirm_delete') }}',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonText: '{{ trans('admin.confirm_delete_yes') }}',
    confirmButtonColor: "#DD6B55",
    cancelButtonText: '{{ trans('admin.confirm_delete_no') }}',
    reverseButtons: true,

    preConfirm: function() {
        return new Promise(function(resolve) {
            $.ajax({
                method: 'post',
                url: '{{ $urlDeleteItem ?? '' }}',
                data: {
                  id:id,
                    _token: '{{ csrf_token() }}',
                },
                success: function (data) {
                    if(data.error == 1){
                      alertMsg('error', 'Cancelled', data.msg);
                      return;
                    }else{
                      alertMsg('success', 'Success');
                      window.location.replace('{{ route('admin_menu.index') }}');
                    }

                }
            });
        });
    }

  }).then((result) => {
    if (result.value) {
      alertMsg('success', '{{ trans('admin.confirm_delete_deleted_msg') }}', '{{ trans('admin.confirm_delete_deleted') }}');
    } else if (
      // Read more about handling dismissals
      result.dismiss === Swal.DismissReason.cancel
    ) {
      // swalWithBootstrapButtons.fire(
      //   'Cancelled',
      //   'Your imaginary file is safe :)',
      //   'error'
      // )
    }
  })

});


$('#menu-sort').nestable();
$('.menu-sort-save').click(function () {
    $('#loading').show();
    var serialize = $('#menu-sort').nestable('serialize');
    var menu = JSON.stringify(serialize);
    $.ajax({
      url: '{{ route('admin_menu.update_sort') }}',
      type: 'POST',
      dataType: 'json',
      data: {
        _token: '{{ csrf_token() }}',
        menu: menu
      },
    })
    .done(function(data) {
      $('#loading').hide();
      if(data.error == 0){
        location.reload();
      }else{
        alertMsg('error', data.msg, 'Cancelled');
      }
      //console.log(data);
    });
});


$(document).ready(function() {
    $('.active-item').parents('li').removeClass('dd-collapsed');

    $('.select2').select2();
      //icon picker
    $('.icon').iconpicker({placement:'bottomLeft'});
});

</script>
@endpush

@section('version-jquery')
  <script src="{{ asset('admin/AdminLTE/bower_components/jquery/dist/jQuery-2.1.4.min.js')}}"></script>
@endsection
