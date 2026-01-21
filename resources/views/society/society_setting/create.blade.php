@extends('society.layout.master')

@section('content')
<div class="container-fluid px-4">
    @include('society.layout.site_header')

    <div class="row">
        <div class="col-xl-12">

            <form method="POST"
                action="{{ route('society.settings.update', $setting->id) }}">
                @csrf
                @method('PUT')

                <td>
                    <input type="number" step="0.01"
                        name="maintenance_late_fee"
                        value="{{ $setting->maintenance_late_fee }}"
                        class="form-control">
                </td>

                <td>
                    <select name="late_fee_type" class="form-control">
                        <option value="fixed"
                            {{ $setting->late_fee_type == 'fixed' ? 'selected' : '' }}>
                            Fixed
                        </option>
                        <option value="percentage"
                            {{ $setting->late_fee_type == 'percentage' ? 'selected' : '' }}>
                            Percentage
                        </option>
                    </select>
                </td>

                <td>
                    <input type="number"
                        name="grace_days"
                        value="{{ $setting->grace_days }}"
                        class="form-control">
                </td>

                <td>
                    <button class="btn btn-primary btn-sm">
                        Update
                    </button>
                </td>
            </form>
        </div>
    </div>
</div>


@endsection
