<?php

namespace App\Livewire\Account\Role;

use Exception;
use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Helpers\PermissionHelper;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\Account\RoleRepository;
use App\Repositories\Account\PermissionRepository;

class Detail extends Component
{
    public $objId;

    #[Validate('required', message: 'Nama Harus Diisi', onUpdate: false)]
    public $name;

    public $accesses = [];

    public function mount()
    {
        $permissions = PermissionRepository::getIdAndNames();
        foreach ($permissions as $permission) {
            $access = PermissionHelper::getAccess($permission->name);
            if (!isset($this->accesses[$access])) {
                $this->accesses[$access] = [
                    'name' => isset(PermissionHelper::TRANSLATE_ACCESS[$access]) ? PermissionHelper::TRANSLATE_ACCESS[$access] : $access,
                    'permissions' => []
                ];
            }

            $this->accesses[$access]['permissions'][] = [
                'id' => $permission->id,
                'name' => $permission->name,
                'translated_name' => PermissionHelper::getTranslatedType($permission->name),
                'is_checked' => false,
            ];
        }

        if ($this->objId) {
            $id = Crypt::decrypt($this->objId);
            $role = RoleRepository::find($id);
            $this->name = $role->name;

            foreach ($role->permissions as $rolePermission) {
                foreach ($this->accesses as $keyAccess => $access) {
                    foreach ($access['permissions'] as $keyPermission => $permission) {
                        if ($rolePermission->id == $permission['id']) {
                            $this->accesses[$keyAccess]['permissions'][$keyPermission]['is_checked'] = true;
                            break;
                        }
                    }
                }
            }
        }
    }

    #[On('on-dialog-confirm')]
    public function onDialogConfirm()
    {
        if ($this->objId) {
            $this->redirectRoute('role.edit', $this->objId);
        } else {
            $this->redirectRoute('role.create');
        }
    }

    #[On('on-dialog-cancel')]
    public function onDialogCancel()
    {
        $this->redirectRoute('role.index');
    }

    public function store()
    {
        $this->validate();

        $selectedPermissions = [];
        foreach ($this->accesses as $access) {
            foreach ($access['permissions'] as $permission) {
                if ($permission['is_checked']) {
                    $selectedPermissions[] = $permission['name'];
                }
            }
        }

        $validatedData = [
            'name' => $this->name
        ];

        try {
            DB::beginTransaction();
            if ($this->objId) {
                $id = Crypt::decrypt($this->objId);
                RoleRepository::update($id, $validatedData);
                $role = RoleRepository::find($id);
                $role->syncPermissions($selectedPermissions);
            } else {
                $role = RoleRepository::create($validatedData);
                $role->givePermissionTo($selectedPermissions);
            }
            DB::commit();

            Alert::confirmation(
                $this,
                Alert::ICON_SUCCESS,
                "Berhasil",
                "Jabatan Berhasil Diperbarui",
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
        return view('livewire.account.role.detail');
    }
}
