TYPE=TRIGGERS
triggers='CREATE DEFINER=`root`@`%` trigger triggers_insert after insert on triggers\nfor each row\ninsert into changelog (object,objectid,operation,clock)\nvalues (5,new.triggerid,1,unix_timestamp())' 'CREATE DEFINER=`root`@`%` trigger triggers_update after update on triggers\nfor each row\ninsert into changelog (object,objectid,operation,clock)\nvalues (5,old.triggerid,2,unix_timestamp())' 'CREATE DEFINER=`root`@`%` trigger triggers_delete before delete on triggers\nfor each row\ninsert into changelog (object,objectid,operation,clock)\nvalues (5,old.triggerid,3,unix_timestamp())'
sql_modes=1411383296 1411383296 1411383296
definers='root@%' 'root@%' 'root@%'
client_cs_names='utf8mb4' 'utf8mb4' 'utf8mb4'
connection_cl_names='utf8mb4_bin' 'utf8mb4_bin' 'utf8mb4_bin'
db_cl_names='utf8mb4_bin' 'utf8mb4_bin' 'utf8mb4_bin'
created=1743684389546420 1743684389554459 1743684389565774
