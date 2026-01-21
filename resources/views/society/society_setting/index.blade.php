@extends('society.layout.master')

@section('content')
<div class="container-fluid px-4">
    @include('society.layout.site_header')

    <div class="row">
        <div class="col-xl-12">
            <table class="table table-bordered">
                <tr>
                    <th>Late Fee</th>
                    <th>Type</th>
                    <th>Grace Days</th>
                    <th>Action</th>
                </tr>
                @foreach($settings as $setting)
                <tr>
                    <td>{{ $setting->maintenance_late_fee }}</td>
                    <td>{{ ucfirst($setting->late_fee_type) }}</td>
                    <td>{{ $setting->grace_days }}</td>
                    <td>
                        <button onclick="openSettingModal({{ $setting->id }})"
                            class="btn btn-sm btn-warning">Edit</button>
                    </td>
                </tr>
                @endforeach
            </table>
            <div class="row">
                @if($flatTypes->count() != $maintenances->count())
                <div class="col-xl-12 text-right">
                    <a href="{{ route('society.setting.create') }}" class="btn btn-primary float-end">New Society flat wise maintainance</a>
                </div>
                @endif
            </div>
            <table class="table table-bordered">
                <tr>
                    <th>Flat Type</th>
                    <th>Maintenance Amount</th>
                    <th>Action</th>
                </tr>
                @foreach($maintenances as $row)
                <tr>
                    <td>{{ $row->flatType->name }}</td>
                    <td>₹ {{ $row->maintenance_amount }}</td>
                    <td>
                        <a href="{{ route('society.setting.edit',$row->id) }}"
                            class="btn btn-sm btn-warning">Edit</a>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
<!-- Edit Settings Modal -->
<div class="modal fade" id="settingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Society Settings</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div id="settingSuccess"
                class="alert alert-success d-none">
            </div>
            <form id="settingForm">
                @csrf
                <input type="hidden" id="setting_id" name="id">

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Late Fee Amount</label>
                        <input type="number" step="0.01"
                            name="maintenance_late_fee"
                            id="maintenance_late_fee"
                            class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Late Fee Type</label>
                        <select name="late_fee_type"
                            id="late_fee_type"
                            class="form-control">
                            <option value="fixed">Fixed</option>
                            <option value="percentage">Percentage</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Grace Days</label>
                        <input type="number"
                            name="grace_days"
                            id="grace_days"
                            class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit"
                        class="btn btn-primary">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('script')

function openSettingModal(id) {

$.get("{{ url('society/setting') }}/" + id, function(data) {

$('#setting_id').val(data.id);
$('#maintenance_late_fee').val(data.maintenance_late_fee);
$('#late_fee_type').val(data.late_fee_type);
$('#grace_days').val(data.grace_days);

$('#settingModal').modal('show');
});
}

$('#settingForm').submit(function(e) {
e.preventDefault();

let id = $('#setting_id').val();

$.ajax({
url: "{{ url('society/setting') }}/" + id + "/maintainance",
type: "PUT",
data: $(this).serialize(),
success: function(response) {

$('#settingSuccess')
.removeClass('d-none')
.text(response.message);

setTimeout(() => {
$('#settingModal').modal('hide');
location.reload(); // optional
}, 1000);
},
error: function(xhr) {
alert('Validation error!');
}
});
});

@endsection