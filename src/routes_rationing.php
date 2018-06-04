<?php
/**
 * PHP version 7.2
 * src\routes_user.php
 */

use Slim\Http\Request;
use Slim\Http\Response;
use Swagger\Annotations as SWG;
use TDW18\Usuarios\Entity\Razonamiento;
use TDW18\Usuarios\Entity\Solucion;
use TDW18\Usuarios\Messages;

/**
 * Summary: Returns all rationings
 * Notes: Returns all rationings from the system that the user has access to.
 *
 * @SWG\Get(
 *     method      = "GET",
 *     path        = "/rationings",
 *     tags        = { "Rationings" },
 *     summary     = "Returns all rationings",
 *     description = "Returns all rationings from the system that the user has access to.",
 *     operationId = "tdw_cget_rationings",
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 200,
 *          description = "Rationing array response",
 *          schema      = { "$ref": "#/definitions/RazonamientoArray" }
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
    $_ENV['RUTA_API'] . '/rationings',
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
                            'message'   => Messages::MESSAGES['tdw_cget_rationings_403']
                        ],
                        403
                    );
        }

        $razonamiento = getEntityManager()
            ->getRepository(Razonamiento::class)
            ->findAll();

        $this->logger->info(
            $request->getMethod() . ' ' . $request->getUri()->getPath(),
            ['uid' => $this->jwt->user_id, 'status' => $razonamiento ? 200 : 404]
        );

        return empty($razonamiento)
            ? $response
                ->withJson(
                    [
                        'code'      => 404,
                        'message'   => Messages::MESSAGES['tdw_cget_rationings_404']
                    ],
                    404
                )
            : $response
                ->withJson(
                    [
                        'razonamientos' => $razonamiento
                    ],
                    200
                );
    }
)->setName('tdw_cget_rationings');


/**
 * Summary: Returns a rationing based on a single ID
 * Notes: Returns the rationing identified by &#x60;rationingID&#x60;.
 *
 * @SWG\Get(
 *     method      = "GET",
 *     path        = "/rationings/{idRationing}",
 *     tags        = { "Rationings" },
 *     summary     = "Returns a rationing based on a single ID",
 *     description = "Returns the rationing identified by `idRationing`.",
 *     operationId = "tdw_get_rationings",
 *     parameters  = {
 *          { "$ref" = "#/parameters/idRationing" }
 *     },
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 200,
 *          description = "Rationing",
 *          schema      = { "$ref": "#/definitions/Razonamiento" }
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
    $_ENV['RUTA_API'] . '/rationings/{id:[0-9]+}',
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
                        'message'   => Messages::MESSAGES['tdw_get_rationings_403']
                    ],
                    403
                );
        }

        $razonamiento = getEntityManager()
            ->getRepository(Solucion::class)
            ->findOneBy(array('idRationing' => $args['id']));

        $this->logger->info(
            $request->getMethod() . ' ' . $request->getUri()->getPath(),
            ['uid' => $this->jwt->user_id, 'status' => $razonamiento ? 200 : 404]
        );

        return empty($razonamiento)
            ? $response
                ->withJson(
                    [
                        'code'      => 404,
                        'message'   => Messages::MESSAGES['tdw_get_rationings_404']
                    ],
                    404
                )
            : $response
                ->withJson(
                    $razonamiento,
                    200
                );
    }
)->setName('tdw_get_rationings');

/**
 * Summary: Deletes a rationing
 * Notes: Deletes the rationing identified by &#x60;rationingId&#x60;.
 *
 * @SWG\Delete(
 *     method      = "DELETE",
 *     path        = "/rationings/{idRationing}",
 *     tags        = { "Rationings" },
 *     summary     = "Deletes a rationing",
 *     description = "Deletes the rationing identified by `rationingId`.",
 *     operationId = "tdw_delete_rationings",
 *     parameters={
 *          { "$ref" = "#/parameters/idRationing" }
 *     },
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 204,
 *          description = "Rationing deleted <Response body is empty>"
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
    $_ENV['RUTA_API'] . '/rationings/{id:[0-9]+}',
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
                        'message' => Messages::MESSAGES['tdw_delete_rationings_403']
                    ],
                    403
                )
                ->withStatus(403, Messages::MESSAGES['tdw_delete_rationings_403']);
        }

        $em = getEntityManager();

        $razonamiento = $em
            ->getRepository(Solucion::class)
            ->findOneBy(array('idRationing' => $args['id']));

        $this->logger->info(
            $request->getMethod() . ' ' . $request->getUri()->getPath(),
            ['uid' => $this->jwt->user_id, 'status' => $razonamiento ? 204 : 404]
        );

        if (!$razonamiento) {
            return $response
                ->withJson(
                    [
                        'code' => 404,
                        'message' => Messages::MESSAGES['tdw_delete_rationings_404']
                    ],
                    404
                )
                ->withStatus(404, Messages::MESSAGES['tdw_delete_rationings_404']);
        }

        $em -> remove($razonamiento);
        $em -> flush();

        return $response
            ->withStatus(204, Messages::MESSAGES['tdw_delete_rationings_204']);
    }
)->setName('tdw_delete_rationings');

/**
 * Summary: Provides the list of HTTP supported methods
 * Notes: Return a &#x60;Allow&#x60; header with a list of HTTP supported methods.
 *
 * @SWG\Options(
 *     method      = "OPTIONS",
 *     path        = "/rationings",
 *     tags        = { "Rationings" },
 *     summary     = "Provides the list of HTTP supported methods",
 *     description = "Return a `Allow` header with a list of HTTP supported methods.",
 *     operationId = "tdw_options_solutions",
 *     @SWG\Response(
 *          response    = 200,
 *          description = "`Allow` header <Response body is empty>",
 *     )
 * )
 *
 * @SWG\Options(
 *     method      = "OPTIONS",
 *     path        = "/rationings/{idRationing}",
 *     tags        = { "Rationings" },
 *     summary     = "Provides the list of HTTP supported methods",
 *     description = "Return a `Allow` header with a list of HTTP supported methods.",
 *     operationId = "tdw_options_rationing_id",
 *     parameters={
 *          { "$ref" = "#/parameters/idRationing" },
 *     },
 *     @SWG\Response(
 *          response    = 200,
 *          description = "`Allow` header <Response body is empty>",
 *     )
 * )
 */
/** @noinspection PhpUnusedParameterInspection */
$app->options(
    $_ENV['RUTA_API'] . '/rationings[/{id:[0-9]+}]',
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
)->setName('tdw_options_rationings');

/**
 * Summary: Creates a new rationing
 * Notes: Creates a new rationing
 *
 * @SWG\Post(
 *     method      = "POST",
 *     path        = "/rationings",
 *     tags        = { "Rationings" },
 *     summary     = "Creates a new rationing",
 *     description = "Creates a new rationing",
 *     operationId = "tdw_post_rationings",
 *     parameters  = {
 *          {
 *          "name":        "data",
 *          "in":          "body",
 *          "description": "`Rationing` properties to add to the system",
 *          "required":    true,
 *          "schema":      { "$ref": "#/definitions/RazonamientoData" }
 *          }
 *     },
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 201,
 *          description = "`Created` Rationing created",
 *          schema      = { "$ref": "#/definitions/User" }
 *     ),
 *     @SWG\Response(
 *          response    = 400,
 *          description = "`Bad Request` Rationing id already exists.",
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
 *          description = "`Unprocessable entity` Title, idQuestion or justifyRationing is left out",
 *          schema      = { "$ref": "#/definitions/Message" }
 *     )
 * )
 */
$app->post(
    $_ENV['RUTA_API'] . '/rationings',
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
                        'message'   => Messages::MESSAGES['tdw_post_rationings_403']
                    ],
                    403
                );
        }

        $req_data
            = $request->getParsedBody()
            ?? json_decode($request->getBody(), true);

        if (!isset($req_data['title'], $req_data['justifyRationing'], $req_data['idSolution'])) { // 422 - Faltan datos
            $this->logger->info(
                $request->getMethod() . ' ' . $request->getUri()->getPath(),
                [ 'uid' => $this->jwt->user_id, 'status' => 422 ]
            );

            return $response
                ->withJson(
                    [
                        'code' => 422,
                        'message' => Messages::MESSAGES['tdw_post_rationings_422']
                    ],
                    422
                );
        }

        $razonamiento = new Razonamiento();

        $title = $req_data['title'];
        $justifyRationing = $req_data['justifyRationing'];
        $idSolution = $req_data['idSolution'];

        $em = getEntityManager();

        $solution = $em
            ->getRepository(Solucion::class)
            ->findOneBy(array('idAnswer' => $idSolution));

        if (empty($solution)) {
            return $response
                ->withJson(
                    [
                        'code' => 400,
                        'message' => Messages::MESSAGES['tdw_post_rationings_400']
                    ],
                    400
                )
                ->withStatus(400, Messages::MESSAGES['tdw_post_rationings_400']);
        } else {
            $razonamiento->setTitle($title);
            $razonamiento->setJustifyRationing($justifyRationing);
            $razonamiento->setIdAnswer($idSolution);

            $em -> persist($razonamiento);
            $em -> flush();

            return $response
                ->withJson(
                    $razonamiento,
                    201
                )
                ->withStatus(201, Messages::MESSAGES['tdw_post_rationings_201']);
        }
    }
)->setName('tdw_post_rationings');

/**
 * Summary: Updates a rationing
 * Notes: Updates the rationing identified by &#x60;idRationing&#x60;.
 *
 * @SWG\Put(
 *     method      = "PUT",
 *     path        = "/rationings/{idRationing}",
 *     tags        = { "Rationings" },
 *     summary     = "Updates a rationing",
 *     description = "Updates the rationing identified by `idRationing`.",
 *     operationId = "tdw_put_rationings",
 *     parameters={
 *          { "$ref" = "#/parameters/idRationing" },
 *          {
 *          "name":        "data",
 *          "in":          "body",
 *          "description": "`Rationing` data to update",
 *          "required":    true,
 *          "schema":      { "$ref": "#/definitions/RazonamientoData" }
 *          }
 *     },
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 209,
 *          description = "`Content Returned` Rationing previously existed and is now updated",
 *          schema      = { "$ref": "#/definitions/Razonamiento" }
 *     ),
 *     @SWG\Response(
 *          response    = 400,
 *          description = "`Bad Request`",
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
 *          description = "`Unprocessable entity` idSolution, title or justifyRationing is left out",
 *          schema      = { "$ref": "#/definitions/Message" }
 *     )
 * )
 */
$app->put(
    $_ENV['RUTA_API'] . '/rationings/{id:[0-9]+}',
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
                        'message'   => Messages::MESSAGES['tdw_put_rationings_403']
                    ],
                    403
                );
        }

        $req_data
            = $request->getParsedBody()
            ?? json_decode($request->getBody(), true);

        $em = getEntityManager();

        $razonamiento = $em
            ->getRepository(Razonamiento::class)
            ->findOneBy(array('idRationing' => $args['id']));

        if ($razonamiento) {

            if (isset($req_data['title'])) {
                $razonamiento->setTitle($req_data['title']);
            }

            if (isset($req_data['justifyRationing'])) {
                $razonamiento->setJustifyRationing($req_data['justifyRationing']);
            }

            if (isset($req_data['idSolution'])) {
                $razonamiento->setIdSolution($req_data['idSolution']);
            }


            $em->flush();

            return $response
                ->withJson(
                    $razonamiento
                )
                ->withStatus(209, Messages::MESSAGES['tdw_put_rationings_209']);
        } else {
            return $response
                ->withJson(
                    [
                        'code' => 404,
                        'message' => Messages::MESSAGES['tdw_put_rationings_404']
                    ],
                    404
                )
                ->withStatus(404, Messages::MESSAGES['tdw_put_rationings_404']);
        }
    }
)->setName('tdw_put_rationings');
