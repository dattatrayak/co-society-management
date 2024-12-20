@extends('admin.layout.master')
<style>
    .select-show {
        font-family: 'FontAwesome', 'sans-serif';
    }
</style>
@section('content')
<div class="container-fluid px-4">
    @include('admin.layout.site_header')

    <div class="row">
        <div class="col-xl-12">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Password (leave blank to keep current)</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>

                <div class="form-group">
                    <label for="user_type_id">User Type</label>
                    <select name="user_type_id" id="user_type_id" class="form-control" required>
                        @foreach ($userTypes as $type)
                            <option value="{{ $type->id }}" {{ $user->user_type_id == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Update User</button>
            </form>
        </div>
    </div>
</div>
@endsection
