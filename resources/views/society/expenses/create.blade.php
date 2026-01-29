@extends('society.layout.master')

@section('content')
<div class="container-fluid px-4">
    @include('society.layout.site_header')

    <div class="row">
        <div class="col-xl-12">

            <form method="POST"
                action="{{ isset($expense)
    ? route('society.expencess.update', $expense->id)
    : route('society.expencess.store') }}" enctype="multipart/form-data">

                @csrf
                @isset($expense) @method('PUT') @endisset
                <div class="row border border-primary rounded m-2 p-3">
                    <div class="col-12">
                        <h3>Expencess Management</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Category</label>
                            <select name="cash_category_id" class="form-control" required>
                                <option value="">Select</option>
                                @foreach($cashCategories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ (isset($expense) && $expense->cash_category_id == $cat->id) ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Payment Type</label>
                            <select name="frequency" class="form-control">
                                <option value="">Select</option>
                                @foreach($frequency as $key=>$value)
                                <option value="{{ $key }}"
                                    {{ (isset($expense) && $expense->frequency == $key) ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label>Expense Date</label>
                            <input type="date" name="expense_date" class="form-control"
                                value="{{ $expense->expense_date ?? '' }}" required>
                        </div>


                        <div class="col-md-2 mb-3">
                            <label>Payment Mode</label>
                            <select name="payment_mode" class="form-control">
                                @foreach($payment_mode as $mode)
                                <option value="{{ $mode }}"
                                    {{ (isset($expense) && $expense->payment_mode == $mode) ? 'selected' : '' }}>
                                    {{ ucfirst($mode) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label>Reference </label>
                            <input type="text" name="reference_no"
                                value="{{ $expense->reference_no ?? '' }}"
                                class="form-control">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label>Cheque No</label>
                            <input type="text" name="check_no"
                                value="{{ $expense->check_no ?? '' }}"
                                class="form-control">
                        </div>

                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label>Payment given to</label>
                                <select name="paid_to" class="form-control">
                                    <option value="member" {{ isset($expense) && $expense->paid_to == 'member' ? 'selected' : '' }}>Member</option>
                                    <option value="other" {{ isset($expense) && $expense->paid_to == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3" id="paid_to_name_hide">
                                <label>Name</label>
                                <input type="text" name="paid_to_name" id="paid_to_name"
                                    value="{{ $expense->paid_to_name ?? '' }}" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3" id="member_id_hide">
                                <label>Member</label>
                                <select name="member_id" id="member_id" class="form-control">
                                    <option value="">---Select member---</option>
                                    @foreach($members as $mem)
                                    <option value="{{ $mem->id }}"
                                        {{ (isset($expense) && $expense->society_members_id == $mem->id) ? 'selected' : '' }}>
                                        {{ $mem->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Amount</label>
                                <input type="number" step="0.01" name="amount"
                                    value="{{ $expense->amount ?? '' }}"
                                    class="form-control" required>

                            </div>
                            <div class="col-md-4 mt-3">
                                <label>Attachment</label>
                                <input type="file"
                                    name="attachment"
                                    class="form-control"
                                    accept="image/*">
                            </div>
                            @if(isset($expense) && $expense->attachment)
                            <div class="col-2  col-sm-2 col-md-2">
                                <div class="upload_gallery" id="previewGallery1"> 
                                    <div class="img-container">
                                        <img src="{{ asset('storage/expencess_attachment/' . $expense->attachment) }}" width="200">
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Note</label>
                            <textarea name="note" class="form-control">{{ $expense->note ?? '' }}</textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="paid" {{ isset($expense) && $expense->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="pending" {{ isset($expense) && $expense->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary mt-3">Add</button>
                        </div>
                    </div>
                </div>

            </form>


        </div>
    </div>
</div>
@endsection
@section('scriptDockReady')
$('#flat_id, #year, #month, #to_year, #to_month')
.on('change', function () {
calculateMaintenance();
});
@endsection
@section('script')
function calculateMaintenance() {
$.ajax({
url: "{{ route('society.maintenance.calculate') }}",
type: "POST",
data: {
_token: "{{ csrf_token() }}",
flat_type_id: $('#flat_id').val(),
from_year: $('#year').val(),
from_month: $('#month').val(),
to_year: $('#to_year').val(),
to_month: $('#to_month').val(),
},
success: function (res) {

let html = `<table class="table table-striped">
    <tr>
        <th>#</th>
        <th>Month</th>
        <th>Amount</th>
        <th>Late Fee</th>
        <th>Total</th>
    </tr>
    `;
    res.monthly_breakup.forEach(row => {
    html += `
    <tr>
        <td>1</td>
        <td>${row.month}/${row.year}</td>
        <td>${row.base_amount}</td>
        <td>${row.late_fee}</td>
        <td><b>${row.total}</b></td>
    </tr>
    `;
    });
    html += `
</table>`;
$('#maintenanceTable').html(html);
$('#amount').val(res.total_amount);
}
});
}
@endsection