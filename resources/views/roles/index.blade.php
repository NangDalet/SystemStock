@extends('layouts.master')
@section('header')
    <strong>Role</strong>
@endsection

@section('content')
    <div class="card card-gray">
        <div class="toolbox">
        <a href="{{('role/create')}}" class="btn btn-primary btn-sm btn-oval">
                <i class="fa fa-plus-circle">

                </i>
                {{trans('label.create')}}
            </a>
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
                        <th>{{trans('label.role')}}</th>
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
                    @foreach($roles as $role)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>
                                <a href="{{url('role/detail/'.$role->id)}}">{{$role->name}}</a>
                            </td>

                            <td class="action">
                                <a href="{{url('role/delete/'.$role->id)}}" class="text-danger" title='Delete'
                                     onclick="return confirm('You want to delete?')">
                                    <i class="fa fa-trash"></i>
                                </a> &nbsp;
                                <a href="{{url('role/edit/'.$role->id)}}" class="text-success" title='Edit'>
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{$roles->links()}}
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function(){
            $('#sidebar-menu').removeClass('active open');
            $('#sidebar-menu li ul li').removeClass('active');
            $('#menu_security').addClass('active open');
            $('#security_collapse').addClass('collapse in');
            $('#menu_role').addClass('active');
        })
    </script>
@endsection
