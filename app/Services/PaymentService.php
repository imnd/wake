<?php

namespace App\Services;

use App\Helpers\Statuses;
use App\Models\Bouquet;
use App\Models\Memorial;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Stripe\{Customer, EphemeralKey, PaymentIntent, Stripe, StripeClient};
use Stripe\Exception\ApiErrorException;

class PaymentService
{
    private StripeClient $stripe;

    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }

    public function createCustomer(array $data): ?Customer
    {
        try {
            return Customer::create($data);
        } catch (ApiErrorException $e) {
            Log::error($e);
            return null;
        }
    }

    public function createPaymentIntent(array $data): PaymentIntent
    {
        $data['amount'] = $data['amount'] * 100;

        return $this->stripe->paymentIntents->create(
            array_merge($data, [
                // In the latest version of the API, specifying the `automatic_payment_methods` parameter
                // is optional because Stripe enables its functionality by default.
                'currency' => config('app.payment.currency'),
                'automatic_payment_methods' => [
                    'enabled' => 'true',
                ],
            ])
        );
    }

    public function createEphemeralKey($customerId): EphemeralKey
    {
        return $this->stripe->ephemeralKeys->create([
            'customer' => $customerId,
        ], [
            'stripe_version' => '2024-04-10',
        ]);
    }

    public function setPayment(Authenticatable $user, Model $model, float $amount): array
    {
        $customer = $this->createCustomer([
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $ephemeralKey = $this->createEphemeralKey($customer->id);

        $paymentIntent = $this->createPaymentIntent([
            'amount' => $amount,
            'customer' => $customer->id,
        ]);

        $model->update([
            'payment_intent_id' => $paymentIntent->id,
            'amount' => $amount,
        ]);

        return [
            'paymentIntent' => $paymentIntent->client_secret,
            'ephemeralKey' => $ephemeralKey->secret,
            'customer' => $customer->id,
            'publishableKey' => env('STRIPE_KEY')
        ];
    }

    # Webhook

    /**
     * Handle successful payment
     */
    public function finalizePayment(array $data): Bouquet
    {
        $object = $data['object'];

        if (
            !$model = Bouquet::where([
                'payment_intent_id' => $object['id'],
            ])->first()
        ) {
            $model = Memorial::where([
                'payment_intent_id' => $object['id'],
            ])->first();
        }

        $paymentMethod = $this->stripe->paymentMethods->retrieve($object['payment_method'], []);

        $model->update([
            'payment_method' => $paymentMethod->type,
            'status' => Statuses::STATUS_PAID,
        ]);

        return $model;
    }

    /**
     * Handle payment method attachment
     */
    public function attachPaymentMethod(array $data): void
    {
        // Retrieve the payment method ID and customer ID from the payload
        $object = $data['object'];

        // update the user's default payment method
        if ($user = User::where('stripe_id', $object['customer'])->first()) {
            $user->update(['pm_type' => $object['id']]);
        }
    }
}
