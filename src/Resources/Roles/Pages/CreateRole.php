<?php

declare(strict_types=1);

namespace BezhanSalleh\FilamentShield\Resources\Roles\Pages;

use BezhanSalleh\FilamentShield\Resources\Roles\RoleResource;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Collection;

class CreateRole extends CreateRecord
{
    public Collection $permissions;

    protected static string $resource = RoleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->permissions = Utils::extractRolePermissionsFromFormData($data);

        return Utils::normalizeRoleFormData($data);
    }

    protected function afterCreate(): void
    {
        $permissionModels = Utils::buildPermissionModels($this->permissions, $this->data['guard_name']);

        $this->record->syncPermissions($permissionModels);
    }
}
