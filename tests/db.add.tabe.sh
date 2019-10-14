export ORACLE_HOME=/u01/app/oracle/product/11.2.0/xe;
export ORACLE_SID=XE;
$ORACLE_HOME/bin/sqlplus -S travis/travis <<SQL
whenever sqlerror exit 2;
CREATE SEQUENCE test_seq;
CREATE TABLE test (sample_id NUMBER DEFAULT TEST_SEQ.nextval NOT NULL PRIMARY KEY,sample_string VARCHAR2(255) NOT NULL);
SQL
