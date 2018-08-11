<?php
/**
 * Created by PhpStorm.
 * User: lvinkim
 * Date: 2018/8/11
 * Time: 2:02 PM
 */

namespace App\Action;


use App\Action\Component\ActionInterface;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class IndexAction implements ActionInterface
{

    /**
     * ActionInterface constructor.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {

    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        return $response->withJson([
            'success' => true,
            'message' => "redis pipeline",
            'data' => [
                'hostname' => getenv('HOST_NICKNAME') ?: gethostname(),
            ]
        ]);
    }
}