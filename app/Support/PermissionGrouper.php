<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;

class PermissionGrouper
{
    public static function allDefinedNames(): array
    {
        $names = [];

        foreach (config('permissions.groups', []) as $group) {
            $names = array_merge($names, self::extractNamesFromGroup($group));
        }

        return array_values(array_unique($names));
    }

    public static function grouped(Collection $permissions): array
    {
        $byName = $permissions->keyBy('name');
        $assigned = [];
        $groups = [];

        foreach (config('permissions.groups', []) as $group) {
            $built = self::buildGroup($group, $byName, $assigned);

            if ($built !== null) {
                $groups[] = $built;
            }
        }

        $uncategorized = $permissions
            ->filter(fn (Permission $permission) => ! in_array($permission->name, $assigned, true))
            ->values();

        if ($uncategorized->isNotEmpty()) {
            $groups[] = [
                'label' => 'Other',
                'sections' => [],
                'permissions' => $uncategorized->all(),
            ];
        }

        return $groups;
    }

    private static function buildGroup(array $group, Collection $byName, array &$assigned): ?array
    {
        if (! empty($group['sections'])) {
            $sections = [];

            foreach ($group['sections'] as $section) {
                $items = self::resolvePermissions($section['permissions'] ?? [], $byName, $assigned);

                if ($items !== []) {
                    $sections[] = [
                        'label' => $section['label'],
                        'permissions' => $items,
                    ];
                }
            }

            if ($sections === []) {
                return null;
            }

            return [
                'label' => $group['label'],
                'sections' => $sections,
                'permissions' => [],
            ];
        }

        $items = self::resolvePermissions($group['permissions'] ?? [], $byName, $assigned);

        if ($items === []) {
            return null;
        }

        return [
            'label' => $group['label'],
            'sections' => [],
            'permissions' => $items,
        ];
    }

    private static function resolvePermissions(array $names, Collection $byName, array &$assigned): array
    {
        $items = [];

        foreach ($names as $name) {
            if (! $byName->has($name) || in_array($name, $assigned, true)) {
                continue;
            }

            $assigned[] = $name;
            $items[] = $byName->get($name);
        }

        return $items;
    }

    private static function extractNamesFromGroup(array $group): array
    {
        $names = $group['permissions'] ?? [];

        foreach ($group['sections'] ?? [] as $section) {
            $names = array_merge($names, $section['permissions'] ?? []);
        }

        return $names;
    }
}
