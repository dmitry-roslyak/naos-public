<?php
namespace App\Classes;

use Vdp\VisaAPIClient;
use Illuminate\Http\Request;
use App\Classes\IPayment;

class VisaPayment implements IPayment
{
    public function pay(Request $request, $cash_amount, $order_id)
    // public static function VisaPay($card, $cash_amount, $order_id)
    {
        $card = $request->card;
        $card_exp_date=''.date('Y')[0].date('Y')[1].$card['expire']['year'].'-'.$card['expire']['month'];
        $d_in_year = str_pad(date('z') + 1, 3, "0", STR_PAD_LEFT);
        $d_h=date('y')[1].$d_in_year.date('H');

        $AuditNumber = substr($order_id, -6) + 0;
        $retrievalReferenceNumber = $d_h.str_pad($AuditNumber, 6, "0", STR_PAD_LEFT);
        $strDate = date('Y-m-d\TH:i:s', time());
        $fundsTransferRequest = json_encode([
            "acquirerCountryCode"=> "804",
            "acquiringBin"=> "408999",
            "businessApplicationId"=> "MD",
            "cardAcceptor"=> [
                "address"=> ["country"=> "UKR"],
                "idCode"=> "ABCD1234ABCD123",
                "name"=> "Visa Inc. USA-Foster City",
                "terminalId"=> "ABCD1234"
            ],
            "amount"=>''.$cash_amount,
            "localTransactionDateTime"=> $strDate,
            "senderCardExpiryDate"=> $card_exp_date,
            "senderCurrencyCode"=> "UAH",
            "senderPrimaryAccountNumber"=> $card['number'],
            "cardCvv2Value"=> $card['cvv2'],
            "foreignExchangeFeeTransaction"=> "0",
            "surcharge"=> "0",
            "systemsTraceAuditNumber"=> ''.$AuditNumber,
            "retrievalReferenceNumber"=> $retrievalReferenceNumber
        ]);
    
        $baseUrl = "visadirect/";
        $resourcePath = "fundstransfer/v1/pullfundstransactions";

        $VisaAPIClientConfig = [
                        "VDP" => [
                                "cert" =>"cert.pem",
                                "key" =>"key.pem",
                                "userId"=> env('VISA_USER_ID'),
                                "password"=>env('VISA_PASSWORD'),
                                "visaUrl"=>"https://sandbox.api.visa.com/"
                        ]
                ];

        $visaAPIClient = new VisaAPIClient($VisaAPIClientConfig);
        $statusCode = $visaAPIClient->doMutualAuthCall('post', $baseUrl.$resourcePath, 'Push Funds Transaction Test', $fundsTransferRequest);
        
        if ($statusCode == 200) {
            $order_state = 'new';
        } else {
            $order_state = 'payment fail';
        }

        return [
            'payment_state' => $statusCode,
            'visa_an' => $AuditNumber,
            'visa_rrn' => $retrievalReferenceNumber,
            'order_state' => $order_state
        ];
    }
}
