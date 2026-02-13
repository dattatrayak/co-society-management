<?php


namespace App\Http\Repository;

use App\Models\MaintenanceRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MaintenanceRepository
{
    private $userId = null;
    public function __construct()
    {
        $societyUser = Auth::guard('society_user')->user();
        $this->userId = $societyUser->id;
    }
    public function baseQuery(Request $request)
    {

        $search = $request->input('search', '');
        $building_id = $request->input('building_id', null);
        $flat_type = $request->input('flat_type', null);

        return MaintenanceRecord::with(['flat.flatType', 'building'])
            ->where('society_id', $this->userId)
            ->when($request->year, function ($q) use ($request) {
                $q->where('year', $request->year);
            })
            ->when($request->month, function ($q) use ($request) {
                $q->where('month', $request->month);
            })
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($search, function ($q) use ($search) {
                $q->whereHas('flat', function ($q) use ($search) {
                    $q->where('flat_no', 'LIKE', "%{$search}%");
                });
            })
            ->when($building_id, function ($q) use ($building_id) {
                $q->where('building_id', $building_id);
            })
            ->when($flat_type, function ($q) use ($flat_type) {
                $q->whereHas('flat', function ($q) use ($flat_type) {
                    $q->where('society_flat_type_id', $flat_type);
                });
            })
            ->orderByDesc('year')
            ->orderByDesc('month');
    }
    /**
     * Get paginated records for listing
     */
    public function paginate(Request $request, int $perPage = 15)
    {
        return $this->baseQuery($request)
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Get records for Excel export (NO pagination)
     */
    public function getForExport(Request $request)
    {
        return $this->baseQuery($request)->get();
    }
}
