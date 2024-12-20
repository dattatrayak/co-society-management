@extends('admin.layout.master')

@section('content')
    <style>
        .select-show {
            font-family: 'FontAwesome', 'sans-serif';
        }
    </style>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Menu Management</h1>
        @include('admin.layout.site_header')

        <div class="row">
            <div class="col-xl-8">

                <form method="POST" action="{{ route('admin.society-user-types.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">User Type Name</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ $userType->name ?? '' }}" required>
                    </div>
                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
