<?php

namespace Database\Factories;

use App\Domain\Categories\Models\Category;
use App\Domain\Currencies\Models\Currency;
use App\Domain\Microsites\Models\Microsite;
use App\Domain\Microsites\Models\MicrositeType;
use App\Support\Definitions\MicrositesTypes;
use App\Support\Definitions\Status;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

/**
 * @extends Factory<Microsite>
 */
class MicrositeFactory extends Factory
{
    protected $model = Microsite::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'microsites_type_id' => MicrositeType::factory(),
            'category_id' => Category::factory(),
            'name' => fake()->name,
            'logo_path' => UploadedFile::fake()
                ->image('microsite_image.png', 640, 480),
            'description' => fake()->paragraph(),
            'currency_id' => Currency::factory(),
            'date_expire_pay' => fake()->date(),
            'status' => Status::ACTIVE->value,
            'fields' => [],
        ];
    }

    public function invoiceType(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'microsites_type_id' => MicrositesTypes::INVOICE->value,
            ];
        });
    }
}
