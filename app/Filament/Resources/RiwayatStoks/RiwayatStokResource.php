<?php

namespace App\Filament\Resources\RiwayatStoks;

use App\Filament\Resources\RiwayatStoks\Pages\CreateRiwayatStok;
use App\Filament\Resources\RiwayatStoks\Pages\EditRiwayatStok;
use App\Filament\Resources\RiwayatStoks\Pages\ListRiwayatStoks;
use App\Filament\Resources\RiwayatStoks\Schemas\RiwayatStokForm;
use App\Filament\Resources\RiwayatStoks\Tables\RiwayatStoksTable;
use App\Models\RiwayatStok;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RiwayatStokResource extends Resource
{
    protected static ?string $model = RiwayatStok::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return RiwayatStokForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RiwayatStoksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRiwayatStoks::route('/'),
            'create' => CreateRiwayatStok::route('/create'),
            'edit' => EditRiwayatStok::route('/{record}/edit'),
        ];
    }
}
