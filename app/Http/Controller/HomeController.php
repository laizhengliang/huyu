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

use App\Model\Data\GoodsData;
use Swoft;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\View\Renderer;
use Throwable;
use function bean;
use function context;
use Swoft\Http\Message\Request;
use Swoft\Co;
use Swoft\Db\DB;

/**
 * Class HomeController
 * @Controller()
 */
class HomeController
{
    /**
     * @RequestMapping("/")
     * @throws Throwable
     */
    public function index(): Response
    {
        return context()->getResponse()->withContent('2222');
    }

    /**
     * @RequestMapping("/v1/",method={"GET"})
     * @param  $request
     * @return Response
     */
    public function aaaa()
    {

    }

    /**
     * @RequestMapping("/v1/hi",method={"GET"})
     * @param  $request
     * @return array
     */
    public function hi(Request $request): Array
    {
//        $data = $request->post();
//        Co::create(function ()use($data) {
//            $a = $this->factorial((int)$data['number']);
//            var_dump($a);
//        });
        $a = DB::table('goods')->where('id', '=', 888)->get();
        $str = json_encode($a);

        return ['data'=>$a];
    }

    /**
     * 闭包递归，计算阶乘
     *
     * @RequestMapping(route="factorial/{number}")
     *
     * @param int $number
     *
     * @return array
     */
    public function factorial(int $number): array
    {
        $factorial = function ($arg) use (&$factorial) {
            if ($arg == 1) {
                return $arg;
            }
            sleep(1);
            var_dump(Co::id());
            return $arg * $factorial($arg - 1);
        };
        return [$factorial($number)];
    }

    /**
     * @RequestMapping("/hello[/{name}]")
     * @param string $name
     *
     * @return Response
     */
    public function hello(string $name): Response
    {
        return context()->getResponse()->withContent('Hello' . ($name === '' ? '' : ", {$name}"));
    }

    /**
     * @RequestMapping("/wstest", method={"GET"})
     *
     * @return Response
     * @throws Throwable
     */
    public function wsTest(): Response
    {
        return view('home/ws-test');
    }

    /**
     * @RequestMapping("/dataConfig", method={"GET"})
     *
     * @return array
     * @throws Throwable
     */
    public function dataConfig(): array
    {
        return bean(GoodsData::class)->getConfig();
    }
}
