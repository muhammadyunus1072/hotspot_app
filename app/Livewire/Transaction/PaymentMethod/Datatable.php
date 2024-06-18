<?php

namespace App\Livewire\Transaction\PaymentMethod;

use Carbon\Carbon;
use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Traits\WithDatatable;
use App\Models\MonthlyHotspot;
use App\Helpers\NumberFormatter;
use App\Helpers\PermissionHelper;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Account\UserRepository;
use App\Repositories\Transaction\PaymentMethodRepository;

class Datatable extends Component
{
    use WithDatatable;

    public $isCanUpdate;
    public $isCanDelete;

    // Delete Dialog
    public $targetDeleteId;

    public function onMount()
    {
        $authUser = UserRepository::authenticatedUser();
        $this->isCanDelete = $authUser->hasPermissionTo(PermissionHelper::transform(PermissionHelper::ACCESS_PAYMENT_METHOD, PermissionHelper::TYPE_DELETE));
        $this->isCanUpdate = $authUser->hasPermissionTo(PermissionHelper::transform(PermissionHelper::ACCESS_PAYMENT_METHOD, PermissionHelper::TYPE_UPDATE));
    }

    #[On('on-delete-dialog-confirm')]
    public function onDialogDeleteConfirm()
    {
        if (!$this->isCanDelete || $this->targetDeleteId == null) {
            return;
        }
        $id = Crypt::decrypt($this->targetDeleteId);
        PaymentMethodRepository::delete($id);
        Alert::success($this, 'Berhasil', 'Data berhasil dihapus');
    }

    #[On('on-delete-dialog-cancel')]
    public function onDialogDeleteCancel()
    {
        $this->targetDeleteId = null;
    }

    public function showDeleteDialog($id)
    {
        $this->targetDeleteId = $id;

        Alert::confirmation(
            $this,
            Alert::ICON_QUESTION,
            "Hapus Data",
            "Apakah Anda Yakin Ingin Menghapus Data Ini ?",
            "on-delete-dialog-confirm",
            "on-delete-dialog-cancel",
            "Hapus",
            "Batal",
        );
    }

    public function getColumns(): array
    {
        return [
            [
                'name' => 'Action',
                'sortable' => false,
                'searchable' => false,
                'render' => function ($item) {
                    $id = Crypt::encrypt($item->id);
                    $editHtml = "";
                    if ($this->isCanUpdate) {
                        if ($item->remarks_type !== MonthlyHotspot::class)
                        {
                            $editUrl = route('payment_method.edit', $id);
                            $editHtml = "<div class='col-auto mb-2'>
                                <a class='btn btn-primary btn-sm' href='$editUrl'>
                                    <i class='ki-duotone ki-notepad-edit fs-1'>
                                        <span class='path1'></span>
                                        <span class='path2'></span>
                                    </i>
                                    Ubah
                                </a>
                            </div>";
                        }
                    }

                    $destroyHtml = "";
                    if ($this->isCanDelete) {
                        $destroyHtml = "<div class='col-auto mb-2'>
                            <button class='btn btn-danger btn-sm m-0' 
                                wire:click=\"showDeleteDialog($id)\">
                                <i class='ki-duotone ki-trash fs-1'>
                                    <span class='path1'></span>
                                    <span class='path2'></span>
                                    <span class='path3'></span>
                                    <span class='path4'></span>
                                    <span class='path5'></span>
                                </i>
                                Hapus
                            </button>
                        </div>";
                    }

                    $html = "<div class='row'>
                        $destroyHtml $editHtml 
                    </div>";

                    return $html;
                },
            ],
            [
                'key' => 'name',
                'name' => 'Nama',
            ],
            [
                'key' => 'description',
                'name' => 'Deskripsi',
            ],
        ];
    }

    public function getQuery(): Builder
    {
        return PaymentMethodRepository::datatable();
    }

    public function getView(): string
    {
        return 'livewire.transaction.payment-method.datatable';
    }
}
