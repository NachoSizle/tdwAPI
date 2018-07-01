<?php
/**
 * PHP version 7.2
 * src\routes_user.php
 */

use Slim\Http\Request;
use Slim\Http\Response;
use Swagger\Annotations as SWG;
use TDW18\Usuarios\Entity\Cuestion;
use TDW18\Usuarios\Entity\Solucion;
use TDW18\Usuarios\Messages;

/**
 * Summary: Returns all solutions
 * Notes: Returns all solutions from the system that the user has access to.
 *
 * @SWG\Get(
 *     method      = "GET",
 *     path        = "/solutions",
 *     tags        = { "Solutions" },
 *     summary     = "Returns all solutions",
 *     description = "Returns all solutions from the system that the user has access to.",
 *     operationId = "tdw_cget_solutions",
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 200,
 *          description = "Solucion array response",
 *          schema      = { "$ref": "#/definitions/SolucionArray" }
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
    $_ENV['RUTA_API'] . '/solutions',
    function (Request $request, Response $response): Response {
        $soluciones = getEntityManager()
            ->getRepository(Solucion::class)
            ->findAll();

        $this->logger->info(
            $request->getMethod() . ' ' . $request->getUri()->getPath(),
            ['uid' => $this->jwt->user_id, 'status' => $soluciones ? 200 : 404]
        );

        return empty($soluciones)
            ? $response
                ->withJson(
                    [
                        'code'      => 404,
                        'message'   => Messages::MESSAGES['tdw_cget_solutions_404']
                    ],
                    404
                )
            : $response
                ->withJson(
                    [
                        'soluciones' => $soluciones
                    ],
                    200
                );
    }
)->setName('tdw_cget_solutions');


/**
 * Summary: Returns a solution based on a single ID
 * Notes: Returns the user identified by &#x60;answerID&#x60;.
 *
 * @SWG\Get(
 *     method      = "GET",
 *     path        = "/solutions/{idAnswer}",
 *     tags        = { "Solutions" },
 *     summary     = "Returns a solution based on a single ID",
 *     description = "Returns the solution identified by `answerId`.",
 *     operationId = "tdw_get_solutions",
 *     parameters  = {
 *          { "$ref" = "#/parameters/idAnswer" }
 *     },
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 200,
 *          description = "User",
 *          schema      = { "$ref": "#/definitions/Solucion" }
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
    $_ENV['RUTA_API'] . '/solutions/{id:[0-9]+}',
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
                        'message'   => Messages::MESSAGES['tdw_get_solutions_403']
                    ],
                    403
                );
        }

        $solucion = getEntityManager()
            ->getRepository(Solucion::class)
            ->findOneBy(array('idAnswer' => $args['id']));

        $this->logger->info(
            $request->getMethod() . ' ' . $request->getUri()->getPath(),
            ['uid' => $this->jwt->user_id, 'status' => $solucion ? 200 : 404]
        );

        return empty($solucion)
            ? $response
                ->withJson(
                    [
                        'code'      => 404,
                        'message'   => Messages::MESSAGES['tdw_get_solutions_404']
                    ],
                    404
                )
            : $response
                ->withJson(
                    $solucion,
                    200
                );
    }
)->setName('tdw_get_solutions');

/**
 * Summary: Deletes a solution
 * Notes: Deletes the solution identified by &#x60;solutionId&#x60;.
 *
 * @SWG\Delete(
 *     method      = "DELETE",
 *     path        = "/solutions/{idAnswer}",
 *     tags        = { "Solutions" },
 *     summary     = "Deletes a solution",
 *     description = "Deletes the solution identified by `answerId`.",
 *     operationId = "tdw_delete_solutions",
 *     parameters={
 *          { "$ref" = "#/parameters/idAnswer" }
 *     },
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 204,
 *          description = "Solution deleted <Response body is empty>"
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
    $_ENV['RUTA_API'] . '/solutions/{id:[0-9]+}',
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
                        'message' => Messages::MESSAGES['tdw_delete_solutions_403']
                    ],
                    403
                )
                ->withStatus(403, Messages::MESSAGES['tdw_delete_solutions_403']);
        }

        $em = getEntityManager();

        $solucion = $em
            ->getRepository(Solucion::class)
            ->findOneBy(array('idAnswer' => $args['id']));

        $this->logger->info(
            $request->getMethod() . ' ' . $request->getUri()->getPath(),
            ['uid' => $this->jwt->user_id, 'status' => $solucion ? 204 : 404]
        );

        if (!$solucion) {
            return $response
                ->withJson(
                    [
                        'code' => 404,
                        'message' => Messages::MESSAGES['tdw_delete_solutions_404']
                    ],
                    404
                )
                ->withStatus(404, Messages::MESSAGES['tdw_delete_solutions_404']);
        }

        $em -> remove($solucion);
        $em -> flush();

        return $response
            ->withStatus(204, Messages::MESSAGES['tdw_delete_solutions_204']);
    }
)->setName('tdw_delete_users');

/**
 * Summary: Provides the list of HTTP supported methods
 * Notes: Return a &#x60;Allow&#x60; header with a list of HTTP supported methods.
 *
 * @SWG\Options(
 *     method      = "OPTIONS",
 *     path        = "/solutions",
 *     tags        = { "Solutions" },
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
 *     path        = "/solutions/{idAnswer}",
 *     tags        = { "Solutions" },
 *     summary     = "Provides the list of HTTP supported methods",
 *     description = "Return a `Allow` header with a list of HTTP supported methods.",
 *     operationId = "tdw_options_solution_id",
 *     parameters={
 *          { "$ref" = "#/parameters/idAnswer" },
 *     },
 *     @SWG\Response(
 *          response    = 200,
 *          description = "`Allow` header <Response body is empty>",
 *     )
 * )
 */
/** @noinspection PhpUnusedParameterInspection */
$app->options(
    $_ENV['RUTA_API'] . '/solutions[/{id:[0-9]+}]',
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
)->setName('tdw_options_solutions');

/**
 * Summary: Creates a new solution
 * Notes: Creates a new solution
 *
 * @SWG\Post(
 *     method      = "POST",
 *     path        = "/solutions",
 *     tags        = { "Solutions" },
 *     summary     = "Creates a new solution",
 *     description = "Creates a new solution",
 *     operationId = "tdw_post_solutions",
 *     parameters  = {
 *          {
 *          "name":        "data",
 *          "in":          "body",
 *          "description": "`Solution` properties to add to the system",
 *          "required":    true,
 *          "schema":      { "$ref": "#/definitions/SolucionData" }
 *          }
 *     },
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 201,
 *          description = "`Created` Solution created",
 *          schema      = { "$ref": "#/definitions/User" }
 *     ),
 *     @SWG\Response(
 *          response    = 400,
 *          description = "`Bad Request` Question title already exists.",
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
 *          description = "`Unprocessable entity` Student, question title or proposed solution is left out",
 *          schema      = { "$ref": "#/definitions/Message" }
 *     )
 * )
 */
$app->post(
    $_ENV['RUTA_API'] . '/solutions',
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
                        'message'   => Messages::MESSAGES['tdw_post_solutions_403']
                    ],
                    403
                );
        }

        $req_data
            = $request->getParsedBody()
            ?? json_decode($request->getBody(), true);

        if (!isset($req_data['student'], $req_data['questionTitle'], $req_data['proposedSolution'], $req_data['idQuestion'])) { // 422 - Faltan datos
            $this->logger->info(
                $request->getMethod() . ' ' . $request->getUri()->getPath(),
                [ 'uid' => $this->jwt->user_id, 'status' => 422 ]
            );

            return $response
                ->withJson(
                    [
                        'code' => 422,
                        'message' => Messages::MESSAGES['tdw_post_solutions_422']
                    ],
                    422
                );
        }

        $solucion = new Solucion();

        $userName = $req_data['student'];
        $questionTitle = $req_data['questionTitle'];
        $proposedSolution = $req_data['proposedSolution'];
        $idQuestion = $req_data['idQuestion'];

        $em = getEntityManager();

        $cuestion = $em
            ->getRepository(Cuestion::class)
            ->findBy(array('idCuestion' => $idQuestion));

        if (empty($cuestion)) {
            return $response
                ->withJson(
                    [
                        'code' => 400,
                        'message' => Messages::MESSAGES['tdw_post_solutions_400']
                    ],
                    400
                )
                ->withStatus(400, Messages::MESSAGES['tdw_post_solutions_400']);
        }

        $solucion->setStudent($userName);
        $solucion->setQuestionTitle($questionTitle);
        $solucion->setProposedSolution($proposedSolution);
        $solucion->setIdQuestion($idQuestion);

        if(isset($req_data['answers'])) {
            $solucion->addAnswer($req_data['answers']);
        }

        $em -> persist($solucion);
        $em -> flush();

        return $response
            ->withJson(
                $solucion,
                201
            )
            ->withStatus(201, Messages::MESSAGES['tdw_post_solution_201']);
    }
)->setName('tdw_post_solutions');

/**
 * Summary: Updates a solution
 * Notes: Updates the solution identified by &#x60;idAnswer&#x60;.
 *
 * @SWG\Put(
 *     method      = "PUT",
 *     path        = "/solutions/{idAnswer}",
 *     tags        = { "Solutions" },
 *     summary     = "Updates a solution",
 *     description = "Updates the solution identified by `idAnswer`.",
 *     operationId = "tdw_put_solutions",
 *     parameters={
 *          { "$ref" = "#/parameters/idAnswer" },
 *          {
 *          "name":        "data",
 *          "in":          "body",
 *          "description": "`Solution` data to update",
 *          "required":    true,
 *          "schema":      { "$ref": "#/definitions/SolucionData" }
 *          }
 *     },
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 209,
 *          description = "`Content Returned` Solucion previously existed and is now updated",
 *          schema      = { "$ref": "#/definitions/Solucion" }
 *     ),
 *     @SWG\Response(
 *          response    = 400,
 *          description = "`Bad Request` Question title already exists.",
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
 *          description = "`Unprocessable entity` Student, question title or proposed solution is left out",
 *          schema      = { "$ref": "#/definitions/Message" }
 *     )
 * )
 */
$app->put(
    $_ENV['RUTA_API'] . '/solutions/{id:[0-9]+}',
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
                        'message'   => Messages::MESSAGES['tdw_put_solutions_403']
                    ],
                    403
                );
        }

        $req_data
            = $request->getParsedBody()
            ?? json_decode($request->getBody(), true);

        $em = getEntityManager();

        $solucion = $em
            ->getRepository(Solucion::class)
            ->findOneBy(array('idAnswer' => $args['id']));

        if ($solucion) {

            if (isset($req_data['student'])) {
                $usernameUpdate = $req_data['student'];
                $solutionsWithTheSameUserName = $em
                    ->getRepository(Solucion::class)
                    ->findBy(array('student' => $usernameUpdate));

                if (count($solutionsWithTheSameUserName) > 0) {
                    return $response
                        ->withJson(
                            [
                                'code' => 400,
                                'message' => Messages::MESSAGES['tdw_put_solutions_400']
                            ],
                            400
                        )
                        ->withStatus(400, Messages::MESSAGES['tdw_put_solutions_400']);

                }

                $solucion->setStudent($usernameUpdate);
            }

            if (isset($req_data['questionTitle'])) {
                $questionTitleUpdate = $req_data['questionTitle'];
                $solutionsWithTheSameQuestionTitle = $em
                    ->getRepository(Solucion::class)
                    ->findBy(array('questionTitle' => $questionTitleUpdate));

                if (count($solutionsWithTheSameQuestionTitle) > 0) {
                    return $response
                        ->withJson(
                            [
                                'code' => 400,
                                'message' => Messages::MESSAGES['tdw_put_solutions_400']
                            ],
                            400
                        )
                        ->withStatus(400, Messages::MESSAGES['tdw_put_solutions_400']);
                }

                $solucion->setQuestionTitle($questionTitleUpdate);
            }

            if (isset($req_data['proposedSolution'])) {
                $proposedSolutionUpdate = $req_data['proposedSolution'];
                $solutionsWithTheSameProposedSolution = $em
                    ->getRepository(Solucion::class)
                    ->findBy(array('proposedSolution' => $proposedSolutionUpdate));

                if (count($solutionsWithTheSameProposedSolution) > 0) {
                    return $response
                        ->withJson(
                            [
                                'code' => 400,
                                'message' => Messages::MESSAGES['tdw_put_solutions_400']
                            ],
                            400
                        )
                        ->withStatus(400, Messages::MESSAGES['tdw_put_solutions_400']);
                }

                $solucion->setProposedSolution($proposedSolutionUpdate);
            }

            if(isset($req_data['answers'])) {
                $solucion->addAnswer($req_data['answers']);
            }

            $em->flush();

            return $response
                ->withJson(
                    $solucion
                )
                ->withStatus(209, Messages::MESSAGES['tdw_put_solutions_209']);
        } else {
            return $response
                ->withJson(
                    [
                        'code' => 404,
                        'message' => Messages::MESSAGES['tdw_put_solutions_404']
                    ],
                    404
                )
                ->withStatus(404, Messages::MESSAGES['tdw_put_solutions_404']);
        }
    }
)->setName('tdw_put_solutions');
