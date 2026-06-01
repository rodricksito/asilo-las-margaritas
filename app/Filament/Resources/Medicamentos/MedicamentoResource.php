<?php

namespace App\Filament\Resources\Medicamentos;

use App\Filament\Resources\Medicamentos\Pages\CreateMedicamento;
use App\Filament\Resources\Medicamentos\Pages\EditMedicamento;
use App\Filament\Resources\Medicamentos\Pages\ListMedicamentos;
use App\Filament\Resources\Medicamentos\Pages\ViewMedicamento;
use App\Filament\Resources\Medicamentos\Schemas\MedicamentoForm;
use App\Filament\Resources\Medicamentos\Schemas\MedicamentoInfolist;
use App\Filament\Resources\Medicamentos\Tables\MedicamentosTable;
use App\Models\Medicamento;
use BackedEnum;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MedicamentoResource extends Resource
{
    protected static ?string $model = Medicamento::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBeaker;

    protected static ?string $navigationLabel = 'Medicamentos';

    protected static ?string $modelLabel = 'Medicamento';

    protected static ?string $pluralModelLabel = 'Medicamentos';

    protected static UnitEnum|string|null $navigationGroup = 'Catálogos';

    protected static ?int $navigationSort = 4;

    protected static ?string $slug = 'medicamentos';

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

    public static function form(Schema $schema): Schema
    {
        return MedicamentoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MedicamentoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MedicamentosTable::configure($table);
    }

    /**
     * Cuenta de medicamentos activos próximos a caducar (≤ 3 meses).
     * Aparece como badge rojo junto al nombre en el sidebar.
     */
    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()
            ->where('activo', true)
            ->where('fecha_caducidad', '<=', now()->addMonths(3))
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Medicamentos próximos a caducar (≤ 3 meses)';
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
            'index' => ListMedicamentos::route('/'),
            'create' => CreateMedicamento::route('/create'),
            'view' => ViewMedicamento::route('/{record}'),
            'edit' => EditMedicamento::route('/{record}/edit'),
        ];
    }
}
