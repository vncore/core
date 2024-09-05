@extends($vncore_templatePathAdmin.'layout')

@section('main')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa fa-plus"></i>{!! $title!!}</h3>
            </div>

                <div class="box-body">
                    <div class="fields-group">
                        <form action="{{ vncore_route_admin('admin_language_manager.add') }}" method="POST">
                            @csrf
                            <table class="table table-hover box-body text-wrap table-bordered">
                              <tr>
                                  <th>{{ vncore_language_render('admin.language_manager.select_position') }}</th>
                                  <th>{{ vncore_language_render('admin.language_manager.code') }}</th>
                                  <th>{{ vncore_language_render('admin.language_manager.text') }}</th>
                              </tr>
                              <tr class="form-add-new">
                                <td class="{{ ($errors->has('position') || $errors->has('position_new')) ? 'text-red':'' }}">
                                      <select class="form-control form-control-sm select-position select2" name="position">
                                        <option value="">{{ vncore_language_render('admin.language_manager.select_position') }}</option>
                                        @foreach ($positionLang as $itemPosition)
                                            <option value="{{ $itemPosition }}"  {{ (old('position') == $itemPosition) ? 'selected':'' }} >{{ $itemPosition }}</option>
                                        @endforeach
                                      </select>
                                        {{ vncore_language_render('admin.language_manager.new_position') }}:
                                      @if ($errors->has('position'))
                                      <span class="form-text">
                                          <i class="fa fa-info-circle"></i>
                                          {{ $errors->first('position') }}
                                      </span>
                                      @endif

                                      <input name="position_new" value="{{ old('position_new') }}" class="form-control form-control-sm" placeholder="{{ vncore_language_render('admin.language_manager.position') }}">
                                      @if ($errors->has('position_new'))
                                      <span class="form-text">
                                          <i class="fa fa-info-circle"></i>
                                          {{ $errors->first('position_new') }}
                                      </span>
                                      @endif
                                </td>
                                <td class="{{ $errors->has('code') ? 'text-red':'' }}">
                                    <input name="code" value="{{ old('code') }}" placeholder="New code" class="form-control form-control-sm">
                                    @if ($errors->has('code'))
                                    <span class="form-text">
                                        <i class="fa fa-info-circle"></i>
                                        {{ $errors->first('code') }}
                                    </span>
                                    @endif
                                </td>
                                <td class="{{ $errors->has('text') ? 'text-red':'' }}">
                                    <textarea name="text" placeholder="Value" class="form-control form-control-sm">{{ old('text') }}</textarea>
                                    @if ($errors->has('text'))
                                    <span class="form-text">
                                        <i class="fa fa-info-circle"></i>
                                        {{ $errors->first('text') }}
                                    </span>
                                    @endif
                                    <span class="form-text">
                                        <i class="fa fa-info-circle"></i>
                                        {!! vncore_language_render('admin.language_manager.text_help',['link' => vncore_route_admin('admin_language_manager.index')]) !!}
                                    </span>
                                </td>
                              </tr>
                              <tr id="save-language">
                                <td colspan="4">
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-save"></i></button>
                                    </div>
                                </td>
                            </tr>
                            </table>
                            </form>
                        
                    </div>
                </div>

                <!-- /.box-footer -->
        </div>
    </div>
</div>


@endsection

@push('styles')
@endpush

@push('scripts')
@endpush