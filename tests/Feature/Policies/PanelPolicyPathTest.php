<?php

declare(strict_types=1);

use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Filesystem\Filesystem;

afterEach(function () {
    Filament::setCurrentPanel(null);
});

it('keeps base policy path when panel policy path is disabled', function () {
    $panel = Panel::make()->id('admin');
    Filament::setCurrentPanel($panel);

    config()->set('filament-shield.policies.panel_path', false);
    config()->set('filament-shield.policies.path', app_path('Policies'));

    $path = Utils::getPolicyPath();

    expect($path)->toEndWith(DIRECTORY_SEPARATOR . 'Policies');
    expect($path)->not->toEndWith(DIRECTORY_SEPARATOR . 'Policies' . DIRECTORY_SEPARATOR . 'Admin');
});

it('keeps base policy path for default panel when enabled', function () {
    $panel = Panel::make()->id('app')->default();
    Filament::setCurrentPanel($panel);

    config()->set('filament-shield.policies.panel_path', true);
    config()->set('filament-shield.policies.path', app_path('Policies'));

    $path = Utils::getPolicyPath();

    expect($path)->toEndWith(DIRECTORY_SEPARATOR . 'Policies');
    expect($path)->not->toEndWith(DIRECTORY_SEPARATOR . 'Policies' . DIRECTORY_SEPARATOR . 'App');
});

it('appends panel segment to policy path when enabled for non-default panel', function () {
    $panel = Panel::make()->id('system');
    Filament::setCurrentPanel($panel);

    config()->set('filament-shield.policies.panel_path', true);
    config()->set('filament-shield.policies.path', app_path('Policies'));

    $path = Utils::getPolicyPath();

    expect($path)->toEndWith(DIRECTORY_SEPARATOR . 'Policies' . DIRECTORY_SEPARATOR . 'System');
});

it('resolves role policy path within the panel policy directory', function () {
    $panel = Panel::make()->id('system');
    Filament::setCurrentPanel($panel);

    config()->set('filament-shield.policies.panel_path', true);
    config()->set('filament-shield.policies.path', app_path('Policies'));

    $policyPath = Utils::getPolicyPath();
    $filesystem = new Filesystem;
    $filesystem->ensureDirectoryExists($policyPath);
    $filesystem->put($policyPath . DIRECTORY_SEPARATOR . 'RolePolicy.php', '<?php');

    $rolePolicy = Utils::getRolePolicyPath();

    expect($rolePolicy)->toBe('App\\Policies\\System\\RolePolicy');
});
