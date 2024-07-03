<?php

use App\Models\Bouquet;
use App\Models\Memorial;
use App\Services\PaymentService;
use Illuminate\Http\Response;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Tests\TestCase;

/**
 * @package Tests\Feature
 */
class PaymentsTest extends TestCase
{
    protected string $prefix = 'payments';
    protected PaymentIntent $paymentIntent;

    private Bouquet $bouquet;
    private Memorial $memorial;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $this->memorial = Memorial::factory()->create($this->getMemorialData());

        $this->bouquet = Bouquet::factory()->create($this->getBouquetData($this->memorial));

        $service = new PaymentService();

        $customer = $service->createCustomer([
            'name' => $this->user->name,
            'email' => $this->user->email,
        ]);
        $this->paymentIntent = $service->createPaymentIntent([
            'amount' => $this->faker->numberBetween(100, 1000),
            'customer' => $customer->id,
        ]);

        for ($i = 0; $i < 3; $i++) {
            $service->createPaymentIntent([
                'amount' => $this->faker->numberBetween(100, 1000),
                'customer' => $customer->id,
            ]);
        }

        $this->user->update([
            'pm_type' => 'pm_card_visa',
        ]);
    }

    /**
     * @test
     */
    public function can_bouquet_payment()
    {
        $result = $this->postRequest('bouquet-payment', [
            'amount' => 100,
            'bouquet_id' => $this->bouquet->id,
        ], Response::HTTP_OK);

        $this->assertArrayHasKey('paymentIntent', $result);
        $this->assertArrayHasKey('ephemeralKey', $result);
        $this->assertArrayHasKey('customer', $result);
        $this->assertArrayNotHasKey('error', $result);
    }

    /**
     * @test
     */
    public function can_memorial_payment()
    {
        $result = $this->postRequest('memorial-payment', [
            'amount' => 100,
            'memorial_id' => $this->memorial->id,
        ], Response::HTTP_OK);

        $this->assertArrayHasKey('paymentIntent', $result);
        $this->assertArrayHasKey('ephemeralKey', $result);
        $this->assertArrayHasKey('customer', $result);
        $this->assertArrayNotHasKey('error', $result);
    }

    /**
     * @test
     */
    public function can_succeeded_payment_intent()
    {
        $this->postRequest('webhook', [
            'type' => 'payment_intent.created',
            'data' => [
                'object' => [
                    'id' => 'pi_3PAmrKP9mMWS8EX70ITm89CJ',
                    'payment_method' => 'pm_1PAoEkP9mMWS8EX7yYi232HR',
                    'amount' => 2000,
                ],
            ],
        ], Response::HTTP_NO_CONTENT);
    }

    /**
     * @test
     */
    public function can_get_bouquet_payment_info()
    {
        $this->prefix = 'bouquets';
        $result = $this->getRequest(['payment-info', $this->bouquet->id]);
        $this->assertArrayHasKey('payment_method', $result);
        $this->assertArrayHasKey('amount', $result);
        $this->assertArrayNotHasKey('error', $result);
    }

    /**
     * @test
     */
    public function can_attach_payment_method()
    {
        $this->postRequest('webhook', [
            'type' => 'payment_intent.created',
            'data' => [
                'object' => [
                    'id'       => 'pm_1PAnp6P9mMWS8EX7MoFsoGhr',
                    'customer' => 'cus_Q0pUKBcdxbgUWQ',
                ],
            ],
        ], Response::HTTP_NO_CONTENT);
    }
}
