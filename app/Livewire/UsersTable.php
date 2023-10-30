<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersTable extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $searchName = '';
    public $searchEmail = '';
    public $role = '';

    public function destroy(User $user)
    {
        $user->delete();
    }
    public function render()
    {
        return view(
            'livewire.users-table',
            [
                'users' => User::searchName($this->searchName)
                    ->when($this->role !== '', function ($query) {
                        $query->where('is_admin', $this->role);
                    })
                    ->searchEmail($this->searchEmail)
                    ->paginate($this->perPage),
            ]
        );
    }
}
