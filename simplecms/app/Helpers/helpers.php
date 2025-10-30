<?php

if (!function_exists('formatBytes')) {
    /**
     * Format bytes to human readable format
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('get_menu')) {
    /**
     * Get menu by location
     *
     * @param string $location
     * @return \App\Models\Menu|null
     */
    function get_menu($location)
    {
        return \App\Models\Menu::with(['menuItems' => function ($query) {
            $query->whereNull('parent_id')->with('children')->orderBy('order');
        }])
            ->where('location', $location)
            ->where('is_active', true)
            ->first();
    }
}

if (!function_exists('render_menu')) {
    /**
     * Render menu HTML
     *
     * @param string $location
     * @param string $ulClass
     * @param string $liClass
     * @param string $aClass
     * @return string
     */
    function render_menu($location, $ulClass = '', $liClass = '', $aClass = '')
    {
        $menu = get_menu($location);

        if (!$menu || !$menu->menuItems || $menu->menuItems->count() === 0) {
            return '';
        }

        $html = '<ul class="' . $ulClass . '">';

        foreach ($menu->menuItems as $item) {
            $html .= render_menu_item($item, $liClass, $aClass);
        }

        $html .= '</ul>';

        return $html;
    }
}

if (!function_exists('render_menu_item')) {
    /**
     * Render single menu item with children
     *
     * @param \App\Models\MenuItem $item
     * @param string $liClass
     * @param string $aClass
     * @return string
     */
    function render_menu_item($item, $liClass = '', $aClass = '')
    {
        $hasChildren = $item->children && $item->children->count() > 0;
        $activeClass = request()->is(trim($item->url, '/')) ? ' active' : '';

        $html = '<li class="' . $liClass . ($hasChildren ? ' has-dropdown' : '') . $activeClass . '">';

        // Get URL based on item type
        $url = $item->getUrl();

        $html .= '<a href="' . $url . '" class="' . $aClass . '" target="' . $item->target . '">';
        $html .= htmlspecialchars($item->title);
        if ($hasChildren) {
            $html .= ' <i class="fas fa-chevron-down ml-1"></i>';
        }
        $html .= '</a>';

        // Render children
        if ($hasChildren) {
            $html .= '<ul class="dropdown-menu">';
            foreach ($item->children as $child) {
                $html .= render_menu_item($child, $liClass . ' dropdown-item', $aClass);
            }
            $html .= '</ul>';
        }

        $html .= '</li>';

        return $html;
    }
}
