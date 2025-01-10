<?php
class Widgets_Config {
    public static function get_widget_list() {
        return [
            'site-logo' => [
                'title' => __('Site Logo', 'panda-hf'),
                'icon' => 'eicon-site-logo',
                'categories' => ['panda-hf'],
            ],
            'nav-menu' => [
                'title' => __('Navigation Menu', 'panda-hf'),
                'icon' => 'eicon-nav-menu',
                'categories' => ['panda-hf'],
            ]
        ];
    }
}
