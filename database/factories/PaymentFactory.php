<?php

namespace Database\Factories;

use App\Domain\Invoices\Models\Invoice;
use App\Domain\Payments\Models\Payment;
use App\Domain\Microsites\Models\Microsite;
use App\Support\Definitions\DocumentsTypes;
use App\Support\Definitions\PaymentGateway;
use App\Support\Definitions\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->email(),
            'value' => '60000',
            'invoice_id' => Invoice::factory(),
            'type_document' => DocumentsTypes::CC->value,
            'num_document' => '123455',
            'microsite_id' => Microsite::factory(),
            'status' => PaymentStatus::PENDING->value,
            'payment_type' => PaymentGateway::PLACETOPAY->value,
            'reference' => 'PAYMENT_MICROSITE_'. date('ymdHis'),
        ];
    }
}
