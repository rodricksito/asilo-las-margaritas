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
use Illuminate\Database\Eloquent\Builder;
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

    /**
     * Busqueda global insensible a tildes y mayusculas.
     * Usa la funcion unaccent() registrada en AppServiceProvider.
     * "maria" encuentra "Maria", "jose" encuentra "Jose", etc.
     */
    protected static function applyGlobalSearchAttributeConstraint(
        Builder $query,
        string $search,
        array $searchAttributes,
        bool &$isFirst,
    ): Builder {
        foreach ($searchAttributes as $searchAttribute) {
            $whereClause = $isFirst ? 'whereRaw' : 'orWhereRaw';

            // Para relaciones con punto, usamos comportamiento default de Filament
            if (str_contains($searchAttribute, '.')) {
                parent::applyGlobalSearchAttributeConstraint(
                    $query,
                    $search,
                    [$searchAttribute],
                    $isFirst,
                );
                continue;
            }

            $column = $query->qualifyColumn($searchAttribute);

            // unaccent() normaliza el texto: minusculas + sin tildes
            // Esta funcion esta registrada en AppServiceProvider
            $query->{$whereClause}(
                "unaccent({$column}) LIKE unaccent(?)",
                ['%' . $search . '%'],
            );

            $isFirst = false;
        }

        return $query;
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
