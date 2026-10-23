<?php

namespace App\View\Components\Dashboard\Sidebar;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LinkWithChild extends Component
{
    public $title;
    public $icon;
    public $children;
    public $permissions;

    public function __construct($title, $icon, $permissions = [], $children = [])
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->children = $children;
        $this->permissions = $permissions;
    }

    public function render(): View
    {
        return view('components.dashboard.sidebar.link-with-child', [
            'icon' => $this->icon,
            'title' => $this->title,
            'children' => $this->children,
            'permissions' => $this->permissions,
        ]);
    }
}
