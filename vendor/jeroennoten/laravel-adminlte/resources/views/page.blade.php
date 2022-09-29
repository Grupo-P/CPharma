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
                        <div class="row">
                            <div class="col-12">
                                <div class="shadow small-box bg-danger">
                                    <div class="inner">
                                        <h3>Licencia</h3>
                                        {{$validate_licence['mensaje'];}}<br />
                                        Debe renovar su licencia para continuar usando el producto.
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-lock"></i>
                                    </div>
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
