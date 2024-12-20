@extends('society.layout.master')

@section('content')
<div class="container-fluid px-4">
    @include('society.layout.site_header')

    <div class="row">
        <div class="col-xl-12">
            <a href="{{ route('society.society-user.create') }}" class="btn btn-primary">Create new society</a>
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
                    @foreach ($societies as $society)
                        <tr>
                            <td>{{ $society->name }}</td>
                            <td>{{ $society->email }}</td>
                            <td>{{ $society->address }}</td>
                            <td>
                                <a href="{{ route('society.society-menus.edit', $society) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('society.society-user.destroy', $society) }}" method="POST" style="display:inline;">
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
