@extends('layouts.master')
@section('header')
    <strong>Products</strong>
@endsection

@section('content')
    <div class="card card-gray">
        <div class="toolbox">
            <div class="row">
                <div class="col-sm-3">
                    <a href="{{('product/create')}}" class="btn btn-primary btn-sm btn-oval">
                        <i class="fa fa-plus-circle">
                         Create
                        </i>
                    </a>
                    <button class="btn btn-primary btn-sm btn-oval" type="button" data-toggle='modal' data-target='#modalImport'>
                        <i class="fa fa-download">Import</i>
                    </button>
                    </div>
            <div class="col-sm-5">
                <form action="{{url('product/search')}}" method="GET">
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
                        <th>Photo</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Onhand</th>
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
                    @foreach($products as $p)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>
                                <img src="{{asset($p->photo)}}" alt="" width="27">
                            </td>
                            <td>
                                <a href="{{url('product/detail/'.$p->id)}}">{{$p->code}}</a>
                            </td>
                            <td>{{$p->name}}</td>
                            <td>{{$p->brand}}</td>
                            <td>{{$p->cname}}</td>
                            <td>{{$p->onhand}}{{$p->uname}}</td>
                            <td class="action">
                                <a href="{{route('product.edit',$p->id)}}" class="text-success" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>&nbsp;
                                <a href="{{route('product.delete',$p->id)}}" class="text-danger" onclick="return confirm('You want to delete?')">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{$products->appends(request()->input('q'))->links()}}
        </div>
    </div>
<!-- Modal for import product-->
<div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <form action="{{url('product/import')}}" method="POST" enctype="multipart/form-data">
          {{csrf_field()}}
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalImportLabel">Import Products</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
            <label for="import_file" class="col-sm-3">File Name <span class="text-danger">*</span></label>
            <div class="col-sm-9">
                <input type="file" class="form-control" name='file_import' id='file_import' required>
                <p></p>
                <p>
                    If you don't have product CSV file template,
                    <a href="{{asset('import/template.csv')}}">Please click here to download.</a>
                </p>
            </div>
        </div>
      </div>
      <div class="modal-footer">
      <button type="submit" class="btn btn-primary btn-sm btn-oval"><i class="fa fa-download"></i>
          Import Now</button>
        <button type="button" class="btn btn-danger btn-sm btn-oval" data-dismiss="modal"><i class="fa fa-times"></i>
            Close</button>

      </div>
    </div>
    </form>
  </div>
</div>
@endsection

@section('js')
    <script>
        $(document).ready(function(){
            $('#sidebar-menu').removeClass('active open');
            $('#sidebar-menu li ul li').removeClass('active');
            $('#menu_product').addClass('active');
        })
    </script>
@endsection
