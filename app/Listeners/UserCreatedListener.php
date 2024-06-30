<?php

namespace App\Listeners;

use App\Events\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\apiClick;
class UserCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected $apiClick;
   



    public function __construct(apiClick $apiClick)
    {
        $this->apiClick = $apiClick;
    }
     public function handle(UserCreated $event)
     {
        $user = $event->user;

        // بررسی وجود کاربر
        $existingUser = $this->apiClick->checkUserExists($user->username);
      
        if (empty($existingUser['Data'])) {
            $userData = [
                'title' => $user->first_name . ' ' . $user->last_name,
                'first_name' => $user->first_name,
                'name' => $user->first_name,
                'father_name' => '', // فیلدهای مورد نیاز را اینجا اضافه کنید
                'national_code' => '',
                'cert_no' => '',
                'cert_date' => '',
                'card_number' => '',
                'account_number' => '',
                'shaba_number' => '',
                'economy_code' => '',
                'job' => '',
                'manager_name' => '',
                'classification_type' => 0,
                'price_list_code' => '',
                'pay_method' => 0,
                'discount' => 0.0,
                'credit' => 0.0,
                'cash_payment_discount' => 0.0,
                'card_no' => '',
                'email' =>'',
                'site' => '',
                'mobile' => $user->username,
                'phone' => '',
                'fax' => '',
                'address' => '',
                'postal_code' => '',
                'type' => 0,
                'has_seller' => 0,
                'deactivated' => false
            ];
            // ارسال داده‌ها به API
            $this->apiClick->createUser($userData);
        } else {
            \Log::info('User already exists in accounting system: ');
            return;
        }




       
     }
}
