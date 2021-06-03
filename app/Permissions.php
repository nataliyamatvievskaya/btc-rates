<?php


namespace App;


use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class Permissions
 * middleware for check permissions
 * @package App
 */
class Permissions
{
	/**
	 * @param \Slim\Psr7\Request $request
	 * @return bool
	 */
    private function __checkBearer(\Slim\Psr7\Request $request): bool {

        $header =  $request->getHeader('Authorization');

        if (empty($header)) {
            return false;
        }

        if (substr($header, 0, 7) !== 'Bearer ') {
            return false;
        }
        $token =  trim(substr($header, 7));

        if ($token  == APP_ACCESS_TOKEN) {
            return $token;
        }
        return false;
    }

	/**
	 * @param \Slim\Psr7\Request $request
	 * @param RequestHandlerInterface $handler
	 * @return ResponseInterface
	 * @throws \Slim\Exception\HttpForbiddenException
	 */
    public function __invoke(\Slim\Psr7\Request $request, RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface{

        if(!$this->__checkBearer($request)) {
            //throw new \Slim\Exception\HttpForbiddenException($request, 'Invalid token');
        }

        return $handler->handle($request);
    }

}