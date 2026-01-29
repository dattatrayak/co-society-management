@extends('society.layout.master')

@section('content')
<div class="container-fluid px-4">
    @include('society.layout.site_header')

    <div class="row">
        <div class="col-xl-12">
            <a href="{{ route('society.maintenance.create') }}" class="btn btn-primary">Add new Maintenance</a>
        </div>
        <div class="col-xl-12">
            <form method="GET" action="{{ route('society.maintenance.index') }}" class="mb-3">
                <div class="row mt-2">
                    <!-- Search -->
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control"
                            placeholder="flat Number" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="flat_type" class="form-control">
                            <option value="">Select Flat Type</option>
                            @foreach ($societyFlatType as $flatType)
                            <option value="{{ $flatType->id }}"
                                {{ request('flat_type') == $flatType->id ? 'selected' : '' }}>
                                {{ $flatType->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Building Filter -->
                    <div class="col-md-3">
                        <select name="building_id" class="form-control">
                            <option value="">Select Building</option>
                            @foreach ($buildings as $building)
                            <option value="{{ $building->id }}"
                                {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </div>
            </form>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Flat</th>
                        <th>Building</th>
                        <th>Month</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Payment Mode</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($records as $record)
                    <tr>
                        <td>{{ $record->flat->flat_no }}</td>
                        <td>{{ $record->building?->name }}</td>

                        <td>{{ date('F Y', mktime(0,0,0,$record->month,1,$record->year)) }}</td>
                        <td>₹ {{ number_format($record->amount, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $record->status === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($record->status) }}
                            </span>
                        </td>
                        <td>{{ ucfirst($record->payment_mode) }}</td>
                        <td>
                            <a href="{{ route('society.maintenance.create', $record) }}" class="btn btn-sm btn-info">
                                Edit
                            </a>

                            @if($record->status === 'pending')
                            <form action="{{ route('society.maintenance.create', $record) }}"
                                method="POST"
                                style="display:inline;">
                                @csrf
                                <button class="btn btn-sm btn-success">
                                    Mark Paid
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No records found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $records->links('vendor.pagination.bootstrap-5') }}
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