@extends('society.layout.master')

@section('content')
    <div class="container-fluid px-4">
        @include('society.layout.site_header')

        <div class="row">
            <div class="col-xl-12">
                <a href="{{ route('society.member.create') }}" class="btn btn-primary">Create new society</a>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Society</th>
                            <th>Building</th>
                            <th>Flat No</th>
                            <th>Flat Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $member)
                            <tr>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->society->name }}</td>
                                <td>{{ $member->building->name }}</td>
                                <td>{{ $member->flat_no }}</td>
                                <td>{{ $member->flat_type }}</td>
                                <td>
                                    <a href="{{ route('society.member.edit', $member->id) }}"
                                        class="btn btn-warning">Edit</a>
                                    <form action="{{ route('society.member.destroy', $member->id) }}" method="POST"
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
                 {{ $members->links() }}
            </div>
        </div>
    </div>
@endsection
