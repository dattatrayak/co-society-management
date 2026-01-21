@extends('society.layout.master')
@section('content')
<div class="container-fluid px-4">
    @include('society.layout.site_header')

    <div class="row">

        <div class="col-xl-12">
            <form method="POST"
                action="{{ isset($maintenance)
            ? route('society.setting.update',$maintenance->id)
            : route('society.setting.store') }}">

                @csrf
                @isset($maintenance) @method('PUT') @endisset

                <div class="row mt-3">
                    <div class="col-md-4">
                        <label>Society Flat type:</label>
                        <select name="society_flat_type_id" class="form-control mb-2">
                            @foreach($flatTypes as $type)
                            <option value="{{ $type->id }}"
                                {{ isset($maintenance) && $maintenance->society_flat_type_id == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label>Maintainance Amount:</label>
                        <input type="number" name="maintenance_amount"
                            value="{{ $maintenance->maintenance_amount ?? '' }}"
                            class="form-control mb-2" placeholder="Maintenance Amount">
                    </div>
                </div>
                <button class="btn btn-success">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection