<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // guests can check out; they just have to give an e-mail
    }

    public function rules(): array
    {
        return [
            'email'        => [$this->user() ? 'nullable' : 'required', 'email:rfc', 'max:255'],
            'full_name'    => ['required', 'string', 'max:255'],
            'phone'        => ['required', 'string', 'max:50'],
            'city'         => ['required', 'string', 'max:100'],
            'address_line' => ['required', 'string', 'max:255'],
            'postal_code'  => ['nullable', 'string', 'max:20'],
            'country'      => ['nullable', 'string', 'max:100'],
        ];
    }

    /**
     * Shipping data in the shape of the `shippings` table.
     *
     * @return array{full_name:string, phone:string, address:string, city:string, country:string}
     */
    public function shipping(): array
    {
        $data = $this->validated();

        return [
            'full_name' => $data['full_name'],
            'phone'     => $data['phone'],
            'address'   => $data['address_line'],
            'city'      => $data['city'],
            'country'   => $data['country'] ?? 'N/A',
        ];
    }
}
