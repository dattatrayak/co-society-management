@extends('admin.layout.master')

@section('content')
    <div class="container-fluid px-4">
        @include('admin.layout.site_header')

        <div class="container">
            <form action="{{ route('admin.user-types-permissions.store') }}" method="POST">
                @csrf
                <div class="row ">
                    <div class="col-sm-8 col-sm-offset-4 search-wrap">
                        <div class="form-group">
                            <label for="user_type">Select User Type</label>
                            <select id="user_type" name="user_type" class="form-control">
                                <option value="">Select User Type</option>
                                @foreach ($userTypes as $userType)
                                    <option value="{{ $userType->id }}">{{ $userType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="menu-heading-h1">
                    <div class="menu-header">
                        <strong><input type="checkbox" name="permissionsAll" id="permissionsAll"> Dashboard</strong>
                        <div class="permissions">
                            <div>
                                <button type="submit" class="btn btn-success">Save</button>
                                <button type="reset" class="btn btn-success">Reset</button>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="row">
                    @foreach ($menus as $menu)
                        @include('admin.user_types_permission.menu-item', ['menu' => $menu])
                    @endforeach
                </div>
            </form>
        </div>

    </div>
@endsection
@section('script')
    $(".checkbox").on('click', function (event) {
    $(this).parent().parent().next().find('input[type="checkbox"]').prop('checked', event.target.checked);
    });
    document.getElementById('permissionsAll').addEventListener('change',(event) => {
    $('.checkbox').prop('checked', event.target.checked);
    $('.checkbox-access').prop('checked', event.target.checked);
    });
    document.getElementById('user_type').addEventListener('change', function () {
    const userTypeId = this.value;
    if (!userTypeId) {
    document.getElementById('permissionsForm').style.display = 'none';
    return;
    }

    fetchPermissions(userTypeId);
    });

    function fetchPermissions(userTypeId) {
    fetch("{{ route('admin.permissions.get') }}", {
    method: 'POST',
    headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ user_type_id: userTypeId })
    })
    .then(response => response.json())
    .then(data => {

    if(typeof data.menus === 'object' && Object.keys(data.menus).length == 0){
    $('.checkbox').prop('checked', false);
    $('.checkbox-access').prop('checked', false);
    }
    data.menus.forEach(menu => {
    $("#permissions-"+menu.menu_id+"-view").prop('checked', menu.view);
    $("#permissions-"+menu.menu_id+"-add").prop('checked', menu.add);
    $("#permissions-"+menu.menu_id+"-delete").prop('checked', menu.delete);
    $("#permissions-"+menu.menu_id+"-view_own").prop('checked', menu.view_own);
    $("#permissions-"+menu.menu_id+"-delete_own").prop('checked', menu.delete_own);
    $("#permissions-"+menu.menu_id+"-delete_other").prop('checked', menu.delete_other);
    });

    })
    .catch(error => console.error('Error fetching permissions:', error));
    }
@endsection
