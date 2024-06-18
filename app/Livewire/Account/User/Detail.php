<?php

namespace App\Livewire\Account\User;

use Exception;
use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\Account\RoleRepository;
use App\Repositories\Account\UserRepository;

class Detail extends Component
{
    public $objId;

    public $roles = [];

    #[Validate('required', message: 'Nama Harus Diisi', onUpdate: false)]
    public $name;

    #[Validate('required', message: 'Email Harus Diisi', onUpdate: false)]
    #[Validate('email', message: "Format Email Tidak Sesuai", onUpdate: false)]
    public $email;

    #[Validate('required', message: 'Jabatan Harus Dipilih', onUpdate: false)]
    public $role;

    public $password;


    public function mount()
    {
        $this->roles = RoleRepository::getIdAndNames()->pluck('name');
        $this->role = $this->roles[0];

        if ($this->objId) {
            $id = Crypt::decrypt($this->objId);
            $user = UserRepository::find($id);

            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->roles[0]->name;
        }
    }

    #[On('on-dialog-confirm')]
    public function onDialogConfirm()
    {
        if ($this->objId) {
            $this->redirectRoute('user.edit', $this->objId);
        } else {
            $this->redirectRoute('user.create');
        }
    }

    #[On('on-dialog-cancel')]
    public function onDialogCancel()
    {
        $this->redirectRoute('user.index');
    }

    public function store()
    {
        $this->validate();

        $otherUser = UserRepository::findByEmail($this->email);
        if (!empty($otherUser) && $otherUser->id != $this->objId) {
            Alert::fail($this, "Gagal", "Email telah digunakan pada akun yang lainnya. Silahkan gunakan email lain.");
            return;
        }

        if (empty($this->objId) && empty($this->password)) {
            Alert::fail($this, "Gagal", "Password Harus Diisi");
            return;
        }

        $validatedData = [
            'name' => $this->name,
            'email' => $this->email,
        ];
        if (!empty($this->password)) {
            $validatedData['password'] = Hash::make($this->password);
        }

        try {
            DB::beginTransaction();
            if ($this->objId) {
                $id = Crypt::decrypt($this->objId);
                UserRepository::update($id, $validatedData);
                $user = UserRepository::find($id);
                $user->syncRoles($this->role);
            } else {
                $user = UserRepository::create($validatedData);
                $user->assignRole($this->role);
            }
            DB::commit();

            Alert::confirmation(
                $this,
                Alert::ICON_SUCCESS,
                "Berhasil",
                "Pengguna Berhasil Diperbarui",
                "on-dialog-confirm",
                "on-dialog-cancel",
                "Oke",
                "Tutup",
            );
        } catch (Exception $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.account.user.detail');
    }
}
