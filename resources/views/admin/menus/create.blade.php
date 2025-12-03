@extends('admin.layout.master')

@section('content')
    <style>
        .select-show {
            font-family: 'FontAwesome', 'sans-serif';
        }
    </style>
    <div class="container-fluid px-4">
        @include('admin.layout.site_header')

        <div class="row">
            <div class="col-xl-8">

                <form method="POST" action="{{ route('admin.menus.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Page Heading</label>
                        <input type="text" name="page_heading" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="url">URL</label>
                        <input type="text" name="url" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="icon">Icon</label>
                        <div class="input-group">
                            <select class="form-control select-show" id="icon" name="icon" required>
                                <option value="">--- Select Icon ---</option>
                                @foreach (getIconList() as $iconClass => $iconName)
                                    <option value="{{ $iconClass }}">
                                        <i class="{{ $iconClass }}"></i> <?php echo $iconName; ?>
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <small class="form-text text-muted">Choose an icon from Font Awesome.</small>
                    </div>

                    <div class="form-group">
                        <label for="parent_id">Parent Menu</label>
                        <select name="parent_id" id="parent_id" class="form-control">
                            <option value="">No Parent</option>
                            @foreach ($menus as $menu)
                                @include('admin.menus.menu-select-option', ['menu' => $menu, 'level' => 0])
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Page Sub Heading</label>
                        <input type="text" name="sub_heading" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="order">Order</label>
                        <input type="number" name="order" class="form-control" required>
                    </div>
                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
