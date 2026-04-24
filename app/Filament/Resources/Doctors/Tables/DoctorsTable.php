<?php

namespace App\Filament\Resources\Doctors\Tables;

use App\Filament\Resources\Doctors\Pages\ListApprovedDoctors;
use App\Models\Doctor;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Cashier\Subscription;

class DoctorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with([
                'user.subscriptions' => fn (HasMany $subscriptionQuery): HasMany => $subscriptionQuery
                    ->where('type', 'doctor-subscription')
                    ->latest('created_at'),
            ]))
            ->columns([
                TextColumn::make('user.name')
                    ->label('Doctor Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('specialization.name')
                    ->label('Specialization')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('registration_no')
                    ->searchable()
                    ->toggleable(),
             
                TextColumn::make('profile_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'under review' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('subscription_status')
                    ->label('Subscription')
                    ->badge()
                    ->state(fn (Doctor $record): string => static::getSubscriptionStatus($record))
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Continuing Until End Date' => 'warning',
                        'Paused / Ended' => 'danger',
                        default => 'gray',
                    })
                    ->visibleOn(ListApprovedDoctors::class),
                TextColumn::make('subscription_ends_at')
                    ->label('Subscription End Date')
                    ->state(fn (Doctor $record): ?string => static::getDoctorSubscription($record)?->ends_at?->format('M d, Y'))
                    ->placeholder('No end date')
                    ->visibleOn(ListApprovedDoctors::class),
                IconColumn::make('is_active')
                    ->boolean()
                    ->toggleable(),
                TextColumn::make('submitted_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('profile_status')
                    ->options([
                        'draft' => 'Draft',
                        'under_review' => 'Under Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function getDoctorSubscription(Doctor $record): ?Subscription
    {
        if (! $record->relationLoaded('user') || ! $record->user) {
            return null;
        }

        if (! $record->user->relationLoaded('subscriptions')) {
            return null;
        }

        return $record->user->subscriptions->first();
    }

    protected static function getSubscriptionStatus(Doctor $record): string
    {
        $subscription = static::getDoctorSubscription($record);

        if (! $subscription) {
            return 'No Subscription';
        }

        if ($subscription->onGracePeriod()) {
            return 'Continuing Until End Date';
        }

        if ($subscription->active()) {
            return 'Active';
        }

        return 'Paused / Ended';
    }
}
