TYPE=VIEW
query=select `sr`.`id` AS `stock_id`,`sr`.`product_name` AS `product_name`,`sh`.`name` AS `head_name`,`su`.`name` AS `unit_name`,`sr`.`opening_stock_qty` AS `opening_stock_qty`,`sr`.`opening_stock_rate_per_unit` AS `opening_stock_rate_per_unit`,`sr`.`rate_per_unit` AS `rate_per_unit`,`sr`.`head_id` AS `head_id` from ((`usama_db`.`stock_registration` `sr` join `usama_db`.`stock_heads` `sh` on(`sr`.`head_id` = `sh`.`id`)) join `usama_db`.`stock_units` `su` on(`sr`.`unit_id` = `su`.`id`)) where `sr`.`is_stock_item` = 1
md5=c102f18f2494688571a889f4530c417b
updatable=1
algorithm=0
definer_user=root
definer_host=localhost
suid=2
with_check_option=0
timestamp=0001753528749962973
create-version=2
source=SELECT \n    sr.id AS stock_id,\n    sr.product_name,\n    sh.name AS head_name,\n    su.name AS unit_name,\n    sr.opening_stock_qty,\n    sr.opening_stock_rate_per_unit,\n    sr.rate_per_unit,\n    sr.head_id\nFROM \n    stock_registration sr\nJOIN \n    stock_heads sh ON sr.head_id = sh.id\nJOIN \n    stock_units su ON sr.unit_id = su.id\nWHERE \n    sr.is_stock_item = 1
client_cs_name=utf8mb4
connection_cl_name=utf8mb4_unicode_ci
view_body_utf8=select `sr`.`id` AS `stock_id`,`sr`.`product_name` AS `product_name`,`sh`.`name` AS `head_name`,`su`.`name` AS `unit_name`,`sr`.`opening_stock_qty` AS `opening_stock_qty`,`sr`.`opening_stock_rate_per_unit` AS `opening_stock_rate_per_unit`,`sr`.`rate_per_unit` AS `rate_per_unit`,`sr`.`head_id` AS `head_id` from ((`usama_db`.`stock_registration` `sr` join `usama_db`.`stock_heads` `sh` on(`sr`.`head_id` = `sh`.`id`)) join `usama_db`.`stock_units` `su` on(`sr`.`unit_id` = `su`.`id`)) where `sr`.`is_stock_item` = 1
mariadb-version=100432
