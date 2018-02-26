START TRANSACTION;

DELETE FROM wp_term_taxonomy WHERE description = 'New idea proposed.';
DELETE FROM wp_terms WHERE name = 'Pitch';

INSERT INTO wp_terms (name,slug,term_group)
SELECT 'With Approver', 'with-approver', 0
FROM wp_terms
WHERE NOT EXISTS(
    SELECT name,slug,term_group
    FROM wp_terms
    WHERE name = 'With Approver'
	AND slug = 'with-approver'
	AND term_group = 0
)
LIMIT 1;

SELECT @id:=term_id AS ID FROM wp_terms WHERE name = 'With Approver' AND slug = 'with-approver' AND term_group = 0;

INSERT INTO wp_term_taxonomy (term_id,taxonomy,description,parent,count)
SELECT @id, 'post_status','Page is ready to be approved', 0, 0
FROM wp_term_taxonomy
WHERE NOT EXISTS(
    SELECT term_id,taxonomy,description,parent,count
    FROM wp_term_taxonomy
    WHERE term_id = @id
	AND taxonomy = 'post_status'
	AND description =  'Page is ready to be approved'
	AND parent = 0
	AND count = 0
)
LIMIT 1;

COMMIT;