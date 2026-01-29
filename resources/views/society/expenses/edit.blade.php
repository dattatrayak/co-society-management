@extends('society.layout.master')
@section('content')
<div class="container-fluid px-4">
    @include('society.layout.site_header')

    <div class="row">

        <div class="col-xl-12">
            <form action="{{ route('society.maintenance.update', $maintenance->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row border border-primary rounded m-2 p-3">
                    <div class="col-12">
                        <h3>Society Information</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Flat</label>
                            <input type="text"
                                class="form-control"
                                value="{{ $maintenance->flat->flat_no }}"
                                disabled>
                        </div>

                        <div class="col-md-3">
                            <label>Year</label>
                            <input type="number"
                                class="form-control"
                                value="{{ $maintenance->year }}"
                                disabled>
                        </div>

                        <div class="col-md-3">
                            <label>Month</label>
                            <input type="text"
                                class="form-control"
                                value="{{ date('F', mktime(0,0,0,$maintenance->month,1)) }}"
                                disabled>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label>Amount</label>
                            <input type="number"
                                name="amount"
                                class="form-control"
                                value="{{ $maintenance->amount }}"
                                required>
                        </div>

                        <div class="col-md-4">
                            <label>Payment Mode</label>
                            <select name="payment_mode" class="form-control">
                                @foreach(['online','cash','cheque','dd'] as $mode)
                                <option value="{{ $mode }}"
                                    {{ $maintenance->payment_mode === $mode ? 'selected' : '' }}>
                                    {{ ucfirst($mode) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Cheque / DD No</label>
                            <input type="text"
                                name="check_no"
                                class="form-control"
                                value="{{ $maintenance->check_no }}">
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <label>Online Payment Screenshot</label>
                        <input type="file"
                            name="attachment"
                            class="form-control"
                            accept="image/*">
                    </div>
                    @if($maintenance->attachment)
                    <div class="mt-3">
                        <label>Payment Screenshot</label><br>

                        <a href="{{ asset('storage/maintenance/' . $maintenance->attachment) }}"
                            target="_blank">
                            <img src="{{ asset('storage/maintenance/' . $maintenance->attachment) }}"
                                class="img-thumbnail"
                                style="max-width:150px">
                        </a>
                    </div>
                    @endif
                    <div class="mt-3">
                        <label>Note</label>
                        <textarea name="note"
                            class="form-control">{{ $maintenance->note }}</textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary mt-3">Add</button>
                    </div>

                </div>


            </form>
        </div>
    </div>
</div>
@endsection