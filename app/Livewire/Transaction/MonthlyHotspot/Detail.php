<?php

namespace App\Livewire\Product\MonthlyHotspot;

use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Helpers\NumberFormatter;
use App\Helpers\PermissionHelper;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\Account\UserRepository;
use App\Repositories\Product\MonthlyHotspotRepository;

class Detail extends Component
{
    public $objId;
    public $isCanUpdate;

    #[Validate('required', message: 'Nama Produk Harus Diisi', onUpdate: false)]
    public $name;
    
    public $description;
    
    #[Validate('required', message: 'Harga Produk Harus Diisi', onUpdate: false)]
    public $price = 0;

    public $price_before_discount = 0;

    #[On('on-dialog-confirm')]
    public function onDialogConfirm()
    {
        if ($this->objId) {
            $this->redirectRoute('monthly_hotspot.edit', $this->objId);
        } else {
            $this->redirectRoute('monthly_hotspot.create');
        }
    }

    #[On('on-dialog-cancel')]
    public function onDialogCancel()
    {
        $this->redirectRoute('monthly_hotspot.index');
    }

    public function mount()
    {
        $authUser = UserRepository::authenticatedUser();
        $this->isCanUpdate = $authUser->hasPermissionTo(PermissionHelper::transform(PermissionHelper::ACCESS_MONTHLY_HOTSPOT, PermissionHelper::TYPE_UPDATE));
        if($this->objId)
        {
            $this->getProduct();
        }
    }

    public function getProduct()
    {
        $id = Crypt::decrypt($this->objId);
        $category = MonthlyHotspotRepository::find($id);
        $this->name = $category->name;
        $this->description = $category->description;
        $this->price = $category->price;
        $this->price_before_discount = $category->price_before_discount;
    }

    public function store()
    {
        $this->validate();

        $validatedData = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => NumberFormatter::imaskToValue($this->price),
            'price_before_discount' => NumberFormatter::imaskToValue($this->price_before_discount),
        ];

        try {
            DB::beginTransaction();

            // Course Detail
            if ($this->objId) {
                $id = Crypt::decrypt($this->objId);
                MonthlyHotspotRepository::update($id, $validatedData);
            } else {
                $category = MonthlyHotspotRepository::create($validatedData);
                
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

            $this->getProduct();
        } catch (Exception $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.product.monthly-hotspot.detail');
    }
}
