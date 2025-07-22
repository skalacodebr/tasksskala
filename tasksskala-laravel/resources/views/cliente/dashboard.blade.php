@if($isMobile ?? false)
    @include('cliente.dashboard-mobile')
@else
    @include('cliente.dashboard-desktop')
@endif