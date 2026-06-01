<?php

namespace App\Filament\Resources\Enfermeras;

use App\Filament\Resources\Enfermeras\Pages\CreateEnfermera;
use App\Filament\Resources\Enfermeras\Pages\EditEnfermera;
use App\Filament\Resources\Enfermeras\Pages\ListEnfermeras;
use App\Filament\Resources\Enfermeras\Pages\ViewEnfermera;
use App\Filament\Resources\Enfermeras\Schemas\EnfermeraForm;
use App\Filament\Resources\Enfermeras\Schemas\EnfermeraInfolist;
use App\Filament\Resources\Enfermeras\Tables\EnfermerasTable;
use App\Models\Enfermera;
use BackedEnum;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EnfermeraResource extends Resource
{
    protected static ?string $model = Enfermera::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?string $navigationLabel = 'Enfermeras';

    protected static ?string $modelLabel = 'Enfermera';

    protected static ?string $pluralModelLabel = 'Enfermeras';

    protected static UnitEnum|string|null $navigationGroup = 'Catálogos';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'enfermeras';

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
        return EnfermeraForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EnfermeraInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EnfermerasTable::configure($table);
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
            'index' => ListEnfermeras::route('/'),
            'create' => CreateEnfermera::route('/create'),
            'view' => ViewEnfermera::route('/{record}'),
            'edit' => EditEnfermera::route('/{record}/edit'),
        ];
    }
}
