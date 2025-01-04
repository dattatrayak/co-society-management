@extends('society.layout.master')

@section('content')
    <div class="container-fluid px-4">
        @include('society.layout.site_header')

        <div class="row">

            <div class="col-xl-12">
                <form action="{{ route('society.building.update', $building->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row border border-primary rounded m-2 p-3">
                        <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                            <div class="form-group">
                                <label for="email">Building Name</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name', $building->name) }}" required>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 pt-3">
                                    <div class="form-group">
                                        <label for="flat_count">Total Flat</label>
                                        <input type="text" name="flat_count" id="flat_count"
                                            value="{{ old('flat_count', $building->flat_count) }}" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 pt-3">
                                    <div class="form-group">
                                        <label for="floor">Floor</label>
                                        <input type="text" name="floor" id="floor"
                                            value="{{ old('floor', $building->floor) }}" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 pt-3">
                                    <div class="form-group">
                                        <label for="flat_no_start">Flat No Start</label>
                                        <input type="text" name="flat_no_start" id="flat_no_start"
                                            value="{{ old('flat_no_start', $building->flat_no_start) }}"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 pt-3">
                                    <div class="form-group">
                                        <label for="flat_per_floor">Per Floor Flat</label>
                                        <input type="text" name="flat_per_floor" id="flat_per_floor"
                                            value="{{ old('flat_per_floor', $building->flat_per_floor) }}"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 pt-3">
                                    <div class="form-group">
                                        <label for="society_flat_types_id">Flat Type</label>
                                        <select name="society_flat_types_id" id="society_flat_types_id"
                                            class="form-control">
                                            <option value="" disabled selected>Select Building Type</option>
                                            @foreach ($societyFlatType as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 pt-3">
                                    <div class="form-group">
                                        <label for="cctv">cctv</label>
                                        <input type="text" name="cctv" id="cctv"
                                            value="{{ old('cctv', $building->flat_per_floor) }}" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 pt-3">
                                    <div class="form-group">
                                        <label for="water_tank">Water tank</label>
                                        <input type="text" name="water_tank" id="water_tank"
                                            value="{{ old('water_tank', $building->flat_per_floor) }}" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 pt-3">
                                    <div class="form-group">
                                        <label for="maintance_per_month">Maintance</label>
                                        <input type="text" name="maintance_per_month" id="maintance_per_month"
                                            placeholder="99999.99"
                                            value="{{ old('maintance_per_month', $building->maintance_per_month) }}"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-8 col-md-8 col-lg-9 col-xl-9">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 pt-3">
                                    <div class="row border-upload">
                                        <div class="col-6 col-sm-6 col-md-6">
                                            <div class="upload_dropZone">
                                                click to upload (Society image)
                                                <i class="fas fa-upload upload_icon"></i>
                                                <input type="file" name="building_img" id="fileInput1" hidden>
                                            </div>
                                        </div>
                                        <div class="col-6  col-sm-6 col-md-6">
                                            <div class="upload_gallery" id="previewGallery1">
                                                @if ($building->building_img)
                                                    <div class="img-container">
                                                        <img src="{{ asset('storage/uploads/society/building/' . $building->building_img) }}"
                                                            width="180">
                                                    </div>
                                                @else
                                                    <div class="show-img-wrapper"> Preview image...</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 pt-3">
                                    <div class="row border-upload">
                                        <div class="col-6">
                                            <div class="upload_dropZone">
                                                click to upload (Society floor plan)
                                                <i class="fas fa-upload upload_icon"></i>
                                                <input type="file" name="floor_plan" id="fileInput2" hidden>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="upload_gallery" id="previewGallery2">
                                                @if ($building->floor_plan)
                                                    <div class="img-container">
                                                        <img src="{{ asset('storage/uploads/society/floor_plan/' . $building->floor_plan) }}"
                                                            width="180">
                                                    </div>
                                                @else
                                                    <div class="show-img-wrapper"> Preview image...</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 pt-3">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary mt-3">Add</button>
                                    </div>
                                </div>
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
