<?php

namespace App\Livewire\Transaction\Transaction;

use Carbon\Carbon;
use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Traits\WithDatatable;
use App\Helpers\NumberFormatter;
use App\Helpers\PermissionHelper;
use App\Models\TransactionStatus;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Account\UserRepository;
use App\Repositories\Transaction\TransactionRepository;

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
        $this->isCanDelete = $authUser->hasPermissionTo(PermissionHelper::transform(PermissionHelper::ACCESS_TRANSACTION, PermissionHelper::TYPE_DELETE));
        $this->isCanUpdate = $authUser->hasPermissionTo(PermissionHelper::transform(PermissionHelper::ACCESS_TRANSACTION, PermissionHelper::TYPE_UPDATE));
    }

    #[On('on-delete-dialog-confirm')]
    public function onDialogDeleteConfirm()
    {
        if (!$this->isCanDelete || $this->targetDeleteId == null) {
            return;
        }

        TransactionRepository::delete($this->targetDeleteId);
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
                      
                    $editHtml = "";
                    if ($this->isCanUpdate) {
                        $editUrl = route('transaction.edit', $item->id);
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

                    $destroyHtml = "";
                    if ($this->isCanDelete) {
                        $destroyHtml = "<div class='col-auto mb-2'>
                            <button class='btn btn-danger btn-sm m-0' 
                                wire:click=\"showDeleteDialog($item->id)\">
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
                        $editHtml $destroyHtml 
                    </div>";

                    return $html;
                },
            ],
            [
                'sortable' => false,
                'searchable' => false,
                'key' => 'number',
                'name' => 'Nomor',
                'render' => function($item)
                {
                    return $item->number;
                }
            ],
            [
                'sortable' => false,
                'searchable' => false,
                'name' => 'Nama',
                'render' => function($item)
                {
                    return $item->user->name;
                }
            ],
            [
                'sortable' => false,
                'searchable' => false,
                'name' => 'Metode Pembayaran',
                'render' => function($item)
                {
                    return $item->payment_method ? $item->payment_method->name : 'Belum Dipilih';
                }
            ],
            [
                'sortable' => false,
                'searchable' => false,
                'name' => 'Status',
                'render' => function($item)
                {
                    $status_style = TransactionStatus::status_style($item->last_status->name);
                    return "<div class='badge badge-$status_style' style='font-size:15px;'>".$item->last_status->name."</div>";
                }
            ],
            [
                'sortable' => false,
                'searchable' => false,
                'name' => 'Detail',
                'render' => function ($item) {
                    $html = "<ul class='list-group list-group-flush'>";
                        foreach($item->details as $detail)
                        {
                            $html .= "<li class='list-group-item'>".$detail->product['name']." / QTY: ".$detail->qty." / @ ".NumberFormatter::format($detail->qty * $detail->product_price)."</li>";
                        }
                    $html ."</ul>";
                    return $html;
                }
            ],
        ];
    }

    public function getQuery(): Builder
    {
        return TransactionRepository::datatable();
    }

    public function getView(): string
    {
        return 'livewire.transaction.transaction.datatable';
    }
}
