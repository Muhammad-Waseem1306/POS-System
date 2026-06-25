<?php

namespace App\Support;

class TableActions
{
    protected array $items = [];

    public static function make(): static
    {
        return new static();
    }

    public function link(string $url, string $label, string $icon = '', array $attrs = []): static
    {
        $this->items[] = [
            'type' => 'link',
            'url' => $url,
            'label' => $label,
            'icon' => $icon,
            'attrs' => $attrs,
        ];

        return $this;
    }

    public function confirmLink(
        string $url,
        string $label,
        string $icon = '',
        string $confirm = 'Are you sure?',
        string $title = 'Confirm',
        string $variant = 'default',
        string $confirmOk = 'Confirm'
    ): static {
        return $this->link($url, $label, $icon, [
            'data-confirm' => $confirm,
            'data-confirm-title' => $title,
            'data-confirm-variant' => $variant,
            'data-confirm-ok' => $confirmOk,
        ]);
    }

    public function delete(
        string $url,
        string $label = 'Delete',
        string $confirm = 'Are you sure?',
        bool $disabled = false
    ): static {
        $this->items[] = [
            'type' => 'delete',
            'url' => $url,
            'label' => $label,
            'confirm' => $confirm,
            'disabled' => $disabled,
        ];

        return $this;
    }

    public function divider(): static
    {
        if ($this->items !== [] && end($this->items)['type'] !== 'divider') {
            $this->items[] = ['type' => 'divider'];
        }

        return $this;
    }

    public function render(): string
    {
        $items = $this->normalizedItems();

        if ($items === []) {
            return '';
        }

        $buttons = [];

        foreach ($items as $item) {
            if ($item['type'] === 'divider') {
                continue;
            }

            if ($item['type'] === 'link') {
                $buttons[] = [
                    'type' => 'link',
                    'url' => $item['url'],
                    'icon' => $item['icon'] !== '' ? $item['icon'] : 'fas fa-link',
                    'title' => $item['label'],
                    'variant' => $this->variantForAction($item['label'], $item['icon']),
                    'attrs' => $item['attrs'],
                ];
                continue;
            }

            if ($item['type'] === 'delete') {
                $buttons[] = [
                    'type' => 'delete',
                    'url' => $item['url'],
                    'icon' => 'fas fa-trash',
                    'title' => $item['label'],
                    'confirm' => $item['confirm'],
                    'disabled' => $item['disabled'],
                ];
            }
        }

        return static::inline($buttons);
    }

    public static function view(string $url, string $label = 'View'): string
    {
        return static::inlineButton($url, 'fas fa-eye', $label, 'primary');
    }

    public static function inlineButton(
        string $url,
        string $icon,
        string $title,
        string $variant = 'ghost',
        array $attrs = []
    ): string {
        $attrsStr = '';
        foreach ($attrs as $key => $value) {
            $attrsStr .= ' ' . e($key) . '="' . e($value) . '"';
        }

        return '<a href="' . e($url) . '" class="table-actions-btn table-actions-btn--' . e($variant) . '"'
            . ' title="' . e($title) . '" aria-label="' . e($title) . '"' . $attrsStr . '>'
            . '<i class="' . e($icon) . '" aria-hidden="true"></i></a>';
    }

    public static function inline(array $buttons): string
    {
        if ($buttons === []) {
            return '';
        }

        $html = '<div class="table-actions-inline">';

        foreach ($buttons as $button) {
            $type = $button['type'] ?? 'link';

            if ($type === 'link') {
                $html .= static::inlineButton(
                    $button['url'],
                    $button['icon'],
                    $button['title'],
                    $button['variant'] ?? 'ghost',
                    $button['attrs'] ?? []
                );
                continue;
            }

            if ($type === 'confirm') {
                $html .= static::inlineButton(
                    $button['url'],
                    $button['icon'],
                    $button['title'],
                    $button['variant'] ?? 'danger',
                    array_merge($button['attrs'] ?? [], [
                        'data-confirm' => $button['confirm'] ?? 'Are you sure?',
                        'data-confirm-title' => $button['confirmTitle'] ?? 'Confirm',
                        'data-confirm-variant' => $button['confirmVariant'] ?? 'danger',
                    ])
                );
                continue;
            }

            if ($type === 'button') {
                $variant = e($button['variant'] ?? 'ghost');
                $title = e($button['title']);
                $icon = e($button['icon']);
                $attrsStr = '';
                foreach ($button['attrs'] ?? [] as $key => $value) {
                    $attrsStr .= ' ' . e($key) . '="' . e($value) . '"';
                }
                $html .= '<button type="button" class="table-actions-btn table-actions-btn--' . $variant . '"'
                    . ' title="' . $title . '" aria-label="' . $title . '"' . $attrsStr . '>';
                $html .= '<i class="' . $icon . '" aria-hidden="true"></i></button>';
                continue;
            }

            if ($type === 'delete') {
                $disabled = ! empty($button['disabled']);
                $html .= '<form action="' . e($button['url']) . '" method="POST" class="table-actions__form-inline"'
                    . ' data-confirm="' . e($button['confirm'] ?? 'Are you sure?') . '"'
                    . ' data-confirm-title="' . e($button['confirmTitle'] ?? 'Delete item') . '"'
                    . ' data-confirm-ok="Delete"'
                    . ' data-confirm-variant="danger">';
                $html .= csrf_field() . method_field('DELETE');
                $html .= '<button type="submit" class="table-actions-btn table-actions-btn--danger' . ($disabled ? ' is-disabled' : '') . '"'
                    . ($disabled ? ' disabled' : '')
                    . ' title="' . e($button['title']) . '" aria-label="' . e($button['title']) . '">';
                $html .= '<i class="' . e($button['icon'] ?? 'fas fa-trash') . '" aria-hidden="true"></i></button></form>';
            }
        }

        $html .= '</div>';

        return $html;
    }

    protected function variantForAction(string $label, string $icon): string
    {
        $label = strtolower($label);
        $icon = strtolower($icon);

        if (str_contains($label, 'delete') || str_contains($icon, 'trash')) {
            return 'danger';
        }

        if (str_contains($label, 'edit') || str_contains($icon, 'edit') || str_contains($icon, 'pencil')) {
            return 'primary';
        }

        if (str_contains($label, 'view') || str_contains($icon, 'eye') || str_contains($icon, 'file-alt')) {
            return 'primary';
        }

        if (str_contains($label, 'suspend') || str_contains($icon, 'times-circle')) {
            return 'warning';
        }

        if (
            str_contains($label, 'due')
            || str_contains($label, 'collection')
            || str_contains($icon, 'hand-holding')
        ) {
            return 'warning';
        }

        if (
            str_contains($label, 'activate')
            || str_contains($label, 'invoice')
            || str_contains($label, 'sales')
            || str_contains($label, 'purchase')
            || str_contains($icon, 'cart')
            || str_contains($icon, 'invoice')
            || str_contains($icon, 'receipt')
            || str_contains($icon, 'check-square')
        ) {
            return 'success';
        }

        if (str_contains($label, 'default') || str_contains($icon, 'star')) {
            return 'warning';
        }

        return 'ghost';
    }

    protected function normalizedItems(): array
    {
        $items = $this->items;

        while ($items !== [] && end($items)['type'] === 'divider') {
            array_pop($items);
        }

        $normalized = [];
        foreach ($items as $item) {
            if ($item['type'] === 'divider') {
                if ($normalized !== [] && end($normalized)['type'] !== 'divider') {
                    $normalized[] = $item;
                }
                continue;
            }

            $normalized[] = $item;
        }

        while ($normalized !== [] && end($normalized)['type'] === 'divider') {
            array_pop($normalized);
        }

        return $normalized;
    }
}
