<form action="{{ $action }}" method="POST">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="form-group">
        <label for="society_id">Society</label>
        <select name="society_id" id="society_id" class="form-control" required>
            <option value="">Select Society</option>
            @foreach($societies as $society)
                <option value="{{ $society->id }}" {{ $member && $member->society_id == $society->id ? 'selected' : '' }}>
                    {{ $society->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="building_id">Building</label>
        <select name="building_id" id="building_id" class="form-control" required>
            <option value="">Select Building</option>
            @foreach($buildings as $building)
                <option value="{{ $building->id }}" {{ $member && $member->building_id == $building->id ? 'selected' : '' }}>
                    {{ $building->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ $member->name ?? old('name') }}" required>
    </div>

    <div class="form-group">
        <label for="date_of_birth">Date of Birth</label>
        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ $member->date_of_birth ?? old('date_of_birth') }}" required>
    </div>

    <div class="form-group">
        <label for="permanent_address">Permanent Address</label>
        <textarea name="permanent_address" id="permanent_address" class="form-control" required>{{ $member->permanent_address ?? old('permanent_address') }}</textarea>
    </div>

    <div class="form-group">
        <label for="flat_no">Flat No</label>
        <input type="text" name="flat_no" id="flat_no" class="form-control" value="{{ $member->flat_no ?? old('flat_no') }}" required>
    </div>

    <div class="form-group">
        <label for="flat_type">Flat Type</label>
        <select name="flat_type" id="flat_type" class="form-control" required>
            <option value="1 BHK" {{ $member && $member->flat_type == '1 BHK' ? 'selected' : '' }}>1 BHK</option>
            <option value="2 BHK" {{ $member && $member->flat_type == '2 BHK' ? 'selected' : '' }}>2 BHK</option>
            <option value="3 BHK" {{ $member && $member->flat_type == '3 BHK' ? 'selected' : '' }}>3 BHK</option>
            <option value="1 RK" {{ $member && $member->flat_type == '1 RK' ? 'selected' : '' }}>1 RK</option>
        </select>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="{{ $member->email ?? old('email') }}" required>
    </div>

    <div class="form-group">
        <label for="mobile">Mobile</label>
        <input type="text" name="mobile" id="mobile" class="form-control" value="{{ $member->mobile ?? old('mobile') }}" required>
    </div>

    <div class="form-group">
        <label for="gender">Gender</label>
        <select name="gender" id="gender" class="form-control">
            <option value="">Select Gender</option>
            <option value="Male" {{ $member && $member->gender == 'Male' ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ $member && $member->gender == 'Female' ? 'selected' : '' }}>Female</option>
            <option value="Other" {{ $member && $member->gender == 'Other' ? 'selected' : '' }}>Other</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
