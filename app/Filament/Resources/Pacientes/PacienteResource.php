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
