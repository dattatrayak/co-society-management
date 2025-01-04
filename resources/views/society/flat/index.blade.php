@extends('society.layout.master')

@section('content')
<div class="container-fluid px-4">
    @include('society.layout.site_header')

    
    <div class="row">
        <div class="col-xl-12">
            <form method="GET" action="{{  route('society.flat.index')  }}" class="mb-3">
                <div class="row">
                    <!-- Search -->
                    <div class="col-md-4">
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search Flat No or Description"
                            value="{{ request('search') }}">
                    </div>

                    <!-- Building Filter -->
                    <div class="col-md-3">
                        <select name="building_id" class="form-control">
                            <option value="">Select Building</option>
                            @foreach($buildings as $building)
                                <option value="{{ $building->id }}" {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                    {{ $building->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Flat Type Filter -->
                    <div class="col-md-3">
                        <select name="flat_type" class="form-control">
                            <option value="">Select Flat Type</option>
                            @foreach($societyFlatType as $flatType)
                                <option value="{{ $flatType->id }}" {{ request('flat_type') == $flatType->id ? 'selected' : '' }}>
                                    {{ $flatType->type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search Button -->
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </div>
            </form>
            <a href="{{ route('society.flat.create') }}" class="btn btn-primary float-end">Create new Flat</a>

            <form method="GET" action="{{ route('society.flat.index') }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Flat No">
                <button type="submit">Search</button>
            </form>
            <table class="table">
                <thead>
                    <tr>
                        <th> <input type="checkbox" name="selectId" id="selectId" /></th>
                        <th>Name</th>
                        <th>Building Name</th>
                        <th>Flat Type</th>
                        <th>Floor No</th>
                        <th>Maintenance</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($flats as $flat)
                        <tr>
                            <td><input type="checkbox" name="id[]" id="{{ $flat->id }}" /></td>
                            <td>{{ $flat->flat_no }}</td>
                            <td>{{ $flat->building->name }}</td>
                            <td>{{ $flat->flatType->name }}</td>
                            <td>{{ $flat->floor_number }}</td>
                            <td>{{ $flat->maintance_per_month }}</td>
                            <td>
                                <a href="{{ route('society.flat.edit', $flat) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('society.flat.destroy', $flat) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Pagination Links -->
        <div class="d-flex justify-content-center">
            {{ $flats->links('vendor.pagination.bootstrap-5')  }}
        </div>
        </div>
    </div>
</div>
@endsection
