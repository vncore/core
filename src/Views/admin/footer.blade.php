<footer class="main-footer">
  @if (!vncore_config('hidden_copyright_footer_admin'))
    <div class="float-right d-none d-sm-inline-block">
      <strong>Env</strong>
      {{ config('app.env') }}
      &nbsp;&nbsp;
      <strong>Version</strong> 
      {{ config('vncore.sub-version') }} ({{ config('vncore.core-sub-version') }})
    </div>
    <strong>
      Copyright &copy; {{ date('Y') }} 
      <a href="{{ config('vncore.github') }}">Vncore: {{ config('vncore.name') }}</a>.
    </strong> 
  @endif
</footer>
