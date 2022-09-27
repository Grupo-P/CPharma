@extends('adminlte::master')

@php    
    use App\Models\Core\Licencia;
    $validate_licence = Licencia::validate_licence();
    $rutaActual = Licencia::validate_route();    
@endphp

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')
    <div class="wrapper">

        {{-- Preloader Animation --}}
        @if($layoutHelper->isPreloaderEnabled())
            @include('adminlte::partials.common.preloader')
        @endif

        {{-- Top Navbar --}}
        @if($layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.navbar.navbar-layout-topnav')
        @else
            @include('adminlte::partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if(!$layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.sidebar.left-sidebar')
        @endif

        @if ($validate_licence['validate_licence'] || $rutaActual )
            {{-- Content Wrapper --}}
            @empty($iFrameEnabled)        
                @include('adminlte::partials.cwrapper.cwrapper-default')
            @else
                @include('adminlte::partials.cwrapper.cwrapper-iframe')
            @endempty
        @else
            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <h1>Licencia</h1>
                        <div class="content">
                            <div class="container-fluid">                                                                
                                <div class="text-danger mt-5">            
                                    <p><strong><i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;@php echo $validate_licence['mensaje']; @endphp</strong></p>
                                    <p><strong><i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp; Debe renovar su <a style="text-decoration: none;" href="{{ route('core.licencias.index') }}">licencia</a> para continuar usando el producto.</strong></p>
                                </div>                                    
                            </div>                        
                        </div>
                    </div>
                </div>            
            </div>
        @endif        

        {{-- Footer --}}
        @hasSection('footer')            
            @include('adminlte::partials.footer.footer')
        @endif

        {{-- Right Control Sidebar --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.sidebar.right-sidebar')
        @endif

    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop
