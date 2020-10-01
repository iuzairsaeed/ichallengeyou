@extends('layouts.app')

@section('content')
<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <h4 class="card-title">Transactions</h4>
                        <p class="card-text">Here you can see the list of transaction entries.</p>
                        <span class="card-text float-right">Total Amount on the App (
                            {{config('global.CURRENCY').' '.$net_total}} )</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-block table-responsive">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_from" class="label-control">From Date</label>
                                    <input type="date" class="form-control" id='date_from' value="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date_to" class="label-control">To Date</label>
                                    <input type="date" class="form-control" id='date_to' value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <table class="table table-striped table-bordered" id="dTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Amount</th>
                                        <th>Reason</th>
                                        <th>Created Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
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
        ajax: {
            url: '{{ route("amounts.getList") }}',
            type: 'GET',
            dataType: 'JSON',
            data: function (data) {
                data.date_from = $('#date_from').val();
                data.date_to = $('#date_to').val();
            },
            error: function (reason) {
                return true;
            }
        },
        columns: [{
                data: 'serial'
            },
            {
                data: 'user.name'
            },
            {
                data: 'amount'
            },
            {
                data: 'reason' , render: function (data, type, full, meta) {
                    return `<p>${full.reason} ${full.challenge_title != "" ? '('+full.challenge_title+')' : "" }</p>`;
                }
            },
            {
                data: 'created_at'
            },
            {
                data: 'actions',
                render: function (data, type, full, meta) {
                    return `<a href="/amounts/${full.id}" class="info success" title="View">
                                            <i class="ft-eye font-medium-3"></i>
                                        </a>`;
                }
            }
        ],
        order: [0, 'desc'],
        columnDefs: [{
                width: "6%",
                "targets": [-1, 0]
            },
            {
                orderable: false,
                targets: [-1, -5, -6]
            }
        ],
    });
    $('#date_from, #date_to').change(function () {
        $('#dTable').DataTable().ajax.reload();
    })

</script>
@endsection
