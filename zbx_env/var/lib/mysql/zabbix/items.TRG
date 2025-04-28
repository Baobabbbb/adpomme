TYPE=TRIGGERS
triggers='CREATE DEFINER=`root`@`%` trigger items_name_upper_insert\nbefore insert on items for each row\nset new.name_upper=upper(new.name)' 'CREATE DEFINER=`root`@`%` trigger items_insert after insert on items\nfor each row\ninsert into changelog (object,objectid,operation,clock)\nvalues (3,new.itemid,1,unix_timestamp())' 'CREATE DEFINER=`root`@`%` trigger items_name_upper_update\nbefore update on items for each row\nbegin\nif new.name<>old.name\nthen\nset new.name_upper=upper(new.name);\nend if;\nend' 'CREATE DEFINER=`root`@`%` trigger items_update after update on items\nfor each row\ninsert into changelog (object,objectid,operation,clock)\nvalues (3,old.itemid,2,unix_timestamp())' 'CREATE DEFINER=`root`@`%` trigger items_delete before delete on items\nfor each row\ninsert into changelog (object,objectid,operation,clock)\nvalues (3,old.itemid,3,unix_timestamp())'
sql_modes=1411383296 1411383296 1411383296 1411383296 1411383296
definers='root@%' 'root@%' 'root@%' 'root@%' 'root@%'
client_cs_names='utf8mb4' 'utf8mb4' 'utf8mb4' 'utf8mb4' 'utf8mb4'
connection_cl_names='utf8mb4_bin' 'utf8mb4_bin' 'utf8mb4_bin' 'utf8mb4_bin' 'utf8mb4_bin'
db_cl_names='utf8mb4_bin' 'utf8mb4_bin' 'utf8mb4_bin' 'utf8mb4_bin' 'utf8mb4_bin'
created=1743684389524062 1743684389494242 1743684389535957 1743684389504210 1743684389514408
