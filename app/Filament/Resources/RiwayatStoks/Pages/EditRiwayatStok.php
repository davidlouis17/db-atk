<?php

namespace App\Filament\Resources\RiwayatStoks\Pages;

use App\Filament\Resources\RiwayatStoks\RiwayatStokResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRiwayatStok extends EditRecord
{
    protected static string $resource = RiwayatStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
