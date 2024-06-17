<?php

namespace App\Livewire\Member;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Helpers\UserHelper;
use App\Helpers\NumberFormatter;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\Member\MemberRepository;

class Dashboard extends Component
{
    public $user_id;
    public $date;
    public $product_name;
    public $product_description;
    public $product_price;

    public $monthly_hotspot_status;

    public function mount()
    {
        if(UserHelper::role() == User::ROLE_MEMBER)
        {
            $this->date = Carbon::now()->format('d F Y');
            $this->user_id = Crypt::encrypt(UserHelper::id());
            $data = MemberRepository::getData($this->user_id, );
            if($data['prodcut'])
            {
                $this->product_name = $data['product']['name'];
                $this->product_description = $data['product']['description'];
                $this->product_price = NumberFormatter::format($data['product']['price']);
            }
            if($data['transaction'])
            {
                $this->monthly_hotspot_status = $data['transaction']['status_name'];
            }

        }
    }

    public function render()
    {
        return view('livewire.member.dashboard');
    }
}
