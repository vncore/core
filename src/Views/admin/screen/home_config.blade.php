@extends('vncore-admin::layout')

@section('main')
@php
    $id = empty($id) ? 0 : $id;
@endphp
<div class="row">

  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{!! $title_action !!}</h3>
        @if ($layout == 'edit')
        <div class="btn-group float-right" style="margin-right: 5px">
            <a href="{{ vncore_route_admin('admin_home_config.index') }}" class="btn btn-sm  btn-flat btn-default" title="List"><i class="fa fa-list"></i><span class="hidden-xs"> {{ vncore_language_render('admin.back_list') }}</span></a>
        </div>
      @endif
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ $url_action }}" method="post" accept-charset="UTF-8" class="form-horizontal" id="form-main">
        <div class="card-body">

          <div class="form-group {{ $errors->has('view') ? ' text-red' : '' }}">
            <label for="view"
                class="col-form-label">{!! vncore_language_render('admin.admin_home_config.view') !!}
            </label>
            <select class="form-control form-control-sm select2" name="view" data-live-search="true"  title="Please select item..."  data-actions-box="true">
                @foreach ($listView as $pathView)
                <option value="{{ $pathView }}"
                {{ ($pathView == old('view', $block['view'] ?? '')) ? 'selected':'' }}>{{ $pathView }}
                </option>
                @endforeach
            </select>
            @if ($errors->has('view'))
            <span class="text-sm">
              <i class="fa fa-info-circle"></i> {{ $errors->first('view') }}
            </span>
            @endif
          </div>


          <div class="form-group {{ $errors->has('size') ? ' text-red' : '' }}">
            <label for="size"
                class="col-form-label">{!! vncore_language_render('admin.admin_home_config.size') !!}
            </label>
            <select class="form-control form-control-sm select2" name="size" data-live-search="true"  title="Please select item..."  data-actions-box="true">
                @foreach (range(1, 12) as $sizeValue)
                <option value="{{ $sizeValue }}"
                {{ ($sizeValue == old('size', $block['size'] ?? '12')) ? 'selected':'' }}>{{ $sizeValue }}
                </option>
                @endforeach
            </select>
            @if ($errors->has('size'))
            <span class="text-sm">
              <i class="fa fa-info-circle"></i> {{ $errors->first('size') }}
            </span>
            @endif
          </div>


          <div class="form-group {{ $errors->has('sort') ? ' text-red' : '' }}">
            <label for="sort" class="col-form-label">{!! vncore_language_render('admin.admin_home_config.sort') !!}</label>
              <div class="input-group mb-3">
                <input type="number" id="sort" name="sort"
                    value="{{ old()?old('sort'):$block['sort']??'0' }}"
                    class="form-control form-control-sm {{ $errors->has('sort') ? ' is-invalid' : '' }}" placeholder=""/>
              </div>

              @if ($errors->has('sort'))
              <span class="text-sm">
                <i class="fa fa-info-circle"></i> {{ $errors->first('sort') }}
              </span>
              @endif
          </div>

          <div class="form-group row {{ $errors->has('status') ? ' text-red' : '' }}">
            <label for="status" class="col-sm-2 col-form-label">{!! vncore_language_render('admin.admin_home_config.status') !!}</label>
            <div class="col-sm-10 ">
              <div class="input-group mb-3">
                <input class="checkbox" type="checkbox" id="status" name="status"
                    class="form-control form-control-sm input {{ $errors->has('status') ? ' is-invalid' : '' }}" placeholder="" {!!
                      old('status',(empty($block['status'])?0:1))?'checked':''!!}/>
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


  <div class="col-md-8">

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
                      window.location.replace('{{ vncore_route_admin('admin_home_config.index') }}');
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
