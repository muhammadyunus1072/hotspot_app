<?php

namespace App\Livewire\Transaction\PaymentMethod;

use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Helpers\NumberFormatter;
use App\Helpers\PermissionHelper;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\Account\UserRepository;
use App\Repositories\Transaction\PaymentMethodRepository;

class Detail extends Component
{
    public $objId;
    public $isCanUpdate;

    #[Validate('required', message: 'Nama Produk Harus Diisi', onUpdate: false)]
    public $name;
    
    public $description;

    #[On('on-dialog-confirm')]
    public function onDialogConfirm()
    {
        if ($this->objId) {
            $this->redirectRoute('payment_method.edit', $this->objId);
        } else {
            $this->redirectRoute('payment_method.create');
        }
    }

    #[On('on-dialog-cancel')]
    public function onDialogCancel()
    {
        $this->redirectRoute('payment_method.index');
    }

    public function mount()
    {
        $authUser = UserRepository::authenticatedUser();
        $this->isCanUpdate = $authUser->hasPermissionTo(PermissionHelper::transform(PermissionHelper::ACCESS_PAYMENT_METHOD, PermissionHelper::TYPE_UPDATE));
        if($this->objId)
        {
            $this->getPaymentMethod();
        }
    }

    public function getPaymentMethod()
    {
        $id = Crypt::decrypt($this->objId);
        $category = PaymentMethodRepository::find($id);
        $this->name = $category->name;
        $this->description = $category->description;
    }

    public function store()
    {
        $this->validate();

        $validatedData = [
            'name' => $this->name,
            'description' => $this->description,
        ];

        try {
            DB::beginTransaction();

            // Course Detail
            if ($this->objId) {
                $id = Crypt::decrypt($this->objId);
                PaymentMethodRepository::update($id, $validatedData);
            } else {
                $category = PaymentMethodRepository::create($validatedData);
                
            }
            DB::commit();

            Alert::confirmation(
                $this,
                Alert::ICON_SUCCESS,
                "Berhasil",
                "Data Berhasil Diperbarui",
                "on-dialog-confirm",
                "on-dialog-cancel",
                "Oke",
                "Tutup",
            );

            $this->getPaymentMethod();
        } catch (Exception $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.transaction.payment-method.detail');
    }
}
