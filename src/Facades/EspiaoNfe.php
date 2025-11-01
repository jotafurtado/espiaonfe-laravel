<?php

namespace Jcf\EspiaoNfe\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Query Builders (Métodos Fluentes - Recomendado)
 *
 * @method static \Jcf\EspiaoNfe\Query\EmpresasQuery empresas()
 * @method static \Jcf\EspiaoNfe\Query\CertificadosQuery certificados()
 * @method static \Jcf\EspiaoNfe\Query\NfeQuery nfe()
 * @method static \Jcf\EspiaoNfe\Query\CteQuery cte()
 * @method static \Jcf\EspiaoNfe\Query\NfseQuery nfse()
 * @method static \Jcf\EspiaoNfe\Query\XmlsQuery xmls()
 * @method static \Jcf\EspiaoNfe\Query\LogsQuery logs()
 * @method static \Jcf\EspiaoNfe\Query\ResgateXmlQuery resgateXml()
 *
 * Métodos Legados (mantidos para compatibilidade)
 * @method static array getCertificados()
 * @method static array createCertificado(array $data)
 * @method static array updateCertificado(string $serial, array $data)
 * @method static array deleteCertificado(string $serial, string $comando)
 * @method static array getEmpresas(array $params = [])
 * @method static array createEmpresa(array $data)
 * @method static array getEmpresa(array $params)
 * @method static array updateEmpresa(string $cnpjCpf, array $data)
 * @method static array getNfeResumo(array $params)
 * @method static array getCteResumo(array $params)
 * @method static array getNfseResumo(array $params)
 * @method static array getXmls(array $params)
 * @method static array getLogs(array $params)
 * @method static array getXmlByChave(array $params)
 * @method static array getPdfByChave(array $params)
 * @method static array importarXml(array $data)
 * @method static array manifestarNfe(array $data)
 * @method static array desacordoCte(array $data)
 * @method static array consultarNfsePorCidade(array $params)
 * @method static array getCidadesHomologadas()
 * @method static array inserirChavesResgateXml(array $data)
 * @method static array consultarAndamentoResgateXml(array $params)
 * @method static array consultarResgatados(array $params)
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
