@extends('society.layout.master')

@section('content')
<div class="container-fluid px-4">
    @include('society.layout.site_header')

    <div class="row">
        <div class="col-xl-12">
            <a href="{{ route('society.expencess.create') }}" class="btn btn-primary">
                + Add Expense
            </a>
        </div>
        <div class="col-xl-12">
            
            <form method="GET" action="{{ route('society.maintenance.index') }}" class="mb-3">
                <div class="row mt-2">
                    <!-- Search -->
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control"
                            placeholder="flat Number" value="{{ request('search') }}">
                    </div>

                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </div>
            </form>
           
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th>Payment Mode</th>
                        <th>Status</th>
                        <th width="150">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                    <tr>
                        <td>{{ $expense->expense_date }}</td>
                        <td>{{ $expense->cashCategory->name ?? '-' }}</td>
                        <td>₹ {{ number_format($expense->amount,2) }}</td>
                        <td>{{ ucfirst($expense->payment_mode) }}</td>
                        <td>
                            <span class="badge bg-{{ $expense->status == 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($expense->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('society.expencess.edit', $expense->id) }}"
                                class="btn btn-sm btn-info">Edit</a>

                            <form action="{{ route('society.expencess.destroy', $expense->id) }}"
                                method="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No expenses found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $expenses->links('vendor.pagination.bootstrap-5') }}
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