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
                <div class="card-body px-4">
                    <div class="border-bottom mb-4">
                        <div class="align-self-center halfway-fab text-center mb-4">
                            @if(strstr($challenge->file_mime, "video/"))
                                <video class="width-400" controls>
                                    <source src="{{ asset($challenge->file) }}" type="{{$challenge->file_mime}}">
                                </video>
                            @elseif(strstr($challenge->file_mime, "image/"))
                                <img src="{{ asset($challenge->file) }}" class="width-400" alt="File not available.">
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
                                    <div class="row" >
                                        <img src="{{ asset($challenge->user->avatar) }}" style="margin-left: 12px" class="width-50 margin-50" alt="File not available.">
                                        <p style="margin:10px" > {{$challenge->user->name}}</p>
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
                                        <p class="font"> {{ print(nl2br($challenge->description)??'-') }}  </p>
                                    </div>
                                </div>   
                            </div>

                            <div class="row">
                                @if (now() > $challenge->after_date )
                                    @if ($winner)
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="text-bold-700">Winner </label> 
                                                <p class="font text-bold-500"> {{ (optional(optional($winner)->user)->name)?? ' ' }}  <i class="icon-trophy"></i></p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="text-bold-700">Voters on this challenge </label>
                                                <div class="input-group">
                                                    <div class="custom-control custom-radio display-inline-block pr-3">
                                                        <input type="radio" class="custom-control-input" name="is_voter" id="is_active3" value='donators' {{($challenge->allowVoter == 'donators') ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="is_active1">Donators</label>
                                                    </div>
                                                    <div class="custom-control custom-radio display-inline-block">
                                                        <input type="radio" class="custom-control-input" name="is_voter" id="is_active4" value='premiumUsers' {{($challenge->allowVoter == 'premiumUsers') ? 'checked' :'' }}>
                                                        <label class="custom-control-label" for="is_active2">Donators & Premium Users</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="text-bold-700">Status</label>
                                        <div class="input-group">
                                            <div class="custom-control custom-radio display-inline-block pr-3">
                                                <input type="radio" class="custom-control-input" name="is_active" id="is_active1" value='approved' {{($challenge->status == 'Approved') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="is_active1">Approved</label>
                                            </div>
                                            <div class="custom-control custom-radio display-inline-block">
                                                <input type="radio" class="custom-control-input" name="is_active" id="is_active2" value='pending' {{($challenge->status == 'Pending') ? 'checked' :'' }}>
                                                <label class="custom-control-label" for="is_active2">Pending</label>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-actions left">
                                <button type="submit" form="updateForm" disable class="btn btn-raised btn-success">
                                    <i class="icon-check"></i> Upadate Challenge
                                </button>
                                <button type="submit" form="deleteForm" class="btn btn-raised btn-danger">
                                    <i class="icon-trash"></i> Delete
                                </button>
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
                        <p class="card-text">Here you can see the list of donations that users made on this challenge.</p>
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
                        <h4 class="card-title">Bids</h4>
                        <p class="card-text">Here you can see the list of bids that users made on this challenge.</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-block table-responsive">
                        <div class="row">
                            <table class="table table-striped table-bordered" id="bidsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Bid Amount</th>
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
    
    <div class="modal fade text-left" id="editSubmitorDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel3" aria-hidden="true">
        <div class="modal-dialog" role="document">
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="text-bold-700" for="name">Name</label>
                                    <p id="name"></p>
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
    
</section>
@endsection

@section('afterScript')
<script>    

    $('#donationsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax:
        {
            url: '{{ route("challenges.getDonations", $challenge->id) }}',
            type: 'GET',
            dataType: 'JSON',
            data:function(data){
                data.date_from= $('#date_from').val();
                data.date_to= $('#date_to').val();
            },
            error: function (reason) {
                return reason;
            }
        },
        columns: [
            { data: 'serial' },
            { data: 'user.name' },
            { data: 'amount' },
            { data: 'created_at' },
            { data: 'actions', render:function (data, type, full, meta) {
                                return `<a href="/challenges/${full.id}" class="info success" title="View">
                                            <i class="ft-eye font-medium-3"></i>
                                        </a>`;
                                }
            }
        ],
        columnDefs: [
            { orderable: false, targets: [-1, -2] }
        ],
    });

    $('#bidsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax:
        {
            url: '{{ route("challenges.getBids", $challenge->id) }}',
            type: 'GET',
            dataType: 'JSON',
            data:function(data){
                data.date_from= $('#date_from').val();
                data.date_to= $('#date_to').val();
            },
            error: function (reason) {
                return reason;
            }
        },
        columns: [
            { data: 'serial' },
            { data: 'user.name' },
            { data: 'bid_amount' },
            { data: 'created_at' },
            { data: 'actions', render:function (data, type, full, meta) {
                                return `<a href="/challenges/${full.id}" class="info success" title="View">
                                            <i class="ft-eye font-medium-3"></i>
                                        </a>`;
                                }
            }
            
        ],
        columnDefs: [
            { orderable: false, targets: [-1, -2] }
        ],
    });

    $('#acceptorsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax:
        {
            url: '{{ route("challenges.getAcceptors", $challenge->id) }}',
            type: 'GET',
            dataType: 'JSON',
            data:function(data){
                data.date_from= $('#date_from').val();
                data.date_to= $('#date_to').val();
            },
            error: function (reason) {
                return reason;
            }
        },
        columns: [
            { data: 'serial' },
            { data: 'user.name' },
            { data: 'created_at' },
            { data: 'actions', render:function (data, type, full, meta) {
                                return `<a href="/challenge/${full.id}" class="info success" title="View">
                                            <i class="ft-eye font-medium-3"></i>
                                        </a>`;
                                }
            }
        ],
        columnDefs: [
            { orderable: false, targets: [-1, -2] }
        ],
    });

    $('#submitorsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax:
        {
            url: '{{ route("challenges.getSubmitors", $challenge->id) }}',
            type: 'GET',
            dataType: 'JSON',
            data:function(data){
                data.date_from= $('#date_from').val();
                data.date_to= $('#date_to').val();
            },
            error: function (reason) {
                return reason;
            }
        },
        columns: [
            { data: 'serial' },
            { data: 'user.name' },
            { data: 'created_at' },
            { data: 'actions', render:function (data, type, full, meta) {
                // console.log(full);
                                return `<a class="success p-0 mr-2" title="Edit" data-id="${full.id}" data-toggle="modal" 
                                        data-keyboard="false" data-target="#editSubmitorDetail">
                                            <i class="ft-edit font-medium-3"></i>
                                        </a>`;
                                }
            }
        ],
        columnDefs: [
            { orderable: false, targets: [-1, -2] }
        ],
    });

    $('#editSubmitorDetail').on('show.bs.modal',function(event){

        const button = $(event.relatedTarget);
        const id = button.data('id');
        var datas = null;
        $.ajax({
            url: '{{ route("challenges.getSubmitor", $challenge->id) }}', 
            data: {id:id},
            success: function(data){
                console.log(data);
            }
        });
        // var type = jQuery.type(value);
        // if(type == 'number'){
        //     $("#valueDiv").html('<input type="number" class="form-control border-primary" id="value" name="value" value="" novalidate required>');
        // } else if (type == 'string'){
        //     $("#valueDiv").html('<textarea class="form-control border-primary" id="value" name="value" novalidate required></textarea>');
        // } 
        // const id = button.data('id');
        // const modal = $(this);
        // $(this).find('.modal-body #name').text(name);
        // $(this).find('.modal-body #value').val(valueOriginal);
        // $(this).find('.modal-body #id').val(id);
    });

    $('#updateSubmitorDetail').submit(function(e){
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
                $('#editSubmitorDetail').modal('hide');
            },
            error: function (e) {
                swal("Error!", "There has been some error!", "error");
            }
        });
    });
    
    $('#votersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax:
        {
            url: '{{ route("challenges.voters", $challenge->id) }}',
            type: 'GET',
            dataType: 'JSON',
            data:function(data){
                data.date_from= $('#date_from').val();
                data.date_to= $('#date_to').val();
            },
            error: function (reason) {
                return reason;
            }
        },
        columns: [
            { data: 'serial' },
            { data: 'voter.user.name' },
            { data: 'voter.vote_up' },
            { data: 'voter.vote_down' },
            { data: 'created_at' },
            { data: 'actions', render:function (data, type, full, meta) {
                                return `<a href="/votes/${full.voter.id}" class="info success" title="View">
                                            <i class="ft-eye font-medium-3"></i>
                                        </a>`;
                                }
            }
        ],
        columnDefs: [
            { orderable: false, targets: [-1, -2] }
        ],
    });
    
    

    

</script>
@endsection
