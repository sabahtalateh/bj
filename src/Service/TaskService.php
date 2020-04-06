<?php

namespace App\Service;

use App\Enum\TaskStatus;

class TaskService extends BaseService
{
    const TASKS_PER_PAGE = 3;

    public function find($id): ?array
    {
        $query = "
            SELECT t.id          as task_id,
                   t.description as task_desc,
                   t.status      as task_status,
                   t.modified    as task_modified,
                   u.id          as assignee_id,
                   u.name        as assignee_name,
                   u.email       as assignee_email
            FROM task t
                     JOIN user u on t.assignee = u.id
            WHERE t.id = '${id}'
        ";

        $result = $this->query($query);
        if (count($result) === 1) {
            return $result[0];
        }
        return null;
    }

    public function tasksList(int $page = 1, ?string $orderColumn = null, ?string $orderDirection = 'DESC'): array
    {
        $offset = ($page * self::TASKS_PER_PAGE) - self::TASKS_PER_PAGE;
        $limit = self::TASKS_PER_PAGE;

        $orderByQueryPart = "";
        if ($orderColumn) {
            $orderByQueryPart .= "ORDER BY ${orderColumn} ${orderDirection}";
        }

        $query = "
            SELECT t.id          as task_id,
                   t.description as task_desc,
                   t.status      as task_status,
                   t.modified    as task_modified,
                   u.name        as user_name,
                   u.email       as user_email
            FROM task t
                     JOIN user u on t.assignee = u.id
            ${orderByQueryPart}
            LIMIT {$limit} OFFSET ${offset}
        ";

        $countQuery = "SELECT COUNT(t.id) as c FROM task t";
        $total = (int)$this->query($countQuery)[0]['c'];

        return [
            'result' => $this->query($query),
            'last_page' => (int)ceil($total / self::TASKS_PER_PAGE)
        ];
    }

    public function addTask(string $assigneeId, string $description)
    {
        $query = "INSERT INTO task (assignee, description) VALUES ('${assigneeId}', '${description}')";
        $this->query($query);
    }

    public function editTask($id, string $newAssigneeId, string $newDescription)
    {
        $oldTask = "SELECT assignee, description FROM task WHERE id = '${id}'";

        $oldAssignee = $this->query($oldTask)[0]['assignee'];
        $oldDescription = $this->query($oldTask)[0]['description'];

        $assigneeUpdated = $oldAssignee !== $newAssigneeId;
        $descriptionUpdated = $oldDescription !== $newDescription;

        $updates = [];
        if ($assigneeUpdated) {
            $updates[] = "assignee = ${newAssigneeId}";
        }
        if ($descriptionUpdated) {
            $updates[] = "description = '${newDescription}'";
            $updates[] = "modified = true";
        }
        if (0 === count($updates)) {
            return;
        }
        $updateQuery = "UPDATE task SET " . implode(", ", $updates) . " WHERE id = '${id}'";
        $this->query($updateQuery);
    }

    public function toggle($taskId)
    {
        $currentStatusQuery = "SELECT status FROM task WHERE id = '${taskId}'";
        $currentStatus = $this->query($currentStatusQuery)[0]['status'];
        $newStatus = $currentStatus === TaskStatus::IN_PROGRESS()->getValue()
            ? TaskStatus::DONE()
            : TaskStatus::IN_PROGRESS();

        $updateStatusQuery = "UPDATE task SET status = '${newStatus}' WHERE id = '${taskId}'";
        $this->query($updateStatusQuery);
    }
}