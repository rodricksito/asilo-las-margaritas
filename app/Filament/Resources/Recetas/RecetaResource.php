<?php

namespace App\Filament\Resources\Recetas;

use App\Filament\Resources\Recetas\Pages\CreateReceta;
use App\Filament\Resources\Recetas\Pages\EditReceta;
use App\Filament\Resources\Recetas\Pages\ListRecetas;
use App\Filament\Resources\Recetas\Pages\ViewReceta;
use App\Filament\Resources\Recetas\Schemas\RecetaForm;
use App\Filament\Resources\Recetas\Schemas\RecetaInfolist;
use App\Filament\Resources\Recetas\Tables\RecetasTable;
use App\Models\Receta;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RecetaResource extends Resource
{
    protected static ?string $model = Receta::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Recetas';

    protected static ?string $modelLabel = 'Receta';

    protected static ?string $pluralModelLabel = 'Recetas';

    protected static UnitEnum|string|null $navigationGroup = 'Pacientes';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'recetas';

    public static function form(Schema $schema): Schema
    {
        return RecetaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RecetaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RecetasTable::configure($table);
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
            'index' => ListRecetas::route('/'),
            'create' => CreateReceta::route('/create'),
            'view' => ViewReceta::route('/{record}'),
            'edit' => EditReceta::route('/{record}/edit'),
        ];
    }
}
