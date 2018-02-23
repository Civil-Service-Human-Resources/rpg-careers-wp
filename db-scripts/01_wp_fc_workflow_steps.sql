START TRANSACTION;

UPDATE wp_fc_workflow_steps
SET
step_info = '{\"process\":\"review\",\"step_name\":\"Author assignment\",\"assign_to_all\":0,\"task_assignee\":{\"roles\":[\"content_author\"],\"users\":[],\"groups\":[]},\"review_approval\":\"everyone\"}',
process_info = '{\"assign_subject\":\"\",\"assign_content\":\"\",\"reminder_subject\":\"\",\"reminder_content\":\"\"}',
update_datetime = NOW()
WHERE ID = 1;

UPDATE wp_fc_workflow_steps
SET
step_info = '{\"process\":\"review\",\"step_name\":\"Approve\",\"assign_to_all\":0,\"task_assignee\":{\"roles\":[\"administrator\",\"content_approver\"],\"users\":[],\"groups\":[]}}',
process_info = '{\"assign_subject\":\"\",\"assign_content\":\"\",\"reminder_subject\":\"\",\"reminder_content\":\"\"}',
update_datetime = NOW()
WHERE ID = 2;

UPDATE wp_fc_workflow_steps
SET
step_info = '{\"process\":\"publish\",\"step_name\":\"Publish\",\"assign_to_all\":0,\"task_assignee\":{\"roles\":[\"administrator\",\"content_publisher\"],\"users\":[],\"groups\":[]}}',
process_info = '{\"assign_subject\":\"\",\"assign_content\":\"\",\"reminder_subject\":\"\",\"reminder_content\":\"\"}',
update_datetime = NOW()
WHERE ID = 3;

COMMIT;