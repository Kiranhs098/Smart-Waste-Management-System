<?php
class PaymentProcessor {
    // Bank Redirect URLs with more comprehensive list
    private $bankRedirects = [
        'sbi' => 'https://retail.onlinesbi.sbi/retail/login.htm',
        'hdfc' => 'https://netbanking.hdfcbank.com/netbanking/',
        'icici' => 'https://infinity.icicibank.com/corp/Login/LoginWithoutMenu',
        'axis' => 'https://www.axisbank.com/netbanking',
        'pnb' => 'https://netbanking.pnbindia.in/',
        'kotak' => 'https://www.kotak.com/en/personal-banking/digital-banking/netbanking.html',
        'canara' => 'https://netbanking.canarabank.in/',
        'indian_bank' => 'https://netbanking.indianbank.in/'
    ];

    public function processPayment($data) {
        // Validate input and prepare transaction
        $transaction_id = $this->generateTransactionID();

        // Payment method specific processing
        switch ($data['paymentMethod']) {
            case 'netbanking':
                // Check if bank is provided
                if (!isset($data['bank']) || empty($data['bank'])) {
                    return 'a:3:{s:6:"status";s:5:"error";s:7:"message";s:24:"Please select a bank";s:8:"metadata";N;}';
                }
                return $this->processNetBanking($data['bank'], $transaction_id);
            
            case 'upi':
                return $this->processUPIPayment($transaction_id);
            
            case 'cod':
                return $this->processCOD($transaction_id);
            
            default:
                return 'a:3:{s:6:"status";s:5:"error";s:7:"message";s:20:"Invalid payment method";s:8:"metadata";N;}';
        }
    }

    public function getSupportedBanks() {
        // Return the list of supported banks
        return array_keys($this->bankRedirects);
    }

    private function processNetBanking($bank, $transaction_id) {
        // Validate bank
        if (!isset($this->bankRedirects[$bank])) {
            return 'a:3:{s:6:"status";s:5:"error";s:7:"message";s:19:"Bank not supported";s:8:"metadata";N;}';
        }

        return serialize([
            'status' => 'redirect',
            'transaction_id' => $transaction_id,
            'redirect_url' => $this->bankRedirects[$bank]
        ]);
    }

    private function processUPIPayment($transaction_id) {
        // Generate a mock QR code (replace with actual QR code generation)
        $qr_code = $this->generateUPIQRCode($transaction_id);

        return serialize([
            'status' => 'upi_qr',
            'transaction_id' => $transaction_id,
            'qr_code' => $qr_code
        ]);
    }

    private function processCOD($transaction_id) {
        return serialize([
            'status' => 'cod_confirmed',
            'transaction_id' => $transaction_id,
            'message' => 'Cash on Delivery Order Confirmed'
        ]);
    }

    private function generateTransactionID() {
        // Generate a unique transaction ID
        return 'TXN' . time() . rand(1000, 9999);
    }

    private function generateUPIQRCode($transaction_id) {
        // Simulated QR code generation
        return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAYAAAB5fY51AAAAA';
    }
}

// Handle Payment Request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $processor = new PaymentProcessor();
    $result = $processor->processPayment($_POST);
    
    header('Content-Type: text/plain');
    echo $result;
    exit();
}
?>