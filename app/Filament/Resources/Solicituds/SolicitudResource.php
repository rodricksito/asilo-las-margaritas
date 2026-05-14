<?php

namespace App\Filament\Resources\Solicituds;

use App\Filament\Resources\Solicituds\Pages\CreateSolicitud;
use App\Filament\Resources\Solicituds\Pages\EditSolicitud;
use App\Filament\Resources\Solicituds\Pages\ListSolicituds;
use App\Filament\Resources\Solicituds\Pages\ViewSolicitud;
use App\Filament\Resources\Solicituds\RelationManagers\EntregaArticuloRelationManager;
use App\Filament\Resources\Solicituds\RelationManagers\MedicamentoSolicitudRelationManager;
use App\Filament\Resources\Solicituds\Schemas\SolicitudForm;
use App\Filament\Resources\Solicituds\Schemas\SolicitudInfolist;
use App\Filament\Resources\Solicituds\Tables\SolicitudsTable;
use App\Models\Solicitud;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SolicitudResource extends Resource
{
    protected static ?string $model = Solicitud::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Solicitudes';

    protected static ?string $modelLabel = 'Solicitud';

    protected static ?string $pluralModelLabel = 'Solicitudes';

    protected static UnitEnum|string|null $navigationGroup = 'Operaciones';

    protected static ?int $navigationSort = 1;

    protected static ?string $slug = 'solicitudes';

    /**
     * Badge en sidebar con count de solicitudes pendientes (incompletas + vencidas).
     */
    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::pendientes()->count();

        return $count > 0 ? (string) $count : null;
    }

    /**
     * Badge rojo si hay alguna solicitud vencida; amarillo si solo hay vigentes.
     */
    public static function getNavigationBadgeColor(): ?string
    {
        $vencidas = static::getModel()::vencidas()->count();

        return $vencidas > 0 ? 'danger' : 'warning';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Solicitudes incompletas pendientes';
    }

    public static function form(Schema $schema): Schema
    {
        return SolicitudForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SolicitudInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SolicitudsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            MedicamentoSolicitudRelationManager::class,
            EntregaArticuloRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSolicituds::route('/'),
            'create' => CreateSolicitud::route('/create'),
            'view' => ViewSolicitud::route('/{record}'),
            'edit' => EditSolicitud::route('/{record}/edit'),
        ];
    }
}
