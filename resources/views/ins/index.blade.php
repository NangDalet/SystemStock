@extends('layouts.master')
@section('header')
    <strong>Stock In</strong>
@endsection

@section('content')
    <div class="card card-gray">
        <div class="toolbox">
            <div class="row">
                <div class="col-sm-3">
                    <a href="{{('stock-in/create')}}" class="btn btn-primary btn-sm btn-oval">
                        <i class="fa fa-plus-circle">
                         Create
                        </i>
                    </a>

                    </div>
            <div class="col-sm-5">
                <form action="{{url('stock-in/search')}}" method="GET">
                    <input type="text" name='q' value="{{$q}}">
                    <button>Find</button>
                </form>

                </div>
            </div>
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
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>In Date</th>
                        <th>Reference</th>
                        <th>PO No.</th>
                        <th>Warehouse</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                   <?php
                        $page=@$_GET['page'];
                        if(!$page){
                            $page=1;
                        }
                        $i=config('app.row') * ($page - 1) + 1;
                   ?>
                    @foreach($ins as $in)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>
                                <a href="{{url('stock-in/detail/'.$in->id)}}">{{$in->in_date}}</a>
                            </td>
                            <td>{{$in->reference}}</td>
                            <td>{{$in->po_no}}</td>
                            <td>{{$in->name}}</td>
                            <td>{{$in->description}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{$ins->links()}}
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function(){
            $('#sidebar-menu').removeClass('active open');
            $('#sidebar-menu li ul li').removeClass('active');
            $('#menu_in').addClass('active');
        })
    </script>
@endsection
