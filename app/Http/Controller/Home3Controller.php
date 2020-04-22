<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Http\Controller;

use App\Rpc\Lib\UserInterface;
use App\Rpc\Lib\OrderInterface;
use Exception;
use Swoft\Co;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Rpc\Client\Annotation\Mapping\Reference;

/**
 * Class Home3Controller
 *
 * @since 2.0
 *
 * @Controller()
 */
class Home3Controller
{

    /**
     * @Reference(pool="user.pool")
     *
     * @var UserInterface
     */
    private $userService;

    /**
     * @Reference(pool="order.pool")
     *
     * @var OrderInterface
     */
    private $orderService;

    /**
     * @RequestMapping("/v3/getList")
     * @return array
     */
    public function getList(): array
    {
        $result  = $this->userService->getList(111, 'type');
        $result3 = $this->orderService->getList(222, 'type');
        return [$result, $result3];
    }

    /**
     * @RequestMapping("/v3/getList2")
     * @return array
     * @throws Exception
     */
    public function getList2()
    {
        $ret = $this->request('tcp://swoft.ourvae.com:18307', \App\Rpc\Lib\UserInterface::class, 'getList',  [1, 2], "1.0");
        return [$ret];
    }

    public function request($host, $class, $method, $param, $version = '1.0', $ext = []) {
        $rpc_eol = "\r\n\r\n";
        $fp = stream_socket_client($host, $errno, $errstr);
        if (!$fp) {
            throw new Exception("stream_socket_client fail errno={$errno} errstr={$errstr}");
        }

        $req = [
            "jsonrpc" => '2.0',
            "method" => sprintf("%s::%s::%s", $version, $class, $method),
            'params' => $param,
            'id' => '',
            'ext' => $ext,
        ];
        $data = json_encode($req) . $rpc_eol;
        fwrite($fp, $data);

        $result = '';
        while (!feof($fp)) {
            $tmp = stream_socket_recvfrom($fp, 1024);
            if ($pos = strpos($tmp, $rpc_eol)) {
                $result .= substr($tmp, 0, $pos);
                break;
            } else {
                $result .= $tmp;
            }
        }

        fclose($fp);
        return json_decode($result, true);
    }

    /**
     * @RequestMapping("returnBool")
     *
     * @return array
     */
    public function returnBool(): array
    {
        $result = $this->userService->delete(12);

        if (is_bool($result)) {
            return ['bool'];
        }

        return ['notBool'];
    }

    /**
     * @RequestMapping()
     *
     * @return array
     */
    public function bigString(): array
    {
        $string = $this->userService->getBigContent();

        return ['string', strlen($string)];
    }

    /**
     * @RequestMapping()
     *
     * @return array
     */
    public function sendBigString(): array
    {
        $content = Co::readFile(__DIR__ . '/../../Rpc/Service/big.data');

        $len    = strlen($content);
        $result = $this->userService->sendBigContent($content);
        return [$len, $result];
    }

    /**
     * @RequestMapping()
     *
     * @return array
     */
    public function returnNull(): array
    {
        $this->userService->returnNull();
        return [null];
    }

    /**
     * @RequestMapping()
     *
     * @return array
     *
     * @throws Exception
     */
    public function exception(): array
    {
        $this->userService->exception();

        return ['exception'];
    }
}
