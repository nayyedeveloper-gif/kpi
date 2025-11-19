<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $showCreateForm = false;
    public $showEditForm = false;
    public $editingUserId = null;

    // Form fields
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
    ];

    protected $messages = [
        'name.required' => 'Name is required',
        'email.required' => 'Email is required',
        'email.email' => 'Please enter a valid email address',
        'email.unique' => 'This email is already registered',
        'password.required' => 'Password is required',
        'password.min' => 'Password must be at least 8 characters',
        'password.confirmed' => 'Password confirmation does not match',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function showCreateForm()
    {
        $this->resetForm();
        $this->showCreateForm = true;
        $this->showEditForm = false;
    }

    public function showEditForm($userId)
    {
        $user = User::findOrFail($userId);
        $this->editingUserId = $userId;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->showEditForm = true;
        $this->showCreateForm = false;

        // Update validation rules for editing
        $this->rules['email'] = 'required|email|unique:users,email,' . $userId;
        $this->rules['password'] = 'nullable|string|min:8|confirmed';
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->editingUserId = null;
        $this->showCreateForm = false;
        $this->showEditForm = false;

        // Reset validation rules
        $this->rules['email'] = 'required|email|unique:users,email';
        $this->rules['password'] = 'required|string|min:8|confirmed';
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->password) {
            $data['password'] = bcrypt($this->password);
        }

        if ($this->editingUserId) {
            User::findOrFail($this->editingUserId)->update($data);
            session()->flash('message', 'User updated successfully!');
        } else {
            $data['password'] = bcrypt($this->password);
            $data['email_verified_at'] = now();
            User::create($data);
            session()->flash('message', 'User created successfully!');
        }

        $this->resetForm();
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);

        // Prevent deleting the current authenticated user
        if ($userId === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account!');
            return;
        }

        // Prevent deleting if it's the last admin user
        if (User::count() <= 1) {
            session()->flash('error', 'Cannot delete the last user!');
            return;
        }

        $user->delete();
        session()->flash('message', 'User deleted successfully!');
    }

    public function getUsersProperty()
    {
        return User::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.user-management', [
            'users' => $this->users,
        ])->layout('layouts.app');
    }
}
