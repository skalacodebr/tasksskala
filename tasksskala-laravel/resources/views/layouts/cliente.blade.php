@if($isMobile ?? false)
    @include('layouts.cliente-mobile')
@else
    @include('layouts.cliente-desktop')
@endif