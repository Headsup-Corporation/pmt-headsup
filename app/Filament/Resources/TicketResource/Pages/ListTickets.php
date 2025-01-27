<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Project;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function shouldPersistTableFiltersInSession(): bool
    {
        return true;
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // protected function getTableQuery(): Builder
    // {
    //     return parent::getTableQuery()
    //         ->where(function ($query) {
    //             return $query->where('owner_id', auth()->user()->id)
    //                 ->orWhere('responsible_id', auth()->user()->id)
    //                 ->orWhereHas('project', function ($query) {
    //                     return $query->where('owner_id', auth()->user()->id)
    //                         ->orWhereHas('users', function ($query) {
    //                             return $query->where('users.id', auth()->user()->id);
    //                         });
    //                 });
    //         });
    // }
    protected function getTableQuery(): Builder
{
    return parent::getTableQuery()
        ->where(function ($query) {
            // Check if the logged-in user is the owner of any project
            $isOwner = Project::where('owner_id', auth()->user()->id)->exists();

            if ($isOwner) {
                // If the user is the owner, show all tickets
                return $query;
            } else {
                // If the user is not the owner, show only tickets where they are responsible
                return $query->where('responsible_id', auth()->user()->id);
            }
        });
}

}
