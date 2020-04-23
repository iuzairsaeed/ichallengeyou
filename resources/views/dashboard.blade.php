@extends('layouts.app')

@section('content')
<section id="minimal-statistics">
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-12">
            <div class="card">
                <div class="card-body">
                <div class="media align-items-stretch">
                    <div class="p-2 text-center rounded-left ">
                        <i class="icon-speedometer font-large-2 text-red"></i>
                    </div>
                    <div class="p-2 media-body text-right">
                        <h5 class="text-bold-400 m-0">33</h5>
                        <span>Total Challenges</span>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-12">
            <div class="card">
                <div class="card-body">
                <div class="media align-items-stretch">
                    <div class="p-2 text-center rounded-left ">
                        <i class="icon-graph font-large-2 text-red"></i>
                    </div>
                    <div class="p-2 media-body text-right">
                        <h5 class="text-bold-400 m-0">20</h5>
                        <span>Approved Challenges</span>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
