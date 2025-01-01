@extends('vncore-admin::layout')

@section('main')
<div class="container-fluid">
  @if (!empty($blockDashboard))
  <div class="row">
    @foreach ($blockDashboard as $block)
        @if (isset($block['view']) && view()->exists($block['view']))
        <div class="col-md-{{ ($block['size'] ?? 12) }}">
          @includeIf($block['view'])
        </div>
        @else
            {{-- Nothing --}}
        @endif
    @endforeach
  </div>
  @else
    {{-- Nothing --}}
  @endif
</div>

@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
