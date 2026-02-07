@extends('society.layout.master')

@section('content')
<div class="container-fluid px-4">
    @include('society.layout.site_header')

    <div class="row">

        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    Account Balance
                </div>
                <div class="card-body">
                    <h5 class="card-title">Total Cash Balance</h5>
                    <p class="card-text">{{ $balance['cash'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header">
                    Account Balance
                </div>
                <div class="card-body">
                    <h5 class="card-title">Total Bank Balance</h5>
                    <p class="card-text">{{ $balance['bank'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('addJs')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
@if (isset($meters[0]))

@section('script')
document.getElementById('delete-button').addEventListener('click', function(event) {
var form = $(this).closest("form");
event.preventDefault();
Swal.fire({
title: 'Are you sure?',
text: "You won't be able to revert this!",
icon: 'warning',
showCancelButton: true,
confirmButtonColor: '#3085d6',
cancelButtonColor: '#d33',
confirmButtonText: 'Yes, delete it!'
}).then((result) => {
if (result.isConfirmed) {
form.submit();
}
});
});
@endsection

@endif