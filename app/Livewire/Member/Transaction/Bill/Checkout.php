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

class Checkout extends Component
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

    public $transaction = [];


    #[On('on-dialog-confirm')]
    public function onDialogConfirm()
    {
        if ($this->objId) {
            $this->redirectRoute('bill.checkout', $this->objId);
        } 
    }

    #[On('on-dialog-cancel')]
    public function onDialogCancel()
    {
        $this->redirectRoute('bill.checkout');
    }

    public function mount()
    {
        $authUser = UserRepository::authenticatedUser();
        $this->isCanUpdate = $authUser->hasPermissionTo(PermissionHelper::transform(PermissionHelper::ACCESS_PRODUCT, PermissionHelper::TYPE_UPDATE));
        if($this->objId)
        {
            $id = Crypt::decrypt($this->objId);
            $transaction = BillRepository::findTransaction($id);
            if ($transaction->payment_method_id && $transaction->payment_method_id == PaymentMethod::MIDTRANS_ID) {
                if (!$transaction->snap_token) {
                    $amount = $transaction->details->sum('product_price');
                    $snapToken = MidtransPayment::getSnapToken(
                        $transaction->id,
                        $amount,
                        [
                            'first_name' => $transaction->user->name,
                            'last_name' => '',
                            'email' => $transaction->user->email,
                            'phone' => $transaction->user->phone,
                        ]
                    );

                    $validatedData = [
                        'snap_token' => $snap_token,
                    ];
                    TransactionRepository::update($id, $validatedData);
                    $this->snapToken = $snap_token;
                }else{

                    $this->snapToken = $transaction->snap_token;
                }
            } 
            $this->transaction = $transaction;
        }
    }
    
    public function checkout()
    {
        $this->dispatch('midtransCheckout', $this->snapToken);
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
                TransactionRepository::update($id, $validatedData);

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
        return view('livewire.member.transaction.bill.checkout');
    }
}
