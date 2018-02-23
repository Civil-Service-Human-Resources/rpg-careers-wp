UPDATE wp_fc_workflows
SET
name = 'RPG Content Workflow',
description = 'RPG Content Workflow',
wf_info = '{\"steps\":{\"step0\":{\"fc_addid\":\"step0\",\"fc_label\":\"Approve\",\"fc_dbid\":\"2\",\"fc_process\":\"review\",\"fc_position\":[\"133px\",\"169px\"]},\"step1\":{\"fc_addid\":\"step1\",\"fc_label\":\"Author assignment\",\"fc_dbid\":\"1\",\"fc_process\":\"review\",\"fc_position\":[\"377px\",\"144px\"]},\"step2\":{\"fc_addid\":\"step2\",\"fc_label\":\"Publish\",\"fc_dbid\":\"3\",\"fc_process\":\"publish\",\"fc_position\":[\"128px\",\"532px\"]}},\"conns\":{\"0\":{\"sourceId\":\"step0\",\"targetId\":\"step1\",\"post_status\":\"draft\",\"connset\":{\"connector\":\"StateMachine\",\"paintStyle\":{\"lineWidth\":3,\"strokeStyle\":\"red\"}}},\"1\":{\"sourceId\":\"step1\",\"targetId\":\"step0\",\"post_status\":\"pending\",\"connset\":{\"connector\":\"StateMachine\",\"paintStyle\":{\"lineWidth\":3,\"strokeStyle\":\"blue\"}}},\"2\":{\"sourceId\":\"step0\",\"targetId\":\"step2\",\"post_status\":\"ready-to-publish\",\"connset\":{\"connector\":\"StateMachine\",\"paintStyle\":{\"lineWidth\":3,\"strokeStyle\":\"blue\"}}},\"3\":{\"sourceId\":\"step2\",\"targetId\":\"step1\",\"post_status\":\"draft\",\"connset\":{\"connector\":\"StateMachine\",\"paintStyle\":{\"lineWidth\":3,\"strokeStyle\":\"red\"}}}},\"first_step\":[{\"step\":\"step0\",\"post_status\":\"pending\"}]}',
version = 1,
parent_id = 0,
start_date = '2018-02-01',
end_date = '0000-00-00',
is_auto_submit = 0,
auto_submit_info = NULL,
is_valid = 1,
update_datetime = NOW(),
wf_additional_info = 'a:4:{s:16:\"wf_for_new_posts\";i:1;s:20:\"wf_for_revised_posts\";i:1;s:12:\"wf_for_roles\";a:0:{}s:17:\"wf_for_post_types\";a:1:{i:0;s:4:\"page\";}}'
WHERE ID = '1';