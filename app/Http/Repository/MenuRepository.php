<?php


namespace App\Http\Repository;
use Illuminate\Support\Facades\DB;

class MenuRepository
{
   public function getMenuList(){
    return  DB::table('menus')->select("*")->orderBy('id')->get();
   }
}
