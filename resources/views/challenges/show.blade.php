@extends('layouts.app')
@section('content')
<section id="user-area">
    <a class="btn btn-primary" href="/challenges"><i class="fa fa-reply"></i> Go Back</a>
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <h4 class="card-title">Challenge Detail</h4>
                    </div>
                </div>
                <div class="card-body px-4 px-md-2">
                    <div class="border-bottom mb-4">
                        <div class="align-self-center halfway-fab text-center mb-4">
                            @if(strstr($challenge->file_mime, "video/"))
                            <video class="width-400 mw-100" controls>
                                <source src="{{ asset($challenge->file) }}" type="{{$challenge->file_mime}}">
                            </video>
                            @elseif(strstr($challenge->file_mime, "image/"))
                            <img src="{{ asset($challenge->file) }}" class="width-400 mw-100" alt="File not available.">
                            @endif
                        </div>
                    </div>
                    <form id="deleteForm" action="/challenges/{{$challenge->id}}" method="POST">
                        @method('Delete')
                        @csrf
                    </form>
                    <form id="updateForm" action="/challenges/{{$challenge->id}}" method="POST">
                        @method('PUT')
                        @csrf

                        <div class="form-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="text-bold-700">Title</label>
                                        <p>{{$challenge->title ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-bold-700">Initial Amount</label><br>
                                    <p> {{$challenge->initialAmount->amount}}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-bold-700">Start Time</label><br>
                                    <p> {{$challenge->start_time->format(config('global.DATE_FORMAT')) ?? '' }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="text-bold-700">Duration Days</label>
                                        <p>{{$challenge->duration_days ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="text-bold-700">Duration Hours</label>
                                        <p>{{$challenge->duration_hours ?? '-'}}</p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="text-bold-700">Duration Minutes</label>
                                        <p>{{$challenge->duration_minutes ?? '-'}}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label class="text-bold-700">Result Type</label>
                                    <p>{{$challenge->result_type == 'first_win' ? 'First Win' : 'Vote'}}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-bold-700">Category</label>
                                    <p>{{$challenge->category->name}}</p>
                                </div>
                                <div class="col-md-4">
                                    <label class="text-bold-700">Location</label>
                                    <p>{{$challenge->location}}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label class="text-bold-700">Creater</label><br>
                                    <div class="row">
                                        <img src="{{ asset($challenge->user->avatar) }}" style="margin-left: 12px"
                                            class="width-50 margin-50" alt="File not available.">
                                        <p style="margin:10px">
                                            {{$challenge->user->name ?? $challenge->user->username }}</p>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="text-bold-700">Created At</label>
                                        <p>{{$challenge->created_at->format(config('global.DATE_FORMAT'))??'-'}}</p>
                                    </div>
                                </div>
                            </div><br>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="text-bold-700">Description</label>
                                        <p class="font"> {{ print(nl2br($challenge->description)??'-') }} </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="text-bold-700">Voters on this challenge </label>
                                        <p class="" >{{$challenge->allowVoter}}</p>
                                    </div>
                                </div>
                            
                                @if ($challenge->status == Completed() )
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="text-bold-700">Winner </label>
                                            <p class="font text-bold-500">
                                                {{ (optional(optional($winner)->user)->name)?? ' ' }} <i
                                                    class="icon-trophy success"></i></p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if (
                            $challenge->status == Pending() ||
                            $challenge->status == Approved() ||
                            $challenge->status == Denied()
                            )
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-bold-700">Status</label>
                                        <div class="input-group">
                                            @if($challenge->status == Pending() || now() <= $challenge->start_time)
                                                <div class="custom-control custom-radio display-inline-block">
                                                    <input type="radio" class="custom-control-input" name="is_active"
                                                        id="is_active2" value='pending'
                                                        {{($challenge->status == 'Pending') ? 'checked' :''}}
                                                        {{  ($challenge->status == 'Completed') ? 'disabled' :''  }}>
                                                    <label class="custom-control-label" for="is_active2">Pending</label>
                                                </div>
                                            @endif
                                            <div class="custom-control custom-radio display-inline-block ml-2">
                                                <input type="radio" class="custom-control-input" name="is_active"
                                                    id="is_active1" value='approved'
                                                    {{($challenge->status == 'Approved') ? 'checked' : ''}}
                                                    {{  ($challenge->status == 'Completed') ? 'disabled' :''  }}>
                                                <label class="custom-control-label" for="is_active1">Approved</label>
                                            </div>
                                            <div class="custom-control custom-radio display-inline-block ml-2">
                                                <input type="radio" class="custom-control-input" name="is_active"
                                                    id="is_active3" value='denied'
                                                    {{($challenge->status == 'Denied') ? 'checked' :''}}
                                                    {{  ($challenge->status == 'Completed') ? 'disabled' :''  }}>
                                                <label class="custom-control-label" for="is_active3">Reject</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-bold-700">Status</label>
                                        <p>{{ $challenge->status }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-actions left">
                                @if (
                                $challenge->status == Pending() ||
                                $challenge->status == Approved() ||
                                $challenge->status == Denied()
                                )
                                <button type="submit" form="updateForm" disable class="btn btn-raised btn-success">
                                    <i class="icon-check"></i> Update
                                </button>
                                <button type="button" class="btn btn-raised btn-danger delete">
                                    <i class="icon-trash"></i> Delete
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <h4 class="card-title">Donations</h4>
                        <p class="card-text">Here you can see the list of donations that users made on this challenge.
                        </p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-block table-responsive">
                        <div class="row">
                            <table class="table table-striped table-bordered" id="donationsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Amount</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <h4 class="card-title">Acceptors</h4>
                        <p class="card-text">Here you can see the list of Acceptors.</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-block table-responsive">
                        <div class="row">
                            <table class="table table-striped table-bordered" id="acceptorsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title-wrap">
                        <h4 class="card-title">Submitors</h4>
                        <p class="card-text">Here you can see the list of Submitors.</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-block table-responsive">
                        <div class="row">
                            <table class="table table-striped table-bordered" id="submitorsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Vote Up</th>
                                        <th>Vote Down</th>
                                        <th>Total Votes</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left " id="editSubmitorDetail" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel3" aria-hidden="true">
        <div class="modal-lg modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h3 class="modal-title" id="myModalLabel3">Submitted Challenge Detial</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="POST" name="updateSubmitorDetail" id="updateSubmitorDetail">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title-wrap bar-success">
                                            <h4 class="card-title">Submitted Challenge</h4>
                                            <input type="hidden" name="submit_challenge_id" id="submitedChallengeID">
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="card-block">
                                            <div id="carousel-example-generic" class="carousel slide"
                                                data-ride="carousel">
                                                <div class="carousel-inner" role="listbox">
                                                    {{-- Submited MEdia --}}
                                                </div>
                                                <a class="carousel-control-prev" href="#carousel-example-generic"
                                                    role="button" data-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                                <a class="carousel-control-next" href="#carousel-example-generic"
                                                    role="button" data-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title-wrap bar-success">
                                            <h4 class="card-title">Submitted Date Time</h4>
                                            <div class="submit_date">
                                                {{-- Submited Date --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title-wrap bar-success">
                                            <h4 class="card-title">Submitor Detail</h4>
                                            <div class="submitor">
                                                {{-- Submitor Detail  --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card winnerCard" hidden>
                                    <div class="card-header">
                                        <div class="card-title-wrap bar-success">
                                            <h4 class="card-title">Winner</h4>
                                            <div class="winner">
                                                <div class="col-md-4">
                                                    <div class="form-group winnerSpan">
                                                        {{-- Winner Checked 1|0  --}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-actions center pb-0">
                            <input type="hidden" id="id" name="id">
                            <button type="reset" data-dismiss="modal" class="btn btn-raised btn-danger mr-1">
                                <i class="icon-trash"></i> Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('afterScript')
<script>
    $('#donationsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("challenges.getDonations", $challenge->id) }}',
            type: 'GET',
            dataType: 'JSON',
            data: function (data) {
                data.date_from = $('#date_from').val();
                data.date_to = $('#date_to').val();
            },
            error: function (reason) {
                return reason;
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
                data: 'created_at'
            },
            {
                data: 'actions',
                render: function (data, type, full, meta) {
                    return `<a href="/users/${full.user.id}" class="info success" title="View">
                                            <i class="ft-eye font-medium-3"></i>
                                        </a>`;
                }
            }
        ],
        order: [0, 'desc'],
        columnDefs: [{
            orderable: false,
            targets: [-1, -2]
        }],
    });

    $('#bidsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("challenges.getBids", $challenge->id) }}',
            type: 'GET',
            dataType: 'JSON',
            data: function (data) {
                data.date_from = $('#date_from').val();
                data.date_to = $('#date_to').val();
            },
            error: function (reason) {
                return reason;
            }
        },
        columns: [{
                data: 'serial'
            },
            {
                data: 'user.name'
            },
            {
                data: 'bid_amount'
            },
            {
                data: 'created_at'
            },
            {
                data: 'actions',
                render: function (data, type, full, meta) {
                    return `<a href="/users/${full.user.id}" class="info success" title="View">
                                            <i class="ft-eye font-medium-3"></i>
                                        </a>`;
                }
            }

        ],
        order: [0, 'desc'],
        columnDefs: [{
            orderable: false,
            targets: [-1, -2]
        }],
    });

    $('#acceptorsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("challenges.getAcceptors", $challenge->id) }}',
            type: 'GET',
            dataType: 'JSON',
            data: function (data) {
                data.date_from = $('#date_from').val();
                data.date_to = $('#date_to').val();
            },
            error: function (reason) {
                return reason;
            }
        },
        columns: [{
                data: 'serial'
            },
            {
                data: 'user.name'
            },
            {
                data: 'created_at'
            },
            {
                data: 'actions',
                render: function (data, type, full, meta) {
                    return `<a href="/users/${full.user.id}" class="info success" title="View">
                                            <i class="ft-eye font-medium-3"></i>
                                        </a>`;
                }
            }
        ],
        order: [0, 'desc'],
        columnDefs: [{
            orderable: false,
            targets: [-1, -2]
        }],
    });

    $('#submitorsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("challenges.getSubmitors", $challenge->id) }}',
            type: 'GET',
            dataType: 'JSON',
            data: function (data) {
                data.date_from = $('#date_from').val();
                data.date_to = $('#date_to').val();
            },
            error: function (reason) {
                return reason;
            }
        },
        columns: [
            {
                data: 'serial'
            },
            {
                data: 'user.name',
                render: function (data, type, full, meta) {
                    if (full.showTrophy != "Winner") {
                        return `<p>` + full.user.name + `</p>`;
                    } else {
                        return `<p>` + full.user.name +
                            ` <i class="bold success icon-trophy"></i> </p>`;
                    }
                }
            },
            {
                data: 'vote_up',
                render: function (data, type, full, meta) {
                    return `<p>` + full.vote_up + ` <i class="ft-thumbs-up"></i></p> `;
                }
            },
            {
                data: 'vote_down',
                render: function (data, type, full, meta) {
                    return `<p>` + full.vote_down + ` <i class="ft-thumbs-down"></i></p>`;
                }
            },
            {
                data: 'total_votes',
                render: function (data, type, full, meta) {
                    return `<p>` + full.total_votes + ` <i class="ft-thumbs-up"></i></p> `;
                }
            },
            {
                data: 'created_at'
            },
            { data: 'actions', render:function (data, type, full, meta) {
                                if(full.isWinner != "Winner" && full.showWinBtn == true){
                                    $('.winnerCard').prop('hidden' , false);
                                    $('.winnerSpan').html(
                                        `<div><button type="submit" class="btn btn-raised btn-success updateBtn">
                                            <i class="icon-trophy"></i> Mark as Winner ★
                                        </button>`
                                    );
                                } 
                            return `<a class="success p-0 mr-2" title="Edit" data-id="${full.id}" data-toggle="modal"
                                        data-keyboard="false" data-target="#editSubmitorDetail">
                                            <i class="ft-edit font-medium-3"></i>
                                    </a>`;
                }
            }
        ],
        order: [0, 'desc'],
        columnDefs: [{
            orderable: false,
            targets: [-1, -2]
        }],
    });

    $('#editSubmitorDetail').on('show.bs.modal', function (event) {

        const button = $(event.relatedTarget);
        const id = button.data('id');
        $('#submitedChallengeID')[0].value = id;
        var datas = null;
        $.ajax({
            url: '{{ route("challenges.getSubmitor", $challenge->id) }}',
            data: {
                id: id
            },
            dataType: "json",
            success: function (res) {
                let submit_files = res.data[0].submit_files;
                let submitted_date = res.data[0].submit_challenge.created_at;
                let user = res.data[0].user;
                if (res.data[0].submit_challenge.isWinner == true) {
                    $('.winnerCard').prop('hidden', false);
                    $('.winnerSpan').html(
                        '<div><p>' + user.name +
                        ' is Winnner <i class="bold success icon-trophy"></i></p></div>'
                    );
                } else {
                    $('.winnerCard').prop('hidden', true);
                }

                $('.carousel-inner').empty();
                for (let i = 0; i < submit_files.length; i++) {
                    $('.carousel-inner').append(
                        `<div class="carousel-item" id="y-tube">
                            <iframe src="/` + submit_files[i].file + `" width="100%" height="300px"></iframe>
                        </div>`
                    );
                }
                $(".carousel-inner").find("div").eq(0).addClass("active");
                $('.submit_date').html(
                    `<p>` + submitted_date + `</p>`
                );
                $('.submitor').html(
                    `<div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <img src="/` + user.avatar + `" style="margin-left: 12px" class="width-50 margin-50" alt="File not available.">
                                <p style="margin:10px" >` + user.name + `</p>
                            </div>
                        </div>
                    </div>
                    `
                );



            }
        });
    });

    $('#updateSubmitorDetail').submit(function (e) {
        e.preventDefault();
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0CC27E',
            cancelButtonColor: '#FF586B',
            confirmButtonText: 'Yes, Update',
            cancelButtonText: "No, Cancel"
        }).then(function (isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: "{{ route('submitor.winner') }}",
                    method: "POST",
                    dataType: 'json',
                    data: {
                        id: $('#submitedChallengeID').val(),
                        value: 'yes'
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        swal("Updated!", "Action has been performed successfully!",
                            "success").catch(swal.noop);
                        $('#submitorsTable').DataTable().ajax.reload();
                        $('#editSubmitorDetail').modal('hide');
                        location.reload();
                    },
                    error: function (e) {
                        swal("Error!", "There has been some error!", "error");
                    }
                });
            }
        }).catch(swal.noop);
    });

    $('#votersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("challenges.voters", $challenge->id) }}',
            type: 'GET',
            dataType: 'JSON',
            data: function (data) {
                data.date_from = $('#date_from').val();
                data.date_to = $('#date_to').val();
            },
            error: function (reason) {
                return reason;
            }
        },
        columns: [{
                data: 'serial'
            },
            {
                data: 'voter.user.username'
            },
            {
                data: 'voter.vote_up'
            },
            {
                data: 'voter.vote_down'
            },
            {
                data: 'created_at'
            },
            {
                data: 'actions',
                render: function (data, type, full, meta) {
                    return `<a href="/votes/${full.voter.id}" class="info success" title="View">
                                            <i class="ft-eye font-medium-3"></i>
                                        </a>`;
                }
            }
        ],
        order: [0, 'desc'],
        columnDefs: [{
            orderable: false,
            targets: [-1, -2]
        }],
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
                var action = $('#deleteForm').attr('action');
                $.ajax({
                    type: "DELETE",
                    url: action,
                    data: {
                        value: $('.modal-body #value').val()
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        swal("Deleted!", "Challenge has been deleted successfully!",
                            "success").catch(swal.noop);
                        location.reload();
                        $('#editSubmitorDetail').modal('hide');
                    },
                    error: function (e) {
                        swal("Error!", "There has been some error!", "error");
                    }
                });
            }
        }).catch(swal.noop);
    });

    $('#editSubmitorDetail').on('hidden.bs.modal', function () {
        $('iframe').attr('src', '');
    });

</script>
@endsection
