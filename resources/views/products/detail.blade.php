@extends('layouts.master')
@section('header')
    <strong>Detail Product</strong>
@endsection

@section('content')
<form action="#">
    <div class="card card-gray">
        <div class="toolbox">
            <a href="{{url('product')}}" class="btn btn-warning btn-sm btn-oval">
                <i class="fa fa-reply">
                    Back
                </i>
            </a>
            <a href="{{route('product.create')}}" class="btn btn-primary btn-sm btn-oval">
                        <i class="fa fa-plus-circle">
                         Create
                        </i>
                    </a>
                    <a href="{{route('product.edit',$p->id)}}" class="btn btn-primary btn-sm btn-oval">
                        <i class="fa fa-edit">
                         Edit
                        </i>
                    </a>
                    <a href="{{route('product.delete',$p->id)}}" class="btn btn-danger btn-sm btn-oval" onclick="return confirm('You want to delete?')">
                        <i class="fa fa-trash">
                         Delete
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

                <div class="row">
                    <div class="col-sm-6">

                    <div class="form-group row">
                            <label for="code" class="col-sm-3">Code </label>
                            <div class="col-sm-8">
                                : {{$p->code}}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-sm-3">Name </label>
                            <div class="col-sm-8">
                               : {{$p->name}}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="barcode" class="col-sm-3">Barcode </label>
                            <div class="col-sm-8">
                            : {{$p->barcode}}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="brand" class="col-sm-3">Brand </label>
                            <div class="col-sm-8">
                            : {{$p->brand}}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="category_id" class="col-sm-3">Category </label>
                            <div class="col-sm-8">
                            : {{$p->cname}}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="unit_id" class="col-sm-3">Unit </label>
                            <div class="col-sm-8">
                            : {{$p->uname}}
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="price" class="col-sm-3">Price </label>
                            <div class="col-sm-8">
                            : {{$p->price}} $
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="cost" class="col-sm-3">Cost($) </label>
                            <div class="col-sm-8">
                            : {{$p->cost}} $
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3">Onhand </label>
                            <div class="col-sm-8">
                            : {{$p->onhand}} {{$p->uname}}
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group row">
                                <label for="description" class="col-sm-3">Description </label>
                                <div class="col-sm-8">
                                : {{$p->description}}
                            </div>
                        </div>

                        <div class="form-group row">
                                <label for="photo" class="col-sm-3">Photo </label>
                                <div class="col-sm-8">
                                <img src="{{asset($p->photo)}}" alt="" id='img'width="150">
                            </div>
                        </div>
                        <hr>
                       <center>
                       <img src="{{asset($p->qrcode)}}" alt="" width="150ox">
                       </center>
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
            $('#menu_product').addClass('active');
        });

        function preview(evt){
            var img=document.getElementById('img');
            img.src=URL.createObjectURL(evt.target.files[0]);
        }
    </script>
@endsection
