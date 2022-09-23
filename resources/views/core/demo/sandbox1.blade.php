@extends('adminlte::page')

@section('title', 'Sandbox 1')

@section('footer')
    <!-- Footer theme -->
@stop

@section('css')
@stop

@section('js')
    
    <!-- Sweet Alert -->
    <script type="text/javascript">
        Swal.fire(
            'Good job!',
            'You clicked the button!',
            'success'
        );
    </script>
    <!-- Sweet Alert -->

    <!-- myChart -->
    <script>        
        const data = {
            labels: [
                'Red',
                'Blue',
                'Yellow'
            ],
            datasets: [{
                label: 'My First Dataset',
                data: [300, 50, 100],
                backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
                ],
                hoverOffset: 4
            }]
        };

        const config = {
            type: 'doughnut',
            data: data,            
        };

        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
    </script>
    <!-- myChart -->       
@stop

@section('content_header')
    <h1>Sandbox 1</h1>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Sandbox 1</li>
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
        
    <div class="card col-6">
        <div class="card-header">
            Welcome to this beautiful data table demo.
        </div>
        <div class="card-body">
            A nice little card body.
        </div>
    </div>
        
    <div class="card col-6">
        <div class="card-header">
            Welcome to this beautiful data table demo.
        </div>
        <div class="card-body">        
            <canvas id="myChart"></canvas>            
        </div>
    </div>    

    <div class="card">
        <div class="card-header">
            Welcome to this beautiful data table demo.
        </div>
        <div class="card-body">
            {{-- With buttons --}}
            <x-adminlte-datatable id="myTable" :heads="$heads" head-theme="dark" theme="light" :config="$config" striped hoverable with-buttons bordered/>    
        </div>
    </div>      
@stop