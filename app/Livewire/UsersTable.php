<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use WithPagination;

    #[Url()]
    public $perPage = 10;
    #[Url(history: true)]
    public $searchName = '';
    #[Url(history: true)]
    public $searchEmail = '';
    #[Url(history: true)]
    public $role = '';

    #[Url(history: true)]
    public $sortBy = 'created_at';
    #[Url(history: true)]
    public $sortDir = 'DESC';

    public function destroy(User $user)
    {
        $user->delete();
    }

    public function updatedSearchName()
    {
        $this->resetPage();
    }

    public function updatedSearchEmail()
    {
        $this->resetPage();
    }

    public function setSortBy($sortByField)
    {
        if ($this->sortBy === $sortByField) {
            $this->sortDir = $this->sortDir === 'ASC' ? 'DESC' : 'ASC';
            return;
        }

        $this->sortBy = $sortByField;
        $this->sortDir = 'DESC';
    }

    public function render()
    {
        return view(
            'livewire.users-table',
            [
                'users' => User::searchName($this->searchName)
                    ->searchEmail($this->searchEmail)
                    ->when($this->role !== '', function ($query) {
                        $query->where('is_admin', $this->role);
                    })
                    ->orderBy($this->sortBy, $this->sortDir)
                    ->paginate($this->perPage),
            ]
        );
    }
}
