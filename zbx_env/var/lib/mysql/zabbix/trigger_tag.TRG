TYPE=TRIGGERS
triggers='CREATE DEFINER=`root`@`%` trigger trigger_tag_insert after insert on trigger_tag\nfor each row\ninsert into changelog (object,objectid,operation,clock)\nvalues (6,new.triggertagid,1,unix_timestamp())' 'CREATE DEFINER=`root`@`%` trigger trigger_tag_update after update on trigger_tag\nfor each row\ninsert into changelog (object,objectid,operation,clock)\nvalues (6,old.triggertagid,2,unix_timestamp())' 'CREATE DEFINER=`root`@`%` trigger trigger_tag_delete before delete on trigger_tag\nfor each row\ninsert into changelog (object,objectid,operation,clock)\nvalues (6,old.triggertagid,3,unix_timestamp())'
sql_modes=1411383296 1411383296 1411383296
definers='root@%' 'root@%' 'root@%'
client_cs_names='utf8mb4' 'utf8mb4' 'utf8mb4'
connection_cl_names='utf8mb4_bin' 'utf8mb4_bin' 'utf8mb4_bin'
db_cl_names='utf8mb4_bin' 'utf8mb4_bin' 'utf8mb4_bin'
created=1743684389602198 1743684389619720 1743684389664718
