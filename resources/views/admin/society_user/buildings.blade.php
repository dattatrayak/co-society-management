 @extends('admin.layout.popup')

 @section('content')
 <div class="modal-header">
   <h5 class="modal-title">Add New Building</h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
 </div>

 <div class="modal-body">
   <form id="buildingForm">
     @csrf
     <div id="buildings-container">
       <div class="row border-bottom border-2 pb-2 m-2 building-block" data-index="1">
         <div class="col-md-4">
           <div class="form-floating mb-3">
             <input type="text" name="building_name[]" id="building_name1"
               class="form-control" value="{{ old('building_name1') }}"
               placeholder="Building Name..">
             <label for="building_name1">Building Name</label>
           </div>
         </div>
         <div class="col-md-2">
           <div class="form-floating mb-3">
             <input type="number" name="floor[]" id="floor1"
               class="form-control" value="{{ old('floor1') }}"
               placeholder="Floor count start" required>
             <label for="floor1" title="Floor count start">Floor count start</label>
           </div>
         </div>
         <div class="col-md-2">
           <div class="form-floating mb-3">
             <input type="number" name="flat_no_start[]" id="flat_no_start1"
               class="form-control" value="{{ old('flat_no_start1') }}"
               placeholder="Floor count start" required>
             <label for="lift_count" title="Floor count start">Floor count start</label>
           </div>
         </div>
         <div class="col-md-2">
           <div class="form-floating mb-3">
             <input type="number" name="flat_per_floor[]" id="flat_per_floor1"
               class="form-control" value="{{ old('flat_per_floor1') }}"
               placeholder="Flat per Floor" required>
             <label for="lift_count" title="Flat per Floor">Flat per Floor</label>
           </div>
         </div>
         <div class="col-md-2">
           <div class="form-floating mb-3">
             <input type="number" name="cctv[]" id="cctv1" class="form-control"
               value="{{ old('cctv1') }}" placeholder="CCTV Count">
             <label for="lift_count" title="CCTV Count">CCTV Count</label>
           </div>
         </div>
         <div class="col-md-2">
           <div class="form-floating mb-3">
             <input type="number" name="lift[]" id="lift1" class="form-control"
               value="{{ old('lift1') }}" placeholder="Lift Count">
             <label for="lift_count" title="Lift Count">Lift Count</label>
           </div>
         </div>
         <div class="col-md-2">
           <div class="form-floating mb-3">
             <input type="number" name="water_tank[]" id="water_tank1" class="form-control"
               value="{{ old('water_tank1') }}" placeholder="Water tank Count">
             <label for="lift_count" title="Water tank Count">Water tank Count</label>
           </div>
         </div>
         <div class="col-md-2">
           <div class="form-floating mb-3">
             <select class="form-select" id="floatingSelect" name="society_flat_types_id[]" id="society_flat_types_id"
               class="form-control">
               <option value="" disabled selected>Select Flat Type</option>
               @foreach ($societyFlatType as $key => $value)
               <option value="{{ $key }}">{{ $value }}</option>
               @endforeach
             </select>
             <label for="floatingSelect">Select flat type</label>
           </div>
         </div>
         <div class="col-md-2 mt-3 d-flex justify-content-start align-items-start pt-2">
           <button type="button" class="btn btn-primary me-2 add-building" id="add_building1" title="Add New">
             <i class="fa fa-plus-square"></i>
           </button>
           <button type="button" class="btn btn-danger del-building" id="del_building1" aria-label="Delete">
             <i class="fa fa-trash"></i>
           </button>
         </div>
       </div>
     </div>
     <div class="row ">
       <div class="form-group">
         <button type="submit" class="btn btn-primary mt-3">Add</button>
       </div>
     </div>
   </form>
 </div>
 @endsection

 @section('scripts')
 <script>
   (function() {
     const MAX_BUILDINGS = 10;

     // Helper functions (kept local)
     function hasValue(input) {
       return input && String(input.value).trim().length > 0;
     }

     function isPositiveInteger(val) {
       return /^[1-9]\d*$/.test(String(val).trim());
     }

     function isNonNegativeInteger(val) {
       return /^\d+$/.test(String(val).trim());
     }

     function clearInvalid(input) {
       if (!input) return;
       input.classList.remove('is-invalid');
       const fb = input.closest('.form-group')?.querySelector('.invalid-feedback');
       if (fb) fb.remove();
     }

     function setInvalid(input, message) {
       if (!input) return;
       input.classList.add('is-invalid');
       const group = input.closest('.form-group');
       if (group) {
         const existing = group.querySelector('.invalid-feedback');
         if (!existing) {
           const fb = document.createElement('div');
           fb.className = 'invalid-feedback';
           fb.textContent = message;
           group.appendChild(fb);
         }
       }
       input.focus();
     }

     function validateRequiredFields(block) {
       const buildingName = block.querySelector('input[name="building_name[]"]');
       const totalFloor = block.querySelector('input[name="floor[]"]');
       const floorStart = block.querySelector('input[name="flat_no_start[]"]');
       const floorFlat = block.querySelector('input[name="flat_per_floor[]"]');

       let valid = true;
       [buildingName, totalFloor, floorStart, floorFlat].forEach(clearInvalid);

       if (!hasValue(buildingName)) {
         setInvalid(buildingName, 'Building Name is required.');
         valid = false;
       }

       if (!hasValue(totalFloor)) {
         setInvalid(totalFloor, 'Total floor is required.');
         valid = false;
       } else if (!isPositiveInteger(totalFloor.value)) {
         setInvalid(totalFloor, 'Total floor must be a positive integer.');
         valid = false;
       }

       if (!hasValue(floorStart)) {
         setInvalid(floorStart, 'Floor start is required.');
         valid = false;
       } else if (!isNonNegativeInteger(floorStart.value)) {
         setInvalid(floorStart, 'Floor start must be a non-negative integer.');
         valid = false;
       }

       if (!hasValue(floorFlat)) {
         setInvalid(floorFlat, 'Floor flat is required.');
         valid = false;
       } else if (!isPositiveInteger(floorFlat.value)) {
         setInvalid(floorFlat, 'Floor flat must be a positive integer.');
         valid = false;
       }

       return valid;
     }

     // Utility to update IDs & clear values on a cloned block
     function updateIdsAndClear(block, index) {
       const map = [{
           base: 'building_name',
           selector: 'input[name="building_name[]"]'
         },
         {
           base: 'floor',
           selector: 'input[name="floor[]"]'
         },
         {
           base: 'flat_no_start',
           selector: 'input[name="flat_no_start[]"]'
         },
         {
           base: 'flat_per_floor',
           selector: 'input[name="flat_per_floor[]"]'
         },
         {
           base: 'cctv',
           selector: 'input[name="cctv[]"]'
         },
         {
           base: 'lift',
           selector: 'input[name="lift[]"]'
         },
         {
           base: 'water_tank',
           selector: 'input[name="water_tank[]"]'
         },
         {
           base: 'society_flat_types_id',
           selector: 'input[name="society_flat_types_id[]"]'
         },
       ];

       map.forEach(f => {
         const input = block.querySelector(f.selector);
         if (input) {
           input.id = `${f.base}${index}`;
           input.value = '';
           input.classList.remove('is-invalid');
           const label = input.closest('.form-group')?.querySelector('label');
           if (label) label.setAttribute('for', input.id);
           const fb = input.closest('.form-group')?.querySelector('.invalid-feedback');
           if (fb) fb.remove();
         }
       });

       const addBtn = block.querySelector('.add-building');
       const delBtn = block.querySelector('.del-building');
       if (addBtn) addBtn.id = `add_building${index}`;
       if (delBtn) delBtn.id = `del_building${index}`;
     }

     function renumberAllBlocks(container) {
       const blocks = Array.from(container.querySelectorAll('.building-block'));
       blocks.forEach((block, idx) => {
         const index = idx + 1;
         block.dataset.index = String(index);

         const mapping = [{
             base: 'building_name',
             selector: 'input[name="building_name[]"]'
           },
           {
             base: 'floor',
             selector: 'input[name="floor[]"]'
           },
           {
             base: 'flat_no_start',
             selector: 'input[name="flat_no_start[]"]'
           },
           {
             base: 'flat_per_floor',
             selector: 'input[name="flat_per_floor[]"]'
           },
           {
             base: 'cctv',
             selector: 'input[name="cctv[]"]'
           },
           {
             base: 'lift',
             selector: 'input[name="lift[]"]'
           },
           {
             base: 'water_tank',
             selector: 'input[name="water_tank[]"]'
           },
           {
             base: 'society_flat_types_id',
             selector: 'input[name="society_flat_types_id[]"]'
           },
         ];

         mapping.forEach(f => {
           const input = block.querySelector(f.selector);
           if (input) {
             input.id = `${f.base}${index}`;
             const label = input.closest('.form-group')?.querySelector('label');
             if (label) label.setAttribute('for', input.id);
           }
         });

         const addBtn = block.querySelector('.add-building');
         const delBtn = block.querySelector('.del-building');
         if (addBtn) addBtn.id = `add_building${index}`;
         if (delBtn) delBtn.id = `del_building${index}`;
       });
     }

     function ensureOnlyLastHasAdd(container) {
       const blocks = Array.from(container.querySelectorAll('.building-block'));
       if (!blocks.length) return;
       blocks.forEach((block, idx) => {
         const addBtn = block.querySelector('.add-building');
         if (!addBtn) return;
         if (idx === blocks.length - 1) {
           addBtn.style.display = '';
           addBtn.disabled = blocks.length >= MAX_BUILDINGS;
           addBtn.title = addBtn.disabled ? `Maximum ${MAX_BUILDINGS} buildings reached` : 'Add New';
         } else {
           addBtn.style.display = 'none';
         }
       });
     }

     function toggleAddButtons(container, disable) {
       const blocks = Array.from(container.querySelectorAll('.building-block'));
       const last = blocks[blocks.length - 1];
       if (!last) return;
       const addBtn = last.querySelector('.add-building');
       if (addBtn) {
         addBtn.disabled = !!disable;
         addBtn.title = disable ? `Maximum ${MAX_BUILDINGS} buildings reached` : 'Add New';
       }
     }

     // DELEGATED EVENT: add / delete buttons (works for AJAX-inserted elements)
     $(document).on('click', '#buildings-container .add-building', function(e) {
       e.preventDefault();
       const container = document.getElementById('buildings-container');
       const currentBlock = this.closest('.building-block');
       if (!currentBlock) return;

       if (!validateRequiredFields(currentBlock)) return;

       const blocks = container.querySelectorAll('.building-block');
       if (blocks.length >= MAX_BUILDINGS) {
         alert(`You can add a maximum of ${MAX_BUILDINGS} buildings.`);
         toggleAddButtons(container, true);
         return;
       }

       const nextIndex = blocks.length + 1;
       const clone = currentBlock.cloneNode(true);
       clone.dataset.index = String(nextIndex);
       updateIdsAndClear(clone, nextIndex);
       currentBlock.insertAdjacentElement('afterend', clone);
       ensureOnlyLastHasAdd(container);
       toggleAddButtons(container, nextIndex >= MAX_BUILDINGS);
     });

     $(document).on('click', '#buildings-container .del-building', function(e) {
       e.preventDefault();
       const container = document.getElementById('buildings-container');
       const block = this.closest('.building-block');
       const blocks = container.querySelectorAll('.building-block');
       if (blocks.length === 1) {
         alert('You must have at least one building.');
         return;
       }
       block.remove();
       renumberAllBlocks(container);
       ensureOnlyLastHasAdd(container);
       const count = container.querySelectorAll('.building-block').length;
       toggleAddButtons(container, count >= MAX_BUILDINGS);
     });

     // DELEGATED EVENT: form submit (works for dynamically injected form)
     $(document).on('submit', '#buildingForm', function(e) {
       e.preventDefault();
       const $form = $(this);

       // optionally do a full validation here for all blocks before sending
       const container = document.getElementById('buildings-container');
       const allBlocks = container.querySelectorAll('.building-block');
       for (const b of allBlocks) {
         if (!validateRequiredFields(b)) {
           return;
         }
       }

       $.ajax({
         url: "{{ route('admin.society-user.buildings.store', $society ?? '') }}", // adjust if needed
         type: "POST",
         data: $form.serialize(),
         success: function(resp) {
           console.log(resp);
           if (resp && resp.success) {
             $("#buildingModal").modal('hide');
             setTimeout(() => {
               location.reload();
             }, 300);
           } else {
             // handle validation errors from server if you return them
             alert(resp.message || 'Something went wrong.');
           }
         },
         error: function(xhr) {
           // example: show first validation error
           if (xhr.responseJSON && xhr.responseJSON.errors) {
             const errors = xhr.responseJSON.errors;
             const firstKey = Object.keys(errors)[0];
             alert(errors[firstKey][0]);
           } else {
             alert('Request failed. Check console for details.');
             console.error(xhr);
           }
         }
       });
     });

     // When the popup is first injected, ensure initial UI is correct.
     // If the HTML is injected and script runs, do a quick ensure:
     $(document).on('shown.bs.modal', '#buildingModal', function() {
       const container = document.getElementById('buildings-container');
       if (container) ensureOnlyLastHasAdd(container);
     });

   })(); // IIFE
 </script>
 @endsection