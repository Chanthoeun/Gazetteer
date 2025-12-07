<?php

namespace App\Filament\Resources\Users\Tables;


use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;

use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('user.name'))
                    ->searchable(),
                TextColumn::make('email')
                    ->label(__('user.email'))
                    ->searchable()
                    ->copyable()
                    ->copyMessage(__('general.action.label.copy', ['name' => __('user.email')]))
                    ->copyMessageDuration(1500),
                TextColumn::make('roles.name')
                    ->label(__('role.plural'))
                    ->badge()
                    ->alignCenter()
                    ->toggleable()
                    ->formatStateUsing(fn($state): string => ucwords(str_replace('_', ' ', $state))),
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
            ->filters([
                Filter::make('created_at')
                    ->schema([
                        DatePicker::make('created_from')
                            ->label(__('general.registered_from')),
                        DatePicker::make('created_until')
                            ->label(__('general.registered_until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->label(__('general.registered_at')),

            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    Action::make('activities')
                        ->label(__('general.activities'))
                        ->icon('heroicon-o-arrow-trending-up')
                        ->color('success')
                        ->url(fn($record) => UserResource::getUrl('activities', ['record' => $record])),
                    Action::make('ban')
                        ->label(__('user.action.ban'))
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (User $record) {
                            $record->ban();
                            Notification::make()->title(__('user.notification.banned'))->success()->send();
                        })
                        ->visible(function (User $record): bool {
                            // Prevent banning oneself and other admins
                            $isAdmin = $record->hasRole('super_admin');

                            return $record->isNotBanned() && Auth::Id() !== $record->id && !$isAdmin;
                        }),
                    Action::make('unban')
                        ->label(__('user.action.unban'))
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (User $record) {
                            $record->unban();
                            Notification::make()->title(__('user.notification.unbanned'))->success()->send();
                        })
                        ->visible(fn(User $record): bool => $record->isBanned()),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
