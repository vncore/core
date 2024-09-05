@extends($vncore_templatePathAdmin.'layout')
@section('main')
      <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
          <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="tab-admin-email-tab" data-toggle="pill" href="#tab-admin-email" role="tab" aria-controls="tab-admin-email" aria-selected="false">{{ vncore_language_render('store.admin.config_admin_email') }}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="tab-admin-other-tab" data-toggle="pill" href="#tab-admin-other" role="tab" aria-controls="tab-admin-other" aria-selected="false">{{ vncore_language_render('store.admin.config_admin_other') }}</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="tab-admin-customize-tab" data-toggle="pill" href="#tab-admin-customize" role="tab" aria-controls="tab-admin-customize" aria-selected="false">{{ vncore_language_render('store.admin.config_customize') }}</a>
            </li>
          </ul>
        </div>
        
        <div class="card-body">
          <div class="tab-content" id="custom-tabs-four-tabContent">

            {{-- Tab admin email --}}
            <div class="tab-pane fade active show" id="tab-admin-email" role="tabpanel" aria-labelledby="tab-admin-email-tab">
              @include($vncore_templatePathAdmin.'screen.config_store.config_admin_email')
            </div>
            {{-- // admin email --}}

            {{-- Tab admin config --}}
            <div class="tab-pane fade" id="tab-admin-other" role="tabpanel" aria-labelledby="tab-admin-other-tab">
              @include($vncore_templatePathAdmin.'screen.config_store.config_admin_other')
            </div>
            {{-- // admin config --}}

            {{-- Tab admin config customize --}}
            <div class="tab-pane fade" id="tab-admin-customize" role="tabpanel" aria-labelledby="tab-admin-customize-tab">
              @include($vncore_templatePathAdmin.'screen.config_store.config_admin_customize')
            </div>
            {{-- // admin config customize --}}

          </div>
        </div>
        <!-- /.card -->
</div>

@endsection

@push('styles')
<!-- Ediable -->
<link rel="stylesheet" href="{{ vncore_file('Vncore/admin/plugin/bootstrap-editable.css')}}">
<style type="text/css">
  #maintain_content img{
    max-width: 100%;
  }
</style>
@endpush

@if (empty($dataNotFound))
@push('scripts')
<!-- Ediable -->
<script src="{{ vncore_file('Vncore/admin/plugin/bootstrap-editable.min.js')}}"></script>

<script type="text/javascript">

  // Editable
$(document).ready(function() {

      //  $.fn.editable.defaults.mode = 'inline';
      $.fn.editable.defaults.params = function (params) {
        params._token = "{{ csrf_token() }}";
        params.storeId = "{{ $storeId }}";
        return params;
      };

      $('.editable-required').editable({
        validate: function(value) {
            if (value == '') {
                return '{{  vncore_language_render('admin.not_empty') }}';
            }
        },
        success: function(data) {
          if(data.error == 0){
            alertJs('success', '{{ vncore_language_render('admin.msg_change_success') }}');
          } else {
            alertJs('error', data.msg);
          }
      }
    });

    $('.editable').editable({
        validate: function(value) {
        },
        success: function(data) {
          console.log(data);
          if(data.error == 0){
            alertJs('success', '{{ vncore_language_render('admin.msg_change_success') }}');
          } else {
            alertMsg('error', data.msg);
          }
      }
    });

});


$('input.check-data-config').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '20%' /* optional */
  }).on('ifChanged', function(e) {
  var isChecked = e.currentTarget.checked;
  isChecked = (isChecked == false)?0:1;
  var name = $(this).attr('name');
    $.ajax({
      url: '{{ $urlUpdateConfig }}',
      type: 'POST',
      dataType: 'JSON',
      data: {
          "_token": "{{ csrf_token() }}",
          "name": $(this).attr('name'),
          "storeId": $(this).data('store'),
          "value": isChecked
        },
    })
    .done(function(data) {
      if(data.error == 0){
        alertJs('success', '{{ vncore_language_render('admin.msg_change_success') }}');
      } else {
        alertJs('error', data.msg);
      }
    });

    });

  $('input.check-data-config-global').iCheck({
    checkboxClass: 'icheckbox_square-blue',
    radioClass: 'iradio_square-blue',
    increaseArea: '20%' /* optional */
  }).on('ifChanged', function(e) {
  var isChecked = e.currentTarget.checked;
  isChecked = (isChecked == false)?0:1;
  var name = $(this).attr('name');
    $.ajax({
      url: '{{ $urlUpdateConfigGlobal }}',
      type: 'POST',
      dataType: 'JSON',
      data: {
          "_token": "{{ csrf_token() }}",
          "name": $(this).attr('name'),
          "value": isChecked
        },
    })
    .done(function(data) {
      if(data.error == 0){
        if (isChecked == 0) {
          $('#smtp-config').hide();
        } else {
          $('#smtp-config').show();
        }
        alertJs('success', '{{ vncore_language_render('admin.msg_change_success') }}');
      } else {
        alertJs('error', data.msg);
      }
    });

    });


</script>




<script>
  // Update store_info

//End update store_info
</script>

@endpush
@endif