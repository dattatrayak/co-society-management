@extends('society.layout.master')

@section('content')
    <div class="container-fluid px-4">
        @include('society.layout.site_header')

        <div class="row">
            <div class="col-xl-12">
                @include('society.layout.error')

                <form action="{{ route('society.society-user.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <h2>User Add</h2>
                    <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                        <div class="progress-fill"></div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="title">Name</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name') }}" placeholder="User full name">
                            </div>
                            <div class="form-group">
                                <label for="title">Permanent Address:</label>
                                <textarea class="form-control" name="address" id="address" rows="3" placeholder="Enter address"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-6 col-sm-4 col-md-4 col-lg-4 col-xl-4 ">
                                    <div class="form-group">
                                        <label for="building_no">Building Name</label>
                                        <input type="text" name="building_no" id="building_no" class="form-control"
                                            value="{{ old('building_no') }}"  placeholder="Select building">
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4 col-md-4 col-lg-4 col-xl-4 ">
                                    <div class="form-group">
                                        <label for="name">Floor No</label>
                                        <input type="text" name="floor_no" id="floor_no" class="form-control"
                                            value="{{ old('floor_no') }}" placeholder="Enter Floor No">
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4 col-md-4 col-lg-4 col-xl-4 ">
                                    <div class="form-group">
                                        <label for="reg_no">Flat no</label>
                                        <input type="text" name="flat_no" id="flat_no" class="form-control"
                                            value="{{ old('flat_no') }}" required>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-4 col-md-4 col-lg-4 col-xl-4 ">
                                    <div class="form-group">
                                        <label for="reg_no">Flat Type</label>
                                        <select name="flat_type_id" id="flat_type_id" class="form-control" required>
                                            <option value="">Select User Type</option>
                                            @foreach ($societyUserTypes as $type)
                                                <option value="{{ $type->id }}"
                                                    {{ $type->name == 'Manager' ? 'selected' : '' }}>{{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="title">Mobile </label>
                                <input type="text" name="mobile" id="mobile" class="form-control"
                                    value="{{ old('mobile') }}" placeholder="Enter mobile..">
                            </div>
                            <div class="form-group">
                                <label for="title">Email</label>
                                <input type="text" name="email" id="email" class="form-control"
                                    value="{{ old('email') }}" placeholder="Enter email address..">
                            </div>
                            <div class="form-group">
                                <label for="title">Password:</label>
                                <input type="text" name="password" id="password" class="form-control"
                                    placeholder="Enter yourUID no..">
                            </div>
                            <div class="form-group">
                                <label for="title">Confirm Password:</label>
                                <input type="text" name="confirm_password" id="confirm_password" class="form-control"
                                    placeholder="Enter confirm password..">
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
