INSERT INTO wp_options (option_name, option_value, autoload)
SELECT 'oasiswf_activate_workflow', 'active', 'yes'
FROM wp_options
WHERE NOT EXISTS(
    SELECT option_name, option_value, autoload
    FROM wp_options
    WHERE option_name = 'oasiswf_activate_workflow'
	AND option_value = 'active'
	AND autoload = 'yes'
)
LIMIT 1;