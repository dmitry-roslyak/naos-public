<?php
namespace App\Classes;
// namespace Vdp;

use Vdp\VisaAPIClient as Visa;
use Illuminate\Http\Request;

use App\Order;

class VisaPayment
{
    public static function pay($deliver_type,$deliver_adr,$user_id,$cart_uuid,$cash_amount,$card,$user_info)
    {
		$card_exp_date=''.date('Y')[0].date('Y')[1].$card['expire']['year'].'-'.$card['expire']['month'];
		$cash_amount=round($cash_amount, 2);
		$AuditNumber=0;
		$d_in_year=date('z');//visa 366 в php 365?$d_in_year+1
		if($d_in_year<'100') $d_in_year='0'.$d_in_year;
		else if($d_in_year<'10') $d_in_year='00'.$d_in_year;
		$d_h=date('y')[1].$d_in_year.date('H');
		
		$order_id = Order::insert([
        [
			'user_id' =>$user_id,
			'cart_uuid' =>$cart_uuid,
			'deliver_type' =>$deliver_type,
			'deliver_adr' =>$deliver_adr,
			'price' =>$cash_amount,
			'payment_type' => 'visa',
			'visa_an'=>($AuditNumber=Order::orderBy('id','desc')->value('visa_an'))>100000&&$AuditNumber<999999?$AuditNumber+1:100001,
			'visa_rrn'=>$d_h.$AuditNumber,
			'name' => $user_info['fname'].' '.$user_info['lname'],
			'email'=>'',
			'phone'=> $user_info['tel'],
			'payment_state'=>'pending',
			'order_state'=>'pending']	
		]);
		$retrievalReferenceNumber=$d_h.$AuditNumber;
		$visaAPIClient = new  Visa;
		$strDate = date('Y-m-d\TH:i:s', time());
		$fundsTransferRequest = json_encode ( [ 
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
		] );
	
		$baseUrl = "visadirect/";
		$resourcePath = "fundstransfer/v1/pullfundstransactions";
		$statusCode = $visaAPIClient->doMutualAuthCall ( 'post', $baseUrl.$resourcePath, 'Push Funds Transaction Test', $fundsTransferRequest );

		if($statusCode==200){$order_state='new';}
		else $order_state='payment fail';
		
		// Order::where('id',$order_id)->update([
		// 	'payment_state'=>$statusCode,
		// 	'order_state'=>$order_state
		// ]);
		// $this->assertEquals($statusCode, "200");
    }
}
