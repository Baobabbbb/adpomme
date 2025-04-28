msiexec /i zabbix_agent2-latest-windows-amd64.msi /qn^
SERVER="192.168.100.128"^
SERVERACTIVE="192.168.100.128"^
HOSTNAME="Windows-%COMPUTERNAME%"^
ENABLEPATH=1^
LOGTYPE=file^
LOGFILE="C:\Program Files\Zabbix Agent\zabbix_agent.log"
