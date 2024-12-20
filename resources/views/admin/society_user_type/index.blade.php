@extends('admin.layout.master')

@section('content')
<div class="container-fluid px-4">
    @include('admin.layout.site_header')

    <div class="row">
        <div class="col-xl-12">
            <a href="{{ route('admin.society-user-types.create') }}" class="btn btn-primary">Create Menu</a>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($societyUserTypes as $type)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $type->name }}</td>
                            <td>
                                <a href="{{ route('admin.society-user-types.edit', $type->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
