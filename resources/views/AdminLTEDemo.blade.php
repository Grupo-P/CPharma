@extends('adminlte::page')

@section('title', 'Tittle AdminLTEDemo')

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css"></script>
    <script src="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.1/css/buttons.dataTables.min.css">
@stop

@section('footer')  
    <span>Copyright © {{date('Y')}}.All rights reserved. <a href="https://www.linkedin.com/in/covacode/" target="_blank" style="text-decoration: none; color:#869099;">Sergio Cova</a></span>
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" ></script>
    <script script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>        
@stop

@section('content_header')

    <h1>H1 AdminLTEDemo</h1>

    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Demo</li>
        </ol>
    </nav>
    
@stop

@section('content')    
      
    @php
        $heads = [
            'ID',
            'Name',
            ['label' => 'Phone', 'width' => 40],
            ['label' => 'Actions', 'no-export' => true, 'width' => 5],
        ];

        $btnDetails = '<button class="btn btn-sm btn-outline-primary mx-1 shadow" title="Details">
                        <i class="fa fa-lg fa-fw fa-eye"></i>
                    </button>';
        $btnEdit = '<button class="btn btn-sm btn-outline-success mx-1 shadow" title="Edit">
                        <i class="fa fa-lg fa-fw fa-edit"></i>
                    </button>';
        $btnDelete = '<button class="btn btn-sm btn-outline-danger mx-1 shadow" title="Delete">
                        <i class="fa fa-lg fa-fw fa-trash"></i>
                    </button>';

        $config = [
            'data' => [
                [22, 'John Bender', '+02 (123) 123456789', '<nobr>'.$btnDetails.$btnEdit.$btnDelete.'</nobr>'],
                [19, 'Sophia Clemens', '+99 (987) 987654321', '<nobr>'.$btnDetails.$btnEdit.$btnDelete.'</nobr>'],
                [3, 'Peter Sousa', '+69 (555) 12367345243', '<nobr>'.$btnDetails.$btnEdit.$btnDelete.'</nobr>'],
                [4, 'John Bender', '+02 (123) 123456789', '<nobr>'.$btnDetails.$btnEdit.$btnDelete.'</nobr>'],
                [18, 'Sophia Clemens', '+99 (987) 987654321', '<nobr>'.$btnDetails.$btnEdit.$btnDelete.'</nobr>'],
                [5, 'Peter Sousa', '+69 (555) 12367345243', '<nobr>'.$btnDetails.$btnEdit.$btnDelete.'</nobr>'],
                [15, 'John Bender', '+02 (123) 123456789', '<nobr>'.$btnDetails.$btnEdit.$btnDelete.'</nobr>'],
                [17, 'Sophia Clemens', '+99 (987) 987654321', '<nobr>'.$btnDetails.$btnEdit.$btnDelete.'</nobr>'],
                [9, 'Peter Sousa', '+69 (555) 12367345243', '<nobr>'.$btnDetails.$btnEdit.$btnDelete.'</nobr>'],
                [2, 'John Bender', '+02 (123) 123456789', '<nobr>'.$btnDetails.$btnEdit.$btnDelete.'</nobr>'],
                [54, 'Sophia Clemens', '+99 (987) 987654321', '<nobr>'.$btnDetails.$btnEdit.$btnDelete.'</nobr>'],
                [62, 'Peter Sousa', '+69 (555) 12367345243', '<nobr>'.$btnDetails.$btnEdit.$btnDelete.'</nobr>'],
            ],
            'order' => [[0, 'asc']],
            'columns' => [null, null, null, ['orderable' => false]],
        ];
    @endphp
    
    <div class="card">
        <div class="card-header">
            Welcome to this beautiful data table demo.
        </div>
        <div class="card-body">
            {{-- With buttons --}}
            <x-adminlte-datatable id="example" :heads="$heads" head-theme="dark" theme="light" :config="$config" striped hoverable with-buttons bordered/>    
        </div>
    </div>  

    <div class="card">
        <div class="card-header">
            Welcome to this beautiful data table demo.
        </div>
        <div class="card-body">
            A nice little card body.
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Welcome to this beautiful data table demo.
        </div>
        <div class="card-body">
            A nice little card body.
        </div>
    </div>

@stop