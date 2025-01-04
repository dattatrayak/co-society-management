 <div class="container">
     <form action="{{ $action }}" method="POST">
         @csrf
         @if ($method !== 'POST')
             @method($method)
         @endif
         <div class="row">
             <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
                 <div class="form-group">
                     <label for="society_id">Society</label>
                     <select name="society_id" id="society_id" class="form-control" required>
                         <option value="">---Select Society---</option>
                         @foreach ($societies as $society)
                             <option value="{{ $society->id }}" selected>
                                 {{ $society->name }}
                             </option>
                         @endforeach
                     </select>
                 </div>
             </div>
             <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
                 <div class="form-group">
                     <label for="building_id">Building</label>
                     <select name="building_id" id="building_id" class="form-control" required>
                         <option value="">Select Building</option>
                         @foreach ($buildings as $building)
                             <option value="{{ $building->id }}"
                                 {{ $member && $member->building_id == $building->id ? 'selected' : '' }}>
                                 {{ $building->name }}
                             </option>
                         @endforeach
                     </select>
                 </div>
             </div>
             <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 ">
                 <div class="form-group">
                     <label for="flat_no">Flat No</label>
                     <select class="select2-multiple form-control" name="flat_no[]" multiple="multiple" id="flat_no">
                         <option value="">---select Flat No---</option>
                     </select>
                 </div>
             </div>
             <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
                 <div class="form-group">
                     <label for="name">Name</label>
                     <input type="text" name="name" id="name" class="form-control"
                         value="{{ $member->name ?? old('name') }}" required>
                 </div>
             </div>
             <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
                 <div class="form-group">
                     <label for="date_of_birth">Date of Birth</label>
                     <input type="date" name="date_of_birth" id="date_of_birth" class="form-control"
                         value="{{ $member->date_of_birth ?? old('date_of_birth') }}" >
                 </div>
             </div>
             <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 ">
                 <div class="form-group">
                     <label for="permanent_address">Permanent Address</label>
                     <textarea name="permanent_address" id="permanent_address" class="form-control" required>{{ $member->permanent_address ?? old('permanent_address') }}</textarea>
                 </div>
             </div>

             <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
                 <div class="form-group">
                     <label for="email">Email</label>
                     <input type="email" name="email" id="email" class="form-control"
                         value="{{ $member->email ?? old('email') }}" required>
                 </div>
             </div>
             <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
                 <div class="form-group">
                     <label for="mobile">Mobile</label>
                     <input type="text" name="mobile" id="mobile" class="form-control"
                         value="{{ $member->mobile ?? old('mobile') }}" required>
                 </div>
             </div>
              <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
                 <div class="form-group">
                     <label for="uid">UID</label>
                     <input type="text" name="uid" id="uid" class="form-control"
                         value="{{ $member->uid ?? old('uid') }}" >
                 </div>
             </div>
              <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3 ">
                 <div class="form-group">
                     <label for="pan">PAN</label>
                     <input type="text" name="pan" id="pan" class="form-control"
                         value="{{ $member->pan ?? old('pan') }}" >
                 </div>
             </div>
             <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                 <div class="form-group">
                     <label for="gender">Gender</label>
                     <div>
                         <input type="radio" name="gender" id="male" value="Male"
                             {{ $member && $member->gender == 'Male' ? 'checked' : '' }}>
                         <label for="male">Male</label>
                     
                         <input type="radio" name="gender" id="female" value="Female"
                             {{ $member && $member->gender == 'Female' ? 'checked' : '' }}>
                         <label for="female">Female</label>
                     </div>
                      
                 </div>
             </div>
             <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-2 ">
                 <button type="submit" class="btn btn-primary">Submit</button>
             </div>
         </div>

     </form>
 </div>
