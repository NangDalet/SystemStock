@extends('layouts.master')
@section('header')
    <strong>Warehouse</strong>
@endsection

@section('content')
    <div class="card card-gray">
        <div class="toolbox">
            <a href="{{('warehouse/create')}}" class="btn btn-primary btn-sm btn-oval">
                <i class="fa fa-plus-circle">
                    Create
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
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th></th>
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
                    @foreach($warehouses as $w)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$w->code}}</td>
                            <td>{{$w->name}}</td>
                            <td>{{$w->address}}</td>
                            <td class="action">
                                <a href="{{url('warehouse/delete/'.$w->id)}}" class="text-danger" title='Delete'
                                     onclick="return confirm('You want to delete?')">
                                    <i class="fa fa-trash"></i>
                                </a> &nbsp;
                                <a href="{{route('warehouse.edit',$w->id)}}" class="text-success" title='Edit'>
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{$warehouses->links()}}
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function(){
            $('#sidebar-menu').removeClass('active open');
            $('#sidebar-menu li ul li').removeClass('active');
            $('#menu_setting').addClass('active open');
            $('#setting_collapse').addClass('collapse in');
            $('#warhouse_user').addClass('active');
        })
    </script>
@endsection
