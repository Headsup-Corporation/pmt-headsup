<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

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

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->where(function ($query) {
                // Check if the logged-in user is the owner of any project
                $isOwner = Project::where('owner_id', auth()->user()->id)->exists();

                // Check if the logged-in user is a Super Admin
                $isSuperAdmin = auth()->user()->roles()->where('name', 'Super Admin')->exists();

                // If the user is a Super Admin or the owner of a project, show all tickets
                if ($isOwner || $isSuperAdmin) {
                    return $query;
                } else {
                    // If the user is not the owner or Super Admin, show only tickets where they are responsible
                    return $query->where('responsible_id', auth()->user()->id);
                }
            });
    }

}
