<?php

namespace App\Http\Controllers\Society;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class DashbaordSocietyController extends Controller
{
    /**
     *used to show dashboard on load
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function dashboard()
    {
        return view('society.dashboard.index');
    }
}
