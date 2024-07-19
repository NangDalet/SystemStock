@extends('layouts.master')
@section('header')
    <strong>Create Unit</strong>
@endsection

@section('content')
<form action="{{route('unit.store')}}" method="POST">
    <div class="card card-gray">
        <div class="toolbox">
            <button class="btn btn-primary btn-sm btn-oval">
                <i class="fa fa-save"> Save</i>
            </button>
            <a href="{{url('unit')}}" class="btn btn-warning btn-sm btn-oval">
                <i class="fa fa-reply">
                    Back
                </i>
            </a>
        </div>
        <div class="card-black">
                @if(Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                <p>
                         {{session('success')}}
                     </p>
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                     </button>
                </div>
                @endif
                @if(Session::has('error'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <p>
                         {{session('error')}}
                     </p>
                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                     </button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                    @foreach($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                    </ul>
                </div>
                @endif

                @csrf
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group row">
                            <label for="name" class="col-sm-3">Name <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="name" name='name' required autofocus
                                value="{{old('name')}}">
                            </div>
                        </div>

                    </div>

                </div>

        </div>
    </div>
    </form>
@endsection

@section('js')
    <script>
        $(document).ready(function(){
            $('#sidebar-menu').removeClass('active open');
            $('#sidebar-menu li ul li').removeClass('active');
            $('#menu_setting').addClass('active open');
            $('#setting_collapse').addClass('collapse in');
            $('#menu_setting').addClass('active');
        })
    </script>
@endsection
