@extends('society.layout.master')

@section('content')
<div class="container-fluid px-4">
    @include('society.layout.site_header')

    <div class="row">
        <div class="col-xl-12">
            <a href="{{ route('society.income.create') }}" class="btn btn-primary">
                + Add Income
            </a>
        </div>
        <div class="col-xl-12">

            <form method="GET" action="{{ route('society.income.index') }}" class="mb-3">
                <div class="row mt-2">
                    <!-- Search -->
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control"
                            placeholder="search " value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="cash_category_id" class="form-control">
                            <option value="">--Select expencess Category--</option>
                            @foreach($cashCategories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ (isset($request) && $request->cash_category_id == $cat->id) ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-2">
                        <label>From Date</label>
                        <input type="date" name="expense_from_date" id="expense_from_date" class="form-control"
                            value="{{ $request->expense_from_date ??  ''  }}">
                    </div>
                    <div class="col-md-2">
                        <label>To Date</label>
                        <input type="date" name="expense_to_date" id="expense_to_date" class="form-control"
                            value="{{ $request->expense_to_date ?? '' }}">
                    </div>
                    <div class="col-md-1">
                        <label>From Month</label>
                        <select name="from_month" id="from_month" class="form-control">
                            <option value="">-Month-</option>
                            @foreach(range(1,12) as $m)
                            <option value="{{ $m }}"
                                {{ (isset($request) && $request->from_month == $m) ? 'selected' : '' }}>
                                {{ date('F', mktime(0,0,0,$m,1)) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label>To Year</label>
                        <input type="number" name="to_year" class="form-control" value="{{ $request->to_year }}">
                    </div>
                    <div class="col-md-1">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            <option value="">-Status-</option>
                            <option value="paid" {{ isset($request) && $request->status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ isset($request) && $request->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label>Mode</label>
                        <select name="payment_mode" class="form-control">
                            <option value="">-Payment-</option>
                            @foreach($payment_mode as $mode)
                            <option value="{{ $mode }}"
                                {{ (isset($request) && $request->payment_mode == $mode) ? 'selected' : '' }}>
                                {{ ucfirst($mode) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1 mt-4">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                    <div class="col-md-1 mt-4">
                        <a type="reset" class="btn btn-primary w-100" href="{{ route('society.expencess.index') }}">Clear</a>
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
                            <a href="{{ route('society.income.edit', $expense->id) }}"
                                class="btn btn-sm btn-info">Edit</a>

                            <form action="{{ route('society.income.destroy', $expense->id) }}"
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