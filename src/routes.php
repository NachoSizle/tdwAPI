<?php
/**
 * PHP version 7.2
 * src\routes.php
 */

use Slim\Http\Request;
use Slim\Http\Response;
use Swagger\Annotations as SWG;
use TDW18\Usuarios\Entity\Usuario;
use TDW18\Usuarios\Messages;

require_once __DIR__ . '/../bootstrap.php';

require __DIR__ . '/routes_user.php';
require __DIR__ . '/routes_question.php';
require __DIR__ . '/routes_category.php';

/**  @var \Slim\App $app */
/** @noinspection PhpUnusedParameterInspection */
$app->get(
    '/',
    function (Request $request, Response $response): Response {
        // Log message
        $this->logger->info(
            $request->getMethod() . ' ' . $request->getUri()->getPath(),
            [ 'uid' => 0, 'status' => 302 ]
        );

        // Redirect index view
        return $response
            ->withRedirect('/api-docs/index.html');
    }
);

/**
 * POST /login
 *
 * @SWG\Post(
 *     method      = "POST",
 *     path        = "/login",
 *     tags        = { "login" },
 *     summary     = "Returns TDW Users api token",
 *     description = "Returns TDW Users api token.",
 *     operationId = "tdw_post_login",
 *     parameters  =  {
 *          {
 *          "name":             "username",
 *          "in":               "formData",
 *          "description":      "User name",
 *          "allowEmptyValue":  false,
 *          "required":         true,
 *          "type":             "string"
 *          },
 *          {
 *          "name":             "password",
 *          "in":               "formData",
 *          "description":      "User password",
 *          "allowEmptyValue":  false,
 *          "required":         true,
 *          "type":             "string",
 *          "format":           "password"
 *          }
 *     },
 *     @SWG\Response(
 *          response    = 200,
 *          description = "TDW Users api token",
 *          @SWG\Header(
 *              header      = "X-Token",
 *              description = "api token",
 *              type        = "string",
 *          ),
 *          @SWG\Schema(
 *              type        = "object",
 *              example     = {
 *                  "X-Token": "<JSON web token>"
 *              }
 *          )
 *     ),
 *     @SWG\Response(
 *          response    = 404,
 *          ref         = "#/responses/404_Resource_Not_Found_Response"
 *     )
 * )
 */
/** @noinspection PhpUnusedParameterInspection */
$app->post(
    $_ENV['RUTA_LOGIN'],
    function (Request $request, Response $response): Response {
        $req_data
            = $request->getParsedBody()
            ?? json_decode($request->getBody(), true);

        /** @var TDW18\Usuarios\Entity\Usuario $user */
        $user = null;
        if (isset($req_data['username'], $req_data['password'])) {
            $user = getEntityManager()
                ->getRepository(Usuario::class)
                ->findOneBy(['username' => $req_data['username']]);
        }

        if (null === $user || !$user->validatePassword($req_data['password'])) {
            $this->logger->info(
                $request->getMethod() . ' ' . $request->getUri()->getPath(),
                [ 'status' => 404 ]
            );

            return $response
                ->withJson(
                    [
                        'code' => 404,
                        'message' => Messages::MESSAGES['tdw_post_login_404']
                    ],
                    404
                );
        }

        $json_web_token = \TDW18\Usuarios\Utils::getToken(
            $user->getId(),
            $user->getUsername(),
            $user->isAdmin()
        );
        $this->logger->info(
            $request->getMethod() . ' ' . $request->getUri()->getPath(),
            [ 'uid' => $user->getId(), 'status' => 200 ]
        );

        return $response
            ->withJson([
                'X-Token' => $json_web_token,
                'User' => $user
                ])
            ->withAddedHeader('X-Token', $json_web_token);
    }
)->setName('tdw_post_login');
