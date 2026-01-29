@extends('society.layout.master')

@section('content')
<div class="container-fluid px-4">
    @include('society.layout.site_header')

    <div class="row">
        <div class="col-xl-12">

            <form action="{{ route('society.maintenance.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row border border-primary rounded m-2 p-3">
                    <div class="col-12">
                        <h3>Maintenance Management</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Flat</label>
                            <select name="flat_id" id="flat_id" class="form-control" required>
                                <option value="">Select Flat</option>
                                @foreach($flats as $flat)
                                <option value="{{ $flat->id }}">
                                    {{ $flat->flat_no }}
                                    ({{ $flat->building->name }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Year</label>
                            <select name="year" id="year" class="form-control">
                                <option value="">Select Year</option>
                                @foreach(lastTenYears() as $year)
                                <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Month</label>
                            <select name="month" id="month" class="form-control" required>
                                <option value="" disabled>---Select Month--- </option>
                                @foreach(range(1,12) as $m)
                                <option value="{{ $m }}">
                                    {{ date('F', mktime(0,0,0,$m,1)) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>To Year</label>
                                    <select name="to_year" id="to_year" class="form-control">
                                        <option value="">Select Year</option>
                                        @foreach(lastTenYears() as $year)
                                        <option value="{{ $year }}">
                                            {{ $year }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label>To Month</label>
                                    <select name="to_month" id="to_month" class="form-control">
                                        <option value="">---Select Month--- </option>
                                        @foreach(range(1,12) as $m)
                                        <option value="{{ $m }}">
                                            {{ date('F', mktime(0,0,0,$m,1)) }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-5">
                            <div id="maintenanceTable">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label>Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label>Payment Mode</label>
                            <select name="payment_mode" class="form-control">
                                <option value="online">Online</option>
                                <option value="cash">Cash</option>
                                <option value="cheque">Cheque</option>
                                <option value="dd">DD</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Cheque / DD No</label>
                            <input type="text" name="check_no" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label>Online Payment Screenshot</label>
                        <input type="file"
                            name="attachment"
                            class="form-control"
                            accept="image/*">
                    </div>
                    <div class="mt-3">
                        <label>Note</label>
                        <textarea name="note" class="form-control"></textarea>
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