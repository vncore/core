{{-- Use vncore_config with storeId, dont use vncore_config_admin because will switch the store to the specified store Id
--}}
<div class="card">
  <div class="card-body table-responsive">
   <table class="table table-hover box-body text-wrap table-bordered">
     <tbody>
       <tr>
         <th>{{ vncore_language_quickly('admin.admin_custom_config.add_new_detail', 'Key detail') }}</th>
         <th>{{ vncore_language_quickly('admin.admin_custom_config.add_new_key', 'Key') }}</th>
         <th>{{ vncore_language_quickly('admin.admin_custom_config.add_new_value', 'Value') }}</th>
         <th></th>
       </tr>
      @foreach ($configCustomize as $config)
      <tr>
        <td>{{ vncore_language_render($config->detail) }}</td>
        <td>{{ $config->key }}</td>
        <td><a href="#" class="editable editable-click" data-name="{{ $config->key }}" data-type="text" data-pk="{{ $config->key }}" data-source="" data-url="{{ $urlUpdateConfig }}" data-title="{{ vncore_language_render($config->detail) }}" data-value="{{ vncore_config($config->key, $storeId) }}" data-original-title="" title=""></a></td>
        <td><span onclick="deleteKey('{{ $config->key }}');" title="Delete" class="btn btn-sm btn-flat btn-sm btn-danger">
          <i class="fas fa-trash-alt"></i>
          </span>
        </td>
      </tr>
    @endforeach
    <tr>
      <td colspan="4">
        <hr>
      </td>
    </tr>
    <form method="POST" action="{{ vncore_route_admin('admin_config.add_new') }}">
      @csrf
      <input type="hidden" name="storeId" value="{{ $storeId }}">
    <tr>
      <td>
        <div class="input-group">
          <input name="detail" placeholder="{{ vncore_language_quickly('admin.admin_custom_config.add_new_detail', 'Key detail') }}" required class="form-control form-control-sm input-sm">
        </div>
      </td>
      <td>
        <div class="input-group">
          <input name="key" placeholder="{{ vncore_language_quickly('admin.admin_custom_config.add_new_key', 'Key') }}" required class="form-control form-control-sm input-sm">
        </div>
      </td>
      <td>
        <div class="input-group">
          <input name="value" placeholder="{{ vncore_language_quickly('admin.admin_custom_config.add_new_value', 'Value') }}" required class="form-control form-control-sm input-sm">
        </div>
      </td>
      <td>
        <div class="btn-group">
          <input type="submit" class="btn btn-sm btn-primary" value="{{ vncore_language_quickly('admin.admin_custom_config.add_new', 'Add new config') }}">
        </div>
      </td>
    </tr>
    </form>
     </tbody>
   </table>
  </div>
</div>

@push('scripts')
<script>

function deleteKey(key){
  Swal.mixin({
    customClass: {
      confirmButton: 'btn btn-success',
      cancelButton: 'btn btn-danger'
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
                url: '{{ vncore_route_admin('admin_config.delete') }}',
                data: {
                  key:key,
                  storeId:{{ $storeId }},
                    _token: '{{ csrf_token() }}',
                },
                success: function (data) {
                    if(data.error == 1){
                      alertMsg('error', data.msg, '{{ vncore_language_render('action.warning') }}');
                      $.pjax.reload('#pjax-container');
                      return;
                    }else{
                      alertMsg('success', data.msg);
                      window.location.replace('{{ vncore_route_admin('admin_config.index') }}');
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

</script>
@endpush