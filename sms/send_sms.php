<?php

require_once "config.php";
require_once "../config/database.php";

include_once(__DIR__ . "/../zenoph/lib/Zenoph/Notify/AutoLoader.php");

use Zenoph\Notify\Request\SMSRequest;
use Zenoph\Notify\Enums\AuthModel;
use Zenoph\Notify\Enums\SMSType;

function sendSMS($appointment_id)
{
    global $conn;

    $sql = "SELECT
                customers.customer_name,
                customers.phone,
                services.service_name,
                payments.amount,
                payments.payment_date
            FROM payments
            INNER JOIN appointments
                ON payments.appointment_id = appointments.id
            INNER JOIN customers
                ON appointments.customer_id = customers.id
            INNER JOIN services
                ON appointments.service_id = services.id
            WHERE payments.appointment_id='$appointment_id'
            ORDER BY payments.id DESC
            LIMIT 1";

    $result = mysqli_query($conn, $sql);

    if (!$result || mysqli_num_rows($result) == 0) {
        return false;
    }

    $row = mysqli_fetch_assoc($result);

    $customer = $row['customer_name'];
    $phone = trim($row['phone']);
    $service = $row['service_name'];
    $amount = number_format($row['amount'],2);
    $date = date("d M Y", strtotime($row['payment_date']));

    if(substr($phone,0,1)=="0"){
        $phone="233".substr($phone,1);
    }

    $message =
SALON_NAME .
"\n\nDear $customer," .
"\n\nYour payment has been received successfully." .
"\n\nService: $service" .
"\nAmount: GHS $amount" .
"\nDate: $date" .
"\n\nThank you for choosing ".SALON_NAME.".";

    $status="Failed";
    $response_message="";

    try{

        $request = new SMSRequest();

        $request->setHost(SMS_HOST);

        $request->setAuthModel(AuthModel::API_KEY);

        $request->setAuthApiKey(SMS_API_KEY);

        $request->setSender(SMS_SENDER_ID);

        $request->setMessage($message);

        $request->setSMSType(SMSType::GSM_DEFAULT);

        $request->addDestination($phone);

        $response = $request->submit();

        if ($response->getReport() != null) {
        
            $status = "Sent";
            $response_message = "SMS sent successfully.";
        
        } else {
        
            $status = "Failed";
            $response_message = "No delivery report received.";
        
        }

    }catch(Exception $e){

        $status="Failed";

        $response_message=$e->getMessage();

    }

    mysqli_query($conn,"
        INSERT INTO sms_logs
        (
            customer_name,
            phone,
            service_name,
            amount,
            message,
            status,
            response_message,
            provider
        )
        VALUES
        (
            '$customer',
            '$phone',
            '$service',
            '{$row['amount']}',
            '".mysqli_real_escape_string($conn,$message)."',
            '$status',
            '".mysqli_real_escape_string($conn,$response_message)."',
            'SMSOnlineGH'
        )
    ");

    return ($status=="Sent");

}