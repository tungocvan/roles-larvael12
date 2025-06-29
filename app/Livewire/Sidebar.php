<?php

namespace App\Livewire;

use Livewire\Component;

class Sidebar extends Component
{
    public $menu = [];

    public function mount()
    {
        $json = file_get_contents(resource_path('data/menuSidebar.json'));
        $this->menu = json_decode($json, true);
    }

    public function render()
    {
        return view('livewire.sidebar');
    }
}
