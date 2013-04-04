<?php
/**
 * @auhtor MParaiso , mparaiso@online.fr
 *
 */

namespace Mparaiso\Provider;
use Silex\ServiceProviderInterface;
use LightOpenID;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

/**
 * EN : provides the LightOpenId library for silex applications<br/>
 * FR : fournisseur de service pour la librairie LightOpenId<br/>
 * exemple :
 * <pre><code>
 * $app->register(new LightOpenIdServiceProvider,array(
 *  "mp.lightopenid.returnUrl"=>"/return-url"  // (optional default to request uri )
 *  "mp.realm"=>"https://myhost.com/" // (optional default to server host )
 * ));
 * <code></pre>
 * @see https://github.com/formapro/LightOpenID
 */
class LightOpenIdServiceProvider implements ServiceProviderInterface
{

    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        $app["mp.lightopenid"] = $app->share(function (Application $app) {
            $l = new LightOpenID($app["mp.lightopenid.host"]);
            if (isset($app["mp.lightopenid.return_url"])) {
                $l->returnUrl = $app["mp.lightopenid.return_url"];
            }
            return $l;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
        if (!isset($app["mp.lightopenid.host"])) {
            $app["mp.lightopenid.host"] = $app->share(function ($app) {
                /* @var $req Request */
                $req = $app["request"];
                return $req->getHttpHost();
            });
        }
    }
}