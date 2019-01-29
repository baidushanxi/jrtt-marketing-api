<?php
/**
 * Created by PhpStorm.
 * User: wangzhongjie  Email: baidushanxi@vip.qq.com
 * Date: 2019/1/29
 * Time: 下午2:29
 */

namespace Baidushanxi\JrttMarketingApi\Server;

use Baidushanxi\JrttMarketingApi\Kernel\Http\BaseClient;

class Campaign extends BaseClient
{

    protected $baseUri = 'https://ad.toutiao.com/open_api/2/campaign/';

    protected $fields = [];


    public function get($config ,array $params, array $fields)
    {


    }



    public function setFields(array $fields)
    {


    }




    public function create()
    {

    }


    public function edit()
    {



    }



    public function update()
    {

    }




    public function upload(string $type, string $path)
    {
        if (!file_exists($path) || !is_readable($path)) {
            throw new InvalidArgumentException(sprintf("File does not exist, or the file is unreadable: '%s'", $path));
        }

        if (!in_array($type, $this->allowTypes, true)) {
            throw new InvalidArgumentException(sprintf("Unsupported media type: '%s'", $type));
        }

        return $this->httpUpload('media/upload', ['media' => $path], ['type' => $type]);
    }



}