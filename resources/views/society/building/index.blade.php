@extends('society.layout.master')

@section('content')
<div class="container-fluid px-4">
    @include('society.layout.site_header')

    <div class="row">
        <div class="col-xl-12">
            <a href="{{ route('society.building.create') }}" class="btn btn-primary">Add new society building</a>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($buildings as $building)
                        <tr>
                            <td>{{ $building->name }}</td>
                            <td>{{ $building->email }}</td>
                            <td>{{ $building->address }}</td>
                            <td>
                                <a href="{{ route('society.building.edit', $building->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('society.building.destroy', $building->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
