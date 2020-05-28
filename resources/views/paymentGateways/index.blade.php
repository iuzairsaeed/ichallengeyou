@extends('layouts.app')

@section('content')
<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <h4 class="card-title">Payment Gateways</h4>
                        <p class="card-text">Here you can see the list of configured payment gateways.</p>
                    </div>
                </div>
                <div class="card-body collapse show">
                    <div class="card-block card-dashboard table-responsive">
                        <table class="table table-striped table-bordered" id="dTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
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
@endsection

@section('afterScript')
<script>
    var table = $('#dTable').DataTable({
        processing: true,
        serverSide: true,
        ajax:
        {
            url: '{{ route("paymentGateways.getList") }}',
            type: 'GET',
            dataType: "JSON",
            error: function (reason) {
                return true;
            }
        },
        columns: [
            { data: 'serial'},
            { data: 'name' },
            { data: 'actions', render:function (data, type, full, meta) {
                                return `<a class="success p-0 mr-2" title="Edit" data-id="${full.id}" data-name="${full.name}" data-value="${full.value}" data-toggle="modal" data-keyboard="false" data-target="#editSetting">
                                            <i class="ft-edit font-medium-3"></i>
                                        </a>`;  }
            }
        ],
        columnDefs: [
            { width: "10%", "targets": [-1, 0] },
            { orderable: false, targets: [-1] }
        ],
    });
</script>
@endsection
