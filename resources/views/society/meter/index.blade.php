@extends('society.layout.master')

@section('content')
    <div class="container-fluid px-4">
        @include('society.layout.site_header')

        <div class="row">
            <div class="col-xl-12">
                <a href="{{ route('society.meter.create') }}" class="btn btn-primary">Create new society</a>
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
                                        <button class="btn btn-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
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
