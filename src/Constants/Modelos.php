<?php

namespace Jcf\EspiaoNfe\Constants;

class Modelos
{
    public const NFE = '55';
    public const NFCE = '65';
    public const CTE = '57';
    public const CTE_OS = '67';
    public const SAT = '59';
    public const NFSE = '41';

    /**
     * Valida se o modelo está na lista de permitidos.
     *
     * @param string $modelo O modelo a ser validado
     * @param array<string> $permitidos Lista de modelos permitidos
     * @param string $contexto Contexto para mensagem de erro (ex: "NF-e", "CT-e")
     * @return void
     * @throws \InvalidArgumentException Se o modelo for inválido
     */
    public static function validar(string $modelo, array $permitidos, string $contexto = ''): void
    {
        if (!in_array($modelo, $permitidos, true)) {
            $modelosStr = implode(', ', array_map(
                fn($m) => "$m (" . self::getNomeModelo($m) . ")",
                $permitidos
            ));
            
            $mensagem = "Modelo inválido: '{$modelo}'";
            if ($contexto) {
                $mensagem .= " para {$contexto}";
            }
            $mensagem .= ". Use: {$modelosStr}";
            
            throw new \InvalidArgumentException($mensagem);
        }
    }

    /**
     * Retorna o nome amigável do modelo.
     */
    protected static function getNomeModelo(string $modelo): string
    {
        return match ($modelo) {
            self::NFE => 'NF-e',
            self::NFCE => 'NFC-e',
            self::CTE => 'CT-e',
            self::CTE_OS => 'CT-e OS',
            self::SAT => 'SAT',
            self::NFSE => 'NFS-e Nacional',
            default => 'Desconhecido',
        };
    }
}

