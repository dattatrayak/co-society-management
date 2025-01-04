<?php 
namespace App\View\Components;

use Illuminate\View\Component;

class SocietyMemberForm extends Component
{
    public $action;
    public $method;
    public $societies;
    public $buildings; 
    public $member;

    public function __construct($action, $method = 'POST', $societies, $buildings, $member = null)
    {
        $this->action = $action;
        $this->method = $method;
        $this->societies = $societies;
        $this->buildings = $buildings; 
        $this->member = $member;
    }

    public function render()
    {
        return view('components.society-member-form');
    }
}
