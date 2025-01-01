@extends('vncore-admin::layout')

@section('main')
@php
    $id = empty($id) ? 0 : $id;
@endphp
<div class="row">

  <div class="col-md-6">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{!! $title_action !!}</h3>
        @if ($layout == 'edit')
        <div class="btn-group float-right" style="margin-right: 5px">
            <a href="{{ vncore_route_admin('admin_language.index') }}" class="btn btn-sm  btn-flat btn-default" title="List"><i class="fa fa-list"></i><span class="hidden-xs"> {{ vncore_language_render('admin.back_list') }}</span></a>
        </div>
      @endif
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ $url_action }}" method="post" accept-charset="UTF-8" class="form-horizontal" id="form-main">
        <div class="card-body">

          <div class="form-group row {{ $errors->has('name') ? ' text-red' : '' }}">
            <label for="name" class="col-sm-2 col-form-label">{{ vncore_language_render('admin.language.name') }}</label>
            <div class="col-sm-10 ">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                </div>
                <input type="text" id="name" name="name" value="{{ old()?old('name'):$language['name']??'' }}" class="form-control form-control-sm name {{ $errors->has('name') ? ' is-invalid' : '' }}">
              </div>

              @if ($errors->has('name'))
              <span class="text-sm">
                <i class="fa fa-info-circle"></i> {{ $errors->first('name') }}
              </span>
              @endif

            </div>
          </div>

          <div class="form-group row {{ $errors->has('code') ? ' text-red' : '' }}">
            <label for="code" class="col-sm-2 col-form-label">{!! vncore_language_render('admin.language.code') !!}</label>
            <div class="col-sm-10 ">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                </div>
                @if (!empty($language['code']) && in_array($language['code'], ['vi','en']))
                <input type="hidden" id="code" name="code" value="{{ $language['code'] }}"
                    placeholder="" />
                <input type="text" disabled="disabled" value="{{ $language['code'] }}"
                    class="form-control form-control-sm" placeholder="" />
                @else
                <input type="text" id="code" name="code"
                    value="{{ old()?old('code'):$language['code']??'' }}"
                    class="form-control form-control-sm {{ $errors->has('code') ? ' is-invalid' : '' }}" placeholder="" />
                @endif
              </div>

              @if ($errors->has('code'))
              <span class="text-sm">
                <i class="fa fa-info-circle"></i> {{ $errors->first('code') }}
              </span>
              @endif

            </div>
          </div>


          <div class="form-group row {{ $errors->has('icon') ? ' text-red' : '' }}">
            <label for="icon" class="col-sm-2 col-form-label">{!! vncore_language_render('admin.language.icon') !!}</label>
            <div class="col-sm-10 ">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                </div>
                <input type="text" id="icon" name="icon" value="{{ old()?old('icon'):$language['icon']??'' }}" class="form-control form-control-sm icon {{ $errors->has('icon') ? ' is-invalid' : '' }}">
                <div class="input-group-append">
                  <span data-input="icon" data-preview="preview_icon" data-type="language"
                      class="btn btn-sm btn-primary lfm"><i class="fa fa-icon"></i>  {{vncore_language_render('admin.choose_icon')}}</span>
                </div>
              </div>

              @if ($errors->has('icon'))
              <span class="text-sm">
                <i class="fa fa-info-circle"></i> {{ $errors->first('icon') }}
              </span>
              @endif
              <div id="preview_icon" class="img_holder"><img
                src="{{ vncore_file(old('icon',$language['icon']??'Vncore/Admin/images/no-image.jpg')) }}">
              </div>
            </div>
          </div>


          <div class="form-group row {{ $errors->has('rtl') ? ' text-red' : '' }}">
            <label for="rtl" class="col-sm-2 col-form-label">{!! vncore_language_render('admin.language.layout_rtl') !!}</label>
            <div class="col-sm-10 ">
              <div class="input-group mb-3">
                <input class="checkbox" type="checkbox" id="rtl" name="rtl"
                    class="form-control form-control-sm input {{ $errors->has('rtl') ? ' is-invalid' : '' }}" placeholder=""  {!!
                      old('rtl',(empty($language['rtl'])?0:1))?'checked':''!!}/>
              </div>

              @if ($errors->has('rtl'))
              <span class="text-sm">
                <i class="fa fa-info-circle"></i> {{ $errors->first('rtl') }}
              </span>
              @endif

            </div>
          </div>

          <div class="form-group row {{ $errors->has('sort') ? ' text-red' : '' }}">
            <label for="sort" class="col-sm-2 col-form-label">{!! vncore_language_render('admin.language.sort') !!}</label>
            <div class="col-sm-10 ">
              <div class="input-group mb-3">
                <input type="number" id="sort" name="sort"
                    value="{{ old()?old('sort'):$language['sort']??'' }}"
                    class="form-control form-control-sm {{ $errors->has('sort') ? ' is-invalid' : '' }}" placeholder=""/>
              </div>

              @if ($errors->has('sort'))
              <span class="text-sm">
                <i class="fa fa-info-circle"></i> {{ $errors->first('sort') }}
              </span>
              @endif

            </div>
          </div>


          <div class="form-group row {{ $errors->has('status') ? ' text-red' : '' }}">
            <label for="status" class="col-sm-2 col-form-label">{!! vncore_language_render('admin.language.status') !!}</label>
            <div class="col-sm-10 ">
              <div class="input-group mb-3">
                <input class="checkbox" type="checkbox" id="status" name="status"
                    class="form-control form-control-sm input {{ $errors->has('status') ? ' is-invalid' : '' }}" placeholder="" {!!
                      old('status',(empty($language['status'])?0:1))?'checked':''!!}/>
              </div>

              @if ($errors->has('status'))
              <span class="text-sm">
                <i class="fa fa-info-circle"></i> {{ $errors->first('status') }}
              </span>
              @endif

            </div>
          </div>


        </div>
        <!-- /.card-body -->
        @csrf
        <div class="card-footer">
          <button type="reset" class="btn btn-sm btn-warning">{{ vncore_language_render('action.reset') }}</button>
          <button type="submit" class="btn btn-sm btn-primary float-right">{{ vncore_language_render('action.submit') }}</button>
        </div>
        <!-- /.card-footer -->
      </form>
    </div>
  </div>


  <div class="col-md-6">

    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-list"></i> {!! $title ?? '' !!}</h3>
      </div>

      <div class="card-body p-0">
            <section id="pjax-container" class="table-list">
              <div class="box-body table-responsivep-0" >
                 <table class="table table-hover box-body text-wrap table-bordered">
                    <thead class="thead-light text-nowrap">
                       <tr>
                        @if (!empty($removeList))
                        <th></th>
                        @endif
                        @foreach ($listTh as $key => $th)
                            <th class="th-{{ $key }}">{!! $th !!}</th>
                        @endforeach
                       </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataTr as $keyRow => $tr)
                            <tr class="{{ (request('id') == $keyRow) ? 'active': '' }}">
                                @if (!empty($removeList))
                                <td>
                                  <input class="checkbox" type="checkbox" class="grid-row-checkbox" data-id="{{ $keyRow }}">
                                </td>
                                @endif
                                @foreach ($tr as $key => $trtd)
                                    <td>{!! $trtd !!}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                 </table>

                 <div class="block-pagination clearfix m-10">
                  <div class="ml-3 float-left">
                    {!! $resultItems??'' !!}
                  </div>
                  <div class="pagination pagination-sm mr-3 float-right">
                    {!! $pagination??'' !!}
                  </div>
                </div>


              </div>
             </section>
    </div>



    </div>
  </div>

</div>
</div>
@endsection


@push('styles')
@endpush

@push('scripts')
   


<script type="text/javascript">
{{-- sweetalert2 --}}
var selectedRows = function () {
    var selected = [];
    $('.grid-row-checkbox:checked').each(function(){
        selected.push($(this).data('id'));
    });

    return selected;
}

$('.grid-trash').on('click', function() {
  var ids = selectedRows().join();
  deleteItem(ids);
});

  function deleteItem(ids){
  Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-sm btn-success',
      cancelButton: 'btn btn-sm btn-danger'
    },
    buttonsStyling: true,
  }).fire({
    title: '{{ vncore_language_render('action.delete_confirm') }}',
    text: "",
    type: 'warning',
    showCancelButton: true,
    confirmButtonText: '{{ vncore_language_render('action.confirm_yes') }}',
    confirmButtonColor: "#DD6B55",
    cancelButtonText: '{{ vncore_language_render('action.confirm_no') }}',
    reverseButtons: true,

    preConfirm: function() {
        return new Promise(function(resolve) {
            $.ajax({
                method: 'post',
                url: '{{ $urlDeleteItem ?? '' }}',
                data: {
                  ids:ids,
                    _token: '{{ csrf_token() }}',
                },
                success: function (data) {
                    if(data.error == 1){
                      alertMsg('error', data.msg, '{{ vncore_language_render('action.warning') }}');
                      $.pjax.reload('#pjax-container');
                      return;
                    }else{
                      alertMsg('success', data.msg);
                      window.location.replace('{{ vncore_route_admin('admin_language.index') }}');
                    }

                }
            });
        });
    }

  }).then((result) => {
    if (result.value) {
      alertMsg('success', '{{ vncore_language_render('action.delete_confirm_deleted_msg') }}', '{{ vncore_language_render('action.delete_confirm_deleted') }}');
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
}
{{--/ sweetalert2 --}}


</script>
@endpush
