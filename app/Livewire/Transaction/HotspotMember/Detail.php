<?php

namespace App\Livewire\Transaction\HotspotMember;

use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Helpers\NumberFormatter;
use App\Helpers\PermissionHelper;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use App\Repositories\Account\UserRepository;
use App\Repositories\Transaction\HotspotMemberRepository;

class Detail extends Component
{
    public $objId;
    public $isCanUpdate;

    #[Validate('required', message: 'Member Harus Dipilih', onUpdate: false)]
    public $user_id;
    public $user_text;

    #[Validate('required', message: 'Paket Harus Dipilih', onUpdate: false)]
    public $product_id;
    public $product_text;

    #[On('on-dialog-confirm')]
    public function onDialogConfirm()
    {
        $this->name = "";
        $this->description = "";
        $this->price = 0;
        $this->price_before_discount = 0;
    }

    #[On('on-dialog-cancel')]
    public function onDialogCancel()
    {
        $this->redirectRoute('hotspot_member.index');
    }

    public function mount()
    {
        $authUser = UserRepository::authenticatedUser();
        $this->isCanUpdate = $authUser->hasPermissionTo(PermissionHelper::transform(PermissionHelper::ACCESS_PRODUCT, PermissionHelper::TYPE_UPDATE));
        if($this->objId)
        {
            $hotspot_member = HotspotMemberRepository::findWithDetail($this->objId);
            $this->user_id = $hotspot_member->member_id;
            $this->user_text = $hotspot_member->member_name;
            $this->product_id = $hotspot_member->product_id;
            $this->product_text = $hotspot_member->product_name." - Rp ".NumberFormatter::format($hotspot_member->product_price);;
        }
    }
    public function addUser($data)
    {
        $this->user_id = $data['id'];
    }
    public function addProduct($data)
    {
        $this->product_id = $data['id'];
    }

    public function store()
    {
        $this->validate();

        $validatedData = [
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
        ];

        try {
            DB::beginTransaction();

            // Course Detail
            if ($this->objId) {
                HotspotMemberRepository::update($this->objId, $validatedData);
            } else {
                $category = HotspotMemberRepository::create($validatedData);
                $this->objId = $category->id;
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
        } catch (Exception $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.transaction.hotspot-member.detail');
    }
}
