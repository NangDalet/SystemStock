@extends('layouts.master')
@section('header')
    <strong>Stock Out</strong>
@endsection

@section('content')
<form >
    <div class="card card-gray">
        <div class="toolbox">
            <button class="btn btn-primary btn-sm btn-oval" type="button" onclick="save()">
                <i class="fa fa-save"></i> Save
            </button>
            <a href="{{url('stock-out')}}" class="btn btn-warning btn-sm btn-oval">
                <i class="fa fa-reply">
                    Back
                </i>
            </a>
        </div>
        <div class="card-block">
            @csrf
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="out_date" class="col-sm-3">Out Date <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" id="out_date">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                            <label for="warehouse" class="col-sm-3">Warehouse <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select id="warehouse" class="form-control">
                                    <option value=""></option>
                                    @foreach($ws as $w)
                                        <option value="{{$w->id}}">{{$w->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="row">

                <div class="col-sm-6">
                    <div class="form-group row">
                            <label for="reference" class="col-sm-3">Reference</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="reference">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                    <div class="form-group row">
                            <label for="description" class="col-sm-3">Description</label>
                                <div class="col-sm-9">
                                    <textarea cols="30" rows="2" class="form-control" id="description"></textarea>
                                </div>
                        </div>
                    </div>
            </div>

            <div class="row">
            <div class="col-sm-6">

            </div>
            <div class="col-sm-6">

                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <h5>Items</h5>
                </div>
                <div class="col-sm-3">
                    <input type="text" class="form-control" id="barcode">
                </div>
                <div class="col-sm-5">
                    <select id="product" class="form-control chosen-select">
                        <option value=""></option>
                        @foreach($products as $p)
                            <option value="{{$p->id}}" pcode="{{$p->code}}" pname="{{$p->name}}"
                            uname="{{$p->uname}}">{{$p->code}} - {{$p->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <input type="text" id="qty" class="form-control" onkeypress="pressEnter(event)">
                </div>
                <div class="col-sm-2">
                   <button class="btn btn-primary btn-oval" type="button" onclick="addItem()">Add</button>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p></p>
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="data"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </form>
    <!-- Modal for edit option-->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <form action="#">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Item</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group row">
            <label for="item" class="col-sm-3">Product <span class="text-danger">*</span></label>
            <div class="col-sm-8">
                <select name="item" id="item" class="form-control" required>
                    <option value="">--Select--</option>
                    @foreach($products as $p)
                        <option value="{{$p->id}}" uname="{{$p->uname}}"
                        pcode="{{$p->code}}" pname="{{$p->name}}">{{$p->code}} - {{$p->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
            <div class="form-group row">
                <label for="qty1" class="col-sm-3">Quantity</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" step="0.1" min="0" name="qty1" id="qty1" value="1">
                </div>
            </div>
      </div>
            <div class="modal-footer">
                    <div style='padding:5px'>
                        <button type="button" class="btn btn-primary btn-sm btn-oval" id="btn" onclick="saveEdit()">
                    Save</button>
                        <button type="button" class="btn btn-danger btn-sm btn-oval" data-dismiss="modal"><i class="fa fa-times"></i>
                    Close</button>
                    </div>
            </div>
        </div>
    </form>
  </div>
</div>
@endsection

@section('js')
    <script>
        var url = "{{url('/')}}";
    </script>
    <script src="{{asset('js/stock-out.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('#sidebar-menu').removeClass('active open');
            $('#sidebar-menu li ul li').removeClass('active');
            $('#menu_out').addClass('active');
        })
    </script>
@endsection
