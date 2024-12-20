@extends('society.layout.master')
@section('content')
    <div class="container-fluid px-4">
        @include('society.layout.site_header')

        <div class="row">

            <div class="col-xl-12">
                @include('society.layout.error')
                <form action="{{ route('society.society-user.update', $societyUser->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row border border-primary rounded m-2 p-3">
                        <div class="col-12">
                            <h3>Society Information</h3>
                        </div>
                        <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
                            <div class="form-group">
                                <label for="title">Site Title</label>
                                <input type="text" name="title" id="title" class="form-control"
                                    value="{{ old('title', $societyUser->title) }}" required>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
                            <div class="form-group">
                                <label for="name">Society Name</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name', $societyUser->name) }}" required>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
                            <div class="form-group">
                                <label for="reg_no">Registoration no</label>
                                <input type="text" name="reg_no" id="reg_no" class="form-control"
                                    value="{{ old('reg_no', $societyUser->reg_no) }}" required>
                            </div>
                        </div>

                        <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
                            <div class="form-group">
                                <label for="reg_year">Registoration year</label>
                                <input type="text" name="reg_year" id="reg_year" class="form-control"
                                    value="{{ old('reg_year', $societyUser->reg_year) }}" required>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
                            <div class="form-group">
                                <label for="mobile_no">Mobile no</label>
                                <input type="text" name="mobile_no" id="mobile_no" class="form-control"
                                    value="{{ old('mobile_no', $societyUser->mobile_no) }}" required>
                            </div>
                        </div>

                        <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
                            <div class="form-group">
                                <label for="user_type_id">User Type</label>
                                <select name="user_type_id" id="user_type_id" class="form-control" readonly required>
                                    <option value="">Select User Type</option>
                                    @foreach ($societyUserTypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ $type->name == 'Manager' ? 'selected' : '' }}>{{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea name="address" id="address" class="form-control" required>{{ old('address', $societyUser->address) }}</textarea>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control">{{ old('description', $societyUser->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row border border-primary rounded m-2 p-3">
                        <div class="col-12">
                            <h3>Society Setting</h3>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ old('email', $societyUser->email) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" >
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" >
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6   ">
                                    <div class="form-group">
                                        <label for="building_count">Total Building</label>
                                        <input type="text" name="building_count" id="building_count" class="form-control"
                                            value="{{ old('building_count', $societyUser->building_count) }}" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6  ">
                                    <div class="form-group">
                                        <label for="lift_count">Total Lift</label>
                                        <input type="text" name="lift_count" id="lift_count" class="form-control"
                                            value="{{ old('lift_count', $societyUser->lift_count) }}" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6   ">
                                    <div class="form-group">
                                        <label for="meter_count">Total Eletricity Mitter</label>
                                        <input type="text" name="meter_count" id="meter_count" class="form-control"
                                            value="{{ old('lift_count', $societyUser->meter_count) }}" required>
                                    </div>
                                </div>
                                {{-- <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6  ">
                                    <div class="form-group">
                                        <label for="lift_countdd">Total Lift</label>
                                        <input type="text" name="lift_countdd" id="lift_countdd" class="form-control"
                                            value="{{ old('lift_countdd',$societyUser->metter_count) }}" required>
                                    </div>
                                </div> --}}
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 pt-3">
                                <div class="row border-upload">
                                    <div class="col-6">
                                        <div class="upload_dropZone">
                                            Drag & Drop files here <br />or <br /> click to upload (Input 1)
                                            <i class="fas fa-upload upload_icon"></i>
                                            <input type="file" name="logo" id="fileInput1" hidden>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="upload_gallery" id="previewGallery1">

                                                @if ($societyUser->logo)
                                                <div class="img-container">
                                                    <img src="{{ asset('storage/uploads/society/logo/'.$societyUser->logo) }}" width="200">
                                                    </div>
                                                    @else
                                                    <div class="show-img-wrapper"> Preview image...</div>
                                                    @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 pt-3">
                                <div class="row border-upload">
                                    <div class="col-6">
                                        <div class="upload_dropZone">
                                            Drag & Drop files here <br />or <br /> click to upload (Input 2)
                                            <i class="fas fa-upload upload_icon"></i>
                                            <input type="file" name="society_image" id="fileInput2" hidden>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="upload_gallery" id="previewGallery2">
                                                @if ($societyUser->society_image)
                                                <div class="img-container">
                                                    <img src="{{ asset('storage/uploads/society/img/'.$societyUser->society_image) }}" width="200">
                                              @else
                                              <div class="show-img-wrapper"> Preview image...</div>
                                              @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary mt-3">Add</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    document.addEventListener('DOMContentLoaded', function() {
    // Handle file input change for file1
    const allowedFileTypes = ['image/jpeg', 'image/png', 'image/svg+xml'];
    const fileInput1 = document.getElementById('fileInput1');
    const previewGallery1 = document.getElementById('previewGallery1');

    fileInput1.addEventListener('change', function(event) {
    previewImage(event.target.files, previewGallery1, fileInput1);
    });

    // Handle file input change for file2
    const fileInput2 = document.getElementById('fileInput2');
    const previewGallery2 = document.getElementById('previewGallery2');

    fileInput2.addEventListener('change', function(event) {
    previewImage(event.target.files, previewGallery2, fileInput2);
    });

    // Handle drop zone events (drag and drop)
    const dropZones = document.querySelectorAll('.upload_dropZone');
    dropZones.forEach(zone => {
    zone.addEventListener('dragover', function(event) {
    event.preventDefault();
    });

    zone.addEventListener('drop', function(event) {
    event.preventDefault();
    const files = event.dataTransfer.files;
    const input = zone.querySelector('input[type="file"]');
    input.files = files;
    const gallery = zone.closest('.border-upload').querySelector('.upload_gallery');
    previewImage(files, gallery, input);
    });

    // Trigger the file input when the upload zone is clicked
    zone.addEventListener('click', function() {
    const input = zone.querySelector('input[type="file"]');
    input.click();
    });
    });

    // Function to preview image files and add "Clear" button
    function previewImage(files, gallery, input) {
    gallery.innerHTML = ''; // Clear previous previews


    Array.from(files).forEach(file => {
    if (!allowedFileTypes.includes(file.type)) {
    alert(`allwoed only jpg,jpeg,png and svg file,\n File type not allowed: ${file.name}`);
    input.value = ''; // Clear invalid input
    return;
    }

    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function(e) {
    const imgContainer = document.createElement('div');
    imgContainer.className = 'img-container';

    const imgContainerRemove = document.createElement('div');
    imgContainerRemove.className = 'show-img-wrapper';
    imgContainerRemove.textContent = 'Preview image...';
    const img = document.createElement('img');
    img.src = e.target.result;
    img.style.width = '200px';
    img.style.height = '150px';
    img.style.objectFit = 'cover';

    const clearBtn = document.createElement('icon');
    //clearBtn.textContent = 'Clear';
    clearBtn.className = 'fa fa-trash text-danger ';
    clearBtn.style.marginTop = '5px';
    clearBtn.style.marginLeft = '10px';
    clearBtn.style.fontSize = '32px';
    clearBtn.style.cursor ='pointer';
    clearBtn.onclick = function() {
    imgContainer.remove(); // Remove the image container
    input.value = ''; // Clear the file input so the user can select a new file
    gallery.appendChild(imgContainerRemove);
    };

    imgContainer.appendChild(img);
    imgContainer.appendChild(clearBtn);
    gallery.appendChild(imgContainer);
    };
    });
    }
    });
@endsection