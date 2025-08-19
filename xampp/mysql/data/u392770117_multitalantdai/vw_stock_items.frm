TYPE=VIEW
query=select coalesce(`sr`.`tenant_id`,1) AS `tenant_id`,`sr`.`id` AS `stock_id`,`sr`.`product_name` AS `product_name`,`sh`.`name` AS `head_name`,`su`.`name` AS `unit_name`,`sr`.`opening_stock_qty` AS `opening_stock_qty`,`sr`.`opening_stock_rate_per_unit` AS `opening_stock_rate_per_unit`,`sr`.`rate_per_unit` AS `rate_per_unit`,`sr`.`head_id` AS `head_id` from ((`u392770117_multitalantdai`.`stock_registration` `sr` join `u392770117_multitalantdai`.`stock_heads` `sh` on(`sh`.`id` = `sr`.`head_id`)) join `u392770117_multitalantdai`.`stock_units` `su` on(`su`.`id` = `sr`.`unit_id`)) where coalesce(`sr`.`is_stock_item`,0) = 1
md5=112aec4b568a867bb29f565123e445ef
updatable=1
algorithm=0
definer_user=u392770117_multitalantdai
definer_host=127.0.0.1
suid=1
with_check_option=0
timestamp=0001755513879188309
create-version=2
source=SELECT coalesce(`sr`.`tenant_id`,1) AS `tenant_id`, `sr`.`id` AS `stock_id`, `sr`.`product_name` AS `product_name`, `sh`.`name` AS `head_name`, `su`.`name` AS `unit_name`, `sr`.`opening_stock_qty` AS `opening_stock_qty`, `sr`.`opening_stock_rate_per_unit` AS `opening_stock_rate_per_unit`, `sr`.`rate_per_unit` AS `rate_per_unit`, `sr`.`head_id` AS `head_id` FROM ((`stock_registration` `sr` join `stock_heads` `sh` on(`sh`.`id` = `sr`.`head_id`)) join `stock_units` `su` on(`su`.`id` = `sr`.`unit_id`)) WHERE coalesce(`sr`.`is_stock_item`,0) = 1
client_cs_name=utf8mb4
connection_cl_name=utf8mb4_general_ci
view_body_utf8=select coalesce(`sr`.`tenant_id`,1) AS `tenant_id`,`sr`.`id` AS `stock_id`,`sr`.`product_name` AS `product_name`,`sh`.`name` AS `head_name`,`su`.`name` AS `unit_name`,`sr`.`opening_stock_qty` AS `opening_stock_qty`,`sr`.`opening_stock_rate_per_unit` AS `opening_stock_rate_per_unit`,`sr`.`rate_per_unit` AS `rate_per_unit`,`sr`.`head_id` AS `head_id` from ((`u392770117_multitalantdai`.`stock_registration` `sr` join `u392770117_multitalantdai`.`stock_heads` `sh` on(`sh`.`id` = `sr`.`head_id`)) join `u392770117_multitalantdai`.`stock_units` `su` on(`su`.`id` = `sr`.`unit_id`)) where coalesce(`sr`.`is_stock_item`,0) = 1
mariadb-version=100432
