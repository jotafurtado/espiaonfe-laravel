<?php

namespace Jcf\EspiaoNfe\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Query Builders - API Fluente
 *
 * @method static \Jcf\EspiaoNfe\Query\EmpresasQuery empresas()
 * @method static \Jcf\EspiaoNfe\Query\CertificadosQuery certificados()
 * @method static \Jcf\EspiaoNfe\Query\NfeQuery nfe()
 * @method static \Jcf\EspiaoNfe\Query\CteQuery cte()
 * @method static \Jcf\EspiaoNfe\Query\NfseQuery nfse()
 * @method static \Jcf\EspiaoNfe\Query\XmlsQuery xmls()
 * @method static \Jcf\EspiaoNfe\Query\LogsQuery logs()
 * @method static \Jcf\EspiaoNfe\Query\ResgateXmlQuery resgateXml()
 */
class EspiaoNfe extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return "espiaonfe";
    }
}
