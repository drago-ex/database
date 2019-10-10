export ORACLE_HOME=/u01/app/oracle/product/11.2.0/xe;
export ORACLE_SID=XE;
$ORACLE_HOME/bin/sqlplus -S travis/travis <<SQL
whenever sqlerror exit 2;
create table "table" ("sample_id" integer not null primary key, "sample_string" varchar2(255));
SQL
