<?php declare(strict_types=1);

namespace App\Domains\Identity\Login;

use App\Shared\Command;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\Casting\BooleanCast;
use WendellAdriel\ValidatedDTO\Attributes\Cast;

/**
 * @phpstan-template TCommand of Command
 * @extends Command
 */
final class LoginCommand extends Command
{
    /** 
     * User's email address.
     * 
     * @phpstan-var string 
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $email;

    /** 
     * User's password.
     * 
     * @phpstan-var string 
     */
    #[Cast(type: StringCast::class, param: null)]
    public string $password;

    /** 
     * Indicates if the session should be persistent ("remember me").
     * 
     * @phpstan-var bool 
     */
    #[Cast(type: BooleanCast::class, param: null)]
    public bool $rememberMe = false;

    /**
     * Returns validation rules for request data.
     * 
     * @phpstan-return array<string, list<string>>
     */
    protected function rules(): array
    {
        return [
            'email' => [
                'bail',
                'required',
                'email:rfc,strict,spoof',
                'max:254',
            ],
            'password' => [
                'bail',
                'required',
                'string',
                'min:8',
                'max:28'
            ],
            'rememberMe' => [
                'bail',
                'sometimes',
                'boolean'
            ],
        ];
    }

    /**
     * Provides default data values.
     * 
     * @phpstan-return array<string, mixed>
     */
    protected function defaults(): array
    {
        return ['rememberMe' => false];
    }
    
    /**
     * Maps incoming data fields to property names.
     * 
     * @phpstan-return array<string, string>
     */
    protected function mapData(): array
    {
        return [
            'remember_me' => 'rememberMe'
        ];
    }
}
