<?php

namespace App\Filament\Resources\ArticuloPersonals;

use App\Filament\Resources\ArticuloPersonals\Pages\CreateArticuloPersonal;
use App\Filament\Resources\ArticuloPersonals\Pages\EditArticuloPersonal;
use App\Filament\Resources\ArticuloPersonals\Pages\ListArticuloPersonals;
use App\Filament\Resources\ArticuloPersonals\Pages\ViewArticuloPersonal;
use App\Filament\Resources\ArticuloPersonals\Schemas\ArticuloPersonalForm;
use App\Filament\Resources\ArticuloPersonals\Schemas\ArticuloPersonalInfolist;
use App\Filament\Resources\ArticuloPersonals\Tables\ArticuloPersonalsTable;
use App\Models\ArticuloPersonal;
use BackedEnum;
use UnitEnum;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ArticuloPersonalResource extends Resource
{
    protected static ?string $model = ArticuloPersonal::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?string $navigationLabel = 'Artículos Personales';

    protected static ?string $modelLabel = 'Artículo Personal';

    protected static ?string $pluralModelLabel = 'Artículos Personales';

    protected static UnitEnum|string|null $navigationGroup = 'Catálogos';

    protected static ?int $navigationSort = 5;

    protected static ?string $slug = 'articulos-personales';

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
        return ArticuloPersonalForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ArticuloPersonalInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ArticuloPersonalsTable::configure($table);
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
            'index' => ListArticuloPersonals::route('/'),
            'create' => CreateArticuloPersonal::route('/create'),
            'view' => ViewArticuloPersonal::route('/{record}'),
            'edit' => EditArticuloPersonal::route('/{record}/edit'),
        ];
    }
}
