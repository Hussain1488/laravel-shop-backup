<?php

namespace App\Providers;
namespace App\Services;

use App\Models\Product;
use App\Models\User;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use Morilog\Jalali\Jalalian;

class apiClick
{
    protected $client;
    protected $userGuid;
    protected $DepotGuid;
    protected $CashDeskGuid;
    protected $BaseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->userGuid = env('userGuid');
        $this->DepotGuid = env('DepotGuid');
        $this->CashDeskGuid = env('CashDeskGuid');
        $this->BaseUrl = env('BaseUrl');
    }
    public function createProduct($product){
 try {
   

            $data = [
                "AccDetailGuid" => "00000000-0000-0000-0000-000000000000",
                "Alias" => $product->title ?? $product->slug,
                "ParentGuid" => $product->category->guid,
                "Code" => $product->id,
                "Title" => $product->title,
                "AliasFormula" => "",
                "ChildDigits" => 0,
                "HasChildren" => false,
                "IsItem" => true,
                "NotAbsoluteRatio" => true,
                "Status" => 0,
                "SalePrice" => $product->prices[0]->price,
                "BuyPrice" => $product->prices[0]->price,
                "HasSerial" => "true",
                "Barcode" =>  "",
                "BrandCode" => "",
                "OldCode" =>  "",
                "ModelNo" => "",
                "SecondaryGroup" => "",
                "TechnicalCode" =>  "",
                "WooCommerceCategoryId" => 0,
                "Unit1Guid" =>'f3bf2698-6024-4a96-8547-3f87cd1f273e' ,
                "Unit2Guid" => "00000000-0000-0000-0000-000000000000",
                "Quantity1"=> 0,
                "MinimumInventory"=> 0,
                "WooCommerceProductId"=> 0,
                "WooCommerceVariableId"=> 0
            ];
            
            $response = $this->client->post("{$this->BaseUrl}/api/Item/Create?userGuid={$this->userGuid}", [
                'headers' => [
                    // 'Authorization' => "userGuid {$this->userGuid}"
                ],
                'json' => $data
            ]);

            $data= json_decode($response->getBody()->getContents(), true);
            Product::where('id', $product->id)->update(['ItemCode' => $data['ItemCode'],'Guid'=>$data['Guid']]);

            \Log::info('product created');

      

  
        } catch (\Exception $e) {
            \Log::error('Failed to send product data to accounting API: ' . $e->getMessage());
            throw $e;
        }
    }
    public function createInvoice($invoice){
 
        try {
            // تبدیل تاریخ میلادی به شمسی
            $jalali_date = Jalalian::fromDateTime($invoice->created_at);

            $formatted_date = $jalali_date->format('Ymd');


            // تنظیم جزئیات فاکتور
            $details = [];
            foreach ($invoice->items as $key => $item) {
               
                $details[] = [
                    "ItemCode" => $item->product->ItemCode,
                    "ItemDescription" => $item->title,
                    "ItemGuid" =>$item->product->Guid,
                    "SellInvoiceGuid"=> "00000000-0000-0000-0000-000000000000",
                    "DepotGuid" => $this->DepotGuid,
                    "DepotIoGuid" => "00000000-0000-0000-0000-000000000000",
                    "Unit1" => "عدد",
                    "Unit1Guid" => "f3bf2698-6024-4a96-8547-3f87cd1f273e",
                    "Unit1Value" => 0.0,
                    "Unit2" => null,
                    "Unit2Guid" => "00000000-0000-0000-0000-000000000000",
                    "Unit2Value" => 0.0,         
                    "Quantity1"=>  $item->quantity,
                    "Quantity2"=>  $item->quantity,
                    "Price" => $item->real_price,
                    "Value" => 200.0,
                    "Discount" =>$item->discount,
                    "DiscountTotal" => 0.0,
                    "ValueAfterDiscount" => $item->price,
                    "Tax" => 0.0,
                    "TaxTotal" => 0.0,
                    "Toll" => 0.0,
                    "TollTotal" => 0.0,
                    "Total"=> 200.0,
                    "Remarks"=> "",
                    "Barcode"=> null

                ];
            }
        
            // تنظیم داده‌های اصلی فاکتور
            $data = [
                "TakeOfDepoIO" => true,
                "No" => $invoice->id,
                "Date" => $formatted_date,
                "CashDeskCode" => "2002",
                "CashDeskDescription" => "صندق پیش فرض وبسایت",
                "CashDeskGuid" => $this->CashDeskGuid,
                "DepotCode" => "2",
                "DepotDescription" => "انبار مرکزی",
                "DepotGuid" =>  $this->DepotGuid,
                "DistributerDescription" => "",
                "Discount" => 0.0,
                "DiscountTotal" => 0.0,
                "DiscountValue" => 0.0,
                "DriverCode" => "",
                "DriverDescription" => "",
                "DriverGuid" => "00000000-0000-0000-0000-000000000000",
                "ExpertCode" => "",
                "ExpertDescription" => "",
                "ExpertGuid" => "00000000-0000-0000-0000-000000000000",
                "MarketerCode" => "",
                "MarketerDescription" => "",
                "MarketerGuid" => "00000000-0000-0000-0000-000000000000",
                "MarketerCode2" => "",
                "MarketerDescription2" => "",
                "MarketerGuid2" => "00000000-0000-0000-0000-000000000000",
                "Reference" => 0,
                "ReferenceNo" => 0,
                "PortageCost" => 0.0,
                "BuyerPortageCost" => $invoice->shipping_cost,
                "SettlementDate" => "",
                "SettlementDayCount" => 0,
                "PriceListGuid" => "00000000-0000-0000-0000-000000000000",
                "Remarks" => $invoice->description,
                "BuyerGuid" => $invoice->user->guid,
                "Tax" => 0.0,
                "TaxTotal" => 0.0,
                "TaxValue" => 0.0,
                "Toll" => 0.0,
                "TollTotal" => 0.0,
                "TollValue" => 0.0,
                "Transferee" => "",
                "Classification" => 1,
                "Type" => 3,
                "Value" => $invoice->price,
                "Status" => 0,
                "CashOperationPrice" => 0.0,
                "BankOperationPrice" => $invoice->price,
                "FinancialDocumentPrice" => 0.0,
                "Details" => $details,
                "BankOperations" => [
                    [
                        "Id" => 1,
                        "No" =>time() . "1",  // شماره فیش یکتا
                        "Date" => $formatted_date,
                        "BankAccountGuid" => "f1980987-225f-448e-81d1-66f210d3238d",
                        "BankAccountCode" => "1",
                        "BankAccountDescription" => "بانک های وب سایت",
                        "Price" => $invoice->price
                    ]
                ],
                "CashOperations" => [
                    [
                        "Id" => 2,
                        "No" => time() . "1",  // شماره فیش یکتا
                        "Date" => $formatted_date,
                        "CashDeskGuid" =>  $this->CashDeskGuid,
                        "CashDeskCode" => "2002",
                        "CashDeskDescription" => "صندق پیش فرض وبسایت",
                        "Price" => $invoice->price
                    ]
                ]
            ];
        // dd($data);
            $response = $this->client->post("{$this->BaseUrl}/api/SellInvoice/Create?userGuid={$this->userGuid}", [
                'headers' => [
                    // 'Authorization' => "userGuid {$this->userGuid}"
                ],
                'json' => $data
            ]);
        
            // لاگ کردن پاسخ موفقیت آمیز
            \Log::info('Invoice created', ['response' => json_decode($response->getBody()->getContents(), true)]);
        
        } catch (\Exception $e) {
            // لاگ کردن خطا
            \Log::error('Failed to send invoice data to accounting API: ' . $e->getMessage());
            if ($e->hasResponse()) {
                $response = json_decode($e->getResponse()->getBody()->getContents(), true);
                \Log::error('Response: ' . json_encode($response));
            }
            throw $e;
        }
    }
    public function checkUserExists($phone)
    {
        try {
            $response = $this->client->get("{$this->BaseUrl}/api/Buyer/List?userGuid={$this->userGuid}&search={$phone}", [
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            \Log::error('Failed to check user existence in accounting API: ' . $e->getMessage());
            if ($e->hasResponse()) {
                \Log::error('Response: ' . $e->getResponse()->getBody()->getContents());
            }
            return false;
        }
    }


    public function createUser($userData)
    {
        try {
            $data = [
                "Title" => $userData['title'],
                "FirstName" => $userData['first_name'],
                "Name" => $userData['name'],
                "FatherName" => $userData['father_name'],
                "NationalCode" => $userData['national_code'],
                "CertNo" => $userData['cert_no'],
                "CertDate" => $userData['cert_date'],
                "CardNumber" => $userData['card_number'],
                "AccountNumber" => $userData['account_number'],
                "ShabaNumber" => $userData['shaba_number'],
                "EconomyCode" => $userData['economy_code'],
                "Job" => $userData['job'],
                "ManagerName" => $userData['manager_name'],
                "ClassificationType" => $userData['classification_type'],
                "PriceListCode" => $userData['price_list_code'],
                "PayMethod" => $userData['pay_method'],
                "Discount" => $userData['discount'],
                "Credit" => $userData['credit'],
                "CashPaymentDiscount" => $userData['cash_payment_discount'],
                "CardNo" => $userData['card_no'],
                "Email" => $userData['email'],
                "Site" => $userData['site'],
                "Mobile" => $userData['mobile'],
                "Phone" => $userData['phone'],
                "Fax" => $userData['fax'],
                "Address" => $userData['address'],
                "PostalCode" => $userData['postal_code'],
                "Type" => $userData['type'],
                "HasSeller" => $userData['has_seller'],
                "Deactivated" => $userData['deactivated']
            ];


            
            $response = $this->client->post("{$this->BaseUrl}/api/Buyer/Create?userGuid={$this->userGuid}", [
                'headers' => [
                    // 'Authorization' => "userGuid {$this->userGuid}"
                ],
                'json' => $data
            ]);

            $data= json_decode($response->getBody()->getContents(), true);

            User::where('username', $userData['mobile'])->update(['buyer_code' => $data['BuyerCode'],'guid'=>$data['Guid']]);
            \Log::info('User created');

      

  
        } catch (\Exception $e) {
            \Log::error('Failed to send user data to accounting API: ' . $e->getMessage());
            throw $e;
        }
    }
}
