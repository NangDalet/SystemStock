@extends('layouts.master')
@section('header')
    <strong>Stock Out Detail</strong>
@endsection

@section('content')
<form>
    @csrf
    <input type="hidden"id="id" value="{{$out->id}}">
    <div class="card card-gray">
        <div class="toolbox">
            <a href="{{url('stock-out/create')}}" class="btn btn-primary btn-sm btn-oval"><i class="fa fa-plus-circle"></i> Create</a>
            <a href="{{url('stock-out')}}" class="btn btn-warning btn-sm btn-oval"><i class="fa fa-reply"> Back</i></a>
            <a href="{{url('stock-out/delete/'.$out->id)}}" class="btn btn-danger btn-sm btn-oval" onclick="return confirm('You want to delete?')"><i class="fa fa-trash"></i> Delete</a>
            <a href="{{url('stock-out/print/'.$out->id)}}" class="btn btn-primary btn-sm btn-oval"><i class="fa fa-print"></i> Print</a>
        </div>
    </div>
    <div class="card block">

        <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="out_date" class="col-sm-3">Out Date </label>
                        <div class="col-sm-9">
                           <span id="lb_out_date">{{$out->out_date}}</span>
                           <input type="date" class="form-control hide" id="out_date" value="{{$out->out_date}}">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group row">
                            <label for="warehouse" class="col-sm-3">Warehouse </label>
                            <div class="col-sm-9">
                            <span id="lb_warehouse"> {{$out->name}}</span>
                                <select id="warehouse" class="form-control hide">
                                    <option value=""></option>
                                    @foreach($warehouse as $w)
                                        <option value="{{$w->id}}" {{$w->id==$out->warehouse_id?'selected':''}}>{{$w->name}}</option>
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
                                <span id="lb_reference"> {{$out->reference}}</span>
                            <input type="text" class="form-control hide" id="reference" value="{{$out->reference}}">
                                </div>
                            </div>
                        </div>
                     <div class="col-sm-6">
                     <div class="form-group row">
                            <label for="description" class="col-sm-3">Description</label>
                                <div class="col-sm-9">
                                <span id="lb_description"> {{$out->description}}</span>
                                <input type="text" class="form-control hide" id="description" value="{{$out->description}}">
                                </div>
                        </div>
                    </div>

            </div>

            <div class="row">
                <div class="col-sm-6">

                    </div>
                <div class="col-sm-6">
                   <button class="btn btn-primary btn-sm btn-oval" type="button" id='btnEdit' onclick="editMater()">
                        <i class="fa fa-edit">Edit</i>
                   </button>
                   <button class="btn btn-primary btn-sm btn-oval hide" type="button" id='btnSave' onclick="saveMater()">
                        <i class="fa fa-save">Save</i>
                   </button>
                   <button class="btn btn-danger btn-sm btn-oval hide" type="button" id='btnCancel' onclick="cancelMater()">
                        <i class="fa fa-times">Cancel</i>
                   </button>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2">
                    <h5>Items</h5>
                </div>
                    <div class="col-sm-2">
                        <button class="btn btn-sm btn-primary btn-oval" type="button" data-toggle="modal" data-target="#addItem">
                            <i class="fa fa-plus"></i>Add
                        </button>
                    </div>
            </div>

            <div class="row">
                    <div class="col-sm-12">
                        <p></p>
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="data">
                                @php($i=1)
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>{{$item->code}}</td>
                                        <td>{{$item->name}}</td>
                                        <td>{{$item->quantity}}</td>
                                        <td>{{$item->uname}}</td>
                                        <td>
                                            <a href="#" class="btn btn-danger btn-sm btn-oval" onclick="removeItem(event,this,{{$item->id}})">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
    </div>
</form>

<div class="modal fade" id="addItem" tabindex="-1" role="dialog" aria-labelledby="addItem"aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{url('stock-out/item/save')}}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{$out->id}}">
            <input type="hidden" name="warehouse_id" value="{{$out->warehouse_id}}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="item" class="col-sm-3">Product <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select name="item" class="form-control" id="item" required>
                                <option value="">--Select--</option>
                                @foreach($products as $p)
                                    <option value="{{$p->id}}">{{$p->code}} - {{$p->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="qty1" class="col-sm-3">Quantity</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.1" class="form-control" name="quantity" id="qty1" value="1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div style='padding:5px'>
                        <button class="btn btn-primary btn-sm btn-oval">Save</button>
                        <button type="button" class="btn btn-danger btn-sm btn-oval" data-dismiss="modal">Close</button>
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
    <script>
        $(document).ready(function(){
            $('#sidebar-menu').removeClass('active open');
            $('#sidebar-menu li ul li').removeClass('active');
            $('#menu_out').addClass('active');
        });
        function editMater(){
            $('#lb_out_date,#btnEdit,#lb_warehouse,#lb_reference,#lb_description').addClass('hide');
            $('#out_date,#btnSave,#btnCancel,#warehouse,#reference,#description').removeClass('hide');
        }
        function cancelMater(){
            $('#lb_out_date,#btnEdit,#lb_warehouse,#lb_reference,#lb_description').removeClass('hide');
            $('#out_date,#btnSave,#btnCancel,#warehouse,#reference,#description').addClass('hide');
        }
        function saveMater(){
            let token = $("input[name='_token']").val();
            let data={
                id: $("#id").val(),
                out_date: $("#out_date").val(),
                warehouse_id: $("#warehouse").val(),
                reference: $("#reference").val(),
                description: $("#description").val()
            };
            let con = confirm("You want to save changes?");
            if(con){
                $.ajax({
                    type:"POST",
                    url:url+"/stock-out/master/save",
                    data:data,
                    beforeSend:function(request){
                        return request.setRequestHeader('X-CSRF-Token',token);
                    },
                    success:function(sms){
                        if(sms>0){
                            location.href = url + "/stock-out/detail/" +sms;
                        }else{
                            alert("Fail to save stock, please check again!");
                        }
                    }
                });
            }
        }
        //function to remove item
        function removeItem(evt, obj, id){
            evt.preventDefault();
            let con = confirm('You want to delete?');
            if(con){
                $.ajax({
                    type:"GET",
                    url:url + "/stock-out/item/delete/"+id,
                    success:function(sms){
                        if(sms){
                            $(obj).parent().parent().remove();
                        }
                    }
                });
            }
        }
    </script>
@endsection
