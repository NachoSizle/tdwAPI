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

        // solutions
        'tdw_post_solution_201'
        => 'Solution created',
        'tdw_delete_solutions_204'
        => 'Solution deleted',
        'tdw_put_solutions_209'
        => 'Solution previously existed and is now updated',
        'tdw_cget_solutions_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_cget_solutions_404'
        => 'Solution object not found',
        'tdw_get_solutions_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_get_solutions_404'
        => 'Resource not found',
        'tdw_delete_solutions_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_delete_solutions_404'
        => 'Resource not found',
        'tdw_post_solutions_400'
        => 'Solution already exists.',
        'tdw_post_solutions_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_post_solutions_422'
        => '`Unprocessable entity`',
        'tdw_put_solutions_400'
        => '`Bad Request` Solution already exists',
        'tdw_put_solutions_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_put_solutions_404'
        => 'Resource not found',

        // rationings
        'tdw_post_rationings_201'
        => 'Rationing created',
        'tdw_delete_rationings_204'
        => 'Rationing deleted',
        'tdw_put_rationings_209'
        => 'Rationing previously existed and is now updated',
        'tdw_cget_rationings_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_cget_rationings_404'
        => 'Rationing object not found',
        'tdw_get_rationings_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_get_rationings_404'
        => 'Resource not found',
        'tdw_delete_rationings_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_delete_rationings_404'
        => 'Resource not found',
        'tdw_post_rationings_400'
        => 'Rationing already exists.',
        'tdw_post_rationings_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_post_rationings_422'
        => '`Unprocessable entity`',
        'tdw_put_rationings_400'
        => '`Bad Request` Rationing already exists',
        'tdw_put_rationings_403'
        => '`Forbidden` You don\'t have permission to access',
        'tdw_put_rationings_404'
        => 'Resource not found',

    ];
}
