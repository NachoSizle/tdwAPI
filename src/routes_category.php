<?php
/**
 * PHP version 7.2
 * src\routes_user.php
 */

use Slim\Http\Request;
use Slim\Http\Response;
use Swagger\Annotations as SWG;
use TDW18\Usuarios\Entity\Categoria;
use TDW18\Usuarios\Messages;

/**
 * Summary: Returns all categories
 * Notes: Returns all categories from the system that the user has access to.
 *
 * @SWG\Get(
 *     method      = "GET",
 *     path        = "/categories",
 *     tags        = { "Categories" },
 *     summary     = "Returns all categories",
 *     description = "Returns all categories from the system that the user has access to.",
 *     operationId = "tdw_cget_categories",
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 200,
 *          description = "Category array response",
 *          schema      = { "$ref": "#/definitions/CategoriesArray" }
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
    $_ENV['RUTA_API'] . '/categories',
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
                            'message'   => Messages::MESSAGES['tdw_cget_categories_403']
                        ],
                        403
                    );
        }

        $categorias = getEntityManager()
            ->getRepository(Categoria::class)
            ->findAll();

        $this->logger->info(
            $request->getMethod() . ' ' . $request->getUri()->getPath(),
            ['uid' => $this->jwt->user_id, 'status' => $categorias ? 200 : 404]
        );

        return empty($usuarios)
            ? $response
                ->withJson(
                    [
                        'code'      => 404,
                        'message'   => Messages::MESSAGES['tdw_cget_categories_404']
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
)->setName('tdw_cget_categories');

