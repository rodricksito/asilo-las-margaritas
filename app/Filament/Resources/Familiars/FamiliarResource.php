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
