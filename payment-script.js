document.addEventListener('DOMContentLoaded', () => {
    const paymentForm = document.getElementById('paymentForm');
    const paymentMethodCards = document.querySelectorAll('.payment-method-card');
    const cardDetails = document.getElementById('cardDetails');
    const bankSelectionContainer = document.getElementById('bankSelectionContainer');
    const bankSelect = document.getElementById('bankSelect');

    // Payment Method Selection
    paymentMethodCards.forEach(card => {
        card.addEventListener('click', function() {
            // Reset all cards
            paymentMethodCards.forEach(c => c.classList.remove('active'));
            
            // Activate clicked card
            this.classList.add('active');
            
            // Select corresponding radio button
            const radioButton = this.querySelector('input[type="radio"]');
            radioButton.checked = true;

            // Show/hide card details and bank selection
            const method = this.dataset.method;
            cardDetails.style.display = method === 'card' ? 'block' : 'none';
            bankSelectionContainer.style.display = method === 'netbanking' ? 'block' : 'none';

            // Manage bank select required attribute
            if (method === 'netbanking') {
                bankSelect.setAttribute('required', 'required');
            } else {
                bankSelect.removeAttribute('required');
            }
        });
    });

    // Advanced PHP unserialization function
    function unserializePHP(str) {
        console.log('Raw response:', str); // Log raw response for debugging

        try {
            // Regex patterns to extract PHP serialized data
            const statusMatch = str.match(/s:6:"status";s:\d+:"([^"]+)"/);
            const messageMatch = str.match(/s:7:"message";s:\d+:"([^"]+)"/);
            const transactionIdMatch = str.match(/s:14:"transaction_id";s:\d+:"([^"]+)"/);
            const redirectUrlMatch = str.match(/s:12:"redirect_url";s:\d+:"([^"]+)"/);

            const result = {
                status: statusMatch ? statusMatch[1] : null,
                message: messageMatch ? messageMatch[1] : null,
                transaction_id: transactionIdMatch ? transactionIdMatch[1] : null,
                redirect_url: redirectUrlMatch ? redirectUrlMatch[1] : null
            };

            console.log('Parsed result:', result); // Log parsed result
            return result;
        } catch (e) {
            console.error('Unserialization error:', e);
            console.error('Original string:', str);
            return null;
        }
    }

    // Form Submission
    paymentForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Basic validation
        if (!paymentForm.checkValidity()) {
            paymentForm.classList.add('was-validated');
            return;
        }

        // Additional validation for netbanking
        const selectedMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
        if (selectedMethod === 'netbanking' && !bankSelect.value) {
            Swal.fire({
                icon: 'error',
                title: 'Bank Selection Required',
                text: 'Please select your bank for net banking payment.'
            });
            return;
        }

        // Collect form data
        const formData = new FormData(paymentForm);

        try {
            const response = await fetch('process_payment.php', {
                method: 'POST',
                body: formData
            });

            const text = await response.text();
            
            // Use advanced unserialize function
            const data = unserializePHP(text);

            if (!data || !data.status) {
                Swal.fire({
                    icon: 'error',
                    title: 'Parsing Error',
                    text: 'Failed to parse server response. Raw response: ' + text
                });
                return;
            }

            // Detailed status handling
            switch (data.status) {
                case 'redirect':
                    Swal.fire({
                        icon: 'info',
                        title: 'Redirecting to Bank',
                        text: 'You will be redirected to your bank\'s net banking portal.',
                        confirmButtonText: 'Proceed',
                        willClose: () => {
                            window.location.href = data.redirect_url;
                        }
                    });
                    break;

                case 'success':
                case 'cod_confirmed':
                case 'upi_qr':
                    Swal.fire({
                        icon: 'success',
                        title: 'Payment Successful!',
                        text: `Transaction ID : ${data.transaction_id}`,
                        confirmButtonText : 'Continue'
                    }).then(() => {
                        window.location.href = 'order-confirmation.php';
                    });
                    break;

                case 'error':
                default:
                    Swal.fire({
                        icon: 'error',
                        title: 'Payment Failed',
                        text: data.message || 'An unexpected error occurred during payment processing.',
                        footer: `Raw Status: ${data.status}`
                    });
                    break;
            }
        } catch (error) {
            console.error('Submission Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Submission Error',
                text: 'An unexpected error occurred during submission.'
            });
        }
    });
});