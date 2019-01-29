<?php
/**
 * Created by PhpStorm.
 * User: wangzhongjie  Email: baidushanxi@vip.qq.com
 * Date: 2018/12/7
 * Time: 上午11:39
 */
namespace Baidushanxi\JrttMarketingApi;

use Baidushanxi\JrttMarketingApi\Server\Campaign;
use Baidushanxi\JrttMarketingApi\Config\Config;


/**
 * @method static Alipay alipay(array $config) 支付宝
 * @method static Wechat wechat(array $config) 微信
 */
class OpenApi
{
    /**
     * Config.
     *
     * @var Config
     */
    protected $config;
    /**
     * Bootstrap.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param array $config
     *
     * @throws \Exception
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
        $this->registerLogService();
        $this->registerEventService();
    }
    /**
     * Magic static call.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param string $method
     * @param array  $params
     *
     * @throws InvalidGatewayException
     * @throws \Exception
     *
     * @return GatewayApplicationInterface
     */
    public static function __callStatic($method, $params)
    {
        $app = new self(...$params);
        return $app->create($method);
    }

    protected function create($method)
    {
        $gateway = __NAMESPACE__.'\\Gateways\\'.Str::studly($method);
        if (class_exists($gateway)) {
            return self::make($gateway);
        }
        throw new \Exception("Gateway [{$method}] Not Exists");
    }
    /**
     * Make a gateway.
     *
     * @author yansongda <me@yansonga.cn>
     *
     * @param string $gateway
     *
     * @throws InvalidGatewayException
     *
     * @return GatewayApplicationInterface
     */
    protected function make($gateway): GatewayApplicationInterface
    {
        $app = new $gateway($this->config);
        if ($app instanceof GatewayApplicationInterface) {
            return $app;
        }
        throw new Exception("Gateway [{$gateway}] Must Be An Instance Of GatewayApplicationInterface");
    }
    /**
     * Register log service.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @throws \Exception
     */
    protected function registerLogService()
    {
        $logger = Log::createLogger(
            $this->config->get('log.file'),
            'yansongda.pay',
            $this->config->get('log.level', 'warning'),
            $this->config->get('log.type', 'daily'),
            $this->config->get('log.max_file', 30)
        );
        Log::setLogger($logger);
    }
    /**
     * Register event service.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @return void
     */
    protected function registerEventService()
    {
        Events::setDispatcher(Events::createDispatcher());
        Events::addSubscriber(new KernelLogSubscriber());
    }
}
