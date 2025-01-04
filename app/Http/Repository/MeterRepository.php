<?php


namespace App\Http\Repository;

use App\Models\ElectricityMeter; 
use Illuminate\Support\Facades\DB;

class MeterRepository
{
   // private $userId = null;
   // public function __construct()
   // {
   //    $societyUser = Auth::guard('society_user')->user();
   //    $this->userId = $societyUser->id;
   // }
   public function getMetterForBuilding($societyId)
   {
      return ElectricityMeter::where('society_id', $societyId)
         ->orderby("name", "ASC")->get();
   }
}
