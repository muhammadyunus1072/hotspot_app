<?php

namespace App\Livewire\Member\Transaction\Bill;

use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\PaymentMethod;
use App\Helpers\MidtransPayment;
use App\Helpers\NumberFormatter;
use App\Helpers\PermissionHelper;
use App\Models\TransactionStatus;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\Account\UserRepository;
use App\Repositories\Member\Transaction\BillRepository;
use App\Repositories\Transaction\TransactionRepository;
use App\Repositories\Transaction\HotspotMemberRepository;
use App\Repositories\Transaction\TransactionDetailRepository;
use App\Repositories\Transaction\TransactionStatusRepository;

class Detail extends Component
{
    public $objId;
    public $isCanUpdate;

    #[Validate('required', message: 'Member Harus Dipilih', onUpdate: false)]
    public $user_id;
    public $user_text;

    public $status;

    public $payment_method_id;
    public $payment_method_name;
    public $snapToken;

    public $transaction_details = [];


    #[On('on-dialog-confirm')]
    public function onDialogConfirm()
    {
        if ($this->objId) {
            $this->redirectRoute('bill.edit', $this->objId);
        } else {
            $this->redirectRoute('bill.create');
        }
    }

    #[On('on-dialog-cancel')]
    public function onDialogCancel()
    {
        $this->redirectRoute('bill.index');
    }

    public function mount()
    {
        $authUser = UserRepository::authenticatedUser();
        $this->isCanUpdate = $authUser->hasPermissionTo(PermissionHelper::transform(PermissionHelper::ACCESS_BILL, PermissionHelper::TYPE_UPDATE));
        if($this->objId)
        {
            $id = Crypt::decrypt($this->objId);
            $transaction = TransactionRepository::findWithDetail($id)->toArray();
            if($transaction['payment_method_id'] && $transaction['payment_method_id'] != PaymentMethod::MIDTRANS_ID)
            {
                $this->redirectRoute('bill.checkout', $this->objId);
            }
            $this->user_id = $transaction['user']['id'];
            $this->user_text = $transaction['user']['name'];

            foreach($transaction['details'] as $detail)
            {
                $this->transaction_details[] = [
                    'text' => $detail['product_name']." - ".$detail['product_price']." / ".$detail['qty'],
                    'qty' => $detail['qty'],
                ];
            }

            $this->status = $transaction['last_status']['name'];
            $this->payment_method_id = $transaction['payment_method_id'];
            $this->payment_method_name = $transaction['payment_method_name'];
        }
    }
    public function addUser($data)
    {
        $this->user_id = $data['id'];
    }
    
    public function addProduct($data)
    {
        $this->transaction_details[] = [
            'product_id' => $data['id'],
            'text' => $data['text'],
            'qty' => 0,
        ];
    }

    public function addPaymentMethod($data)
    {
        $this->payment_method_id = $data['id'];
    }

    public function removeProduct($index, $is_old = false, $value = null)
    {
        unset($this->transaction_details[$index]);
        $this->transaction_details = array_values($this->transaction_details);
    }

    public function store()
    {
        $this->validate();
        try {
            DB::beginTransaction();

            // Course Detail
            if ($this->objId) {
                $id = Crypt::decrypt($this->objId);
                $validatedData = [
                    'payment_method_id' => $this->payment_method_id,
                ];
                $transaction =TransactionRepository::update($id, $validatedData);

                $validatedData = [
                    'transaction_id' => $id,
                    'name' => $this->status,
                    'description' => $this->status,
                ];
                $transactionStatus = TransactionStatusRepository::create($validatedData);
            } else {
                $validatedData = [
                    'user_id' => $this->user_id,
                ];
                $transaction = TransactionRepository::create($validatedData);
                

                foreach ($this->transaction_details as $transaction_detail) {
                    $validatedData = [
                        'transaction_id' => $transaction->id,
                        'product_id' => $transaction_detail['product_id'],
                        'qty' => $transaction_detail['qty'],
                    ];
                    $transactionDetail = TransactionDetailRepository::create($validatedData);
                }
            }
            DB::commit();

            if($transaction->payment_method_id && $transaction->payment_method_id != PaymentMethod::MIDTRANS_ID)
            {
                $this->redirectRoute('bill.checkout', $this->objId);
            }else{
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
            }
        } catch (Exception $e) {
            DB::rollBack();
            Alert::fail($this, "Gagal", $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.member.transaction.bill.detail');
    }
}
