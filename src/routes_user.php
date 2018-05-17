<?php
/**
 * PHP version 7.2
 * src\routes_user.php
 */

use Slim\Http\Request;
use Slim\Http\Response;
use Swagger\Annotations as SWG;
use TDW18\Usuarios\Entity\Usuario;
use TDW18\Usuarios\Messages;

/**
 * Summary: Returns all users
 * Notes: Returns all users from the system that the user has access to.
 *
 * @SWG\Get(
 *     method      = "GET",
 *     path        = "/users",
 *     tags        = { "Users" },
 *     summary     = "Returns all users",
 *     description = "Returns all users from the system that the user has access to.",
 *     operationId = "tdw_cget_users",
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 200,
 *          description = "User array response",
 *          schema      = { "$ref": "#/definitions/UsersArray" }
 *     ),
 *     @SWG\Response(
 *          response    = 401,
 *          ref         = "#/responses/401_Standard_Response"
 *     ),
 *     @SWG\Response(
 *          response    = 403,
 *          ref         = "#/responses/403_Forbidden_Response"
 *     ),
 *     @SWG\Response(
 *          response    = 404,
 *          ref         = "#/responses/404_Resource_Not_Found_Response"
 *     )
 * )
 * @var \Slim\App $app
 */
$app->get(
    $_ENV['RUTA_API'] . '/users',
    function (Request $request, Response $response): Response {
        if (!$this->jwt->isAdmin) {
            $this->logger->info(
                $request->getMethod() . ' ' . $request->getUri()->getPath(),
                ['uid' => $this->jwt->user_id, 'status' => 403]
            );

            return $response
                    ->withJson(
                        [
                            'code'      => 403,
                            'message'   => Messages::MESSAGES['tdw_cget_users_403']
                        ],
                        403
                    );
        }

        $usuarios = getEntityManager()
            ->getRepository(Usuario::class)
            ->findAll();

        $this->logger->info(
            $request->getMethod() . ' ' . $request->getUri()->getPath(),
            ['uid' => $this->jwt->user_id, 'status' => $usuarios ? 200 : 404]
        );

        return empty($usuarios)
            ? $response
                ->withJson(
                    [
                        'code'      => 404,
                        'message'   => Messages::MESSAGES['tdw_cget_users_404']
                    ],
                    404
                )
            : $response
                ->withJson(
                    [
                        'usuarios' => $usuarios
                    ],
                    200
                );
    }
)->setName('tdw_cget_users');

/**
 * Summary: Returns a user based on a single ID
 * Notes: Returns the user identified by &#x60;userId&#x60;.
 *
 * @SWG\Get(
 *     method      = "GET",
 *     path        = "/users/{userId}",
 *     tags        = { "Users" },
 *     summary     = "Returns a user based on a single ID",
 *     description = "Returns the user identified by `userId`.",
 *     operationId = "tdw_get_users",
 *     parameters  = {
 *          { "$ref" = "#/parameters/userId" }
 *     },
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 200,
 *          description = "User",
 *          schema      = { "$ref": "#/definitions/User" }
 *     ),
 *     @SWG\Response(
 *          response    = 401,
 *          ref         = "#/responses/401_Standard_Response"
 *     ),
 *     @SWG\Response(
 *          response    = 403,
 *          ref         = "#/responses/403_Forbidden_Response"
 *     ),
 *     @SWG\Response(
 *          response    = 404,
 *          ref         = "#/responses/404_Resource_Not_Found_Response"
 *     )
 * )
 */
/** @noinspection PhpUnusedParameterInspection */
$app->get(
    $_ENV['RUTA_API'] . '/users/{id:[0-9]+}',
    function (Request $request, Response $response, $args): Response {

        if (!$this->jwt->isAdmin && ($this->jwt->user_id !== $args['id'])) {
            $this->logger->info(
                $request->getMethod() . ' ' . $request->getUri()->getPath(),
                ['uid' => $this->jwt->user_id, 'status' => 403]
            );

            return $response
                ->withJson(
                    [
                        'code'      => 403,
                        'message'   => Messages::MESSAGES['tdw_get_users_403']
                    ],
                    403
                );
        }

        $usuario = getEntityManager()
            ->getRepository(Usuario::class)
            ->findOneBy(array('id' => $args['id']));

        $this->logger->info(
            $request->getMethod() . ' ' . $request->getUri()->getPath(),
            ['uid' => $this->jwt->user_id, 'status' => $usuario ? 200 : 404]
        );

        return empty($usuario)
            ? $response
                ->withJson(
                    [
                        'code'      => 404,
                        'message'   => Messages::MESSAGES['tdw_cget_users_404']
                    ],
                    404
                )
            : $response
                ->withJson(
                    $usuario,
                    200
                );
        }
)->setName('tdw_get_users');

/**
 * Summary: Deletes a user
 * Notes: Deletes the user identified by &#x60;userId&#x60;.
 *
 * @SWG\Delete(
 *     method      = "DELETE",
 *     path        = "/users/{userId}",
 *     tags        = { "Users" },
 *     summary     = "Deletes a user",
 *     description = "Deletes the user identified by `userId`.",
 *     operationId = "tdw_delete_users",
 *     parameters={
 *          { "$ref" = "#/parameters/userId" }
 *     },
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 204,
 *          description = "User deleted <Response body is empty>"
 *     ),
 *     @SWG\Response(
 *          response    = 401,
 *          ref         = "#/responses/401_Standard_Response"
 *     ),
 *     @SWG\Response(
 *          response    = 403,
 *          ref         = "#/responses/403_Forbidden_Response"
 *     ),
 *     @SWG\Response(
 *          response    = 404,
 *          ref         = "#/responses/404_Resource_Not_Found_Response"
 *     )
 * )
 */
/** @noinspection PhpUnusedParameterInspection */
$app->delete(
    $_ENV['RUTA_API'] . '/users/{id:[0-9]+}',
    function (Request $request, Response $response, $args): Response {

        if (!$this->jwt->isAdmin && ($this->jwt->user_id !== $args['id'])) {
            $this->logger->info(
                $request->getMethod() . ' ' . $request->getUri()->getPath(),
                [ 'uid' => $this->jwt->user_id, 'status' => 403 ]
            );

            return $response
                ->withJson(
                    [
                        'code' => 403,
                        'message' => Messages::MESSAGES['tdw_delete_users_403']
                    ],
                    403
                )
                ->withStatus(403, Messages::MESSAGES['tdw_delete_users_403']);
        }

        $em = getEntityManager();

        $usuario = $em
            ->getRepository(Usuario::class)
            ->findOneBy(array('id' => $args['id']));

        $this->logger->info(
            $request->getMethod() . ' ' . $request->getUri()->getPath(),
            ['uid' => $this->jwt->user_id, 'status' => $usuario ? 204 : 404]
        );

        if (!$usuario) {
            return $response
                ->withJson(
                    [
                        'code' => 404,
                        'message' => Messages::MESSAGES['tdw_delete_users_404']
                    ],
                    404
                )
                ->withStatus(404, Messages::MESSAGES['tdw_delete_users_404']);
        }

        $em -> remove($usuario);
        $em -> flush();

        return $response
            ->withStatus(204, Messages::MESSAGES['tdw_delete_users_204']);
    }
)->setName('tdw_delete_users');

/**
 * Summary: Provides the list of HTTP supported methods
 * Notes: Return a &#x60;Allow&#x60; header with a list of HTTP supported methods.
 *
 * @SWG\Options(
 *     method      = "OPTIONS",
 *     path        = "/users",
 *     tags        = { "Users" },
 *     summary     = "Provides the list of HTTP supported methods",
 *     description = "Return a `Allow` header with a list of HTTP supported methods.",
 *     operationId = "tdw_options_users",
 *     @SWG\Response(
 *          response    = 200,
 *          description = "`Allow` header <Response body is empty>",
 *     )
 * )
 *
 * @SWG\Options(
 *     method      = "OPTIONS",
 *     path        = "/users/{userId}",
 *     tags        = { "Users" },
 *     summary     = "Provides the list of HTTP supported methods",
 *     description = "Return a `Allow` header with a list of HTTP supported methods.",
 *     operationId = "tdw_options_users_id",
 *     parameters={
 *          { "$ref" = "#/parameters/userId" },
 *     },
 *     @SWG\Response(
 *          response    = 200,
 *          description = "`Allow` header <Response body is empty>",
 *     )
 * )
 */
/** @noinspection PhpUnusedParameterInspection */
$app->options(
    $_ENV['RUTA_API'] . '/users[/{id:[0-9]+}]',
    function (Request $request, Response $response, array $args): Response {
        $this->logger->info(
            $request->getMethod() . ' ' . $request->getUri()->getPath()
        );

        $methods = isset($args['id'])
            ? ['GET', 'PUT', 'DELETE']
            : ['GET', 'POST'];

        return $response
            ->withAddedHeader(
                'Allow',
                implode(', ', $methods)
            );
    }
)->setName('tdw_options_users');

/**
 * Summary: Creates a new user
 * Notes: Creates a new user
 *
 * @SWG\Post(
 *     method      = "POST",
 *     path        = "/users",
 *     tags        = { "Users" },
 *     summary     = "Creates a new user",
 *     description = "Creates a new user",
 *     operationId = "tdw_post_users",
 *     parameters  = {
 *          {
 *          "name":        "data",
 *          "in":          "body",
 *          "description": "`User` properties to add to the system",
 *          "required":    true,
 *          "schema":      { "$ref": "#/definitions/UserData" }
 *          }
 *     },
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 201,
 *          description = "`Created` User created",
 *          schema      = { "$ref": "#/definitions/User" }
 *     ),
 *     @SWG\Response(
 *          response    = 400,
 *          description = "`Bad Request` Username or email already exists.",
 *          schema      = { "$ref": "#/definitions/Message" }
 *     ),
 *     @SWG\Response(
 *          response    = 401,
 *          ref         = "#/responses/401_Standard_Response"
 *     ),
 *     @SWG\Response(
 *          response    = 403,
 *          ref         = "#/responses/403_Forbidden_Response"
 *     ),
 *     @SWG\Response(
 *          response    = 422,
 *          description = "`Unprocessable entity` Username, e-mail or password is left out",
 *          schema      = { "$ref": "#/definitions/Message" }
 *     )
 * )
 */
$app->post(
    $_ENV['RUTA_API'] . '/users',
    function (Request $request, Response $response): Response {

        if (!$this->jwt->isAdmin) {
            $this->logger->info(
                $request->getMethod() . ' ' . $request->getUri()->getPath(),
                [ 'uid' => $this->jwt->user_id, 'status' => 403 ]
            );

            return $response
                ->withJson(
                    [
                        'code'      => 403,
                        'message'   => Messages::MESSAGES['tdw_post_users_403']
                    ],
                    403
                );
        }

        $req_data
            = $request->getParsedBody()
            ?? json_decode($request->getBody(), true);

        if (!isset($req_data['username'], $req_data['email'], $req_data['password'])) { // 422 - Faltan datos
            $this->logger->info(
                $request->getMethod() . ' ' . $request->getUri()->getPath(),
                [ 'uid' => $this->jwt->user_id, 'status' => 422 ]
            );

            return $response
                ->withJson(
                    [
                        'code' => 422,
                        'message' => Messages::MESSAGES['tdw_post_users_422']
                    ],
                    422
                );
        }

        $usuario = new Usuario();

        $userName = $req_data['username'];
        $email = $req_data['email'];

        $em = getEntityManager();

        $usersWithTheSameUserName = $em
            ->getRepository(Usuario::class)
            ->findBy(array('username' => $userName));

        if (count($usersWithTheSameUserName) > 0) {
            return $response
                ->withJson(
                    [
                        'code' => 400,
                        'message' => Messages::MESSAGES['tdw_post_users_400']
                    ],
                    400
                )
                ->withStatus(400, Messages::MESSAGES['tdw_post_users_400']);
        }

        $usersWithTheSameEmail = $em
            ->getRepository(Usuario::class)
            ->findBy(array('email' => $email));

        if (count($usersWithTheSameEmail) > 0) {
            return $response
                ->withJson(
                    [
                        'code' => 400,
                        'message' => Messages::MESSAGES['tdw_post_users_400']
                    ],
                    400
                )
                ->withStatus(400, Messages::MESSAGES['tdw_post_users_400']);
        }

        $usuario->setUsername($userName);
        $usuario->setEmail($email);
        $usuario->setPassword($req_data['password']);

        if(isset($req_data['isAdmin'])) {
            $usuario->setAdmin($req_data['isAdmin']);
        } else {
            $usuario->setAdmin(false);
        }

        if(isset($req_data['enabled'])) {
            $usuario->setEnabled($req_data['enabled']);
        } else {
            $usuario->setEnabled(false);
        }

        if(isset($req_data['isMaestro'])) {
            $usuario->setMaestro($req_data['isMaestro']);
        } else {
            $usuario->setMaestro(false);
        }

        $em -> persist($usuario);
        $em -> flush();

        return $response
            ->withJson(
                $usuario,
                201
            )
            ->withStatus(201, Messages::MESSAGES['tdw_post_user_201']);
    }
)->setName('tdw_post_users');

/**
 * Summary: Updates a user
 * Notes: Updates the user identified by &#x60;userId&#x60;.
 *
 * @SWG\Put(
 *     method      = "PUT",
 *     path        = "/users/{userId}",
 *     tags        = { "Users" },
 *     summary     = "Updates a user",
 *     description = "Updates the user identified by `userId`.",
 *     operationId = "tdw_put_users",
 *     parameters={
 *          { "$ref" = "#/parameters/userId" },
 *          {
 *          "name":        "data",
 *          "in":          "body",
 *          "description": "`User` data to update",
 *          "required":    true,
 *          "schema":      { "$ref": "#/definitions/UserData" }
 *          }
 *     },
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 209,
 *          description = "`Content Returned` User previously existed and is now updated",
 *          schema      = { "$ref": "#/definitions/User" }
 *     ),
 *     @SWG\Response(
 *          response    = 400,
 *          description = "`Bad Request` User name or e-mail already exists",
 *          schema      = { "$ref": "#/definitions/Message" }
 *     ),
 *     @SWG\Response(
 *          response    = 401,
 *          ref         = "#/responses/401_Standard_Response"
 *     ),
 *     @SWG\Response(
 *          response    = 403,
 *          ref         = "#/responses/403_Forbidden_Response"
 *     ),
 *     @SWG\Response(
 *          response    = 404,
 *          ref         = "#/responses/404_Resource_Not_Found_Response"
 *     )
 * )
 */
$app->put(
    $_ENV['RUTA_API'] . '/users/{id:[0-9]+}',
    function (Request $request, Response $response, array $args): Response {

        if (!$this->jwt->isAdmin && ($this->jwt->user_id !== $args['id'])) {
            $this->logger->info(
                $request->getMethod() . ' ' . $request->getUri()->getPath(),
                [ 'uid' => $this->jwt->user_id, 'status' => 403 ]
            );

            return $response
                ->withJson(
                    [
                        'code'      => 403,
                        'message'   => Messages::MESSAGES['tdw_put_users_403']
                    ],
                    403
                );
        }

        $req_data
            = $request->getParsedBody()
            ?? json_decode($request->getBody(), true);

        $em = getEntityManager();

        $usuario = $em
            ->getRepository(Usuario::class)
            ->findOneBy(array('id' => $args['id']));

        if ($usuario) {

            if (isset($req_data['username'])) {
                $usernameUpdate = $req_data['username'];
                $usersWithTheSameUserName = $em
                    ->getRepository(Usuario::class)
                    ->findBy(array('username' => $usernameUpdate));

                if (count($usersWithTheSameUserName) > 0) {
                    return $response
                        ->withJson(
                            [
                                'code' => 400,
                                'message' => Messages::MESSAGES['tdw_put_users_400']
                            ],
                            400
                        )
                        ->withStatus(400, Messages::MESSAGES['tdw_put_users_400']);

                }

                $usuario->setUsername($usernameUpdate);
            }

            if (isset($req_data['email'])) {
                $emailUpdate = $req_data['email'];
                $usersWithTheSameEmail = $em
                    ->getRepository(Usuario::class)
                    ->findBy(array('email' => $emailUpdate));

                if (count($usersWithTheSameEmail) > 0) {
                    return $response
                        ->withJson(
                            [
                                'code' => 400,
                                'message' => Messages::MESSAGES['tdw_put_users_400']
                            ],
                            400
                        )
                        ->withStatus(400, Messages::MESSAGES['tdw_put_users_400']);
                }

                $usuario->setEmail($emailUpdate);
            }

            if (isset($req_data['password'])) {
                $usuario->setPassword($req_data['password']);
            }

            if (isset($req_data['isAdmin'])) {
                $usuario->setAdmin($req_data['isAdmin']);
            }

            if (isset($req_data['enabled'])) {
                $usuario->setEnabled($req_data['enabled']);
            }

            if (isset($req_data['isMaestro'])) {
                $usuario->setMaestro($req_data['isMaestro']);
            }

            $em->flush();

            return $response
                ->withJson(
                    $usuario
                )
                ->withStatus(209, Messages::MESSAGES['tdw_put_users_209']);
        } else {
            return $response
                ->withJson(
                    [
                        'code' => 404,
                        'message' => Messages::MESSAGES['tdw_put_users_404']
                    ],
                    404
                )
                ->withStatus(404, Messages::MESSAGES['tdw_put_users_404']);
        }
    }
)->setName('tdw_put_users');
