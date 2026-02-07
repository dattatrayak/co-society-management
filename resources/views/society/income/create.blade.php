@extends('society.layout.master')

@section('content')
<div class="container-fluid px-4">
    @include('society.layout.site_header')

    <div class="row">
        <div class="col-xl-12">

            <form method="POST"
                action="{{ isset($income)
    ? route('society.income.update', $income->id)
    : route('society.income.store') }}" enctype="multipart/form-data">

                @csrf
                @isset($income) @method('PUT') @endisset
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
                                    {{ (isset($income) && $income->cash_category_id == $cat->id) ? 'selected' : '' }}>
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
                                    {{ (isset($income) && $income->frequency == $key) ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label>Income Date</label>
                            <input type="date" name="income_date" class="form-control"
                                value="{{ $income->expense_date ?? '' }}" required>
                        </div>


                        <div class="col-md-2 mb-3">
                            <label>Payment Mode</label>
                            <select name="payment_mode" class="form-control">
                                @foreach($payment_mode as $mode)
                                <option value="{{ $mode }}"
                                    {{ (isset($income) && $income->payment_mode == $mode) ? 'selected' : '' }}>
                                    {{ ucfirst($mode) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Parent income Reference</label>
                            <select name="parent_income_id" id="parent_income_id" class="form-control select2bs"></select>
                        </div>

                        <!--div class="col-md-2 mb-3">
                            <label>Reference </label>
                            <input type="text" name="reference_no"
                                value="{{ $income->reference_no ?? '' }}"
                                class="form-control">
                        </div -->
                        <div class="col-md-2 mb-3">
                            <label>Cheque No</label>
                            <input type="text" name="check_no"
                                value="{{ $income->check_no ?? '' }}"
                                class="form-control">
                        </div>

                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label>Payment given to</label>
                                <select name="paid_to" id="paid_to" class="form-control">
                                    <option value="member" {{ isset($income) && $income->paid_to == 'member' ? 'selected' : '' }}>Member</option>
                                    <option value="other" {{ isset($income) && $income->paid_to == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3" id="paid_to_name_hide">
                                <label>Name</label>
                                <input type="text" name="paid_to_name" id="paid_to_name"
                                    value="{{ $income->paid_to_name ?? '' }}" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3" id="member_id_hide">
                                <label>Member</label>
                                <select name="member_id" id="member_id" class="form-control">
                                    <option value="">---Select member---</option>
                                    @foreach($members as $mem)
                                    <option value="{{ $mem->id }}"
                                        {{ (isset($income) && $income->society_members_id == $mem->id) ? 'selected' : '' }}>
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
                                    value="{{ $income->amount ?? '' }}"
                                    class="form-control" required>

                            </div>
                            <div class="col-md-4 mt-3">
                                <label>Attachment</label>
                                <input type="file"
                                    name="attachment"
                                    class="form-control"
                                    accept="image/*">
                            </div>
                            @if(isset($income) && $income->attachment)
                            <div class="col-2  col-sm-2 col-md-2">
                                <div class="upload_gallery" id="previewGallery1">
                                    <div class="img-container">
                                        <img src="{{ asset('storage/expencess_attachment/' . $income->attachment) }}" width="200">
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="col-md-12 mb-3">
                            <label>Note</label>
                            <textarea name="note" class="form-control">{{ $income->note ?? '' }}</textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="paid" {{ isset($income) && $income->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="pending" {{ isset($income) && $income->status == 'pending' ? 'selected' : '' }}>Pending</option>
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
<style>
.select2bs + .select2-container .select2-selection--single {
    height: 37px !important;
    border: 1px solid #ced4da !important;
}
    </style>
@section('addJs')

<link rel="stylesheet" href="{{  asset('theme/css/select2.min.css') }}" rel="stylesheet">
<script src="{{ asset('theme/js/select2.min.js') }}"></script>
@endsection
@section('scriptDockReady')
togglePaidTo();

$('#flat_id, #year, #month, #to_year, #to_month')
.on('change', function () {
calculateMaintenance();
});
document.getElementById('paid_to').addEventListener('change', togglePaidTo);
@endsection
@section('script')
@if(isset($parentincome))
    var option = new Option(
        'Ref: {{ $parentincome->reference_no }} - ₹{{ $parentincome->amount }}',
        '{{ $parentincome->id }}',
        true,
        true
    );
    $('#parent_income_id').append(option).trigger('change');
@endif
$('#parent_income_id').select2({
    placeholder: 'Search parent income by id / name / amount',
    ajax: {
        url: '{{ route("society.expencess.searchParent") }}',
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return { q: params.term };
        },
        processResults: function (data) {
            return { results: data };
        },
        cache: true
    }
});
window.addEventListener('load', togglePaidTo);
function togglePaidTo() {
var paidTo = document.getElementById('paid_to').value;

var memberDiv = document.getElementById('member_id_hide');
var nameDiv = document.getElementById('paid_to_name_hide');

if (paidTo === 'member') {
memberDiv.style.display = 'block';
nameDiv.style.display = 'none';
} else {
memberDiv.style.display = 'none';
nameDiv.style.display = 'block';
}
}
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