@extends('society.layout.master')

@section('content')
    <div class="container-fluid px-4">
        @include('society.layout.site_header')

        <div class="row">
                <div class="col-xl-12">
                    <a href="{{ route('society.meter.create') }}" class="btn btn-primary">Create New Electricity Meter</a>
               </div>
               <div class="col-xl-12">
                <form method="GET" action="{{ route('society.flat.index') }}" class="mb-3">
                    <div class="row mt-2">
                        <!-- Search -->
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control"
                                placeholder="Meter Number" value="{{ request('search') }}">
                        </div>

                        <!-- Building Filter -->
                        <div class="col-md-3">
                            <select name="building_id" class="form-control">
                                <option value="">Select Building</option>
                                @foreach ($buildings as $building)
                                    <option value="{{ $building->id }}"
                                        {{ request('building_id') == $building->id ? 'selected' : '' }}>
                                        {{ $building->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div> 
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                    </div>
                </form>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Building Name</th>
                            <th>Meter Number</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($meters as $meter)
                            <tr>
                                <td>{{ $meter->building->name }}</td>
                                <td>{{ $meter->electricity_meter }}</td>
                                <td>
                                    <a href="{{ route('society.meter.edit', $meter) }}" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('society.meter.destroy', $meter) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE') 
                                         <button type="submit" class="btn btn-danger" id="delete-button">Delete</button>
                                     
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $meters->links('vendor.pagination.bootstrap-5') }}
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