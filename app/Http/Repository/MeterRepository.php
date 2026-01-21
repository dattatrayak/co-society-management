<?php


namespace App\Http\Repository;

use App\Models\ElectricityMeter;
use Illuminate\Support\Facades\DB;

class MeterRepository
{

   public function getMetterForBuilding($societyId)
   {
      return ElectricityMeter::where('society_id', $societyId)
         ->orderby("name", "ASC")->get();
   }
}
