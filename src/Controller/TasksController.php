<?php

namespace App\Controller;

use App\Service\TaskService;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use const App\config\TASK_SERVICE;
use const App\config\USER_SERVICE;

class TasksController extends BaseController
{
    public function list(array $params)
    {
        $page = isset($params['_query']['page']) ? (int)$params['_query']['page'] : 1;
        $orderColumn = $params['_query']['ord_c'] ?? null;
        $orderDirection = $params['_query']['ord_d'] ?? null;

        /** @var TaskService $taskService */
        $taskService = $this->get(TASK_SERVICE);
        $tasks = $taskService->tasksList($page, $orderColumn, $orderDirection);

        return new Response($this->render('tasks.twig', [
            'tasks' => $tasks['result'],
            'pagination' => [
                'page' => $page,
                'last_page' => $tasks['last_page'],
                'first_blocked' => $page == 1,
                'prev_blocked' => $page == 1,
                'last_blocked' => $page == $tasks['last_page'],
                'next_blocked' => $page == $tasks['last_page'],
            ],
            'ordC' => $orderColumn,
            'ordD' => $orderDirection,
            'requestUri' => $params['_requestUri'],
            'user' => $this->getLoggedInUser($params['_token'])
        ]));
    }

    public function add(array $params)
    {
        $user = $this->getLoggedInUser($params['_token']);
        if (!$user || !$user['is_admin']) {
            return new Response('/', Response::HTTP_NOT_FOUND);
        }

        /** @var UserService $userService */
        $userService = $this->get(USER_SERVICE);
        /** @var TaskService $taskService */
        $taskService = $this->get(TASK_SERVICE);
        $assignees = $userService->findAssignees();
        if ('GET' === $params['_method']) {
            return new Response($this->render('task.twig', [
                'assignees' => $assignees,
                'user' => $user
            ]));
        } else {
            $formData = $params['_data'];
            $assignee = $formData['assignee'] ?? null;
            $description = $formData['description'] ?? null;

            if (!$assignee || !$description) {
                return new Response($this->render('task.twig', [
                    'task' => [
                        'assignee_id' => $assignee,
                        'task_desc' => $description,
                    ],
                    'assignees' => $assignees,
                    'errors' => ['Не заполнены поля ответственный или описание задачи'],
                    'user' => $user
                ]));
            }

            $taskService->addTask($assignee, $description);

            return new Response($this->render('task.twig', [
                'assignees' => $assignees,
                'messages' => ['Задача добавлена'],
                'user' => $user
            ]));
        }
    }

    public function edit(array $params)
    {
        $user = $this->getLoggedInUser($params['_token']);
        if (!$user || !$user['is_admin']) {
            return new RedirectResponse('/', Response::HTTP_FOUND);
        }

        $taskId = $params['id'];

        /** @var UserService $userService */
        $userService = $this->get(USER_SERVICE);
        /** @var TaskService $taskService */
        $taskService = $this->get(TASK_SERVICE);
        $task = $taskService->find($taskId);

        $assignees = $userService->findAssignees();
        if ('GET' === $params['_method']) {
            return new Response($this->render('task.twig', [
                'task' => $task,
                'assignees' => $assignees,
                'user' => $user
            ]));
        } else {
            $formData = $params['_data'];
            $assignee = $formData['assignee'] ?? null;
            $description = $formData['description'] ?? null;

            if (!$assignee || !$description) {
                return new Response($this->render('task.twig', [
                    'task' => [
                        'assignee_id' => $assignee,
                        'task_desc' => $description,
                    ],
                    'assignees' => $assignees,
                    'errors' => ['Не заполнены поля ответственный или описание задачи'],
                    'user' => $user
                ]));
            }

            $taskService->editTask($taskId, $assignee, $description);

            return new Response($this->render('task.twig', [
                'task' => $taskService->find($taskId),
                'assignees' => $assignees,
                'messages' => ['Задача обновлена'],
                'user' => $user
            ]));
        }
    }

    public function toggle(array $params)
    {
        $taskId = $params['id'];
        /** @var TaskService $taskService */
        $taskService = $this->get(TASK_SERVICE);
        $taskService->toggle($taskId);

        return new RedirectResponse($params['_referrer']);
    }
}
