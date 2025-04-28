TYPE=TRIGGERS
triggers='CREATE DEFINER=`root`@`%` trigger host_tag_insert after insert on host_tag\nfor each row\ninsert into changelog (object,objectid,operation,clock)\nvalues (2,new.hosttagid,1,unix_timestamp())' 'CREATE DEFINER=`root`@`%` trigger host_tag_update after update on host_tag\nfor each row\ninsert into changelog (object,objectid,operation,clock)\nvalues (2,old.hosttagid,2,unix_timestamp())' 'CREATE DEFINER=`root`@`%` trigger host_tag_delete before delete on host_tag\nfor each row\ninsert into changelog (object,objectid,operation,clock)\nvalues (2,old.hosttagid,3,unix_timestamp())'
sql_modes=1411383296 1411383296 1411383296
definers='root@%' 'root@%' 'root@%'
client_cs_names='utf8mb4' 'utf8mb4' 'utf8mb4'
connection_cl_names='utf8mb4_bin' 'utf8mb4_bin' 'utf8mb4_bin'
db_cl_names='utf8mb4_bin' 'utf8mb4_bin' 'utf8mb4_bin'
created=1743684389715303 1743684389724906 1743684389737796
