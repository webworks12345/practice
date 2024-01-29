<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['clientName'];
    $businessCode = $_POST['businessCode'];
    $email = $_POST['email'];
    $phone = $_POST['mobileNumber'];
    $encodedDetails = json_decode(htmlspecialchars_decode($_POST['itemList']), true);
    $amount = preg_replace('/[^0-9.]/', '', $encodedDetails['total']);
    $businessCode = $_POST['businessCode'];
    $packCode = $_POST['branchCode'];
    $packCode = $_POST['packCode'];
    $clientID = $_POST['clientID'];
    $clientName = $_POST['clientName'];
    $mobileNumber = $_POST['mobileNumber'];
    $email = $_POST['email'];
    $pDate = $_POST['pDate'];
    $deliveryDate = $_POST['deliveryDate'];
    $deliveryAddress = $_POST['deliveryAddress'];

    if (isset($encodedDetails['items'])) {
        $itemNames = array();
    
        foreach ($encodedDetails['items'] as $item) {
            if (isset($item['itemName'])) {
                $itemNames[] = $item['itemName'];
            }
        }
    
    } else {
        echo "Items not found in the decoded details.";
    }


    $checkoutData = [
        'data' => [
            'attributes' => [
                'cancel_url' => 'https://example.com/cancel',
                'billing' => [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                ],
                'description' => 'Order Description',
                'line_items' => [
                    [
                        'amount' => $amount * 100, 
                        'currency' => 'PHP',
                        'name' => $itemNames,
                        'quantity' => 1,
                    ],
                ],
                'payment_method_types' => ['card', 'gcash'],
                'reference_number' => 'EzServe',
                'send_email_receipt' => true,
                'show_description' => true,
                'show_line_items' => true,
                'success_url' => 'http://localhost/webworks-lorem-php/?page=client_purchase&businessCode=' . $businessCode . 
                '&packCode=' . urlencode($packCode) . 
                '&branchCode=' . urlencode($branchCode) . 
                '&clientID=' . urlencode($clientID) . 
                '&clientName=' . urlencode($clientName) . 
                '&mobileNumber=' . urlencode($mobileNumber) . 
                '&email=' . urlencode($email) . 
                '&pDate=' . urlencode($pDate) . 
                '&deliveryDate=' . urlencode($deliveryDate) . 
                '&deliveryAddress=' . urlencode($deliveryAddress),

                'statement_descriptor' => 'Payment Example',
            ],
        ],
    ];

    $response = $client->request('POST', 'https://api.paymongo.com/v1/checkout_sessions', [
        'body' => json_encode($checkoutData),
        'headers' => [
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
            'authorization' => 'Basic c2tfdGVzdF9NY2ZZQjZOTFZFZDNvWWdRTXN5WFRXNmQ6',
        ],
    ]);

    $responseData = json_decode($response->getBody(), true);
    
    $_SESSION['checkout_session_id'] = $responseData['data']['id'];
    // Check if the 'data' key exists in the response
    if (isset($responseData['data'])) {
        $checkoutUrl = $responseData['data']['attributes']['checkout_url'];

        // Redirect the user to the checkout URL
        header("Location: $checkoutUrl");
        exit();
    } else {
        // Handle the case where the 'data' key is not present in the response
        echo "Error retrieving checkout URL.";
    }
}
?>
