@extends('layouts.app')

@section('content')
<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <h4 class="card-title">Settings</h4>
                        <p class="card-text">Here you can see the list of configurable settings.</p>
                    </div>
                </div>
                <div class="card-body collapse show">
                    <div class="card-block card-dashboard table-responsive">
                        <table class="table table-striped table-bordered" id="dTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Value</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade text-left" id="editSetting" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h3 class="modal-title" id="myModalLabel3">Edit Setting</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="POST" name="updateSetting" id="updateSetting">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="text-bold-700" for="name">Name</label>
                                <p id="name"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" >
                            <div class="form-group">
                                <label for="value">Value</label>
                                <div id="valueDiv">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions center pb-0">
                        <input type="hidden" id="id" name="id">
                        <button type="reset" data-dismiss="modal" class="btn btn-raised btn-danger mr-1">
                            <i class="icon-trash"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-raised btn-success">
                            <i class="icon-note"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('afterScript')
<script>
    var table = $('#dTable').DataTable({
        processing: true,
        serverSide: true,
        ajax:
        {
            url: '{{ route("settings.getList") }}',
            type: 'GET',
            dataType: "JSON",
            error: function (reason) {
                return true;
            }
        },
        columns: [
            { data: 'serial'},
            { data: 'name' },
            { data: 'value' },
            { data: 'actions', render:function (data, type, full, meta) {
                                return `<a class="success p-0 mr-2" title="Edit" data-id="${full.id}" data-name="${full.name}" data-valueOriginal="${full.valueOriginal}" data-value="${full.value}"  data-toggle="modal" data-keyboard="false" data-target="#editSetting">
                                            <i class="ft-edit font-medium-3"></i>
                                        </a>`;  }
            }
        ],
        columnDefs: [
            { width: "10%", "targets": [-1, 0] },
            { orderable: false, targets: [-1] }
        ],
    });

    $('#editSetting').on('show.bs.modal',function(event){
        const button = $(event.relatedTarget);
        const name = button.data('name');
        const value = button.data('value');
        const valueOriginal = button.data('valueoriginal');
        var type = jQuery.type(value);
        if(type == 'number'){
            $("#valueDiv").html('<input type="number" class="form-control border-primary" id="value" name="value" value='+valueOriginal+' novalidate required>');
        } else if (type == 'string'){
            $("#valueDiv").html('<textarea class="form-control border-primary" id="value" name="value" novalidate required>'+valueOriginal+'</textarea>');
        } 
        const id = button.data('id');
        const modal = $(this);
        $(this).find('.modal-body #name').text(name);
        $(this).find('.modal-body #value').val(valueOriginal);
        $(this).find('.modal-body #id').val(id);
    });

    $('#updateSetting').submit(function(e){
        e.preventDefault();
        $.ajax({
            type: "PUT",
            url: "/settings/"+$('.modal-body #id').val(),
            data: {
                value: $('.modal-body #value').val()
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success:function(data){
                swal("Updated!", "Action has been performed successfully!", "success").catch(swal.noop);
                $('#dTable').DataTable().ajax.reload();
                $('#editSetting').modal('hide');
            },
            error: function (e) {
                swal("Error!", "There has been some error!", "error");
            }
        });
    });
</script>
@endsection
