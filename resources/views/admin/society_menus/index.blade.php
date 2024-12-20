@extends('admin.layout.master')

@section('content')
<div class="container-fluid px-4">
    @include('admin.layout.site_header')


    <div class="row">
        <div class="col-xl-12">
            <a href="{{ route('admin.society-menus.create') }}" class="btn btn-primary">Create Menu</a>

            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>URL</th>
                        <th>Parent</th>
                        <th>Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($societyMenu as $menu)
                        <tr>
                            <td>{{ $menu->name }}</td>
                            <td>{{ $menu->url }}</td>
                            <td>{{ $menu->parent ? $menu->parent->name : 'None' }}</td>
                            <td>{{ $menu->order }}</td>
                            <td>
                                <a href="{{ route('admin.society-menus.edit', $menu->id) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('admin.society-menus.destroy', $menu->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @if ($menu->children->isNotEmpty())
                            @foreach ($menu->children as $child)
                                <tr>
                                    <td>-- {{ $child->name }}</td>
                                    <td>{{ $child->url }}</td>
                                    <td>{{ $menu->name }}</td>
                                    <td>{{ $child->order }}</td>
                                    <td>
                                        <a href="{{ route('admin.society-menus.edit', $child->id) }}" class="btn btn-warning">Edit</a>
                                        <form action="{{ route('admin.society-menus.destroy', $child->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
