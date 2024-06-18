<?php

namespace App\Livewire\Member\Transaction\Bill;

use Carbon\Carbon;
use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Traits\WithDatatable;
use App\Helpers\NumberFormatter;
use App\Helpers\PermissionHelper;
use App\Models\TransactionStatus;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Account\UserRepository;
use App\Repositories\Member\Transaction\BillRepository;

class Datatable extends Component
{
    use WithDatatable;

    public $isCanUpdate;

    // Delete Dialog
    public $targetDeleteId;

    public function onMount()
    {
        $authUser = UserRepository::authenticatedUser();
        $this->isCanUpdate = $authUser->hasPermissionTo(PermissionHelper::transform(PermissionHelper::ACCESS_BILL, PermissionHelper::TYPE_UPDATE));
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
                        if($item->isEditable())
                        {
                            $editUrl = route('bill.edit', $id);
                            $editHtml = "<div class='col-auto mb-2'>
                                <a class='btn btn-success btn-sm' href='$editUrl'>
                                    <i class='ki-duotone ki-dollar fs-2'>
                                        <span class='path1'></span>
                                        <span class='path2'></span>
                                        <span class='path3'></span>
                                    </i>
                                    Bayar
                                </a>
                            </div>";
                        }
                    }

                    $html = "<div class='row'>
                        $editHtml 
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
                    return $item->payment_method_id ? $item->payment_method_name : 'Belum Dipilih';
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
        return BillRepository::datatable();
    }

    public function getView(): string
    {
        return 'livewire.member.transaction.bill.datatable';
    }
}
