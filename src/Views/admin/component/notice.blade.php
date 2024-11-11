
@php
    $countNotice = \Vncore\Core\Admin\Models\AdminNotice::getCountNoticeNew();
    if ($countNotice) {
      $badgeStatus = 'badge-warning';
    } else {
      $badgeStatus = 'badge-secondary';
    }
    $topNotice = \Vncore\Core\Admin\Models\AdminNotice::getTopNotice();
@endphp
<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
      <i class="far fa-bell"></i>
      <span class="badge {{ $badgeStatus }} navbar-badge">{{ $countNotice }}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right notice">
  @if ($topNotice->count())
  <span class="dropdown-item dropdown-header text-right"><a href="{{ vncore_route_admin('admin_notice.mark_read') }}">{{ vncore_language_render('admin_notice.mark_read') }}</a></span>
    @foreach ($topNotice as $notice)
      <div class="dropdown-divider"></div>
      <a href="{{ vncore_route_admin('admin_notice.url',['type' => $notice->type,'typeId' => $notice->type_id]) }}" class="dropdown-item notice-{{ $notice->status ? 'read':'unread' }}">
        @if (in_array($notice->type, ['vncore_order_created', 'vncore_order_success', 'vncore_order_update_status']))
        <i class="fas fa-cart-plus"></i>
        @elseif(in_array($notice->type, ['vncore_customer_created']))
        <i class="fas fa-users"></i>
        @else
        <i class="far fa-bell"></i>
        @endif
        {{ vncore_content_render($notice->content) }}
      <span class="text-muted notice-time">[{{ $notice->admin->name ?? $notice->admin_id}}] {{ vncore_datetime_to_date($notice->created_at, 'Y-m-d H:i:s') }}</span>
      </a>
    @endforeach
    <div class="dropdown-divider"></div>
      <a href="{{ vncore_route_admin('admin_notice.index') }}" class="dropdown-item text-center">{{ vncore_language_render('action.view_more') }}</a>
    </div>
  @else
    <div class="dropdown-divider"></div>
    <span class="dropdown-item dropdown-header">{{ vncore_language_render('admin_notice.empty') }}</span>
  @endif
  </li>
