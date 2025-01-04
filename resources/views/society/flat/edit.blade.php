@extends('society.layout.master')

@section('content')
    <div class="container-fluid px-4">
        @include('society.layout.site_header')

        <div class="row">

            <div class="col-xl-12"> 
                <form action="{{ route('society.flat.update', $flat->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row border border-primary rounded m-2 p-3">
                        <div class="col-12">
                            <h3>Society Information</h3>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 ">
                            <div class="form-group">
                                <label for="flat_no">Flat No</label>
                                <input type="text" name="flat_no" id="flat_no" class="form-control"
                                    value="{{ old('flat_no', $flat->flat_no) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="building_id">Building number</label>
                                <select name="building_id" id="building_id" class="form-control" required>
                                    <option value="">---Building Name---</option>
                                    @foreach ($buildings as $building)
                                        <option value="{{ $building->id }}"
                                            {{ $building->id == old('building_id', $flat->building_id) ? 'selected' : null }}>
                                            {{ $building->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="society_flat_types_id">Flat Type</label>
                                <select name="society_flat_types_id" id="society_flat_types_id" class="form-control"
                                    required>
                                    <option value="">---Flat Type---</option>
                                    @foreach ($societyFlatType as $flatType)
                                        <option value="{{ $flatType->id }}"
                                            {{ $flatType->id == old('society_flat_types_id', $flat->society_flat_types_id) ? 'selected' : null }}>
                                            {{ $flatType->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="floor_number">Floor No</label>
                                <input type="text" name="floor_number" id="floor_number" class="form-control"
                                    value="{{ old('floor_number', $flat->floor_number) }}" required>
                            </div>
                             <div class="form-group">
                                <label for="maintance_per_month">Maintance</label>
                                <input type="text" name="maintance_per_month" id="maintance_per_month"
                                    placeholder="99999.99"
                                    value="{{ old('maintance_per_month', $flat->maintance_per_month) }}"
                                    class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="desc">Description</label>
                                <textarea type="text" name="desc" id="desc" class="form-control">{{ old('desc', $flat->desc) }}</textarea>
                            </div>
                        </div>


                        <div class="col-xl-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary mt-3">Update Flat</button> <button
                                    type="reset" class="btn btn-danger mt-3">reset</button>
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
