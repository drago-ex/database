export ORACLE_HOME=/u01/app/oracle/product/11.2.0/xe;
export ORACLE_SID=XE;
$ORACLE_HOME/bin/sqlplus -S travis/travis <<SQL
whenever sqlerror exit 2;
CREATE SEQUENCE test_seq START WITH 1 INCREMENT BY 1 NOCACHE;
CREATE TABLE test (sample_id INTEGER DEFAULT TEST_SEQ.NEXTVAL NOT NULL PRIMARY KEY, sample_string VARCHAR2(255) NOT NULL);
INSERT INTO test(sample_id, sample_string) VALUES (null, 'Hello');
SQL