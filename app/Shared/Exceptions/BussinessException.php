<?php

namespace App\Shared\Exceptions;

use Exception;
use Throwable;

abstract class BussinessException extends Exception
{
it('rejects zero and negative quantities', function (int $value): void {
    expect(fn () => new Quantity($value))->toThrow(InvalidArgumentException::class);
})->with([0, -5]);



✅ Punto de control: docker compose exec app ./vendor/bin/pest tests/Unit/Shared pasa con todos los tests nuevos.

Paso 1.7: La excepción base del negocio 📄

Todas las excepciones de negocio de los tres módulos (fondos insuficientes, ticker desconocido, etc.) extienden de esta clase. Cada una tiene:





Un mensaje que escribís vos, en inglés. Es el que queda en los logs y el que ves en los tests.



Una errorKey, por ejemplo wallet.insufficient_funds. Es un código estable que el frontend puede usar y, además, la clave de traducción.



Los params que se reemplazan en la traducción (:ticker, :available...).

<?php

// app/Shared/Exceptions/BusinessException.php

declare(strict_types=1);

namespace App\Shared\Exceptions;

use Exception;
use Throwable;

abstract class BusinessException extends Exception
{
    /**
     * @param string $message mensaje propio (ingles), para logs
     * @param string $errorKey clave estable: "<modulo>.<problema>"
     * @param array<string, string|int> $params valores para la traducción
     */
    public function __construct(
        string $message,
        public readonly string $errorKey,
        public readonly array $params = [],
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }
}
