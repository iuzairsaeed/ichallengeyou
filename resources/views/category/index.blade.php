@extends('layouts.app')

@section('content')
<section id="dom">
    <div class="row">
        <div class="col-md-4 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <h4 class="card-title">Add Category</h4>
                        <p class="card-text">Here you can add the category.</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="px-3">
                        <form action="/categories" class="" method="POST">
                            @csrf
                            <div class="form-body">
                                <h4 class="form-section"> Add Category</h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="projectinput1">Category Name</label>
                                            <input type="text" id="name" class="form-control" name="name"
                                                placeholder="Category" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="reset" class="btn btn-danger mr-1">
                                        <i class="icon-trash"></i> Cancel
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="icon-note"></i> Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-8 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <h4 class="card-title">Category List</h4>
                        <p class="card-text">Here you can see the list of configurable category.</p>
                    </div>
                </div>
                <div class="card-body collapse show">
                    <div class="card-block card-dashboard table-responsive">
                        <table class="table table-striped table-bordered" id="dTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
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
<div class="modal fade text-left" id="editCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h3 class="modal-title" id="myModalLabel3">Edit Category</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form method="POST" name="updateCategory" id="updateCategory">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="value">Category</label>
                                <div id="valueDiv">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3"></div>
                        <div class="col-3">
                            <input type="hidden" id="id" name="id">
                            <button type="submit" class="btn btn-raised btn-success">
                                <i class="icon-note"></i> Update
                            </button>
                        </div>
                </form>
                <div class="col-4">
                    <button type="button" class="delete btn btn-raised btn-danger">
                        <i class="icon-note"></i> Delete
                    </button>
                </div>
            </div>
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
        ajax: {
            url: '{{ route("category.getList") }}',
            type: 'GET',
            dataType: "JSON",
            error: function (reason) {
                return true;
            }
        },
        columns: [{
                data: 'serial'
            },
            {
                data: 'name'
            },
            {
                data: 'actions',
                render: function (data, type, full, meta) {
                    return `<a class="success p-0 mr-2" title="Edit" data-id="${full.id}" data-name="${full.name}" data-toggle="modal" data-keyboard="false" data-target="#editCategory">
                                            <i class="ft-edit font-medium-3"></i>
                                        </a>`;
                }
            }
        ],
        order: [0, 'desc'],
        columnDefs: [{
                width: "20%",
                "targets": [-1, 0]
            },
            {
                orderable: false,
                targets: [-1]
            }
        ],
    });

    $('#editCategory').on('show.bs.modal', function (event) {
        const button = $(event.relatedTarget);
        const name = button.data('name');
        $("#valueDiv").html(
            '<input type="text" class="form-control border-primary" id="value" name="name" value="' + name +
            '" novalidate required>');
        const id = button.data('id');
        const modal = $(this);
        $(this).find('.modal-body #name').val(name);
        $(this).find('.modal-body #id').val(id);
    });

    $('#updateCategory').submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "PUT",
            url: "/categories/" + $('.modal-body #id').val(),
            data: {
                value: $('.modal-body #value').val()
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                swal("Updated!", "Category has been updated successfully!", "success").catch(swal
                    .noop);
                $('#dTable').DataTable().ajax.reload();
                $('#editCategory').modal('hide');
            },
            error: function (e) {
                swal("Error!", "There has been some error!", "error");
            }
        });
    });

    $('#deleteCategory').submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "DELETE",
            url: "/categories/" + $('.modal-body #id').val(),
            data: {
                value: $('.modal-body #value').val()
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                swal("Deleted!", "Category has been deleted successfully!", "success").catch(swal
                    .noop);
                $('#dTable').DataTable().ajax.reload();
                $('#editCategory').modal('hide');
            },
            error: function (e) {
                swal("Error!", "There has been some error!", "error");
            }
        });
    });
    $('.delete').click(function () {
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0CC27E',
            cancelButtonColor: '#FF586B',
            confirmButtonText: 'Yes, Delete it',
            cancelButtonText: "No, Cancel"
        }).then(function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: "DELETE",
                    url: "/categories/" + $('.modal-body #id').val(),
                    data: {
                        value: $('.modal-body #value').val()
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        swal("Deleted!", "Category has been deleted successfully!",
                            "success").catch(swal.noop);
                        $('#dTable').DataTable().ajax.reload();
                        $('#editCategory').modal('hide');
                    },
                    error: function (e) {
                        swal("Error!", "There has been some error!", "error");
                    }
                });
            }
        }).catch(swal.noop);
    });

</script>
@endsection
