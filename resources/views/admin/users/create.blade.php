@extends('admin.layout.master')

@section('content')

    <div class="container-fluid px-4">
        @include('admin.layout.site_header')

        <div class="row">
            <div class="col-xl-8">

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="row">

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="user_type_id">User Type</label>
                        <select name="user_type_id" id="user_type_id" class="form-control" required>
                            <option value="">Select User Type</option>
                            @foreach ($societyUserTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Create User</button>
                </form>
            </div>
        </div>
    </div>
@endsection
