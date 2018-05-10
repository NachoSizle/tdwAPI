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

        return empty($usuarios)
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
                        'cuaestions' => $cuestiones
                    ],
                    200
                );
    }
)->setName('tdw_cget_cuestions');

