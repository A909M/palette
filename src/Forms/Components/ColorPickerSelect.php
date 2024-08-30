<?php

namespace Awcodes\Palette\Forms\Components;

use Awcodes\Palette\Forms\Components\Concerns\HasColors;
use Closure;
use Filament\Forms\Components\Select;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;

class ColorPickerSelect extends Select
{
    use HasColors;

    protected bool | Closure $isHtmlAllowed = true;

    protected bool | Closure $isNative = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->afterStateHydrated(function (ColorPickerSelect $component, ?array $state) {
                $component->state($state['key'] ?? null);
            })
            ->dehydrateStateUsing(function (ColorPickerSelect $component, ?string $state) {
                return $state ? $component->getColors()[$state] : null;
            });
    }

    public function getOptions(): array
    {
        return collect($this->getColors())->mapWithKeys(function ($color) {
            return [$color['key'] => $this->getOptionView($color)];
        })->sortKeys()->toArray();
    }

    public function getOptionView(array $color): string | Htmlable
    {
        return Blade::render('palette::forms.components.select-option', ['color' => $color]);
    }
}