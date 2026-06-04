<?php

namespace App\Livewire\Frontend\User\Address;

use Livewire\Component;
use App\Models\AddressBook;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.account')]
class Index extends Component
{
    public $addresses;
    public $addressId;
    public $title, $address1, $address2, $country, $city, $state, $pincode, $is_default;
    
    public $isEditing = false;
    public $showModal = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'address1' => 'required|string|max:255',
        'address2' => 'nullable|string|max:255',
        'country' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'state' => 'required|string|max:255',
        'pincode' => 'required|string|max:20',
        'is_default' => 'boolean',
    ];

    public function mount()
    {
        $this->loadAddresses();
    }

    public function loadAddresses()
    {
        $this->addresses = AddressBook::where('user_id', Auth::id())->orderBy('is_default', 'desc')->get();
    }

    public function resetFields()
    {
        $this->title = '';
        $this->address1 = '';
        $this->address2 = '';
        $this->country = 'India';
        $this->city = '';
        $this->state = '';
        $this->pincode = '';
        $this->is_default = false;
        $this->addressId = null;
        $this->isEditing = false;
    }

    public function openModal()
    {
        $this->resetFields();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function store()
    {
        $this->validate();

        if ($this->is_default) {
            AddressBook::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        AddressBook::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'country' => $this->country,
            'city' => $this->city,
            'state' => $this->state,
            'pincode' => $this->pincode,
            'is_default' => $this->is_default ?? false,
        ]);

        session()->flash('success', 'Address added successfully.');
        $this->loadAddresses();
        $this->closeModal();
    }

    public function edit($id)
    {
        $address = AddressBook::where('user_id', Auth::id())->findOrFail($id);
        $this->addressId = $id;
        $this->title = $address->title;
        $this->address1 = $address->address1;
        $this->address2 = $address->address2;
        $this->country = $address->country;
        $this->city = $address->city;
        $this->state = $address->state;
        $this->pincode = $address->pincode;
        $this->is_default = (bool)$address->is_default;
        
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate();

        if ($this->is_default) {
            AddressBook::where('user_id', Auth::id())->where('id', '!=', $this->addressId)->update(['is_default' => false]);
        }

        $address = AddressBook::where('user_id', Auth::id())->findOrFail($this->addressId);
        $address->update([
            'title' => $this->title,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'country' => $this->country,
            'city' => $this->city,
            'state' => $this->state,
            'pincode' => $this->pincode,
            'is_default' => $this->is_default ?? false,
        ]);

        session()->flash('success', 'Address updated successfully.');
        $this->loadAddresses();
        $this->closeModal();
    }

    public function delete($id)
    {
        $address = AddressBook::where('user_id', Auth::id())->findOrFail($id);
        $address->delete();

        session()->flash('success', 'Address deleted successfully.');
        $this->loadAddresses();
    }

    public function setAsDefault($id)
    {
        AddressBook::where('user_id', Auth::id())->update(['is_default' => false]);
        AddressBook::where('user_id', Auth::id())->where('id', $id)->update(['is_default' => true]);

        session()->flash('success', 'Default address updated.');
        $this->loadAddresses();
    }

    public function render()
    {
        return view('livewire.frontend.user.address.index');
    }
}
