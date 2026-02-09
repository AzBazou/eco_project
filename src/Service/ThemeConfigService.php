<?php

namespace App\Service;

/**
 * Theme Configuration Service
 */
class ThemeConfigService
{
    private array $config = [
        'site_title' => 'EcoSpot',
        'site_description' => 'Ecology and Environment Theme',
        'archive_layout' => 'layout-1',
        'enable_preloader' => false,
        'enable_footer' => true,
        'logo' => null,
        'header_image' => null,
        'posts_per_page' => 10,
    ];

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        $this->config[$key] = $value;
    }

    public function all(): array
    {
        return $this->config;
    }
}
