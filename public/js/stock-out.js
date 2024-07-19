function addItem(){
    let pid = $("#product").val();
    let pcode = $("#product :selected").attr('pcode');
    let pname = $("#product :selected").attr('pname');
    let unit = $("#product :selected").attr('uname');
    let qty = $("#qty").val();
    if(pid=="" || qty=="" || pcode==undefined){
        alert('Please select a product to add!');
    }else{
        let tr ="<tr pid= '"+pid+"'>";
        tr += "<td>" + pcode + "</td>";
        tr += "<td>" + pname + "</td>";
        tr += "<td>" + qty + "</td>";
        tr += "<td>" + unit + "</td>";
        tr += "<td><a href='#' onclick='removeItem(this, event)' class='btn btn-danger btn-sm btn-oval'>Delete</a>";
        tr += "&nbsp;<a href='#' data-toggle='modal' class='btn btn-sm btn-primary btn-oval' data-target='#editModal' onclick='editItem(this)'>Edit</a></td>";
        tr += "</tr>";
        let trs = $("tbody#data tr");
        if(trs.length>0){
            $("#data tr:last-child").after(tr);
        }else{
            $('#data').html(tr);
        }
        $("#qty").val("");
        $("#product").val("");
        $("#product").trigger("chosen:updated");
    }
}
function pressEnter(evt){
    let code = (evt.keyCode ? evt.keyCode : evt.which);
    if(code==13){
        addItem();
    }
}
function removeItem(obj, evt){
    evt.preventDefault();
    let con = confirm('You want to delete?');
    if(con){
        $(obj).parent().parent().remove();
    }
}
function editItem(obj){
    $('#data tr').removeAttr('active');
    $(obj).parent().parent().attr('active', 'true');
    let tr = $(obj).parent().parent();
    let tds = $(tr).children();
    let id = $(tr).attr('pid');
    let qty = $(tds[2]).html();
    $('#qty1').val(qty);
    $('#item').val(id);
    $('#item').trigger("chosen:updated");
}
function saveEdit(){
    let pid = $("#item").val();
    let pcode = $("#item :selected").attr('pcode');
    let pname = $("#item :selected").attr('pname');
    let unit = $("#item :selected").attr('uname');
    let qty = $("#qty1").val();
    if(pid=="" || qty=="" || pcode==undefined){
        alert('Please select a product to add!');
    }else{
        let tr = $("#data tr[active='true']");
        let tds = $(tr).children();
        $(tds[0]).html(pcode);
        $(tds[1]).html(pname);
        $(tds[2]).html(qty);
        $(tds[3]).html(unit);
        $(tr).attr('pid',pid);
        $('#editModal').modal('hide');
    }
}
function save(){
    //master data Object
    let master ={
        out_date: $("#out_date").val(),
        warehouse_id: $("#warehouse").val(),
        description: $("#description").val(),
        reference: $("#reference").val(),
    }
    let token = $("input[name='_token']").val();
    let items = []; //array
    let trs = $("#data tr");
    if($("#out_date").val()=="" || $("#warehouse").val()=="" || trs.length<=0){
        alert('Please input data correctly!');
    }else{
        for(let i=0; i<trs.length;i++){
            let tds = $(trs[i]).children();
            let item = {
                product_id: $(trs[i]).attr('pid'), //store in row
                quantity: $(tds[2]).html() //store in colunm
            };
            items.push(item); //push item to array
        }
        let data ={
            master:master,
            items:items
        }
        $.ajax({
            type: "POST",
            url: url + "/stock-out",
            data: data,
            beforeSend:function(request){
                return request.setRequestHeader('x-CSRF-Token',token);
            },
            success:function(sms){
                location.href = url + "/stock-out/detail/"+sms;
            }
        });
    }

    //console.log(master);
}
