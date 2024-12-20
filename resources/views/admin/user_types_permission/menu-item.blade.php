<div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
    <div class="col-sm-12 menu-heading"><strong><input type="checkbox"
                name="permissions[{{ $menu->id }}]" class="checkbox"> {{ $menu->name }}
        </strong></div>
    <div class="col-sm-12 menu-listing">
        <label> <input type="checkbox" name="permissions[{{ $menu->id }}][view]" id="permissions-{{ $menu->id }}-view"
                class="checkbox-access"
                {{ optional($menu->userTypePermissions->where('user_type_id', $userType->id)->first())->view ? 'checked' : '' }}>
            View </label>
        <label>
            <input type="checkbox" name="permissions[{{ $menu->id }}][add]" id="permissions-{{ $menu->id }}-add"
                class="checkbox-access"
                {{ optional($menu->userTypePermissions->where('user_type_id', $userType->id)->first())->add ? 'checked' : '' }}>
            Add
        </label>
        <label>
            <input type="checkbox" name="permissions[{{ $menu->id }}][delete]" id="permissions-{{ $menu->id }}-delete"
                class="checkbox-access"
                {{ optional($menu->userTypePermissions->where('user_type_id', $userType->id)->first())->delete ? 'checked' : '' }}>
            Delete
        </label>
        <label>
            <input type="checkbox" name="permissions[{{ $menu->id }}][view_own]" id="permissions-{{ $menu->id }}-view_own"
                class="checkbox-access"
                {{ optional($menu->userTypePermissions->where('user_type_id', $userType->id)->first())->view_own ? 'checked' : '' }}>
            View Own
        </label>
        <label>
            <input type="checkbox" name="permissions[{{ $menu->id }}][delete_own]" id="permissions-{{ $menu->id }}-delete_own"
                class="checkbox-access"
                {{ optional($menu->userTypePermissions->where('user_type_id', $userType->id)->first())->delete_own ? 'checked' : '' }}>
            Delete Own
        </label>
        <label>
            <input type="checkbox" name="permissions[{{ $menu->id }}][delete_other]" id="permissions-{{ $menu->id }}-delete_other"
                class="checkbox-access"
                {{ optional($menu->userTypePermissions->where('user_type_id', $userType->id)->first())->delete_other ? 'checked' : '' }}>
            Delete Other
        </label>
    </div>
</div>

@if ($menu->children->isNotEmpty())

    @foreach ($menu->children as $child)
        @include('admin.user_types_permission.menu-item', ['menu' => $child])
    @endforeach

    {{-- @else
        <div class="no-children">No Child Menus</div> --}}
@endif
