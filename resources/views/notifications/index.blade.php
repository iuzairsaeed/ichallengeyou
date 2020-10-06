@extends('layouts.app')

@section('content')
<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <h4 class="card-title">Notifications</h4>
                        <p class="card-text">Here you can see the list of Notifications.</p>
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
                                        <th>Title</th>
                                        <th>Message</th>
                                        <th>Created At</th>
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
            url: '{{ route("notification.getNotifications") }}',
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
                data: 'title'
            },
            {
                data: 'body'
            },
            {
                data: 'created_at'
            },
        ],
        order: [0, 'desc'],
        columnDefs: [{
                width: "40%",
                targets: [-2, -3]
            },
            {
                orderable: false,
                targets: [-2, -1, -3]
            }
        ],
    });

    $('#date_from').change(function () {
        $('#date_to').attr('min', $(this).val());
    });
    var today = new Date().toISOString().split('T')[0];
    $('#date_from, #date_to').attr('max', today);

    $('#date_from, #date_to').change(function () {
        $('#dTable').DataTable().ajax.reload();
    });
    $('#dTable tbody').on('click', 'tr', function () {
        var data = table.row(this).data();
        if (data.click_action == 'SUBMITED_CHALLENGE_LIST_SCREEN' || data.click_action ==
            'CHALLENGE_DETAIL_SCREEN' || data.click_action == 'SUBMITED_CHALLENGE_DETAIL_SCREEN') {
            window.location.href = "/challenges/" + data.data_id;
        } else if (data.click_action == 'TRANSACTION_LIST') {
            window.location.href = "/users/" + data.data_id;
        }
    });

</script>
@endsection
