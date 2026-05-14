<?php

namespace App\Filament\Resources\Traspasos;

use App\Filament\Resources\Traspasos\Pages\CreateTraspaso;
use App\Filament\Resources\Traspasos\Pages\EditTraspaso;
use App\Filament\Resources\Traspasos\Pages\ListTraspasos;
use App\Filament\Resources\Traspasos\Pages\ViewTraspaso;
use App\Filament\Resources\Traspasos\Schemas\TraspasoForm;
use App\Filament\Resources\Traspasos\Schemas\TraspasoInfolist;
use App\Filament\Resources\Traspasos\Tables\TraspasosTable;
use App\Models\Traspaso;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TraspasoResource extends Resource
{
    protected static ?string $model = Traspaso::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    protected static ?string $navigationLabel = 'Traspasos';

    protected static ?string $modelLabel = 'Traspaso';

    protected static ?string $pluralModelLabel = 'Traspasos';

    protected static UnitEnum|string|null $navigationGroup = 'Operaciones';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'traspasos';

    public static function form(Schema $schema): Schema
    {
        return TraspasoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TraspasoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TraspasosTable::configure($table);
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
            'index' => ListTraspasos::route('/'),
            'create' => CreateTraspaso::route('/create'),
            'view' => ViewTraspaso::route('/{record}'),
            'edit' => EditTraspaso::route('/{record}/edit'),
        ];
    }
}
