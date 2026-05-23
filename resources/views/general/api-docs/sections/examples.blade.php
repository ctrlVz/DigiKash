<!-- Integration Examples Section -->
<section id="integration-examples" class="content-section">
    <h2>{{ __('Integration Examples') }}</h2>
    <p>{{ __('Complete integration examples for popular platforms and frameworks.') }}</p>

    <!-- Environment Configuration Note -->
    <div class="alert alert-warning mb-4">
        <i class="fas fa-info-circle me-2"></i>
        <strong>{{ __('Environment Configuration') }}:</strong> {{ __('Replace {environment} with sandbox or production, and use corresponding credentials - test_ prefix for sandbox, no prefix for production in your configuration files.') }}
    </div>

    <div class="language-tabs">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="#example-laravel">
                    <i class="fa-brands fa-php text-primary me-1" aria-hidden="true"></i>{{ __('PHP (Laravel)') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#example-nodejs">
                    <i class="fa-brands fa-node-js text-success me-1" aria-hidden="true"></i>{{ __('Node.js') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#example-python">
                    <i class="fa-brands fa-python text-primary me-1" aria-hidden="true"></i>{{ __('Python') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#example-curl">
                    <i class="fa-solid fa-terminal text-dark me-1" aria-hidden="true"></i>{{ __('cURL') }}
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="example-laravel">
                <div class="code-block">
                    <pre><code class="php">&lt;?php
// Laravel Integration Service
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class DigiKashService
{
    private string $baseUrl;
    private string $merchantKey;
    private string $apiKey;
    private string $environment;

    public function __construct()
    {
        $this->baseUrl = config('digikash.base_url');
        $this->merchantKey = config('digikash.merchant_key');
        $this->apiKey = config('digikash.api_key');
        $this->environment = config('digikash.environment'); // 'sandbox' or 'production'
    }

    public function initiatePayment(array $paymentData): array
    {
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-Environment' => $this->environment,
                'X-Merchant-Key' => $this->merchantKey,
                'X-API-Key' => $this->apiKey,
            ])->post("{$this->baseUrl}/api/v1/initiate-payment", $paymentData);

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('Payment initiation failed');
        } catch (Exception $e) {
            throw new Exception('DigiKash API Error: ' . $e->getMessage());
        }
    }

    public function verifyPayment(string $transactionId): array
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'X-Environment' => $this->environment,
                'X-Merchant-Key' => $this->merchantKey,
                'X-API-Key' => $this->apiKey,
            ])->get("{$this->baseUrl}/api/v1/verify-payment/{$transactionId}");

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('Payment verification failed');
        } catch (Exception $e) {
            throw new Exception('DigiKash API Error: ' . $e->getMessage());
        }
    }
}

// Configuration (config/digikash.php)
return [
    'base_url' => env('DIGIKASH_BASE_URL', 'https://digikash.coevs.com'),
    'environment' => env('DIGIKASH_ENVIRONMENT', 'sandbox'), // sandbox or production
    'merchant_key' => env('DIGIKASH_MERCHANT_KEY'), // Use appropriate prefix
    'api_key' => env('DIGIKASH_API_KEY'), // Use appropriate prefix
];

// Usage in Controller
class PaymentController extends Controller
{
    public function initiatePayment(Request $request, DigiKashService $digikash)
    {
        $paymentData = [
            'payment_amount' => $request->amount,
            'currency_code' => 'USD',
            'ref_trx' => 'ORDER_' . time(),
            'description' => $request->description,
            'success_redirect' => route('payment.success'),
            'failure_url' => route('payment.failed'),
            'cancel_redirect' => route('payment.cancelled'),
            'ipn_url' => route('webhooks.digikash'),
        ];

        try {
            $result = $digikash->initiatePayment($paymentData);
            return redirect($result['payment_url']);
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}</code></pre>
                </div>
            </div>
            <div class="tab-pane fade" id="example-nodejs">
                <div class="code-block">
                    <pre><code class="javascript">// Node.js Integration Service
const axios = require('axios');

class DigiKashService {
    constructor() {
        this.baseUrl = process.env.DIGIKASH_BASE_URL || 'https://digikash.coevs.com';
        this.environment = process.env.DIGIKASH_ENVIRONMENT || 'sandbox'; // sandbox or production
        this.merchantKey = process.env.DIGIKASH_MERCHANT_KEY; // Use appropriate prefix
        this.apiKey = process.env.DIGIKASH_API_KEY; // Use appropriate prefix
    }

    async initiatePayment(paymentData) {
        try {
            const response = await axios.post(`${this.baseUrl}/api/v1/initiate-payment`, paymentData, {
                headers: {
                    'Content-Type': 'application/json',
                    'X-Environment': this.environment,
                    'X-Merchant-Key': this.merchantKey,
                    'X-API-Key': this.apiKey
                }
            });

            return response.data;
        } catch (error) {
            throw new Error(`DigiKash API Error: ${error.message}`);
        }
    }

    async verifyPayment(transactionId) {
        try {
            const response = await axios.get(`${this.baseUrl}/api/v1/verify-payment/${transactionId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Environment': this.environment,
                    'X-Merchant-Key': this.merchantKey,
                    'X-API-Key': this.apiKey
                }
            });

            return response.data;
        } catch (error) {
            throw new Error(`DigiKash API Error: ${error.message}`);
        }
    }
}

// Express.js Route Example
const express = require('express');
const app = express();
const digikash = new DigiKashService();

app.post('/initiate-payment', async (req, res) => {
    const paymentData = {
        payment_amount: req.body.amount,
        currency_code: 'USD',
        ref_trx: `ORDER_${Date.now()}`,
        description: req.body.description,
        success_redirect: `${req.protocol}://${req.get('host')}/payment/success`,
        failure_url: `${req.protocol}://${req.get('host')}/payment/failed`,
        cancel_redirect: `${req.protocol}://${req.get('host')}/payment/cancelled`,
        ipn_url: `${req.protocol}://${req.get('host')}/webhooks/digikash`,
    };

    try {
        const result = await digikash.initiatePayment(paymentData);
        res.redirect(result.payment_url);
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

module.exports = DigiKashService;</code></pre>
                </div>
            </div>
            <div class="tab-pane fade" id="example-python">
                <div class="code-block">
                    <pre><code class="python"># Python/Django Integration Service
import os
import requests
from django.conf import settings

class DigiKashService:
    def __init__(self):
        self.base_url = getattr(settings, 'DIGIKASH_BASE_URL', 'https://digikash.coevs.com')
        self.environment = getattr(settings, 'DIGIKASH_ENVIRONMENT', 'sandbox')  # sandbox or production
        self.merchant_key = getattr(settings, 'DIGIKASH_MERCHANT_KEY')  # Use appropriate prefix
        self.api_key = getattr(settings, 'DIGIKASH_API_KEY')  # Use appropriate prefix

    def initiate_payment(self, payment_data):
        try:
            headers = {
                'Content-Type': 'application/json',
                'X-Environment': self.environment,
                'X-Merchant-Key': self.merchant_key,
                'X-API-Key': self.api_key
            }

            response = requests.post(
                f"{self.base_url}/api/v1/initiate-payment",
                headers=headers,
                json=payment_data,
                timeout=30
            )

            response.raise_for_status()
            return response.json()

        except requests.RequestException as e:
            raise Exception(f'DigiKash API Error: {str(e)}')

    def verify_payment(self, transaction_id):
        try:
            headers = {
                'Accept': 'application/json',
                'X-Environment': self.environment,
                'X-Merchant-Key': self.merchant_key,
                'X-API-Key': self.api_key
            }

            response = requests.get(
                f"{self.base_url}/api/v1/verify-payment/{transaction_id}",
                headers=headers,
                timeout=30
            )

            response.raise_for_status()
            return response.json()

        except requests.RequestException as e:
            raise Exception(f'DigiKash API Error: {str(e)}')

# Django Settings Configuration
DIGIKASH_BASE_URL = 'https://digikash.coevs.com'
DIGIKASH_ENVIRONMENT = 'sandbox'  # Change to 'production' for live
DIGIKASH_MERCHANT_KEY = os.environ.get('DIGIKASH_MERCHANT_KEY')  # Use appropriate prefix
DIGIKASH_API_KEY = os.environ.get('DIGIKASH_API_KEY')  # Use appropriate prefix

# Django View Example
from django.shortcuts import redirect
from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt
import json

digikash = DigiKashService()

@csrf_exempt
def initiate_payment(request):
    if request.method == 'POST':
        data = json.loads(request.body)
        
        payment_data = {
            'payment_amount': data['amount'],
            'currency_code': 'USD',
            'ref_trx': f'ORDER_{int(time.time())}',
            'description': data['description'],
            'success_redirect': request.build_absolute_uri('/payment/success/'),
            'failure_url': request.build_absolute_uri('/payment/failed/'),
            'cancel_redirect': request.build_absolute_uri('/payment/cancelled/'),
            'ipn_url': request.build_absolute_uri('/webhooks/digikash/'),
        }

        try:
            result = digikash.initiate_payment(payment_data)
            return redirect(result['payment_url'])
        except Exception as e:
            return JsonResponse({'error': str(e)}, status=500)</code></pre>
                </div>
            </div>
            <div class="tab-pane fade" id="example-curl">
                <div class="code-block">
                    <pre><code class="bash"># Environment Variables Setup
export DIGIKASH_ENVIRONMENT="sandbox"  # or "production"
export DIGIKASH_MERCHANT_KEY="test_merchant_your_key"  # or "merchant_your_key" for production
export DIGIKASH_API_KEY="test_your_api_key"  # or "your_api_key" for production

# Initiate Payment
curl -X POST "https://digikash.coevs.com/api/v1/initiate-payment" \
  -H "Content-Type: application/json" \
  -H "X-Environment: $DIGIKASH_ENVIRONMENT" \
  -H "X-Merchant-Key: $DIGIKASH_MERCHANT_KEY" \
  -H "X-API-Key: $DIGIKASH_API_KEY" \
  -d '{
    "payment_amount": 250.00,
    "currency_code": "USD",
    "ref_trx": "ORDER_12345",
    "description": "Premium Subscription",
    "success_redirect": "https://yoursite.com/payment/success",
    "failure_url": "https://yoursite.com/payment/failed",
    "cancel_redirect": "https://yoursite.com/payment/cancelled",
    "ipn_url": "https://yoursite.com/api/webhooks/digikash"
  }'

# Verify Payment
curl -X GET "https://digikash.coevs.com/api/v1/verify-payment/TXNQ5V8K2L9N3XM1" \
  -H "Accept: application/json" \
  -H "X-Environment: $DIGIKASH_ENVIRONMENT" \
  -H "X-Merchant-Key: $DIGIKASH_MERCHANT_KEY" \
  -H "X-API-Key: $DIGIKASH_API_KEY"

# Environment-specific credential examples:
# Sandbox: test_merchant_xxxxx, test_api_key_xxxxx
# Production: merchant_xxxxx, api_key_xxxxx</code></pre>
                </div>
            </div>
        </div>
    </div>
</section>
