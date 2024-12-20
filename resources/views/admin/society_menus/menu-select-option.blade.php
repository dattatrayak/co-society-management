<option value="{{ $menu->id }}">
    {{ str_repeat('-', $level * 2) }} {{ $menu->name }}
</option>

@if ($menu->children->isNotEmpty())
    @foreach ($menu->children as $child)
        @include('admin.menus.menu-select-option', ['menu' => $child, 'level' => $level + 1])
    @endforeach
@endif
