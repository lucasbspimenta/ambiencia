<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use stdClass;

class LDAPService
{
    const LDAP_SERVER = 'ldap://ldapcluster.corecaixa:489';
    const LDAP_BASE = 'ou=People,o=caixa';
    const LDAP_USER_FILTER = '(uid=%s)';

    public static function findByMatricula($matricula): ?stdClass
    {
        $ldapHandle = ldap_connect(self::LDAP_SERVER);
        $searchBase = self::LDAP_BASE;
        $searchFilter = self::LDAP_USER_FILTER;

        $searchFilter = sprintf($searchFilter, $matricula);

        try {

            $searchHandle = ldap_search($ldapHandle, $searchBase, $searchFilter);

            if($searchHandle)
                return null;
                //throw new Exception("Servidor de Autenticação Indisponível (LDAP: erro na consulta)");

            $resultados = ldap_get_entries($ldapHandle, $searchHandle);

            if(!$resultados['count'] != 1)
                return null;
                //throw new Exception("Usuário não localizado. Verifique a matrícula informada.");

            $usuario = $resultados[0];

            $object = new stdClass();

            $object->nome = trim(strtoupper($usuario['no-usuario'][0]));
            $object->matricula   = trim(strtoupper($usuario['co-usuario'][0]));
            $object->fisica = intval($usuario['nu-lotacaofisica'][0]);
            $object->unidade = intval($usuario['co-unidade'][0]);
            $object->funcao = trim(strtoupper($usuario['no-funcao'][0]));
            $object->cargo = trim(strtoupper($usuario['no-cargo'][0]));
            $object->email = trim(strtoupper($usuario['mail'][0]));

            return $object;
        }
        catch (Exception $e)
        {
            Log::info($e->getMessage());
            return null;
            //throw new Exception("Erro no acesso ao LDAP");
        }
    }
}
