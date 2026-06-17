<?php

namespace App\Filament\Resources\Barangs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BarangForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_barang')
                    ->required(),
                TextInput::make('kategori')
                    ->required(),
                TextInput::make('barcode')
                    ->label('Barcode')
                    ->placeholder('Auto-generated jika dikosongkan')
                    ->unique(table: 'barangs', column: 'barcode')
                    ->readOnly(fn ($record) => $record !== null),
                TextInput::make('stok')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('batas_minimum')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
