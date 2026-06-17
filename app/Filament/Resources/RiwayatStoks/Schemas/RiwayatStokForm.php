<?php

namespace App\Filament\Resources\RiwayatStoks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RiwayatStokForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('barang_id')
                    ->relationship('barang', 'nama_barang')
                    ->required()
                    ->searchable()
                    ->preload(),
                \Filament\Forms\Components\Select::make('jenis')
                    ->options([
                        'masuk' => 'Masuk',
                        'keluar' => 'Keluar',
                    ])
                    ->required(),
                TextInput::make('jumlah')
                    ->required()
                    ->numeric(),
                TextInput::make('keterangan'),
            ]);
    }
}
