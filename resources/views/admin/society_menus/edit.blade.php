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
                <form method="POST" action="{{ route('admin.society-menus.update', $societyMenuEdit->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $societyMenuEdit->name }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="name">Page Heading</label>
                        <input type="text" name="page_heading" class="form-control"
                            value="{{ $societyMenuEdit->page_heading }}">
                    </div>
                    <div class="form-group">
                        <label for="url">URL</label>
                        <input type="text" name="url" class="form-control" value="{{ $societyMenuEdit->url }}"
                            disabled>
                    </div>
                    <div class="form-group">
                        <label for="icon">Icon</label>
                        <div class="input-group">
                            <select class="form-control select-show" id="icon" name="icon" required>
                                <option value="">--- Select Icon ---</option>
                                @foreach (getIconList() as $iconClass => $iconName)
                                    <option value="{{ $iconClass }}"
                                        {{ $iconClass == $societyMenuEdit->icon ? 'selected' : '' }}>
                                        <i class="{{ $iconClass }}"></i> <?php echo $iconName; ?>
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <small class="form-text text-muted">Choose an icon from Font Awesome.</small>
                    </div>
                    <div class="form-group">
                        <label for="parent_id">Parent Menu</label>
                        <select name="parent_id" class="form-control">
                            <option value="">None</option>
                            @foreach ($societyMenuList as $parent)
                                <option value="{{ $parent->id }}"
                                    {{ $societyMenuEdit->parent_id == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Page Sub Heading</label>
                        <input type="text" name="sub_heading" class="form-control"
                            value="{{ $societyMenuEdit->sub_heading }}">
                    </div>
                    <div class="form-group">
                        <label for="order">Order</label>
                        <input type="number" name="order" class="form-control" value="{{ $societyMenuEdit->order }}"
                            required>
                    </div>
                    <button type="submit" class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection
