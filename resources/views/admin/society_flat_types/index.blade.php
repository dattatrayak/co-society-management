@extends('admin.layout.master')

@section('content')
<div class="container-fluid px-4">
    @include('admin.layout.site_header')

    <div class="row">
        <div class="col-xl-12">
            <a href="{{ route('admin.society-flat-type.create') }}" class="btn btn-primary">Create Type</a>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($userTypes as $type)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $type->name }}</td>
                            <td>
                                <a href="{{ route('admin.society-flat-type.edit', $type->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <?php
                                // <form action="{{ route('admin.society-flat-types.destroy', $type->id) }}" method="POST" style="display: inline-block;">
                                //     @csrf
                                //     @method('DELETE')
                                //     <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                // </form>
                                ?>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
