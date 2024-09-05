@extends($vncore_templatePath.'.layout')

@section('main')
    {{-- Content --}}
@endsection

@section('breadcrumb')
    <div class="breadcrumbs">
        <ol class="breadcrumb">
          <li><a href="{{ vncore_route('home') }}">{{ vncore_language_render('front.home') }}</a></li>
          <li class="active">{{ $title ?? '' }}</li>
        </ol>
      </div>
@endsection

@push('styles')
      {{-- style css --}}
@endpush

@push('scripts')
      {{-- script --}}
@endpush