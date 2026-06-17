<?php

namespace App\Filament\Resources\RiwayatStoks\Pages;

use App\Filament\Resources\RiwayatStoks\RiwayatStokResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRiwayatStoks extends ListRecords
{
    protected static string $resource = RiwayatStokResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
