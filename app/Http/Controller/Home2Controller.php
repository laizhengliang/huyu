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

use Swoft;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\View\Renderer;
use Throwable;
use function context;
use Swoft\Cache\Cache;

/**
 * Class Home2Controller
 * @Controller()
 */
class Home2Controller
{
    /**
     * @RequestMapping("/vv22")
     * @throws Throwable
     */
    public function index(): Response
    {
        $ok  = Cache::set('ckey', 'cache value',5);
        $ok1 = Cache::set('ckey1', 'cache value2', 5);

        return context()->getResponse()->withContent('33333'.$ok.$ok1);
    }

}
