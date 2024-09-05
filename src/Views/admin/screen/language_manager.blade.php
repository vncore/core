@extends($vncore_templatePathAdmin.'layout')

@section('main')

   <div class="row">
      <div class="col-md-12">
        <div class="card">
    
          <div class="box-body table-responsive">
            <section id="pjax-container" class="table-list">
                <div class="card-body table-responsivep-0" >
                  <form action="{{ vncore_route_admin('admin_language_manager.index') }}" method="GET">
                  <table class="table table-hover box-body text-wrap table-bordered">
                    <tr>
                      <td>
                        <div class="input-group float-left ml-1">
                          <div class="btn-group">
                            <select class="form-control form-control-sm select2" name="position">
                              <option value="">{{ vncore_language_render('admin.language.select_position') }}</option>
                              @foreach ($positionLang as $itemPosition)
                                  <option value="{{ $itemPosition }}" {{ ($itemPosition === $position)? 'selected': '' }}>{{ $itemPosition }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="btn-group">
                            <select class="form-control form-control-sm select2" name="lang">
                              <option value="">{{ vncore_language_render('admin.language.select_lang') }}</option>
                              @php
                                  $lang = $lang ?? 'en';
                              @endphp
                              @foreach ($languages as $key => $itemLang)
                                  <option value="{{ $key }}" {{ ($key == $lang)? 'selected': '' }}>{{ $itemLang->name }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="btn-group">
                            <input name="keyword" value="{{ $keyword }}" class="form-control form-control-sm" placeholder="Input code">
                          </div>
                          <div class="input-group-append">
                              <button id="button_sort" type="submit" class="btn btn-sm btn-primary"><i class="fa fa-filter"></i></button>
                          </div>
                          &nbsp; 
                          <div class="btn-group">
                            <a href="{{ vncore_route_admin('admin_language_manager.add') }}"><button type="button" class="btn btn-sm btn-success"><i class="fa fa-plus"></i></button></a>
                        </div>
                        </div>
                      </td>
                    </tr>
                  </table>
                  </form>

                  <table class="table table-hover box-body text-wrap table-bordered">
                      <thead class="thead-light">
                         <tr>
                           <th>{{ vncore_language_render('admin.language_manager.position') }}</th>
                           <th>{{ vncore_language_render('admin.language_manager.code') }}</th>
                           @if ($lang != 'en')
                           <th>English</th>
                           @endif
                           <th>{{ $languages[$lang]['name'] ?? '' }}</th>
                         </tr>
                      </thead>
                      <tbody>

                        @if (!empty($languagesPosition))
                        @foreach ($languagesPosition as $key => $itemRow)
                            <tr>
                              <td>{{ $itemRow['position'] }}</td>
                              <td>{{ $itemRow['code'] }}</td>
                              @if ($lang != 'en')
                              <td>{{ $languagesPositionEL[$key]['text'] ?? '' }}</td>
                              @endif
                              <td><a href="#" class="editable-required" data-name="{{ $itemRow['code'] }}" data-type="textarea" data-pk="{{ $itemRow['position'] }}" data-source="" data-url="{{ $urlUpdateData }}" data-title="{{ $itemRow['code'] }}" data-value="{{ $itemRow['text'] }}" data-original-title="" title=""></a></td>
                            </tr>
                        @endforeach
                        @endif

                        @if (!empty($arrayKeyDiff) && $languagesPosition !== -1)
                        @foreach ($arrayKeyDiff as $keyRow)
                            <tr>
                              <td>{{ $languagesPositionEL[$keyRow]['position'] }}</td>
                              <td>{{ $keyRow }}</td>
                              @if ($lang != 'en')
                              <td>{{ $languagesPositionEL[$keyRow]['text'] ?? '' }}</td>
                              @endif
                              <td><a href="#" class="editable-required" data-name="{{ $keyRow }}" data-type="textarea" data-pk="{{ $languagesPositionEL[$keyRow]['position'] }}" data-source="" data-url="{{ $urlUpdateData }}" data-title="{{ $keyRow }}" data-value="" data-original-title="" title=""></a></td>
                            </tr>
                        @endforeach
                        @endif
                      </tbody>
                   </table>
                </div>
              </section>
        </div>
      </div>
      </div>
    </div>


@endsection

@push('styles')

@endpush

@push('scripts')
<!-- Ediable -->
<script src="{{ vncore_file('Vncore/admin/plugin/bootstrap-editable.min.js')}}"></script>
<script type="text/javascript">
  // Editable
  $(document).ready(function() {

//  $.fn.editable.defaults.mode = 'inline';
$.fn.editable.defaults.params = function (params) {
  params._token = "{{ csrf_token() }}";
  params.lang = "{{ $lang }}";
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

});
</script>

@endpush
