<?php

namespace App\Filament\Resources\EmployeResource\Widgets;

use App\Models\Employe;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class EmployeChart extends ChartWidget
{
    protected static ?string $heading = 'Employe Created Count';

    protected static ?string $pollingInterval = '10s';

    public function getDescription(): ?string
    {
        return 'The number of employe created per month.';
    }

    public function getColumnSpan(): int|string|array
    {
        return 6; 
        // or 'full'
        // or ['default' => 12, 'md' => 6] for responsive behavior
    }

    protected function getData(): array
    {

        $data = Trend::model(Employe::class)
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Employe Created',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
