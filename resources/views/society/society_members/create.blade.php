@extends('society.layout.master')

@section('content')
    <div class="container-fluid px-4">
        @include('society.layout.site_header')

        <div class="row">
            <div class="col-xl-12"> 
                <x-society-member-form
                        :action="route('society.member.store')"
                        method="POST" 
                        :societies="$societies" 
                        :buildings="$buildings"
                        :flats="$flats"
                    />
            </div>
        </div>
    </div>
@endsection

@section('addJs') 
<link rel="stylesheet" href="{{  asset('theme/css/select2.min.css') }}" rel="stylesheet"> 
<script src="{{ asset('theme/js/select2.min.js') }}" ></script> 
    
@endsection
@vite(['resources/css/app.css', 'resources/js/app.js'])
@section('scriptDockReady')
 $('.select2-multiple').select2({
                placeholder: "Select",
                allowClear: true
            });
        $('#building_id').on('change', function () {
            var buildingId = $(this).val();
            $('#flat_no').empty(); // Clear the flat dropdown

            if (buildingId) {
                $.ajax({
                    url: '<?=config('app.url')?>society/flats-by-building/' + buildingId,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#flat_no').append('<option value="">---Select Flat---</option>');
                        $.each(data, function (key, flat) {
                            $('#flat_no').append('<option value="' + flat.id + '">' + flat.flat_no + '( ' + flat.flatType + ')</option>');
                        });
                    },
                    error: function () {
                        alert('Error fetching flats. Please try again.');
                    }
                });
            } else {
                $('#flat_no').append('<option value="">Select Flat</option>');
            }
        });
@endsection