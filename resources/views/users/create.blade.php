@extends('layouts.master')
@section('header')
<strong>Create User</strong>
@endsection
@section('content')
<form action="{{url('user/save')}}" method="POST" enctype="multipart/form-data">
<div class="card card-gray">
    <div class="toolbox">
    <button class="btn btn-primary btn-sm btn-oval">
                <i class="fa fa-save"> Save</i>
            </button>
    <a href="{{url('user')}}" class="btn btn-warning btn-sm btn-oval">
                <i class="fa fa-reply"></i> Back
    </a>
    </div>
       <div class="card-block">
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
               {{csrf_field()}}
               <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group row">
                            <label for="name" class="col-sm-3">Full Name <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="name" name='name' required autofocus
                                value="{{old('name')}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-sm-3">Email <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="email" name='email'required
                                value="{{old('email')}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="username" class="col-sm-3">Username <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="username" name='username' required
                                value="{{old('name')}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-sm-3">Password <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" id="password" name='password' required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="role" class="col-sm-3">Role <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select name="role" id="role" class="form-control chosen-select">
                                    <option value="">--Select--</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="language" class="col-sm-3">Language <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select name="language" id="language" class="form-control">
                                    <option value="en">English</option>
                                    <option value="km">ភាសាខ្មែរ</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group row">
                            <label for="photo" class="col-sm-3">Photo</label>
                            <div class="col-sm-8">
                                <input type="file" class="form-control" id="photo" name='photo' accept="image/jpeg, image/x-png,image/gif" onchange="preview(event)" >
                                <p style="margin-top:9px">
                                    <img src="" alt="" width="120" id='img'>
                                </p>
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
            $('#menu_security').addClass('active open');
            $('#security_collapse').addClass('collapse in');
            $('#menu_user').addClass('active');
        })
        function preview(evt){
            let img = document.getElementById('img');
            img.src = URL.createObjectURL(evt.target.files[0]);
        }
    </script>
@endsection
