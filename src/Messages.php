<?php
/**
 * PHP version 7.2
 * src\Messages.php
 */

namespace TDW18\Usuarios;

class Messages
{
    const MESSAGES = [
        'tdw_unauthorized_401'
        => 'UNAUTHORIZED: invalid X-Token header',
        'tdw_pathnotfound_404'
        => 'Path not found',
        'tdw_notallowed_405'
        => 'Method not allowed',

        // login
        'tdw_post_login_404'
        => 'User not found or password does not match',

        // users
        'tdw_post_user_201'
        => 'User created',
        'tdw_delete_users_204'
        => 'User deleted',
        'tdw_put_users_209'
        => 'User previously existed and is now updated',
        'tdw_cget_users_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_cget_users_404'
        => 'User object not found',
        'tdw_get_users_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_get_users_404'
        => 'User object not found',
        'tdw_delete_users_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_delete_users_404'
        => 'Resource not found',
        'tdw_post_users_400'
        => '`Bad Request` User name or e-mail already exists',
        'tdw_post_users_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_post_users_422'
        => '`Unprocessable entity` User name, e-mail or password is left out',
        'tdw_put_users_400'
        => '`Bad Request` User name or e-mail already exists',
        'tdw_put_users_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_put_users_404'
        => 'User not found',

        // cuestions
        'tdw_post_question_201'
        => 'Question created',
        'tdw_delete_questions_204'
        => 'Question deleted',
        'tdw_put_questions_209'
        => 'Question previously existed and is now updated',
        'tdw_cget_questions_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_cget_questions_404'
        => 'Question object not found',
        'tdw_get_questions_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_get_questions_404'
        => 'Resource not found',
        'tdw_delete_questions_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_delete_questions_404'
        => 'Resource not found',
        'tdw_post_questions_400'
        => 'Question already exists.',
        'tdw_post_questions_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_post_questions_422'
        => '`Unprocessable entity`',
        'tdw_put_questions_400'
        => '`Bad Request` Question already exists',
        'tdw_put_questions_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_put_questions_404'
        => 'Resource not found',

        // categories
        'tdw_post_category_201'
        => 'Category created',
        'tdw_delete_categories_204'
        => 'Category deleted',
        'tdw_put_categories_209'
        => 'Category previously existed and is now updated',
        'tdw_cget_categories_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_cget_categories_404'
        => 'Category object not found',
        'tdw_get_categories_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_get_categories_404'
        => 'Resource not found',
        'tdw_delete_categories_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_delete_categories_404'
        => 'Resource not found',
        'tdw_post_categories_400'
        => 'Category with this name already exists.',
        'tdw_post_categories_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_post_categories_422'
        => '`Unprocessable entity` Category name is left out',
        'tdw_put_categories_400'
        => '`Bad Request` Category name already exists',
        'tdw_put_categories_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_put_categories_404'
        => 'Resource not found'
    ];
}
