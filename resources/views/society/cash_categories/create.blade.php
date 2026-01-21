@extends('society.layout.master')

@section('content')
<div class="container-fluid px-4">
    @include('society.layout.site_header')

    <div class="row">
        <div class="col-xl-12">

            <form method="POST"
                action="{{ isset($category)
            ? route('society.expencess-type.update',$category->id)
            : route('society.expencess-type.store') }}">

                @csrf
                @isset($category) @method('PUT') @endisset

                 <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
                    <label>Name</label>
                    <input type="text" name="name"
                        value="{{ $category->name ?? '' }}"
                        class="form-control">
                </div>
                 <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
                    <label>Type</label>
                    <select name="type" class="form-control">
                        <option value="income"
                            {{ (isset($category) && $category->type == 'income') ? 'selected' : '' }}>
                            Income
                        </option>
                        <option value="expense"
                            {{ (isset($category) && $category->type == 'expense') ? 'selected' : '' }}>
                            Expense
                        </option>
                    </select>
                </div>
                <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
                    <label>Description</label>
                    <textarea name="description" class="form-control">
                    {{ $category->description ?? '' }}
                    </textarea>
                </div>
                <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
                    <label>
                        <input type="checkbox" name="is_active" value="1"
                            {{ (isset($category) && $category->is_active) ? 'checked' : '' }}>
                        Active
                    </label>
                </div>
                <button class="btn btn-success">
                    {{ isset($category) ? 'Update' : 'Save' }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection