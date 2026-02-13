<?php


namespace App\Http\Repository;

use App\Models\SocietyMember;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SocietyMemberRepository
{
   private $userId = null;
   public function __construct()
   {
      $societyUser = Auth::guard('society_user')->user();
      $this->userId = $societyUser->id;
   }
   public function getSocietyMemberList()
   {
      return  SocietyMember::where('society_id', $this->userId)->get();
   }
}
