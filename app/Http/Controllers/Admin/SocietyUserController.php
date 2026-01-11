<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Repository\FlatRepository;
use App\Models\Building;
use App\Models\SocietyFlatType;
use App\Models\SocietyUser;
use App\Models\SocietyUserType;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SocietyUserController extends Controller
{
    protected $flatRepository;
    public function __construct(FlatRepository $flatRepository)
    {
        $this->flatRepository = $flatRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $societies = SocietyUser::all();
        return view('admin.society_user.index', compact('societies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $societyUserTypes = SocietyUserType::all();
        return view('admin.society_user.create', compact('societyUserTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'reg_no' => 'nullable|numeric',
            'reg_year' => 'nullable|digits:4|integer|min:1900|max:' . date('Y'),
            'address' => 'required|string|max:500',
            'desc' => 'nullable|string|max:1000',
            'mobile_no' => 'required|regex:/^\+?[0-9]{10,15}$/|unique:society_users,mobile_no',

            'email' => 'required|email|unique:society_users,email',
            'password' => 'required|confirmed|min:8',
            //'building_count' => 'required|integer|min:1',
            'lift_count' => 'nullable|integer|min:1',
            //'meter_count' => 'required|integer|min:1',
            'logo' => 'nullable|image|mimes:jpg,png,jpeg,svg|max:2048',
            'file2' => 'nullable|image|mimes:jpg,png,jpeg,svg|max:2048',
        ]);
        $updatedRequest = $request->all();
        //dd($updatedRequest);
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/society/logo', $fileName, 'public'); // Stored in storage/app/public/uploads
            $updatedRequest['logo'] = $fileName;
        }

        if ($request->hasFile('society_image')) {
            $file = $request->file('society_image');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/society/img', $fileName, 'public'); // Stored in storage/app/public/uploads
            // $request->merge(['society_image' => $fileName]);
            $updatedRequest['society_image'] = $fileName;
        }

        //$allData->password = Hash::make($request->password);
        SocietyUser::create($updatedRequest);

        return redirect()->route('admin.society-user.index')->with('success', 'Society created successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SocietyUser $societyUser)
    {
        $societyUserTypes = SocietyUserType::all();
        return view('admin.society_user.edit', compact('societyUser', 'societyUserTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SocietyUser $societyUser)
    {
        $request->validate([
            'name' => 'required|unique:user_types,name,' . $societyUser->id,
        ]);


        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/society/logo', $fileName, 'public'); // Stored in storage/app/public/uploads
            // $request->merge(['logo' => $fileName]);
            $societyUser->logo = $fileName;
            if ($societyUser->logo) {
                $imagePath = Storage::url('public/uploads/society/logo/' . $societyUser->logo);
                File::delete($imagePath);
            }
        }

        if ($request->hasFile('society_image')) {
            $file = $request->file('society_image');
            $fileName = time() . '-' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads/society/img', $fileName, 'public'); // Stored in storage/app/public/uploads
            //$request->merge(['' => $fileName]);
            $societyUser->society_image = $fileName;
            if ($societyUser->society_image) {
                $imagePath = Storage::url('public/uploads/society/logo/' . $societyUser->society_image);
                File::delete($imagePath);
            }
        }
        if (! $request->input('password'))
            $request->request->remove('password');



        $societyUser->update($request->all());

        return redirect()->route('admin.society-user.index')->with('success', 'User type updated successfully.');
    }

    public function buildingsForm(SocietyUser $society)
    {
        $societyFlatType = SocietyFlatType::pluck('name', 'id');
        $society->load('buildings');
        return view('admin.society_user.buildings', compact('society', 'societyFlatType'));
    }

    public function buildingsStore(Request $request, SocietyUser $society)
    {
         try {

        //dump($society);
        $data = $this->validateBuildings($request);
        if ($data) {
            $requestData = $request->all();
            // dd(  $society );
            if (isset($requestData['building_name'])) {
                $index = 0;
                foreach ($requestData['building_name'] as $value) {
                    $insertBuilding = [];
                    $insertBuilding['society_id'] = $society->id;
                    $insertBuilding['name'] = $requestData['building_name'][$index];
                    //$insertBuilding['flat_index'] = $requestData['flat_index'][$index];
                    $insertBuilding['floor'] = $requestData['floor'][$index];
                    $insertBuilding['flat_no_start'] = $requestData['flat_no_start'][$index];
                    $insertBuilding['flat_per_floor'] = $requestData['flat_per_floor'][$index];
                    $insertBuilding['cctv'] = $requestData['cctv'][$index];
                    $insertBuilding['lift'] = $requestData['lift'][$index];
                    $insertBuilding['lift'] = $requestData['lift'][$index];
                    $insertBuilding['water_tank'] = $requestData['water_tank'][$index];
                    $exists = Building::where('society_id', $insertBuilding['society_id'])
                            ->where('name', $insertBuilding['name'])
                            ->exists();

                        if ($exists) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Building name already exists for this society. Please select another building name.'
                            ]);
                        }
                    $building = Building::create($insertBuilding);
                    //insert all flats of society
                    $flatData = [];
                   
                    $flatData['society_flat_types_id'] = $requestData['society_flat_types_id'][$index];
                    $this->flatRepository->insertOrUpdateFlat($building, $flatData);
                    $index++;
                }
            }
        }

        return response()->json([
            'success' =>  true,
            'message' => 'Buildings saved successfully.',
        ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function buildingsUpdate(Request $request, SocietyUser $societyUser)
    {
        // $data = $this->validateBuildings($request);
        // dd($request->getAll());
        // // DB::transaction(function () use ($society, $data) {
        // //     // Replace semantics: clear and recreate
        // //     $society->buildings()->delete();
        // //     foreach ($this->zipBuildings($data) as $row) {
        // //         $society->buildings()->create($row);
        // //     }
        // // });

        // return redirect()->route('admin.society_user.create', $societyUser)
        //     ->with('success', 'Buildings updated successfully.');
    }

    /** Validation + helpers (same as shared earlier) */
    protected function validateBuildings(Request $request): array
    {
        return $request->validate([
            'building_name'   => ['required', 'array', 'min:1', 'max:10'],
            'building_name.*' => ['required', 'string', 'max:255'],
            'floor'           => ['required', 'array'],
            'floor.*'         => ['required', 'integer', 'min:1'],
            'flat_no_start'   => ['required', 'array'],
            'flat_no_start.*' => ['required', 'integer', 'min:0'],
            'flat_per_floor'  => ['required', 'array'],
            'flat_per_floor.*' => ['required', 'integer', 'min:1'],
            'cctv'            => ['nullable', 'array'],
            'cctv.*'          => ['nullable', 'integer', 'min:0'],
            'lift'            => ['nullable', 'array'],
            'lift.*'          => ['nullable', 'integer', 'min:0'],
            'water_tank'      => ['nullable', 'array'],
            'water_tank.*'    => ['nullable', 'integer', 'min:0'],
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocietyUser $societyUser)
    {
        // $userType->delete();

        // return redirect()->route('user-types.index')->with('success', 'User type deleted successfully.');
    }
}
