<?php

namespace App\Filament\Resources\PatronResource\Pages;

use App\Filament\Resources\PatronResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPatron extends EditRecord
{
    protected static string $resource = PatronResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
