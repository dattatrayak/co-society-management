@extends('society.layout.master')

@section('content')
<div class="container-fluid px-4">
    @include('society.layout.site_header')

    <div class="row">
        <div class="col-xl-12 mb-4">
            <a href="{{ route('society.expencess-type.create') }}" class="btn btn-primary">Create New Type</a>
        </div>
        <div class="col-xl-12"> 
            <table class="table table-bordered">
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                @foreach($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>
                        <span class="badge bg-{{ $category->type == 'income' ? 'success' : 'danger' }}">
                            {{ ucfirst($category->type) }}
                        </span>
                    </td>
                    <td>{{ $category->is_active ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <a href="{{ route('society.expencess-type.edit',$category->id) }}"
                            class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('society.expencess-type.destroy',$category->id) }}"
                            method="POST" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </table>
            <div class="d-flex justify-content-center">
                {{ $categories->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection