@extends($vncore_templatePathAdmin.'layout')

@section('main')
   <div class="row">
      <div class="col-md-12">
         <div class="card">
                <div class="card-header with-border">
                    <h2 class="card-title">{{ $title_description??'' }}</h2>

                    <div class="card-tools">
                        <div class="btn-group float-right mr-5">
                            <a href="{{ vncore_route_admin('admin_email_template.index') }}" class="btn btn-sm  btn-flat btn-default" title="List"><i class="fa fa-list"></i><span class="hidden-xs"> {{ vncore_language_render('admin.back_list') }}</span></a>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ $url_action }}" method="post" accept-charset="UTF-8" class="form-horizontal" id="form-main"  enctype="multipart/form-data">


                    <div class="card-body">
                            <div class="form-group row  {{ $errors->has('name') ? ' text-red' : '' }}">
                                <label for="name" class="col-sm-2 col-form-label">{{ vncore_language_render('admin.email_template.name') }}</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                        </div>
                                        <input type="name" id="name" name="name" value="{{ old()?old('name'):$obj['name']??'' }}" class="form-control form-control-sm" placeholder="" />
                                    </div>
                                        @if ($errors->has('name'))
                                            <span class="form-text">
                                                <i class="fa fa-info-circle"></i> {{ $errors->first('name') }}
                                            </span>
                                        @endif
                                </div>
                            </div>

                            <div class="form-group row  {{ $errors->has('group') ? ' text-red' : '' }}">
                                <label for="group" class="col-sm-2 col-form-label">{{ vncore_language_render('admin.email_template.group') }}</label>
                                <div class="col-sm-8">
                                    <select class="form-control form-control-sm group select2" style="width: 100%;" name="group" >
                                        <option value=""></option>
                                        @foreach ($arrayGroup as $k => $v)
                                            <option value="{{ $k }}" {{ (old('group',$obj['group']??'') ==$k) ? 'selected':'' }}>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                        @if ($errors->has('group'))
                                            <span class="form-text">
                                                <i class="fa fa-info-circle"></i> {{ $errors->first('group') }}
                                            </span>
                                        @endif
                                </div>
                            </div>

                            <div class="form-group row {{ $errors->has('text') ? ' text-red' : '' }}">
                                <label for="text" class="col-sm-2 col-form-label">{{ vncore_language_render('admin.email_template.text') }}</label>
                                <div class="col-sm-8">
                                        <textarea class="form-control form-control-sm" rows="10" id="text" name="text">{!! old('text',$obj['text']??'') !!}</textarea>
                                        @if ($errors->has('text'))
                                            <span class="form-text">
                                                <i class="fa fa-info-circle"></i> {{ $errors->first('text') }}
                                            </span>
                                        @endif
                                </div>
                            </div>

                            <div class="form-group row ">
                                <label for="status" class="col-sm-2 col-form-label">{{ vncore_language_render('admin.email_template.status') }}</label>
                                <div class="col-sm-8">
                                    <input class="checkbox" type="checkbox" name="status"  {{ old('status',(empty($obj['status'])?0:1))?'checked':''}}>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-2"></div>
                                <div class="col-sm-8">
                                    <label>{{ vncore_language_render('admin.email_template.variable_support') }}</label>
                                    <div id="list-variables">
                                    </div>                                   
                                </div>
                            </div>
                    </div>

                    <!-- /.card-body -->

                    <div class="card-footer row">
                            @csrf
                        <div class="col-md-2">
                        </div>

                        <div class="col-md-8">
                            <div class="btn-group float-right">
                                <button type="submit" class="btn btn-sm btn-primary">{{ vncore_language_render('action.submit') }}</button>
                            </div>

                            <div class="btn-group float-left">
                                <button type="reset" class="btn btn-sm btn-warning">{{ vncore_language_render('action.reset') }}</button>
                            </div>
                        </div>
                    </div>

                    <!-- /.card-footer -->
                </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ vncore_file('Vncore/Admin/plugin/mirror/doc/docs.css')}}">
<link rel="stylesheet" href="{{ vncore_file('Vncore/Admin/plugin/mirror/lib/codemirror.css')}}">
@endpush

@push('scripts')
<script src="{{ vncore_file('Vncore/Admin/plugin/mirror/lib/codemirror.js')}}"></script>
<script src="{{ vncore_file('Vncore/Admin/plugin/mirror/mode/javascript/javascript.js')}}"></script>
<script src="{{ vncore_file('Vncore/Admin/plugin/mirror/mode/css/css.js')}}"></script>
<script src="{{ vncore_file('Vncore/Admin/plugin/mirror/mode/htmlmixed/htmlmixed.js')}}"></script>
<script>
    window.onload = function() {
      editor = CodeMirror(document.getElementById("text"), {
        mode: "text/html",
        value: document.documentElement.innerHTML
      });
    };
    var myModeSpec = {
    name: "htmlmixed",
    tags: {
        style: [["type", /^text\/(x-)?scss$/, "text/x-scss"],
                [null, null, "css"]],
        custom: [[null, null, "customMode"]]
    }
    }
    var editor = CodeMirror.fromTextArea(document.getElementById("text"), {
      lineNumbers: true,
      styleActiveLine: true,
      matchBrackets: true
    });
  </script>


<script type="text/javascript">
    $(document).ready(function(){
        var group = $("[name='group'] option:selected").val();
        loadListVariable(group);
    });
    $("[name='group']").change(function(){
        var group = $("[name='group'] option:selected").val();
        loadListVariable(group);        
    });
    function loadListVariable(group){
    $.ajax({
        type: "get",
        data:{key:group},
        url: "{{route('admin_email_template.list_variable')}}",
        dataType: "json",
        beforeSend: function(){
                $('#loading').show();
            },        
        success: function (data) {
            html = '<ul>';
            $.each(data, function(i, item) {
                html +='<li>'+item+'</li>';
            });   
            html += '</ul>';         
            $('#list-variables').html(html);
            $('#loading').hide();
        }
    })

    }
</script>
@endpush
