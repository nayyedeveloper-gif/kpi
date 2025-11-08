<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileManagement extends Component
{
    use WithFileUploads;

    public $user;
    public $name;
    public $email;
    public $phone;
    public $bio;
    public $profile_photo;
    public $new_profile_photo;
    
    // Password change
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    
    public $showPasswordModal = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'bio' => 'nullable|string|max:500',
        'new_profile_photo' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $this->user = Auth::user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->bio = $this->user->bio;
        $this->profile_photo = $this->user->profile_photo;
    }

    public function updateProfile()
    {
        $this->validate();

        // Handle photo upload
        if ($this->new_profile_photo) {
            // Delete old photo if exists
            if ($this->user->profile_photo) {
                Storage::disk('public')->delete($this->user->profile_photo);
            }
            
            $photoPath = $this->new_profile_photo->store('profile-photos', 'public');
            $this->user->profile_photo = $photoPath;
        }

        $this->user->name = $this->name;
        $this->user->email = $this->email;
        $this->user->phone = $this->phone;
        $this->user->bio = $this->bio;
        $this->user->save();

        $this->profile_photo = $this->user->profile_photo;
        $this->new_profile_photo = null;

        session()->flash('message', 'Profile updated successfully!');
    }

    public function removePhoto()
    {
        if ($this->user->profile_photo) {
            Storage::disk('public')->delete($this->user->profile_photo);
            $this->user->profile_photo = null;
            $this->user->save();
            $this->profile_photo = null;
            
            session()->flash('message', 'Profile photo removed successfully!');
        }
    }

    public function openPasswordModal()
    {
        $this->showPasswordModal = true;
        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';
    }

    public function closePasswordModal()
    {
        $this->showPasswordModal = false;
        $this->resetValidation();
    }

    public function changePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($this->current_password, $this->user->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        $this->user->password = Hash::make($this->new_password);
        $this->user->save();

        $this->closePasswordModal();
        session()->flash('message', 'Password changed successfully!');
    }

    public function render()
    {
        return view('livewire.profile-management')->layout('layouts.app');
    }
}
