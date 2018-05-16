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

        $em = getEntityManager();

        $categories = $em
            ->getRepository(Categoria::class)
            ->findAll();

        $this->logger->info(
            $request->getMethod() . ' ' . $request->getUri()->getPath(),
            ['uid' => $this->jwt->user_id, 'status' => $categories ? 200 : 404]
        );

        if (count($categories) === 0) {
            return $response
                ->withJson(
                    [
                        'code'      => 404,
                        'message'   => Messages::MESSAGES['tdw_cget_categories_404']
                    ],
                    404
                );
        }

        return empty($categories)
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
                        'categories' => $categories
                    ],
                    200
                );
    }
)->setName('tdw_cget_categories');


/**
 * Summary: Returns a category based on a single ID
 * Notes: Returns the category identified by &#x60;idCategoria&#x60;.
 *
 * @SWG\Get(
 *     method      = "GET",
 *     path        = "/categories/{idCategoria}",
 *     tags        = { "Categories" },
 *     summary     = "Returns a category based on a single ID",
 *     description = "Returns the category identified by `idCategoria`.",
 *     operationId = "tdw_get_categories",
 *     parameters  = {
 *          { "$ref" = "#/parameters/idCategoria" }
 *     },
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 200,
 *          description = "Category",
 *          schema      = { "$ref": "#/definitions/Category" }
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
    $_ENV['RUTA_API'] . '/categories/{id:[0-9]+}',
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
                        'message'   => Messages::MESSAGES['tdw_get_categories_403']
                    ],
                    403
                );
        }

        // TODO
        $categoria = getEntityManager()
            ->getRepository(Categoria::class)
            ->findOneBy(array('idCategoria' => $args['id']));

        $this->logger->info(
            $request->getMethod() . ' ' . $request->getUri()->getPath(),
            ['uid' => $this->jwt->user_id, 'status' => $categoria ? 200 : 404]
        );

        return empty($categoria)
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
                    $categoria,
                    200
                );
    }
)->setName('tdw_get_categories');


/**
 * Summary: Provides the list of HTTP supported methods
 * Notes: Return a &#x60;Allow&#x60; header with a list of HTTP supported methods.
 *
 * @SWG\Options(
 *     method      = "OPTIONS",
 *     path        = "/categories",
 *     tags        = { "Categories" },
 *     summary     = "Provides the list of HTTP supported methods",
 *     description = "Return a `Allow` header with a list of HTTP supported methods.",
 *     operationId = "tdw_options_categories",
 *     @SWG\Response(
 *          response    = 200,
 *          description = "`Allow` header <Response body is empty>",
 *     )
 * )
 *
 * @SWG\Options(
 *     method      = "OPTIONS",
 *     path        = "/categories/{userId}",
 *     tags        = { "Categories" },
 *     summary     = "Provides the list of HTTP supported methods",
 *     description = "Return a `Allow` header with a list of HTTP supported methods.",
 *     operationId = "tdw_options_categories_id",
 *     parameters={
 *          { "$ref" = "#/parameters/idCategoria" },
 *     },
 *     @SWG\Response(
 *          response    = 200,
 *          description = "`Allow` header <Response body is empty>",
 *     )
 * )
 */
/** @noinspection PhpUnusedParameterInspection */
$app->options(
    $_ENV['RUTA_API'] . '/categories[/{id:[0-9]+}]',
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
)->setName('tdw_options_categories');

/**
 * Summary: Creates a new category
 * Notes: Creates a new category
 *
 * @SWG\Post(
 *     method      = "POST",
 *     path        = "/categories",
 *     tags        = { "Categories" },
 *     summary     = "Creates a new categories",
 *     description = "Creates a new categories",
 *     operationId = "tdw_post_categories",
 *     parameters  = {
 *          {
 *          "name":        "data",
 *          "in":          "body",
 *          "description": "`Categories` properties to add to the system",
 *          "required":    true,
 *          "schema":      { "$ref": "#/definitions/CategoryData" }
 *          }
 *     },
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 201,
 *          description = "`Created` Category created",
 *          schema      = { "$ref": "#/definitions/Category" }
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
 *          description = "`Unprocessable entity`",
 *          schema      = { "$ref": "#/definitions/Message" }
 *     )
 * )
 */
$app->post(
    $_ENV['RUTA_API'] . '/categories',
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

        if (!isset($req_data['prop_descripcion'], $req_data['enum_disponible'])) { // 422 - Faltan datos
            $this->logger->info(
                $request->getMethod() . ' ' . $request->getUri()->getPath(),
                [ 'uid' => $this->jwt->user_id, 'status' => 422 ]
            );

            return $response
                ->withJson(
                    [
                        'code' => 422,
                        'message' => Messages::MESSAGES['tdw_post_categories_422']
                    ],
                    422
                );
        }

        // hay datos -> procesarlos
        // TODO

        $propDesc = $req_data['prop_descripcion'];
        $enumAvailable = $req_data['enum_disponible'];

        $categoria = new Categoria($propDesc, $enumAvailable);


        $em = getEntityManager();

        $em -> persist($categoria);
        $em -> flush();

        return $response
            ->withJson(
                [
                    'code' => 201,
                    'message' => Messages::MESSAGES['tdw_post_category_201']
                ],
                201
            );
    }
)->setName('tdw_post_categories');

/**
 * Summary: Deletes a category
 * Notes: Deletes the category identified by &#x60;idCategoria&#x60;.
 *
 * @SWG\Delete(
 *     method      = "DELETE",
 *     path        = "/categories/{idCategoria}",
 *     tags        = { "Categories" },
 *     summary     = "Deletes a category",
 *     description = "Deletes the category identified by `idCategoria`.",
 *     operationId = "tdw_delete_categories",
 *     parameters={
 *          { "$ref" = "#/parameters/idCategoria" }
 *     },
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 204,
 *          description = "Category deleted <Response body is empty>"
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
    $_ENV['RUTA_API'] . '/categories/{id:[0-9]+}',
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
                        'message'   => Messages::MESSAGES['tdw_delete_users_403']
                    ],
                    403
                );
        }

        // TODO
        $em = getEntityManager();

        $category = $em
            ->getRepository(Categoria::class)
            ->findOneBy(array('idCategoria' => $args['id']));

        $this->logger->info(
            $request->getMethod() . ' ' . $request->getUri()->getPath(),
            ['uid' => $this->jwt->user_id, 'status' => $category ? 204 : 404]
        );

        if (!$category) {
            return $response
                ->withJson(
                    [
                        'code'      => 404,
                        'message'   => Messages::MESSAGES['tdw_delete_categories_404']
                    ],
                    404
                );
        }

        $em -> remove($category);
        $em -> flush();

        return $response
            ->withJson(
                [
                    'code'      => 204,
                    'message'   => Messages::MESSAGES['tdw_delete_categories_204']
                ],
                204
            );
    }
)->setName('tdw_delete_categories');

/**
 * Summary: Updates a category
 * Notes: Updates the category identified by &#x60;idCategoria&#x60;.
 *
 * @SWG\Put(
 *     method      = "PUT",
 *     path        = "/categories/{idCategoria}",
 *     tags        = { "Categories" },
 *     summary     = "Updates a category",
 *     description = "Updates the category identified by `idCategoria`.",
 *     operationId = "tdw_put_categories",
 *     parameters={
 *          { "$ref" = "#/parameters/idCategoria" },
 *          {
 *          "name":        "data",
 *          "in":          "body",
 *          "description": "`Category` data to update",
 *          "required":    true,
 *          "schema":      { "$ref": "#/definitions/CategoryData" }
 *          }
 *     },
 *     security    = {
 *          { "ResultsSecurity": {} }
 *     },
 *     @SWG\Response(
 *          response    = 209,
 *          description = "`Content Returned` Category previously existed and is now updated",
 *          schema      = { "$ref": "#/definitions/Category" }
 *     ),
 *     @SWG\Response(
 *          response    = 400,
 *          description = "`Bad Request` Category already exists",
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
    $_ENV['RUTA_API'] . '/categories/{id:[0-9]+}',
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
                        'message'   => Messages::MESSAGES['tdw_put_categories_403']
                    ],
                    403
                );
        }

        $req_data
            = $request->getParsedBody()
            ?? json_decode($request->getBody(), true);

        // TODO
        $em = getEntityManager();

        $category = $em
            ->getRepository(Categoria::class)
            ->findOneBy(array('idCategoria' => $args['id']));

        if ($category) {

            if (isset($req_data['prop_descripcion'])) {
                $category->setPropuestaDescripcion($req_data['prop_descripcion']);
            }

            if (isset($req_data['enum_disponible'])) {
                $category->setCorrecta($req_data['enum_disponible']);
            }

            $em->flush();

            return $response
                ->withJson(
                    Messages::MESSAGES['tdw_put_categories_209']
                )
                ->withStatus(209, Messages::MESSAGES['tdw_put_categories_209']);
        }
    }
)->setName('tdw_put_categories');
