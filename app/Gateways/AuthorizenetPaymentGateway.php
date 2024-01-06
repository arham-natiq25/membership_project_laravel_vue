<?php

namespace App\Gateways;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;
use App\Interfaces\Payments;
use App\Models\CustomerProfile;
use App\Models\TransactionRecords;

class AuthorizenetPaymentGateway implements Payments {
    function charge($request)
    {

    }

    function chargeGuest($request, $user)
    {
          /* Create a merchantAuthenticationType object with authentication details
           retrieved from the constants file */
           $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
           $merchantAuthentication->setName(config('authorize.net.authorize_login_id'));
           $merchantAuthentication->setTransactionKey(config('authorize.net.authorize_trx_key'));

           $refId = 'ref' . time();
           $customerProfileIDFromAuthorize = null;
           $paymentProfileId=null;


           try {
                $creditCard = new AnetAPI\CreditCardType();
                $creditCard->setCardNumber($request->cardNumber);
                $creditCard->setExpirationDate($request->expiryYear."-".$request->expiryMonth);
                $creditCard->setCardCode($request->cvv);



                $paymentOne = new AnetAPI\PaymentType();
                $paymentOne->setCreditCard($creditCard);


                // Set the customer's Bill To address
                $customerAddress = new AnetAPI\CustomerAddressType();
                $customerAddress->setFirstName($user->name);
                $customerAddress->setLastName($user->name);
                $customerAddress->setEmail($user->email); // user emial
                $customerAddress->setPhoneNumber($request->phone); // user emial


                $customerData = new AnetAPI\CustomerDataType();
                $customerData->setType("individual");
                $customerData->setId($user->id); // give own user id
                $customerData->setEmail($user->email); // user emial



                $duplicateWindowSetting = new AnetAPI\SettingType();
                $duplicateWindowSetting->setSettingName("duplicateWindow");
                $duplicateWindowSetting->setSettingValue("60");

                $billTo = new AnetAPI\CustomerAddressType();
                $billTo->setFirstName($user->name);
                $billTo->setLastName($user->name);

                $paymentProfile = new AnetAPI\CustomerPaymentProfileType();
                $paymentProfile->setCustomerType('individual');
                $paymentProfile->setBillTo($billTo);
                $paymentProfile->setPayment($paymentOne);
                $paymentProfiles[] = $paymentProfile;



                $customerProfile = new AnetAPI\CustomerProfileType();
                $customerProfile->setDescription("Payment for Buy Membership");
                $customerProfile->setMerchantCustomerId("M_" . time());
                $customerProfile->setEmail($user->email); // Set the email for the customer
                $customerProfile->setPaymentProfiles($paymentProfiles);


                $REQUEST = new AnetAPI\CreateCustomerProfileRequest();
                $REQUEST->setMerchantAuthentication($merchantAuthentication);
                $REQUEST->setRefId($refId);
                $REQUEST->setProfile($customerProfile);
                $controller = new AnetController\CreateCustomerProfileController($REQUEST);
                $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);



                if ($response != null && $response->getMessages()->getResultCode() == "Ok") {
                    // here successfully get customer profile id
                    $customerProfileIDFromAuthorize = $response->getCustomerProfileId();
                    $paymentProfileId = $response->getCustomerPaymentProfileIdList()[0];
                } else {
                    // Handle the case where the API request was not successful
                    $errorMessages = $response->getMessages()->getMessage();
                }
                $profileToCharge = new AnetAPI\CustomerProfilePaymentType();
                $profileToCharge->setCustomerProfileId($customerProfileIDFromAuthorize);
                $paymentProfile = new AnetAPI\PaymentProfileType();
                $paymentProfile->setPaymentProfileId($paymentProfileId);
                $profileToCharge->setPaymentProfile($paymentProfile);

                $transactionRequestType = new AnetAPI\TransactionRequestType();
                $transactionRequestType->setTransactionType("authCaptureTransaction");
                $transactionRequestType->setAmount($request->totalPayment);
                $transactionRequestType->setProfile($profileToCharge);

                $REQUEST = new AnetAPI\CreateTransactionRequest();
                $REQUEST->setMerchantAuthentication($merchantAuthentication);
                $REQUEST->setRefId($refId);
                $REQUEST->setTransactionRequest($transactionRequestType);
                $controller = new AnetController\CreateTransactionController($REQUEST);
                $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
                if ($response != null) {
                    if ($response->getMessages()->getResultCode() == "Ok") {
                        $tresponse = $response->getTransactionResponse();
                        $lastFourDigitsFull = $tresponse->getAccountNumber(); // Assuming this contains the full card number
                        $lastFourDigits = substr($lastFourDigitsFull, -4);
                        $cardType = $tresponse->getAccountType();
                        $trx_id =  $tresponse->getTransId() . "\n";

                        TransactionRecords::create([
                            'customer_id' => $user->id,
                            'payment' => $request->totalPayment,
                            'trx_id' => $trx_id,
                            'payment_for' => 0 // 0 for membership
                        ]);

                        //  CustomerProfile::create([
                        //     'customer_id' => $user->id,
                        //     'last_four_digits' => $lastFourDigits,
                        //     'customer_payment_id' => $customerProfileIDFromAuthorize,
                        //     'paymentMethodId' => $paymentProfileId
                        // ]);

                        return response()->json(['success' => true, 'message' => 'Payment successful']);
                    }
            }
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);

        }

    }
}
