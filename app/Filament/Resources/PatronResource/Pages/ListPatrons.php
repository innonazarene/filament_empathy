<?php

namespace App\Filament\Resources\PatronResource\Pages;

use App\Filament\Resources\PatronResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPatrons extends ListRecords
{
    protected static string $resource = PatronResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
