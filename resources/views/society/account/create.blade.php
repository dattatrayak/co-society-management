@extends('society.layout.master')

@section('content')
    <div class="container-fluid px-4">
        @include('society.layout.site_header')

        <div class="row">
            <div class="col-xl-12">

                <form action="{{ route('society.meter.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row border border-primary rounded m-2 p-3">
                        <div class="col-12">
                            <h3>Society Information</h3>
                        </div>
                        <div class="form-group">
                            <label for="email">Metter name</label>
                            <input type="text" name="electricity_meter" id="electricity_meter" class="form-control"
                                value="{{ old('electricity_meter') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="building_id">Building</label>
                            <select name="building_id" id="building_id" class="form-control" required>
                                <option value="">Select a building</option>
                                @foreach ($buildings as $building)
                                    <option value="{{ $building->id }}"
                                        {{ isset($electricityMeter) && $electricityMeter->building_id == $building->id ? 'selected' : '' }}>
                                        {{ $building->name }}
                                    </option>
                                @endforeach
                            </select>
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
