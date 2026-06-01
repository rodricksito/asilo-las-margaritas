<?php

namespace App\Filament\Resources\Sucursals;

use App\Filament\Resources\Sucursals\Pages\CreateSucursal;
use App\Filament\Resources\Sucursals\Pages\EditSucursal;
use App\Filament\Resources\Sucursals\Pages\ListSucursals;
use App\Filament\Resources\Sucursals\Pages\ViewSucursal;
use App\Filament\Resources\Sucursals\Schemas\SucursalForm;
use App\Filament\Resources\Sucursals\Schemas\SucursalInfolist;
use App\Filament\Resources\Sucursals\Tables\SucursalsTable;
use App\Models\Sucursal;
use BackedEnum;
use UnitEnum;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SucursalResource extends Resource
{
    protected static ?string $model = Sucursal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $navigationLabel = 'Sucursales';

    protected static ?string $modelLabel = 'Sucursal';

    protected static ?string $pluralModelLabel = 'Sucursales';

    protected static UnitEnum|string|null $navigationGroup = 'Catálogos';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'sucursales';

    protected static ?string $recordTitleAttribute = 'nombre';

    /**
     * Buscador global: lleva al listado con el termino precargado,
     * en vez de abrir directamente la vista del registro.
     */
    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return static::getUrl('index', [
            'tableSearch' => $record->nombre,
        ]);
    }

    public static function form(Schema $schema): Schema
    {
        return SucursalForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SucursalInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SucursalsTable::configure($table);
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
            'index' => ListSucursals::route('/'),
            'create' => CreateSucursal::route('/create'),
            'view' => ViewSucursal::route('/{record}'),
            'edit' => EditSucursal::route('/{record}/edit'),
        ];
    }
}