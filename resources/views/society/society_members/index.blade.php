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
                            <th>Email</th>
                            <th>Flat</th> 
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $member)
                            <tr>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->email }}</td>
                                <td>
                                    <ul class="list-group">
                                        @foreach ($member->flats as $flat)
                                            <li>{{ $flat->flat_no }} ({{ $flat->flatType->name }}) ({{ $flat->building->name }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                               
                                <td>
                                    <a href="{{ route('society.member.edit', $member->id) }}" class="btn btn-warning">Edit</a>
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
