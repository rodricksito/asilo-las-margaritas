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
