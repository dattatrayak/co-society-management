@extends('society.layout.master')

@section('content')
    <div class="container-fluid px-4">
        @include('society.layout.site_header')

        <div class="row">
            <div class="col-xl-12"> 

                <form action="{{ route('society.flat.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row border border-primary rounded m-2 p-3">
                        <div class="col-12">
                            <h3>Society Information</h3>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 ">
                            <div class="form-group">
                                <label for="flat_no">Flat No</label>
                                <input type="text" name="flat_no" id="flat_no" class="form-control"
                                    value="{{ old('flat_no') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="building_id">Building number</label>
                                <select name="building_id" id="building_id" class="form-control" required>
                                    <option value="">---Building Name---</option>
                                    @foreach ($buildings as $building)
                                        <option value="{{ $building->id }}"
                                            {{ $building->id == old('building_id') ? 'selected' : null }}>
                                            {{ $building->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="society_flat_types_id">Flat Type</label>
                                <select name="society_flat_types_id" id="society_flat_types_id" class="form-control"
                                    required>
                                    <option value="">---Flat Type---</option>
                                    @foreach ($societyFlatType as $flatType)
                                        <option value="{{ $flatType->id }}"
                                            {{ $flatType->id == old('society_flat_types_id') ? 'selected' : null }}>
                                            {{ $flatType->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="floor_number">Floor No</label>
                                <input type="text" name="floor_number" id="floor_number" class="form-control"
                                    value="{{ old('floor_number') }}" required>
                            </div> 
                            <div class="form-group">
                                <label for="desc">Description</label>
                                <textarea type="text" name="desc" id="desc" class="form-control">{{ old('desc') }}</textarea>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary mt-3">Add Flat</button> <button type="reset"
                                    class="btn btn-danger mt-3">reset</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
 
