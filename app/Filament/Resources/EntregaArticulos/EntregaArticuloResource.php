<?php

namespace App\Filament\Resources\EntregaArticulos;

use App\Filament\Resources\EntregaArticulos\Pages\CreateEntregaArticulo;
use App\Filament\Resources\EntregaArticulos\Pages\EditEntregaArticulo;
use App\Filament\Resources\EntregaArticulos\Pages\ListEntregaArticulos;
use App\Filament\Resources\EntregaArticulos\Pages\ViewEntregaArticulo;
use App\Filament\Resources\EntregaArticulos\Schemas\EntregaArticuloForm;
use App\Filament\Resources\EntregaArticulos\Schemas\EntregaArticuloInfolist;
use App\Filament\Resources\EntregaArticulos\Tables\EntregaArticulosTable;
use App\Models\EntregaArticulo;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EntregaArticuloResource extends Resource
{
    protected static ?string $model = EntregaArticulo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGift;

    protected static ?string $navigationLabel = 'Entregas de Artículos';

    protected static ?string $modelLabel = 'Entrega';

    protected static ?string $pluralModelLabel = 'Entregas de Artículos';

    protected static UnitEnum|string|null $navigationGroup = 'Operaciones';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'entregas';

    public static function form(Schema $schema): Schema
    {
        return EntregaArticuloForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EntregaArticuloInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EntregaArticulosTable::configure($table);
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
            'index' => ListEntregaArticulos::route('/'),
            'create' => CreateEntregaArticulo::route('/create'),
            'view' => ViewEntregaArticulo::route('/{record}'),
            'edit' => EditEntregaArticulo::route('/{record}/edit'),
        ];
    }
}
