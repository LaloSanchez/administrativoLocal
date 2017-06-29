<?php

/**
 * Clase de Zimbra 'Exception'
 *
 */
namespace sigejupev2\webservice\cliente\zimbra\ZCS;

class Exception extends \Exception
{

    public $error_message = '';

    public function __construct($code)
    {
        parent::__construct(self::getErrorMessage($code));
        $this->error_message = self::getErrorMessage($code);
    }

    private static function getErrorMessage($code)
    {
        switch ($code) {
            case 'account.ACCOUNT_EXISTS':
                return "La cuenta ya existe";
                break;
            case 'account.NO_SUCH_ACCOUNT':
                return "No hay cuentas con el criterio recibido";
                break;
            case 'account.DISTRIBUTION_LIST_EXISTS':
                return "La lista de distribucion ya existe";
                break;
            case 'service.PROXY_ERROR':
                return "Error de proxy";
                break;
            case 'account.NO_SUCH_DOMAIN':
                return "No se encontro el dominio";
                break;
            default:
                return sprintf("An unexpected error has occurred (%s)", $code);
        }
    }

}
