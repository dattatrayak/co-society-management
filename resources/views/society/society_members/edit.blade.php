@extends('society.layout.master')

@section('content')
<div class="container-fluid px-4">
    @include('society.layout.site_header')


    <div class="row">
        <div class="col-xl-12">
            <x-society-member-form
                :action="route('society.member.update', $member->id)"
                method="PUT"
                :societies="$societies"
                :buildings="$buildings"
                :member="$member"
                :flats="$flats"
                :selectedFlats="$selectedFlats"
                :buttonText="'Update'" />
        </div>
    </div>
</div>
@endsection
@section('addJs')

<link rel="stylesheet" href="{{  asset('theme/css/select2.min.css') }}" rel="stylesheet">
<script src="{{ asset('theme/js/select2.min.js') }}"></script>
@endsection
@section('scriptDockReady')
$('.select2-multiple').select2({
placeholder: "Select",
allowClear: true
});
$('#flat_no').select2('destroy').select2();

@endsection