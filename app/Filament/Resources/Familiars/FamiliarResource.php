<?php

namespace App\Filament\Resources\Familiars;

use App\Filament\Resources\Familiars\Pages\CreateFamiliar;
use App\Filament\Resources\Familiars\Pages\EditFamiliar;
use App\Filament\Resources\Familiars\Pages\ListFamiliars;
use App\Filament\Resources\Familiars\Pages\ViewFamiliar;
use App\Filament\Resources\Familiars\Schemas\FamiliarForm;
use App\Filament\Resources\Familiars\Schemas\FamiliarInfolist;
use App\Filament\Resources\Familiars\Tables\FamiliarsTable;
use App\Models\Familiar;
use BackedEnum;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FamiliarResource extends Resource
{
    protected static ?string $model = Familiar::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Familiares';

    protected static ?string $modelLabel = 'Familiar';

    protected static ?string $pluralModelLabel = 'Familiares';

    protected static UnitEnum|string|null $navigationGroup = 'Pacientes';

    protected static ?int $navigationSort = 2;

    protected static ?string $slug = 'familiares';

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
        return FamiliarForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FamiliarInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FamiliarsTable::configure($table);
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
            'index' => ListFamiliars::route('/'),
            'create' => CreateFamiliar::route('/create'),
            'view' => ViewFamiliar::route('/{record}'),
            'edit' => EditFamiliar::route('/{record}/edit'),
        ];
    }
}
