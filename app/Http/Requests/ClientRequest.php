<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'phone' => ['required', 'string', 'min:10', 'max:16'],
            'cpf' => [
                'required',
                'string',
                'size:11',
                Rule::unique('clients')->ignore($this->route('client')),
                function ($attribute, $value, $fail) {
                    if (!$this->validarCPF($value)) {
                        $fail('O CPF informado não é válido.');
                    }
                },
            ],
            'plate' => ['required', 'string', 'min:8', 'max:8'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.min' => 'O nome deve conter pelo menos :min caracteres.',
            'name.max' => 'O nome não pode ter mais que :max caracteres.',
            'phone.required' => 'O campo telefone é obrigatório.',
            'phone.min' => 'O telefone deve ter pelo menos :min caracteres.',
            'phone.max' => 'O telefone não pode ter mais que :max caracteres.',
            'cpf.required' => 'O campo CPF é obrigatório.',
            'cpf.size' => 'O CPF deve ter exatamente :size caracteres.',
            'cpf.unique' => 'Este CPF já está em uso.',
            'cpf' => 'O CPF informado não é válido.',
            'plate.required' => 'O campo placa é obrigatório.',
            'plate.min' => 'A placa deve ter pelo menos :min caracteres.',
            'plate.max' => 'A placa não pode ter mais que :max caracteres.',
        ];
    }

    /**
     * Validate CPF.
     *
     * @param string $cpf
     * @return bool
     */
    protected function validarCPF(string $cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) !== 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($i = 9, $j = 0, $soma = 0; $j < $i; $j++) {
            $soma += $cpf[$j] * (($i + 1) - $j);
        }
        $soma = ((10 * $soma) % 11) % 10;
        if ($cpf[$j] != $soma) {
            return false;
        }

        return true;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'error' => true,
            'message' => 'Erro de validação',
            'data' => $validator->errors(),
        ], 422));
    }
}
