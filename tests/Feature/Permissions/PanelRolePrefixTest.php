<?php

declare(strict_types=1);

use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Facades\Filament;
use Filament\Panel;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;

afterEach(function () {
    Filament::setCurrentPanel(null);
});

it('prefixes role names for non-default panels when enabled', function () {
    config()->set('filament-shield.roles.panel_prefix', true);
    config()->set('filament-shield.roles.panel_prefix_separator', ':');

    $panel = Panel::make()->id('system')->plugins([
        FilamentShieldPlugin::make(),
    ]);
    Filament::registerPanel($panel);
    Filament::setCurrentPanel($panel);

    expect(Utils::prefixRoleName('admin'))->toBe('system:admin');
    expect(Utils::stripPanelRolePrefix('system:admin'))->toBe('admin');
});

it('keeps role names unprefixed for default panel', function () {
    config()->set('filament-shield.roles.panel_prefix', true);
    config()->set('filament-shield.roles.panel_prefix_separator', ':');

    $panel = Panel::make()->id('app')->default();
    Filament::setCurrentPanel($panel);

    expect(Utils::prefixRoleName('admin'))->toBe('admin');
});
