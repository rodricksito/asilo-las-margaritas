<?php

namespace App\Filament\Resources\Pacientes;

use App\Filament\Resources\Pacientes\Pages\CreatePaciente;
use App\Filament\Resources\Pacientes\Pages\EditPaciente;
use App\Filament\Resources\Pacientes\Pages\ListPacientes;
use App\Filament\Resources\Pacientes\Pages\ViewPaciente;
use App\Filament\Resources\Pacientes\RelationManagers\FamiliaresRelationManager;
use App\Filament\Resources\Pacientes\Schemas\PacienteForm;
use App\Filament\Resources\Pacientes\Schemas\PacienteInfolist;
use App\Filament\Resources\Pacientes\Tables\PacientesTable;
use App\Models\Paciente;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PacienteResource extends Resource
{
    protected static ?string $model = Paciente::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Pacientes';

    protected static ?string $modelLabel = 'Paciente';

    protected static ?string $pluralModelLabel = 'Pacientes';

    protected static UnitEnum|string|null $navigationGroup = 'Pacientes';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'pacientes';

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
        return PacienteForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PacienteInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PacientesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            FamiliaresRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPacientes::route('/'),
            'create' => CreatePaciente::route('/create'),
            'view' => ViewPaciente::route('/{record}'),
            'edit' => EditPaciente::route('/{record}/edit'),
        ];
    }
}
