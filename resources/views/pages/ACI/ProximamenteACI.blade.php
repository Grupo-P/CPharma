@extends('layouts.modelUser')

@section('title')
  ACI
@endsection

<style>

    .full-height {
        height: 90vh;
    }
    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }
    .position-ref {
        position: relative;
    }
    .content {
        text-align: center;
    }
    .title {
        font-size: 84px;
    }
    .m-b-md {
        margin-top: 35%;
    }

    .fondopantalla{
        background-image:url(/assets/img/Logo.jpg);
        background-size: 20%;
    }

    .frameS{
        z-index: 100;
        background-color:rgba(255,255,255,0.7);
        width: 100%;
        height: 100%;
    }

    .a{
        text-decoration: none;
    }
</style>

@section('content')
    <hr class="row align-items-start col-12">
    <h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Academia de Capacitacion Integral (ACI)
	</h1>
	<hr class="row align-items-start col-12">

    <div class="card-deck">
        <div class="card border-danger mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-danger">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        Modulo 1
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
            <form action="{{asset('assets/ACI/modulo1.pdf')}}" style="display: inline;" target="_blank">
                    @csrf
                    <button type="submit" name="ACI" role="button" class="btn btn-outline-danger btn-sm"></i>Visualizar</button>
                </form>
            </div>
        </div>

        <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        Modulo 2
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <form action="{{asset('assets/ACI/modulo1.pdf')}}" style="display: inline;" target="_blank">
                    @csrf
                    <button type="submit" name="ACI" role="button" class="btn btn-outline-success btn-sm"></i>Visualizar</button>
                </form>
            </div>
        </div>

        <div class="card border-info mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-info">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        Modulo 3
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <form action="{{asset('assets/ACI/modulo1.pdf')}}" style="display: inline;" target="_blank">
                    @csrf
                    <button type="submit" name="ACI" role="button" class="btn btn-outline-info btn-sm"></i>Visualizar</button>
                </form>
            </div>
        </div>
    </div>

    <div class="card-deck">
        <div class="card border-warning mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-warning">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
                        Modulo 4
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-warning text-right">
		  		<form action="{{asset('assets/ACI/modulo1.pdf')}}" style="display: inline;" target="_blank">
				    @csrf
				    <button type="submit" name="ACI" role="button" class="btn btn-outline-warning btn-sm"></i>Visualizar</button>
                </form>
		  	</div>
        </div>

        <div class="card border-secondary mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-secondary">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
                        Modulo 5
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-secondary text-right">
		  		<form action="{{asset('assets/ACI/modulo1.pdf')}}" style="display: inline;" target="_blank">
				    @csrf
				    <button type="submit" name="ACI" role="button" class="btn btn-outline-secondary btn-sm"></i>Visualizar</button>
                </form>
		  	</div>
        </div>

        <div class="card border-dark mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-dark">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
                        Modulo 6
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-dark text-right">
		  		<form action="{{asset('assets/ACI/modulo1.pdf')}}" style="display: inline;" target="_blank">
				    @csrf
				    <button type="submit" name="ACI" role="button" class="btn btn-outline-dark btn-sm"></i>Visualizar</button>
                </form>
		  	</div>
        </div>
 	</div>
@endsection
