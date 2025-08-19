TYPE=VIEW
query=select coalesce(`v`.`tenant_id`,1) AS `tenant_id`,`v`.`id` AS `voucher_id`,`v`.`date` AS `date`,`v`.`voucher_type` AS `voucher_type`,`ve`.`account_head_id` AS `account_head_id`,`ah`.`name` AS `account_head_name`,`ve`.`type` AS `entry_type`,`ve`.`amount` AS `amount`,`ve`.`narration` AS `narration` from ((`u392770117_multitalantdai`.`vouchers` `v` join `u392770117_multitalantdai`.`voucher_entries` `ve` on(`ve`.`voucher_id` = `v`.`id`)) join `u392770117_multitalantdai`.`account_heads` `ah` on(`ah`.`id` = `ve`.`account_head_id`))
md5=5638e076bb4bc68fa522945db961ab61
updatable=1
algorithm=0
definer_user=u392770117_multitalantdai
definer_host=127.0.0.1
suid=1
with_check_option=0
timestamp=0001755513879198269
create-version=2
source=SELECT coalesce(`v`.`tenant_id`,1) AS `tenant_id`, `v`.`id` AS `voucher_id`, `v`.`date` AS `date`, `v`.`voucher_type` AS `voucher_type`, `ve`.`account_head_id` AS `account_head_id`, `ah`.`name` AS `account_head_name`, `ve`.`type` AS `entry_type`, `ve`.`amount` AS `amount`, `ve`.`narration` AS `narration` FROM ((`vouchers` `v` join `voucher_entries` `ve` on(`ve`.`voucher_id` = `v`.`id`)) join `account_heads` `ah` on(`ah`.`id` = `ve`.`account_head_id`))
client_cs_name=utf8mb4
connection_cl_name=utf8mb4_general_ci
view_body_utf8=select coalesce(`v`.`tenant_id`,1) AS `tenant_id`,`v`.`id` AS `voucher_id`,`v`.`date` AS `date`,`v`.`voucher_type` AS `voucher_type`,`ve`.`account_head_id` AS `account_head_id`,`ah`.`name` AS `account_head_name`,`ve`.`type` AS `entry_type`,`ve`.`amount` AS `amount`,`ve`.`narration` AS `narration` from ((`u392770117_multitalantdai`.`vouchers` `v` join `u392770117_multitalantdai`.`voucher_entries` `ve` on(`ve`.`voucher_id` = `v`.`id`)) join `u392770117_multitalantdai`.`account_heads` `ah` on(`ah`.`id` = `ve`.`account_head_id`))
mariadb-version=100432
