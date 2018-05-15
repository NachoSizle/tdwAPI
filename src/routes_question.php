<?php
/**
 * Created by PhpStorm.
 * User: nachosizle
 * Date: 10/5/18
 * Time: 12:23
 * PHP version 7.2
 * src\routes_user.php
 */


use Slim\Http\Request;
use Slim\Http\Response;
use Swagger\Annotations as SWG;
use TDW18\Usuarios\Entity\Cuestion;
use TDW18\Usuarios\Entity\Usuario;
use TDW18\Usuarios\Messages;

/**
 * Summary: Returns all questions
 * Notes: Returns all questions from the system that the user has access to.
 *
 * @SWG\Get(
 *     method      = "GET",
 *     path        = "/questions",
 *     tags        = { "Questions" },
 *     summary     = "Returns all questions",
 *     description = "Returns all questions from the system that the user has access to.",
 *     operationId = "tdw_cget_cuestions",
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 200,
 *          description = "Question array response",
 *          schema      = { "$ref": "#/definitions/QuestionsArray" }
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
    $_ENV['RUTA_API'] . '/questions',
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
                        'message'   => Messages::MESSAGES['tdw_cget_questions_403']
                    ],
                    403
                );
        }

        $cuestiones = getEntityManager()
            ->getRepository(Cuestion::class)
            ->findAll();

        $this->logger->info(
            $request->getMethod() . ' ' . $request->getUri()->getPath(),
            ['uid' => $this->jwt->user_id, 'status' => $cuestiones ? 200 : 404]
        );

        return empty(cuestiones)
            ? $response
                ->withJson(
                    [
                        'code'      => 404,
                        'message'   => Messages::MESSAGES['tdw_cget_questions_404']
                    ],
                    404
                )
            : $response
                ->withJson(
                    [
                        'cuestions' => $cuestiones
                    ],
                    200
                );
    }
)->setName('tdw_cget_cuestions');


/**
 * Summary: Returns a question based on a single ID
 * Notes: Returns the question identified by &#x60;questionId&#x60;.
 *
 * @SWG\Get(
 *     method      = "GET",
 *     path        = "/questions/{questionId}",
 *     tags        = { "Questions" },
 *     summary     = "Returns a question based on a single ID",
 *     description = "Returns the question identified by `questionId`.",
 *     operationId = "tdw_get_questions",
 *     parameters  = {
 *          { "$ref" = "#/parameters/questionId" }
 *     },
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 200,
 *          description = "Cuestion",
 *          schema      = { "$ref": "#/definitions/Cuestion" }
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
    $_ENV['RUTA_API'] . '/questions/{id:[0-9]+}',
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

        // TODO
        $cuestion = getEntityManager()
            ->getRepository(Cuestion::class)
            ->findOneBy(array('questionId' => $args['id']));

        $this->logger->info(
            $request->getMethod() . ' ' . $request->getUri()->getPath(),
            ['uid' => $this->jwt->user_id, 'status' => $cuestion ? 200 : 404]
        );

        return empty($cuestion)
            ? $response
                ->withJson(
                    [
                        'code'      => 404,
                        'message'   => Messages::MESSAGES['tdw_get_questions_404']
                    ],
                    404
                )
            : $response
                ->withJson(
                    $cuestion,
                    200
                );
    }
)->setName('tdw_get_questions');


/**
 * Summary: Deletes a question
 * Notes: Deletes the question identified by &#x60;questionId&#x60;.
 *
 * @SWG\Delete(
 *     method      = "DELETE",
 *     path        = "/questions/{questionId}",
 *     tags        = { "Questions" },
 *     summary     = "Deletes a question",
 *     description = "Deletes the question identified by `userId`.",
 *     operationId = "tdw_delete_questions",
 *     parameters={
 *          { "$ref" = "#/parameters/questionId" }
 *     },
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 204,
 *          description = "Question deleted <Response body is empty>"
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
    $_ENV['RUTA_API'] . '/questions/{id:[0-9]+}',
    function (Request $request, Response $response, $args): Response {

        if (!$this->jwt->isAdmin && ($this->jwt->user_id !== $args['id'])) {
            $this->logger->info(
                $request->getMethod() . ' ' . $request->getUri()->getPath(),
                [ 'uid' => $this->jwt->user_id, 'status' => 403 ]
            );

            return $response
                ->withJson(
                    [
                        'code'      => 403,
                        'message'   => Messages::MESSAGES['tdw_delete_questions_403']
                    ],
                    403
                );
        }

        // TODO
        $em = getEntityManager();

        $question = $em
            ->getRepository(Cuestion::class)
            ->findOneBy(array('idQuestion' => $args['id']));

        $this->logger->info(
            $request->getMethod() . ' ' . $request->getUri()->getPath(),
            ['uid' => $this->jwt->user_id, 'status' => $question ? 200 : 404]
        );

        if (!$question) {
            return $response
                ->withJson(
                    [
                        'code'      => 404,
                        'message'   => Messages::MESSAGES['tdw_delete_questions_404']
                    ],
                    404
                );
        }

        $em -> remove($question);
        $em -> flush();

        return $response
            ->withJson(
                [
                    'code'      => 204,
                    'message'   => Messages::MESSAGES['tdw_delete_questions_204']
                ],
                204
            );
    }
)->setName('tdw_delete_questions');


/**
 * Summary: Provides the list of HTTP supported methods
 * Notes: Return a &#x60;Allow&#x60; header with a list of HTTP supported methods.
 *
 * @SWG\Options(
 *     method      = "OPTIONS",
 *     path        = "/questions",
 *     tags        = { "Questions" },
 *     summary     = "Provides the list of HTTP supported methods",
 *     description = "Return a `Allow` header with a list of HTTP supported methods.",
 *     operationId = "tdw_options_questions",
 *     @SWG\Response(
 *          response    = 200,
 *          description = "`Allow` header <Response body is empty>",
 *     )
 * )
 *
 * @SWG\Options(
 *     method      = "OPTIONS",
 *     path        = "/questions/{questionId}",
 *     tags        = { "Questions" },
 *     summary     = "Provides the list of HTTP supported methods",
 *     description = "Return a `Allow` header with a list of HTTP supported methods.",
 *     operationId = "tdw_options_questions_id",
 *     parameters={
 *          { "$ref" = "#/parameters/questionId" },
 *     },
 *     @SWG\Response(
 *          response    = 200,
 *          description = "`Allow` header <Response body is empty>",
 *     )
 * )
 */
/** @noinspection PhpUnusedParameterInspection */
$app->options(
    $_ENV['RUTA_API'] . '/questions[/{id:[0-9]+}]',
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
)->setName('tdw_options_questions');


/**
 * Summary: Creates a new question
 * Notes: Creates a new question
 *
 * @SWG\Post(
 *     method      = "POST",
 *     path        = "/questions",
 *     tags        = { "Questions" },
 *     summary     = "Creates a new question",
 *     description = "Creates a new question",
 *     operationId = "tdw_post_questions",
 *     parameters  = {
 *          {
 *          "name":        "data",
 *          "in":          "body",
 *          "description": "`Question` properties to add to the system",
 *          "required":    true,
 *          "schema":      { "$ref": "#/definitions/QuestionData" }
 *          }
 *     },
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 201,
 *          description = "`Created` Question created",
 *          schema      = { "$ref": "#/definitions/Cuestion" }
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
 *          description = "`Unprocessable entity` idCuestion is left out",
 *          schema      = { "$ref": "#/definitions/Message" }
 *     )
 * )
 */
$app->post(
    $_ENV['RUTA_API'] . '/questions',
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
                        'message'   => Messages::MESSAGES['tdw_post_questions_403']
                    ],
                    403
                );
        }

        $req_data
            = $request->getParsedBody()
            ?? json_decode($request->getBody(), true);

        if (!isset($req_data['creador'], $req_data['enum_descripcion'], $req_data['enum_disponible'])) { // 422 - Faltan datos
            $this->logger->info(
                $request->getMethod() . ' ' . $request->getUri()->getPath(),
                [ 'uid' => $this->jwt->user_id, 'status' => 422 ]
            );

            return $response
                ->withJson(
                    [
                        'code' => 422,
                        'message' => Messages::MESSAGES['tdw_post_questions_422']
                    ],
                    422
                );
        }

        // hay datos -> procesarlos
        // TODO
        $em = getEntityManager();

        $idUser = $req_data['creador'];
        $enumDesc = $req_data['enum_descripcion'];
        $enumAvailable = $req_data['enum_disponible'];

        $usuarioCreador = $em
            ->getRepository(Usuario::class)
            ->findOneBy(array('id' => $idUser));

        if(!$usuarioCreador->isMaestro()) {
            return $response
                ->withJson(
                    [
                        'code' => 403,
                        'message' => Messages::MESSAGES['tdw_post_questions_403']
                    ],
                    403
                );
        }

        if($usuarioCreador) {
            $cuestion = new Cuestion($enumDesc, $usuarioCreador, $enumAvailable);

            $em->persist($cuestion);
            $em->flush();

            return $response
                ->withJson(
                    [
                        'code' => 201,
                        'message' => Messages::MESSAGES['tdw_post_question_201']
                    ],
                    201
                );
        } else {
            return $response
                ->withJson(
                    [
                        'code'      => 404,
                        'message'   => Messages::MESSAGES['tdw_cget_users_404']
                    ],
                    404
                );
        }

    }
)->setName('tdw_post_cuestions');


/**
 * Summary: Updates a question
 * Notes: Updates the question identified by &#x60;questionId&#x60;.
 *
 * @SWG\Put(
 *     method      = "PUT",
 *     path        = "/questions/{questionId}",
 *     tags        = { "Questions" },
 *     summary     = "Updates a question",
 *     description = "Updates the question identified by `questionId`.",
 *     operationId = "tdw_put_questions",
 *     parameters={
 *          { "$ref" = "#/parameters/questionId" },
 *          {
 *          "name":        "data",
 *          "in":          "body",
 *          "description": "`Question` data to update",
 *          "required":    true,
 *          "schema":      { "$ref": "#/definitions/QuestionData" }
 *          }
 *     },
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 209,
 *          description = "`Content Returned` Question previously existed and is now updated",
 *          schema      = { "$ref": "#/definitions/Cuestion" }
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
    $_ENV['RUTA_API'] . '/questions/{id:[0-9]+}',
    function (Request $request, Response $response, array $args): Response {

        if (!$this->jwt->isAdmin && ($this->jwt->user_id !== $args['id'])) {
            $this->logger->info(
                $request->getMethod() . ' ' . $request->getUri()->getPath(),
                [ 'uid' => $this->jwt->user_id, 'status' => 401 ]
            );

            return $response
                ->withJson(
                    [
                        'code'      => 401,
                        'message'   => Messages::MESSAGES['tdw_unauthorized_401']
                    ],
                    401
                );
        }

        $req_data
            = $request->getParsedBody()
            ?? json_decode($request->getBody(), true);

        // TODO
        $em = getEntityManager();

        $cuestion = $em
            ->getRepository(Cuestion::class)
            ->findOneBy(array('idCuestion' => $args['id']));

        if ($cuestion) {
            $enumDesc = ($req_data['enum_descripcion']) ? $req_data['enum_descripcion'] : $cuestion->getEnunciadoDescripcion();
            $cuestion->setEnunciadoDescripcion($enumDesc);

            if(isset($req_data['enum_disponible'])) {
                $enumAvailable = $req_data['enum_disponible'];
            } else {
                $enumAvailable = $cuestion->isEnunciadoDisponible();
            }

            $cuestion->setEnunciadoDisponible($enumAvailable);
            if ($enumAvailable) {
                $cuestion->abrirCuestion();
            } else {
                $cuestion->cerrarCuestion();
            }

            if(isset($req_data['creador'])) {
                $idUser = $req_data['creador'];
                $usuarioCreador = $em
                    ->getRepository(Usuario::class)
                    ->findOneBy(array('id' => $idUser));

                if($usuarioCreador) {
                    if(!$usuarioCreador->isMaestro()) {
                        return $response
                            ->withJson(
                                [
                                    'code' => 403,
                                    'message' => Messages::MESSAGES['tdw_put_questions_403']
                                ],
                                403
                            );
                    } else {
                        $cuestion->setCreador($usuarioCreador);

                        $em->flush();

                        return $response
                            ->withJson(
                                $cuestion,
                                209
                            );
                    }
                } else {
                    return $response
                        ->withJson(
                            [
                                'code'      => 404,
                                'message'   => Messages::MESSAGES['tdw_put_questions_404']
                            ],
                            404
                        );
                }
            } else {

                $em->flush();

                return $response
                    ->withJson(
                            $cuestion,
                        209
                    );
            }
        } else {
            return $response
                ->withJson(
                    [
                        'code' => 404,
                        'message' => Messages::MESSAGES['tdw_put_questions_404']
                    ],
                    404
                );
        }
    }
)->setName('tdw_put_questions');
