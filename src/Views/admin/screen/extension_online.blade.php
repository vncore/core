@extends($vncore_templatePathAdmin.'layout')

@section('main')

<div class="row">
  <div class="col-md-12">

    <div class="card card-primary card-outline card-outline-tabs">
      <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link" href="{{ $urlAction['local'] }}" >{{ vncore_language_render('admin.extension.local') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="#" >{{ vncore_language_render('admin.extension.online') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" target=_new  href="{{ $urlAction['urlImport'] }}" ><span><i class="fas fa-save"></i> {{ vncore_language_render('admin.extension.import') }}</span></a>
          </li>
        </ul>
      </div>

      <div class="card-header">
        <div class="float-right" >
          <div class="form-group">
                <div class="input-group input-group-sm">
                  <select class="form-control form-control-sm select2" name="filter_free" data-live-search="true"  title="Select item..."  data-actions-box="true">
                      <option value="">All items</option>
                    <option value="1" {{ ($filter_free == 1) ? 'selected':''  }}>{{ vncore_language_render('admin.extension.only_free') }}</option>
                  </select>
                  <select class="form-control form-control-sm select2" name="filter_type" data-live-search="true"  title="Choose filter"  data-actions-box="true">
                    <option value=""> </option>
                    <option value="download" {{ ($filter_type == 'download') ? 'selected':''  }}>{{ vncore_language_render('admin.extension.sort_download') }}</option>
                    <option value="rating" {{ ($filter_type == 'rating') ? 'selected':''  }}>{{ vncore_language_render('admin.extension.sort_rating') }}</option>
                    <option value="sort_price_asc" {{ ($filter_type == 'sort_price_asc') ? 'selected':''  }}>{{ vncore_language_render('admin.extension.sort_price_asc') }}</option>
                    <option value="sort_price_desc" {{ ($filter_type == 'sort_price_desc') ? 'selected':''  }}>{{ vncore_language_render('admin.extension.sort_price_desc') }}</option>
                  </select>
                  <input type="text" name="filter_keyword" class="form-control float-right" placeholder="{{ vncore_language_render('admin.extension.enter_search_keyword') }}" value="{{ $filter_keyword ?? '' }}">
                  <div class="input-group-sm input-group-append">
                      <button id="filter-button" class="btn btn-primary btn-flat"><i class="fas fa-filter"></i></button>
                  </div>
                </div>
          </div>
      </div>
      </div>

      <div class="card-body" id="pjax-container">
        <div class="tab-content" id="custom-tabs-four-tabContent">
          <div class="table-responsive">
          <table class="table table-hover text-nowrap table-bordered">
            <thead class="thead-light text-nowrap">
              <tr>
                <th>{{ vncore_language_render('admin.extension.image') }}</th>
                <th>{{ vncore_language_render('admin.extension.code') }}</th>
                <th>{{ vncore_language_render('admin.extension.name') }}</th>
                <th>{{ vncore_language_render('admin.extension.version') }}</th>
                <th>{{ vncore_language_render('admin.extension.compatible') }}</th>
                <th>{{ vncore_language_render('admin.extension.auth') }}</th>
                <th>{{ vncore_language_render('admin.extension.image_demo') }}</th>
                <th>{{ vncore_language_render('admin.extension.price') }}</th>
                <th>{{ vncore_language_render('admin.extension.rated') }}</th>
                <th><i class="fa fa-download" aria-hidden="true"></i></th>
                <th>{{ vncore_language_render('admin.extension.date') }}</th>
                <th>{{ vncore_language_render('admin.extension.action') }}</th>
              </tr>
            </thead>
            <tbody>
              @if (!$arrExtensions)
                <tr>
                  <td colspan="12" style="text-align: center;color: red;">
                    {{ vncore_language_render('admin.extension.empty') }}
                  </td>
                </tr>
              @else
                @foreach ($arrExtensions as  $template)
  @php
  $scVersion = explode(',', $template['vncore_version']);
  $scRenderVersion = implode(' ',array_map(
  function($version){
  return '<span title="Vncore version '.$version.'" class="badge badge-primary">'.$version.'</span>';
  },$scVersion)
  );
  
  if (array_key_exists($template['key'], $arrExtensionsLocal)) 
  {
  $templateAction = '<span title="'.vncore_language_render('admin.extension.located').'" class="btn btn-flat btn-default"><i class="fa fa-check" aria-hidden="true"></i></span>';
  } elseif(!in_array(config('vncore.core'), $scVersion)) {
  $templateAction = '';
  } else {
  if(($template['is_free'] || $template['price_final'] == 0)) {
  $templateAction = '<span onClick="installTemplate($(this),\''.$template['key'].'\', \''.$template['file'].'\');" title="'.vncore_language_render('admin.extension.install').'" type="button" class="btn btn-flat btn-success"><i class="fa fa-plus-circle"></i></span>';
  } else {
  $templateAction = '';
  }
  }
  @endphp
                  <tr>
                    <td>{!! vncore_image_render($template['image'],'50px', '', $template['name']) !!}</td>
                    <td>{{ $template['key'] }}</td>
                    <td>{{ $template['name'] }} <span data-toggle="tooltip" title="{!! $template['description'] !!}"><i class="fa fa-info-circle" aria-hidden="true"></i></span></td>
                    <td>{{ $template['version']??'' }}</td>
                    <td><b>SC:</b> {!! $scRenderVersion !!}</td>
                    <td>{{ $template['username']??'' }}</td>
                    <td class="pointer" onclick="imagedemo('{{ $template['image_demo']??'' }}')"><a>{{ vncore_language_render('admin.extension.click_here') }}</a></td>
                    <td>
                      @if ($template['is_free'] || $template['price_final'] == 0)
                        <span class="badge badge-success">{{ vncore_language_render('admin.extension.free') }}</span>
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
                        <span class="btn btn-flat btn-primary" type="button">
                        <i class="fa fa-chain-broken" aria-hidden="true"></i> {!! vncore_language_render('admin.extension.link') !!}
                        </span>
                      </a>
                    </td>                        
                  </tr>
                @endforeach
              @endif
            </tbody>
          </table>
          </div>
        </div>

        <div class="block-pagination clearfix m-10">
          <div class="ml-3 float-left">
            {!! $resultItems??'' !!}
          </div>
          {!! $htmlPaging !!}
        </div>

      </div>
    </div>
</div>
</div>
@endsection

@push('scripts')



<script type="text/javascript">
  function installTemplate(obj,key, path) {
      $('#loading').show()
      obj.button('loading');
      $.ajax({
        type: 'POST',
        dataType:'json',
        url: '{{ $urlAction['install'] }}',
        data: {
          "_token": "{{ csrf_token() }}",
          "key":key,
          "path":path,
        },
        success: function (data) {
          console.log(data);
              if(parseInt(data.error) ==0){
                alertJs('success', data.msg);
              location.reload();
              }else{
                alertMsg('error', data.msg, 'You clicked the button!');
              }
              $('#loading').hide();
              obj.button('reset');
        }
      });
  }
    function imagedemo(image) {
      Swal.fire({
        title: '{{  vncore_language_render('admin.extension.image_demo') }}',
        text: '',
        imageUrl: image,
        imageWidth: 800,
        imageHeight: 800,
        imageAlt: 'Image demo',
      })
    }

</script>

<script>
  $('#filter-button').click(function(){
    var urlNext = '{{ url()->current() }}';
    var filter_free = $('[name="filter_free"] option:selected').val();
    var filter_type = $('[name="filter_type"] option:selected').val();
    var filter_keyword = $('[name="filter_keyword"]').val();
    var urlString = "";
    if(filter_free) {
      urlString +="&filter_free=1";
    }
    if(filter_type) {
      urlString +="&filter_type="+filter_type;
    }
    if(filter_keyword){
      urlString +="&filter_keyword="+filter_keyword;
    }
      urlString = urlString.substr(1);
      urlNext = urlNext+"?"+urlString;
      $('.link-filter').attr('href', urlNext);
      $('.link-filter').trigger('click');
  });
</script>

@endpush
