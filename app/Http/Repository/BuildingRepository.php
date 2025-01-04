<?php


namespace App\Http\Repository;

use App\Models\Building; 
use Illuminate\Support\Facades\DB;

class BuildingRepository
{
   // private $userId = null;
   // public function __construct()
   // {
   //    $societyUser = Auth::guard('society_user')->user();
   //    $this->userId = $societyUser->id;
   // }
   public function getSocietyBuilding($societyId)
   {
      return Building::where('society_id', $societyId)
         ->orderby("name", "ASC")->get();

      // return Building::all();
   }
}
