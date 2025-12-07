<?php

namespace App\Filament\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages\ManageActivityLogs;
use App\Models\ActivityLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    public static function getModelLabel(): string
    {
        return __('activity.single');
    }

    public static function getPluralModelLabel(): string
    {
        return __('activity.plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('general.nav.logs');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('log_name')
                    ->label(__('activity.log_name'))
                    ->searchable(),
                TextColumn::make('event')
                    ->label(__('activity.event'))
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('subject_type')
                    ->label(__('activity.subject_type'))
                    ->searchable(),
                TextColumn::make('subject_id')
                    ->label(__('activity.subject_id'))
                    ->numeric()
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('causer.name')
                    ->label(__('activity.causer'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label(__('general.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('general.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                // ViewAction::make()
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     // DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageActivityLogs::route('/'),
        ];
    }
}
