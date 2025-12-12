@extends('admin.layout.master')

@section('content')
<div class="container-fluid px-4">
    @include('admin.layout.site_header')

    <div class="row">
        <div class="col-xl-12">
            <a href="{{ route('admin.society-user.create') }}" class="btn btn-primary">Create new society</a>
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
                            <a href="#" class="btn btn-success  add-building-btn"  data-society-id="{{ $society->id }}">
                                <i class="fa fa-building" aria-hidden="true"></i>Add 
                            </a>
                            <a href="{{ route('admin.society-user.edit', $society) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.society-user.destroy', $society) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="buildingModal" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content" id="buildingModalContent">
    </div>
  </div>
</div>

@endsection

@section('script') 
$(document).on('click', '.add-building-btn', function () {
    let societyId = $(this).data('society-id');

    $.get("/admin/society-user/" + societyId + "/buildings", function (data) {
        $("#buildingModalContent").html(data);
        $("#buildingModal").modal('show');
    });
});
@endsection
