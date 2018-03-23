START TRANSACTION;

DELETE FROM wp_term_taxonomy WHERE description = 'New idea proposed.';
DELETE FROM wp_terms WHERE name = 'Pitch';

DELETE FROM wp_term_taxonomy WHERE description = 'Page marked for deletion and is ready to be deleted.';
DELETE FROM wp_terms WHERE name = 'Ready to Bin';

DELETE FROM wp_term_taxonomy WHERE description = 'Page is ready for publication.';
DELETE FROM wp_terms WHERE name = 'Ready to Publish';

DELETE FROM wp_term_taxonomy WHERE description = 'Page revision is ready for publication.';
DELETE FROM wp_terms WHERE name = 'Ready to Revise';

DELETE FROM wp_term_taxonomy WHERE description = 'Page is ready to be approved';
DELETE FROM wp_terms WHERE name = 'With Approver'; 	

DELETE FROM wp_term_taxonomy WHERE description = 'Page marked for deletion and is with approver.';
DELETE FROM wp_terms WHERE name = 'With Approver Delete'; 	

DELETE FROM wp_term_taxonomy WHERE description = 'Page revision with approver.';
DELETE FROM wp_terms WHERE name = 'With Approver Revise'; 	


INSERT INTO wp_terms (name,slug,term_group)
SELECT 'Publish - with approver', 'pub-with-approver', 0
FROM wp_terms
WHERE NOT EXISTS(
    SELECT name,slug,term_group
    FROM wp_terms
    WHERE name = 'Publish - with approver'
	AND slug = 'pub-with-approver'
	AND term_group = 0
)
LIMIT 1;

SELECT @id:=term_id AS ID FROM wp_terms WHERE name = 'Publish - with approver' AND slug = 'pub-with-approver' AND term_group = 0;

INSERT INTO wp_term_taxonomy (term_id,taxonomy,description,parent,count)
SELECT @id, 'post_status','Page being published is with the approver.', 0, 0
FROM wp_term_taxonomy
WHERE NOT EXISTS(
    SELECT term_id,taxonomy,description,parent,count
    FROM wp_term_taxonomy
    WHERE term_id = @id
	AND taxonomy = 'post_status'
	AND description =  'Page being published is with the approver.'
	AND parent = 0
	AND count = 0
)
LIMIT 1;

INSERT INTO wp_terms (name,slug,term_group)
SELECT 'Publish - sign off', 'pub-sign-off', 0
FROM wp_terms
WHERE NOT EXISTS(
    SELECT name,slug,term_group
    FROM wp_terms
    WHERE name = 'Publish - sign off'
	AND slug = 'pub-sign-off'
	AND term_group = 0
)
LIMIT 1;

SELECT @id:=term_id AS ID FROM wp_terms WHERE name = 'Publish - sign off' AND slug = 'pub-sign-off' AND term_group = 0;

INSERT INTO wp_term_taxonomy (term_id,taxonomy,description,parent,count)
SELECT @id, 'post_status','Page being published is ready to be signed off.', 0, 0
FROM wp_term_taxonomy
WHERE NOT EXISTS(
    SELECT term_id,taxonomy,description,parent,count
    FROM wp_term_taxonomy
    WHERE term_id = @id
	AND taxonomy = 'post_status'
	AND description =  'Page being published is ready to be signed off.'
	AND parent = 0
	AND count = 0
)
LIMIT 1;



INSERT INTO wp_terms (name,slug,term_group)
SELECT 'Deletion - with approver', 'del-with-approver', 0
FROM wp_terms
WHERE NOT EXISTS(
    SELECT name,slug,term_group
    FROM wp_terms
    WHERE name = 'Deletion - with approver'
	AND slug = 'del-with-approver'
	AND term_group = 0
)
LIMIT 1;

SELECT @id:=term_id AS ID FROM wp_terms WHERE name = 'Deletion - with approver' AND slug = 'del-with-approver' AND term_group = 0;

INSERT INTO wp_term_taxonomy (term_id,taxonomy,description,parent,count)
SELECT @id, 'post_status','Page marked for deletion is with the approver.', 0, 0
FROM wp_term_taxonomy
WHERE NOT EXISTS(
    SELECT term_id,taxonomy,description,parent,count
    FROM wp_term_taxonomy
    WHERE term_id = @id
	AND taxonomy = 'post_status'
	AND description =  'Page marked for deletion is with the approver.'
	AND parent = 0
	AND count = 0
)
LIMIT 1;

INSERT INTO wp_terms (name,slug,term_group)
SELECT 'Deletion - sign off', 'del-sign-off', 0
FROM wp_terms
WHERE NOT EXISTS(
    SELECT name,slug,term_group
    FROM wp_terms
    WHERE name = 'Deletion - sign off'
	AND slug = 'del-sign-off'
	AND term_group = 0
)
LIMIT 1;

SELECT @id:=term_id AS ID FROM wp_terms WHERE name = 'Deletion - sign off' AND slug = 'del-sign-off' AND term_group = 0;

INSERT INTO wp_term_taxonomy (term_id,taxonomy,description,parent,count)
SELECT @id, 'post_status','Page marked for deletion is ready to be signed off.', 0, 0
FROM wp_term_taxonomy
WHERE NOT EXISTS(
    SELECT term_id,taxonomy,description,parent,count
    FROM wp_term_taxonomy
    WHERE term_id = @id
	AND taxonomy = 'post_status'
	AND description =  'Page marked for deletion is ready to be signed off.'
	AND parent = 0
	AND count = 0
)
LIMIT 1;


INSERT INTO wp_terms (name,slug,term_group)
SELECT 'Revision - with approver', 'rev-with-approver', 0
FROM wp_terms
WHERE NOT EXISTS(
    SELECT name,slug,term_group
    FROM wp_terms
    WHERE name = 'Revision - with approver'
	AND slug = 'rev-with-approver'
	AND term_group = 0
)
LIMIT 1;

SELECT @id:=term_id AS ID FROM wp_terms WHERE name = 'Revision - with approver' AND slug = 'rev-with-approver' AND term_group = 0;

INSERT INTO wp_term_taxonomy (term_id,taxonomy,description,parent,count)
SELECT @id, 'post_status','Page being revised is with the approver.', 0, 0
FROM wp_term_taxonomy
WHERE NOT EXISTS(
    SELECT term_id,taxonomy,description,parent,count
    FROM wp_term_taxonomy
    WHERE term_id = @id
	AND taxonomy = 'post_status'
	AND description =  'Page being revised is with the approver.'
	AND parent = 0
	AND count = 0
)
LIMIT 1;

INSERT INTO wp_terms (name,slug,term_group)
SELECT 'Revision - sign off', 'rev-sign-off', 0
FROM wp_terms
WHERE NOT EXISTS(
    SELECT name,slug,term_group
    FROM wp_terms
    WHERE name = 'Revision - sign off'
	AND slug = 'rev-sign-off'
	AND term_group = 0
)
LIMIT 1;

SELECT @id:=term_id AS ID FROM wp_terms WHERE name = 'Revision - sign off' AND slug = 'rev-sign-off' AND term_group = 0;

INSERT INTO wp_term_taxonomy (term_id,taxonomy,description,parent,count)
SELECT @id, 'post_status','Page revision is ready to be signed off.', 0, 0
FROM wp_term_taxonomy
WHERE NOT EXISTS(
    SELECT term_id,taxonomy,description,parent,count
    FROM wp_term_taxonomy
    WHERE term_id = @id
	AND taxonomy = 'post_status'
	AND description =  'Page revision is ready to be signed off.'
	AND parent = 0
	AND count = 0
)
LIMIT 1;


INSERT INTO wp_terms (name,slug,term_group)
SELECT 'Unpublish - with approver', 'unpub-with-approver', 0
FROM wp_terms
WHERE NOT EXISTS(
    SELECT name,slug,term_group
    FROM wp_terms
    WHERE name = 'Unpublish - with approver'
	AND slug = 'unpub-with-approver'
	AND term_group = 0
)
LIMIT 1;

SELECT @id:=term_id AS ID FROM wp_terms WHERE name = 'Unpublish - with approver' AND slug = 'unpub-with-approver' AND term_group = 0;

INSERT INTO wp_term_taxonomy (term_id,taxonomy,description,parent,count)
SELECT @id, 'post_status','Page being unpublished is with the approver.', 0, 0
FROM wp_term_taxonomy
WHERE NOT EXISTS(
    SELECT term_id,taxonomy,description,parent,count
    FROM wp_term_taxonomy
    WHERE term_id = @id
	AND taxonomy = 'post_status'
	AND description =  'Page being unpublished is with the approver.'
	AND parent = 0
	AND count = 0
)
LIMIT 1;

INSERT INTO wp_terms (name,slug,term_group)
SELECT 'Unpublish - sign off', 'unpub-sign-off', 0
FROM wp_terms
WHERE NOT EXISTS(
    SELECT name,slug,term_group
    FROM wp_terms
    WHERE name = 'Unpublish - sign off'
	AND slug = 'unpub-sign-off'
	AND term_group = 0
)
LIMIT 1;

SELECT @id:=term_id AS ID FROM wp_terms WHERE name = 'Unpublish - sign off' AND slug = 'unpub-sign-off' AND term_group = 0;

INSERT INTO wp_term_taxonomy (term_id,taxonomy,description,parent,count)
SELECT @id, 'post_status','Page being unpublished is ready to be signed off.', 0, 0
FROM wp_term_taxonomy
WHERE NOT EXISTS(
    SELECT term_id,taxonomy,description,parent,count
    FROM wp_term_taxonomy
    WHERE term_id = @id
	AND taxonomy = 'post_status'
	AND description =  'Page being unpublished is ready to be signed off.'
	AND parent = 0
	AND count = 0
)
LIMIT 1;

COMMIT;