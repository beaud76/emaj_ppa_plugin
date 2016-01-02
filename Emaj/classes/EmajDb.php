<?php

/**
 * A class that implements the database access for the E-Maj ppa plugin.
 * It currently covers E-Maj versions starting from 0.11.x
 */

class EmajDb {

	/**
	 * Constant
	 */
	private $emaj_schema = "emaj";

	/**
	 * Cache of static data
	 */
	private $emaj_version = '?';
	private $emaj_version_num = 0;
	private $enabled = null;
	private $accessible = null;
	private $emaj_adm = null;
	private $emaj_viewer = null;
	private $dblink_usable = null;
	private $asyncRlbkUsable = null;

	/**
	 * Constructor
	 */
	function EmajDb() {
	}

	/**
	 * Determines whether or not Emaj is installed in the current
	 * database.
	 * @post Will populate version and schema fields, etc.
	 * @return True if Emaj is installed, false otherwise.
	 */
	function isEnabled() {
		// Access cache
		if ($this->enabled !== null) return $this->enabled;

		global $data;

		$this->enabled = false;
		// Check for the emaj schema in the namespace relation.
		$sql = "SELECT nspname AS schema
				FROM pg_catalog.pg_namespace
				WHERE nspname='{$this->emaj_schema}'";
		$rs = $data->selectSet($sql);
		if ($rs->recordCount() == 1) {
			$schema = $rs->fields['schema'];
			$this->emaj_schema = $schema;
			$this->enabled = true;
		}
		return $this->enabled;
	}

	/**
	 * Determines whether or not the current user is granted to access emaj schema.
	 * @return True if enabled Emaj is accessible by the current user, false otherwise.
	 */
	function isAccessible() {
		// Access cache
		if ($this->accessible !== null) return $this->accessible;

		// otherwise compute
		$this->accessible = $this->enabled&&($this->isEmaj_Adm()||$this->isEmaj_Viewer());
		return $this->accessible;
	}

	/**
	 * Determines whether or not the current user is granted the 'emaj_adm' role.
	 * @return True if Emaj is accessible by the current user as E-maj administrator, false otherwise.
	 */
	function isEmaj_Adm() {
		// Access cache
		if ($this->emaj_adm !== null) return $this->emaj_adm;

		global $data, $misc;

		$this->emaj_adm = false;
		$server_info = $misc->getServerInfo();
		// if the current role is superuser, he is considered as E-maj administration
		if ($data->isSuperUser($server_info['username'])){
			$this->emaj_adm = true;
		}else{
		// otherwise, is the current role member of emaj_adm role ?
			$sql = "SELECT CASE WHEN pg_catalog.pg_has_role('emaj_adm','USAGE') THEN 1 ELSE 0 END AS is_emaj_adm";
			$this->emaj_adm = $data->selectField($sql,'is_emaj_adm');
		}
		return $this->emaj_adm;
	}

	/**
	 * Determines whether or not the current user is granted the 'emaj_viewer' role.
	 * @return True if Emaj is accessible by the current user as E-maj viewer, false otherwise.
	 * Note that an 'emaj_adm' role is also considered as 'emaj_viewer'
	 */
	function isEmaj_Viewer() {
		// Access cache
		if ($this->emaj_viewer !== null) return $this->emaj_viewer;

		global $data, $misc;

		$this->emaj_viewer = false;
		if ($this->emaj_adm){
		// emaj_adm role is also considered as E-maj viewer
			$this->emaj_viewer = true;
		}else{
		// otherwise, is the current role member of emaj_viewer role ?
			$sql = "SELECT CASE WHEN pg_catalog.pg_has_role('emaj_viewer','USAGE') THEN 1 ELSE 0 END AS is_emaj_viewer";
			$this->emaj_viewer = $data->selectField($sql,'is_emaj_viewer');
		}
		return $this->emaj_viewer;
	}

	/**
	 * Determines whether or not the a dblink connection can be used for rollbacks.
	 * It opens a test connection, using the _dblink_open_cnx() function, get the return code and finaly closes it.
	 */
	function isDblinkUsable() {
		// Access cache
		if ($this->dblink_usable !== null) return $this->dblink_usable;

		global $data;

		// if the _dblink_open_cnx() function is available for the user, 
		//   open a dblink connection and analyse the result
		$sql = "SELECT CASE 
					WHEN pg_catalog.has_function_privilege('\"{$this->emaj_schema}\"._dblink_open_cnx(text)', 'EXECUTE')
						AND \"{$this->emaj_schema}\"._dblink_open_cnx('test') >= 0 THEN 1 
					ELSE 0 END as cnx_ok";
		$this->dblink_usable = $data->selectField($sql,'cnx_ok');

		// close the test connection if open
		if ($this->dblink_usable) {
			$sql = "SELECT \"{$this->emaj_schema}\"._dblink_close_cnx('test')";
			$data->execute($sql);
		}

		return $this->dblink_usable;
	}

	/**
	 * Determines whether or not the asynchronous rollback can be used by the plugin.
	 * It checks the psql_path and temp_dir parameters from the plugin configuration file
	 * If they are set, one tries to use them.
	 */
	function isAsyncRlbkUsable($conf) {
		// Access cache
		if ($this->asyncRlbkUsable !== null) return $this->asyncRlbkUsable;

		global $misc;

		$this->asyncRlbkUsable = 0;

		// check if the parameters are set
		if (isset($conf['psql_path']) && isset($conf['temp_dir'])) {

		// check the psql exe path supplied in the config file, by executing a simple "psql --version" command
			$psqlExe = $misc->escapeShellCmd($conf['psql_path']);
			$version = array();
			preg_match("/(\d+(?:\.\d+)?)(?:\.\d+)?.*$/", exec($psqlExe . " --version"), $version);
			if (!empty($version)) {

		// ok, check a file can be written into the temp directory supplied in the config file 
				$sep = (substr(php_uname(), 0, 3) == "Win") ? '\\' : '/';
				$testFileName = $conf['temp_dir'] . $sep . 'rlbk_report_test';
				$f = fopen($testFileName,'w');
				if ($f) {
					fclose($f);
					unlink($testFileName);

		// it's OK
					$this->asyncRlbkUsable = 1;
				}
			}
		}

		return $this->asyncRlbkUsable;
	}

	/**
	 * Gets emaj version from either from cache or from a getVersion() call
	 */
	function getEmajVersion() {
		// Access cache
		if ($this->emaj_version !== '?') return $this->emaj_version;
		// otherwise read from the emaj_param table
		$this->getVersion();
		return $this->emaj_version;
	}

	/**
	 * Gets emaj version in numeric format from either from cache or from a getVersion() call
	 */
	function getNumEmajVersion() {
		// Access cache
		if ($this->emaj_version_num !== 0) return $this->emaj_version_num;
		// otherwise read from the emaj_param table
		$this->getVersion();
		return $this->emaj_version_num;
	}

	/**
	 * Gets emaj version from the emaj_param table or the emaj_visible_param if it exists
	 */
	function getVersion() {
		global $data;

		// look at the postgres catalog to see if the emaj_visible_param view exists or not. If not (i.e. old emaj version), use the emaj_param table instead.
		$sql = "SELECT CASE WHEN EXISTS 
					(SELECT relname FROM pg_catalog.pg_class, pg_catalog.pg_namespace
						WHERE relnamespace = pg_namespace.oid AND relname = 'emaj_visible_param' AND nspname = '{$this->emaj_schema}')
				THEN 'emaj_visible_param' ELSE 'emaj_param' END AS param_table";
		$rs = $data->selectSet($sql);
		if ($rs->recordCount() == 1){
			$param_table = $rs->fields['param_table'];

			// search the 'emaj_version' parameter into the proper view or table
			$sql = "SELECT param_value_text AS version
					FROM \"{$this->emaj_schema}\".{$param_table}
					WHERE param_key = 'emaj_version'";
			$rs = $data->selectSet($sql);
			if ($rs->recordCount() == 1){
				$this->emaj_version = $rs->fields['version'];
				if (substr_count($this->emaj_version, '.')==2){
					list($v1,$v2,$v3) = explode(".",$this->emaj_version);
					$this->emaj_version_num = 10000 * $v1 + 100 * $v2 + $v3;
				}
				if (substr_count($this->emaj_version, '.')==1){
					list($v1,$v2) = explode(".",$this->emaj_version);
					$this->emaj_version_num = 10000 * $v1 + 100 * $v2;
				}
			}else{
				$this->emaj_version = '?';
				$this->emaj_version_num = 0;
			}
		}else{
			$this->emaj_version = '?';
			$this->emaj_version_num = 0;
		}
		return;
	}

	/**
	 * Gets tspemaj current size
	 */
	function getEmajSize() {
		global $data;

		if ($this->emaj_adm){
			if ($this->getNumEmajVersion() >= 10000){	// version >= 1.0.0
// The E-Maj size = size of all relations in emaj primary and secondaries schemas + size of linked toast tables
				$sql = "SELECT coalesce(pg_size_pretty(t.emajtotalsize) || 
								to_char(t.emajtotalsize * 100 / pg_database_size(current_database())::float,' = FM990D0%'), '0 B = 0%') as emajsize 
						FROM 
						(SELECT ((t1.totalpages + t2.totalpages) * setting::integer)::bigint as emajtotalsize
							FROM pg_catalog.pg_settings, 
								(
								SELECT sum(relpages) as totalpages
								  FROM pg_catalog.pg_class, pg_catalog.pg_namespace 
								  WHERE relnamespace = pg_namespace.oid 
									AND nspname IN 
										(SELECT DISTINCT rel_log_schema FROM emaj.emaj_relation)
								) as t1,
								(
								SELECT sum(c2.relpages) as totalpages
								  FROM pg_catalog.pg_class c1,pg_catalog. pg_namespace, emaj.emaj_relation,
									   pg_catalog.pg_class c2 
								  WHERE c1.relnamespace = pg_namespace.oid 
									AND c2.oid = c1.reltoastrelid
									AND nspname = rel_log_schema AND c1.relname = rel_schema || '_' || rel_tblseq || '_log' 
									AND rel_kind = 'r'
								) as t2
							WHERE pg_settings.name = 'block_size'
						) as t";
			}else{
				$sql = "SELECT pg_size_pretty(
							(SELECT sum(pg_relation_size(pg_class.oid)) FROM pg_catalog.pg_class, pg_catalog.pg_namespace 
								WHERE relnamespace=pg_namespace.oid AND nspname = '{$this->emaj_schema}')::bigint
							) || to_char(
							(SELECT sum(pg_relation_size(pg_class.oid)) FROM pg_catalog.pg_class, pg_catalog.pg_namespace 
								WHERE relnamespace=pg_namespace.oid AND nspname = '{$this->emaj_schema}')
							*100 / pg_database_size(current_database())::float,' = FM990D0%') as emajsize";
			}
			return $data->selectField($sql,'emajsize');
		}else{
			return '?';
		}
	}

	/**
	 * Checks E-Maj consistency
	 */
	function checkEmaj() {
		global $data;

		$sql = "SELECT * FROM emaj.emaj_verify_all()";
		return $data->selectSet($sql);
	}


	// GROUPS

	/**
	 * Gets all groups referenced in emaj_group table for this database
	 */
	function getGroups() {
		global $data;

		$sql = "SELECT group_name, group_comment FROM \"{$this->emaj_schema}\".emaj_group ORDER BY group_name";

		return $data->selectSet($sql);
	}

	/**
	 * Gets all idle groups referenced in emaj_group table for this database
	 */
	function getIdleGroups() {
		global $data;

		$sql = "SELECT group_name, group_nb_table, group_nb_sequence,
				 CASE WHEN group_is_rollbackable THEN 'ROLLBACKABLE' ELSE 'AUDIT_ONLY' END 
					as group_type, 
				 CASE WHEN length(group_comment) > 100 THEN substr(group_comment,1,97) || '...' ELSE group_comment END 
					as abbr_comment, 
				 to_char(group_creation_datetime,'DD/MM/YYYY HH24:MI:SS') as creation_datetime,
				  (SELECT count(*) FROM emaj.emaj_mark WHERE mark_group = emaj_group.group_name) as nb_mark
				FROM \"{$this->emaj_schema}\".emaj_group
				WHERE ";
		if ($this->getNumEmajVersion() >= 10100){	// version >= 1.1.0
			$sql .=	"NOT group_is_logging ";
		}else{
			$sql .=	"group_state = 'IDLE' ";
		}
		$sql .=	"ORDER BY group_name";

		return $data->selectSet($sql);
	}

	/**
	 * Gets all Logging groups referenced in emaj_group table for this database
	 */
	function getLoggingGroups() {
		global $data;

		$sql = "SELECT group_name, group_nb_table, group_nb_sequence,
				 CASE WHEN group_is_rollbackable THEN 'ROLLBACKABLE' ELSE 'AUDIT_ONLY' END 
					as group_type, 
				 CASE WHEN length(group_comment) > 100 THEN substr(group_comment,1,97) || '...' ELSE group_comment END 
					as abbr_comment, 
				 to_char(group_creation_datetime,'DD/MM/YYYY HH24:MI:SS') as creation_datetime,
				 (SELECT count(*) FROM emaj.emaj_mark WHERE mark_group = emaj_group.group_name) as nb_mark
				FROM \"{$this->emaj_schema}\".emaj_group
				WHERE ";
		if ($this->getNumEmajVersion() >= 10100){	// version >= 1.1.0
			$sql .=	"group_is_logging ";
		}else{
			$sql .=	"group_state = 'LOGGING' ";
		}
		$sql .=	"ORDER BY group_name";

		return $data->selectSet($sql);
	}

	/**
	 * Gets all groups referenced in emaj_group_def but not in emaj_group table
	 */
	function getNewGroups() {
		global $data;

		$sql = "SELECT DISTINCT grpdef_group AS group_name FROM \"{$this->emaj_schema}\".emaj_group_def
				EXCEPT
				SELECT group_name FROM \"{$this->emaj_schema}\".emaj_group
				ORDER BY 1";
		return $data->selectSet($sql);
	}

	/**
	 * Gets properties of one emaj_group 
	 */
	function getGroup($group) {
		global $data;

		$data->clean($group);

		$sql = "SELECT group_name, group_nb_table, group_nb_sequence, group_creation_datetime";
		if ($this->getNumEmajVersion() >= 10100){	// version >= 1.1.0
			$sql .=	", CASE WHEN group_is_logging THEN 'LOGGING' ELSE 'IDLE' END as group_state";
		}else{
			$sql .=	", group_state";
		}
		$sql .=	", CASE WHEN group_is_rollbackable THEN 'ROLLBACKABLE' ELSE 'AUDIT_ONLY' END as group_type
				, group_comment 
				, pg_size_pretty((SELECT sum(pg_total_relation_size('";
		if ($this->getNumEmajVersion() >= 10000){	// version >= 1.0.0
			$sql .=	"\"' || rel_log_schema || '\"";
		}else{
			$sql .=	"emaj";
		}
		$sql .=
				".\"' || rel_schema || '_' || rel_tblseq || '_log\"')) 
					FROM \"{$this->emaj_schema}\".emaj_relation WHERE rel_group = group_name AND rel_kind = 'r')::bigint) as log_size,
				(SELECT count(*) FROM emaj.emaj_mark WHERE mark_group = emaj_group.group_name) as nb_mark
				FROM \"{$this->emaj_schema}\".emaj_group
				WHERE group_name = '{$group}'";

		return $data->selectSet($sql);
	}

	/**
	 * Gets isRollbackable properties of one emaj_group (1 if rollbackable, 0 if audit_only)
	 */
	function isGroupRollbackable($group) {
		global $data;

		$data->clean($group);

		$sql = "SELECT CASE WHEN group_is_rollbackable THEN 1 ELSE 0 END AS is_rollbackable
				FROM \"{$this->emaj_schema}\".emaj_group
				WHERE group_name = '{$group}'";

		return $data->selectField($sql,'is_rollbackable');
	}

	/**
	 * Gets all marks related to a group
	 */
	function getMarks($group) {
		global $data;

		$data->clean($group);

		$sql = "SELECT mark_group, mark_name, mark_datetime, mark_comment, ";
		if ($this->getNumEmajVersion() >= 10100){	// version >= 1.1.0
			$sql .= "CASE WHEN mark_is_deleted THEN 'DELETED' ELSE 'ACTIVE' END as mark_state, ";
		}else{
			$sql .= "mark_state, ";
		}
		$sql .=							// mark_cumlogrows is computed later, at results display
				"coalesce(mark_log_rows_before_next,
					(SELECT SUM(stat_rows) 
						FROM \"{$this->emaj_schema}\".emaj_log_stat_group(emaj_mark.mark_group,emaj_mark.mark_name,NULL)))
				 AS mark_logrows, 0 AS mark_cumlogrows,
				coalesce((SELECT count(*) FROM \"{$this->emaj_schema}\".emaj_mark 
				WHERE mark_group = '{$group}' AND ";
		if ($this->getNumEmajVersion() >= 10100){	// version >= 1.1.0
			$sql .= "NOT mark_is_deleted";
		}else{
			$sql .= "mark_state = 'ACTIVE'";
		}
		$sql .= "),0) AS nb_active_marks_in_group
				FROM \"{$this->emaj_schema}\".emaj_mark
				WHERE mark_group = '{$group}' 
				ORDER BY mark_id DESC";

		return $data->selectSet($sql);
	}

	/**
	 * Gets the content of one emaj_group 
	 */
	function getContentGroup($group) {
		global $data;

		$data->clean($group);

		if ($this->getNumEmajVersion() >= 10200){	// version >= 1.2.0
			$sql = "SELECT rel_schema, rel_tblseq, rel_kind, rel_priority,
						rel_log_schema, rel_log_dat_tsp, rel_log_idx_tsp,
						substring(rel_log_function FROM '(.*)\_log\_fnct') AS emaj_names_prefix,
						CASE WHEN rel_kind = 'r' THEN 
							pg_total_relation_size(quote_ident(rel_log_schema) || '.' || quote_ident(rel_log_table))
						END AS byte_log_size,
						CASE WHEN rel_kind = 'r' THEN 
							pg_size_pretty(pg_total_relation_size(quote_ident(rel_log_schema) || '.' || quote_ident(rel_log_table)))
						END AS pretty_log_size 
					FROM \"{$this->emaj_schema}\".emaj_relation
					WHERE rel_group = '{$group}'
					ORDER BY rel_schema, rel_tblseq";
		} else {
			$sql = "SELECT rel_schema, rel_tblseq, rel_kind, rel_priority";
			if ($this->getNumEmajVersion() >= 10000){	// version >= 1.0.0
				$sql .=
					", rel_log_schema, rel_log_dat_tsp, rel_log_idx_tsp";
			}
			$sql .= ", CASE WHEN rel_kind = 'r' THEN 
						pg_total_relation_size(";
			if ($this->getNumEmajVersion() >= 10000){	// version >= 1.0.0
				$sql .= "quote_ident(rel_log_schema)";
			}else{
				$sql .= "'{$this->emaj_schema}'";
			}
			$sql .= " || '.\"' || rel_schema || '_' || rel_tblseq || '_log\"')
						END as byte_log_size
					, CASE WHEN rel_kind = 'r' THEN 
						pg_size_pretty(pg_total_relation_size(";
			if ($this->getNumEmajVersion() >= 10000){	// version >= 1.0.0
				$sql .= "quote_ident(rel_log_schema)";
			}else{
				$sql .= "'{$this->emaj_schema}'";
			}
			$sql .= " || '.\"' || rel_schema || '_' || rel_tblseq || '_log\"'))
						END as pretty_log_size 
					FROM \"{$this->emaj_schema}\".emaj_relation
					WHERE rel_group = '{$group}'
					ORDER BY rel_schema, rel_tblseq";
		}

		return $data->selectSet($sql);
	}

	/**
	 * Return all non system schemas but emaj from the current database
	 * plus all nonexistent schemas but listed in emaj_group_def
	 */
	function getSchemas() {
		global $data;

		$sql = "SELECT 1, pn.nspname, pu.rolname AS nspowner,
					   pg_catalog.obj_description(pn.oid, 'pg_namespace') AS nspcomment
				FROM pg_catalog.pg_namespace pn
					 LEFT JOIN pg_catalog.pg_roles pu ON (pn.nspowner = pu.oid)
				WHERE nspname NOT LIKE 'pg@_%' ESCAPE '@' AND 
					  nspname != 'information_schema' AND nspname != '{$this->emaj_schema}' ";
		if ($this->getNumEmajVersion() >= 10000){			// version >= 1.0.0
			$sql .=
				"AND nspname NOT IN (SELECT DISTINCT rel_log_schema FROM emaj.emaj_relation WHERE rel_log_schema IS NOT NULL) ";
		}
		$sql .= "UNION
				SELECT DISTINCT 2, grpdef_schema AS nspname, '!' AS nspowner, NULL AS nspcomment
				FROM emaj.emaj_group_def
				WHERE grpdef_schema NOT IN ( SELECT nspname FROM pg_catalog.pg_namespace )
				ORDER BY 1, nspname";

		return $data->selectSet($sql);
	}

	/**
	 * Return all tables and sequences of a schema, 
	 * plus all non existent tables but listed in emaj_group_def with this schema
	 */
	function getTablesSequences($schema) {
		global $data;

		$data->clean($schema);

		$sql = "SELECT 1, nspname, c.relname, c.relkind, pg_catalog.pg_get_userbyid(c.relowner) AS relowner,
					pg_catalog.obj_description(c.oid, 'pg_class') AS relcomment, spcname AS tablespace,
					grpdef_group, grpdef_priority ";
		if ($this->getNumEmajVersion() >= 10000){			// version >= 1.0.0
			$sql .=
				", grpdef_log_schema_suffix ";
		} else {
			$sql .=
				", NULL AS grpdef_log_schema_suffix ";
		}
		if ($this->getNumEmajVersion() >= 10200){			// version >= 1.2.0
			$sql .=
				", grpdef_emaj_names_prefix ";
		} else {
			$sql .=
				", NULL AS grpdef_emaj_names_prefix ";
		}
		if ($this->getNumEmajVersion() >= 10000){			// version >= 1.0.0
			$sql .=
				", grpdef_log_dat_tsp, grpdef_log_idx_tsp ";
		} else {
			$sql .=
				", NULL AS grpdef_log_dat_tsp, NULL AS grpdef_log_idx_tsp ";
		}
		$sql .=
			   "FROM pg_catalog.pg_class c
					LEFT JOIN pg_catalog.pg_namespace n ON n.oid = c.relnamespace
					LEFT JOIN emaj.emaj_group_def ON grpdef_schema = nspname AND grpdef_tblseq = c.relname
					LEFT JOIN pg_catalog.pg_tablespace pt ON pt.oid = c.reltablespace
				WHERE c.relkind IN ('r','S') AND nspname='{$schema}'
				UNION
				SELECT 2, grpdef_schema AS nspname, grpdef_tblseq AS relname, '!' AS relkind, NULL,	NULL, NULL, 
					grpdef_group , grpdef_priority ";
		if ($this->getNumEmajVersion() >= 10000){			// version >= 1.0.0
			$sql .=
				", grpdef_log_schema_suffix ";
		} else {
			$sql .=
				", NULL AS grpdef_log_schema_suffix ";
		}
		if ($this->getNumEmajVersion() >= 10200){			// version >= 1.2.0
			$sql .=
				", grpdef_emaj_names_prefix ";
		} else {
			$sql .=
				", NULL AS grpdef_emaj_names_prefix ";
		}
		if ($this->getNumEmajVersion() >= 10000){			// version >= 1.0.0
			$sql .=
				", grpdef_log_dat_tsp, grpdef_log_idx_tsp ";
		} else {
			$sql .=
				", NULL AS grpdef_log_dat_tsp, NULL AS grpdef_log_idx_tsp ";
		}
		$sql .=
			   "FROM emaj.emaj_group_def
				WHERE grpdef_schema = '{$schema}' AND grpdef_tblseq NOT IN 
					( SELECT relname FROM pg_catalog.pg_class, pg_catalog.pg_namespace
						WHERE relnamespace = pg_namespace.oid AND nspname = '{$schema}' AND relkind IN ('r','S') )
				ORDER BY 1, relname";

		return $data->selectSet($sql);
	}

	/**
	 * Gets group names already known in the emaj_group_def table
	 */
	function getKnownGroups() {
		global $data;

		$data->fieldClean($schema);
		$sql = "SELECT DISTINCT grpdef_group AS group_name 
				FROM \"{$this->emaj_schema}\".emaj_group_def
				ORDER BY 1";
		return $data->selectSet($sql);
	}

	/**
	 * Gets log schema suffix already known in the emaj_group_def table
	 */
	function getKnownSuffix() {
		global $data;

		$data->fieldClean($schema);
		$sql = "SELECT DISTINCT grpdef_log_schema_suffix AS known_suffix 
				FROM \"{$this->emaj_schema}\".emaj_group_def
				WHERE grpdef_log_schema_suffix <> '' AND grpdef_log_schema_suffix IS NOT NULL
				ORDER BY 1";
		return $data->selectSet($sql);
	}

	/**
	 * Gets existing tablespaces
	 */
	function getKnownTsp() {
		global $data;

		$sql = "SELECT spcname  
				FROM pg_catalog.pg_tablespace
				WHERE spcname NOT LIKE 'pg\_%'
				ORDER BY 1";
		return $data->selectSet($sql);
	}

	/**
	 * Insert a table or sequence into the emaj_group_def table
	 */
	function assignTblSeq($schema,$tblseq,$group,$priority,$logSchemaSuffix,$emajNamesPrefix,$logDatTsp,$logIdxTsp) {
		global $data;

		$data->clean($schema);
		$data->clean($tblseq);
		$data->clean($group);
		$data->clean($priority);
		$data->clean($logSchemaSuffix);
		$data->clean($emajNamesPrefix);
		$data->clean($logDatTsp);
		$data->clean($logIdxTsp);

		// get the relkind of the tblseq to process
		$sql = "SELECT relkind 
				FROM pg_catalog.pg_class, pg_catalog.pg_namespace 
				WHERE pg_namespace.oid = relnamespace AND relname = '{$tblseq}' AND nspname = '{$schema}'";
		$rs = $data->selectSet($sql);
		if ($rs->recordCount() == 1){
			$relkind = $rs->fields['relkind'];
		}else{
			$relkind = "?";
		}

		// Insert the new row into the emaj_group_def table
		$sql = "INSERT INTO emaj.emaj_group_def (grpdef_schema, grpdef_tblseq, grpdef_group, grpdef_priority ";
		if ($this->getNumEmajVersion() >= 10000){	// version >= 1.0.0
			$sql .=
				", grpdef_log_schema_suffix ";
		}
		if ($this->getNumEmajVersion() >= 10200){	// version >= 1.2.0
			$sql .=
				", grpdef_emaj_names_prefix ";
		}
		if ($this->getNumEmajVersion() >= 10000){	// version >= 1.0.0
			$sql .=
				", grpdef_log_dat_tsp, grpdef_log_idx_tsp ";
		}
		$sql .=
				") VALUES ('{$schema}', '{$tblseq}', '{$group}' ";
		if ($priority == '')
			$sql .= ", NULL";
		else
			$sql .= ", {$priority}";
		if ($this->getNumEmajVersion() >= 10000){	// version >= 1.0.0
			if ($logSchemaSuffix == '' || $relkind == 'S')
				$sql .= ", NULL";
			else
				$sql .= ", '{$logSchemaSuffix}'";
		}
		if ($this->getNumEmajVersion() >= 10200){	// version >= 1.2.0
			if ($emajNamesPrefix == '' || $relkind == 'S')
				$sql .= ", NULL";
			else
				$sql .= ", '{$emajNamesPrefix}'";
		}
		if ($this->getNumEmajVersion() >= 10000){	// version >= 1.0.0
			if ($logDatTsp == '' || $relkind == 'S')
				$sql .= ", NULL";
			else
				$sql .= ", '{$logDatTsp}'";
			if ($logIdxTsp == '' || $relkind == 'S')
				$sql .= ", NULL";
			else
				$sql .= ", '{$logIdxTsp}'";
		}
		$sql .= ")";

		return $data->execute($sql);
	}

	/**
	 * Update a table or sequence into the emaj_group_def table
	 */
	function updateTblSeq($schema,$tblseq,$group,$priority,$logSchemaSuffix,$emajNamesPrefix,$logDatTsp,$logIdxTsp) {
		global $data;

		$data->clean($schema);
		$data->clean($tblseq);
		$data->clean($group);
		$data->clean($priority);
		$data->clean($logSchemaSuffix);
		$data->clean($emajNamesPrefix);
		$data->clean($logDatTsp);
		$data->clean($logIdxTsp);

		// get the relkind of the tblseq to process
		$sql = "SELECT relkind 
				FROM pg_catalog.pg_class, pg_catalog.pg_namespace 
				WHERE pg_namespace.oid = relnamespace AND relname = '{$tblseq}' AND nspname = '{$schema}'";
		$rs = $data->selectSet($sql);
		if ($rs->recordCount() == 1){
			$relkind = $rs->fields['relkind'];
		}else{
			$relkind = "?";
		}

		// Update the row in the emaj_group_def table
		$sql = "UPDATE emaj.emaj_group_def SET 
					grpdef_group = '{$group}'";
		if ($priority == '')
			$sql .= ", grpdef_priority = NULL";
		else
			$sql .= ", grpdef_priority = {$priority}";
		if ($this->getNumEmajVersion() >= 10000){	// version >= 1.0.0
			if ($logSchemaSuffix == '' || $relkind == 'S')
				$sql .= ", grpdef_log_schema_suffix = NULL";
			else
				$sql .= ", grpdef_log_schema_suffix = '{$logSchemaSuffix}'";
		}
		if ($this->getNumEmajVersion() >= 10200){	// version >= 1.2.0
			if ($emajNamesPrefix == '' || $relkind == 'S')
				$sql .= ", grpdef_emaj_names_prefix = NULL";
			else
				$sql .= ", grpdef_emaj_names_prefix = '{$emajNamesPrefix}'";
		}
		if ($this->getNumEmajVersion() >= 10000){	// version >= 1.0.0
			if ($logDatTsp == '' || $relkind == 'S')
				$sql .= ", grpdef_log_dat_tsp = NULL";
			else
				$sql .= ", grpdef_log_dat_tsp = '{$logDatTsp}'";
			if ($logIdxTsp == '' || $relkind == 'S')
				$sql .= ", grpdef_log_idx_tsp = NULL";
			else
				$sql .= ", grpdef_log_idx_tsp = '{$logIdxTsp}'";
		}
		$sql .=
               " WHERE grpdef_schema = '{$schema}' AND grpdef_tblseq = '{$tblseq}'";

		return $data->execute($sql);
	}

	/**
	 * Delete a table or sequence from emaj_group_def table
	 */
	function removeTblSeq($schema,$tblseq,$group) {
		global $data;

		$data->clean($schema);
		$data->clean($tblseq);
		$data->clean($group);

		// Begin transaction.  We do this so that we can ensure only one row is deleted
		$status = $data->beginTransaction();
		if ($status != 0) {
			$data->rollbackTransaction();
			return -1;
		}

		$sql = "DELETE FROM emaj.emaj_group_def 
				WHERE grpdef_schema = '{$schema}' AND grpdef_tblseq = '{$tblseq}' AND grpdef_group = '{$group}'";
		// Delete row
		$status = $data->execute($sql);

		if ($status != 0 || $data->conn->Affected_Rows() != 1) {
			$data->rollbackTransaction();
			return -2;
		}
		// End transaction
		return $data->endTransaction();
	}

	/**
	 * Creates a group
	 */
	function createGroup($group,$isRollbackable) {
		global $data;

		if ($isRollbackable){
			$sql = "SELECT \"{$this->emaj_schema}\".emaj_create_group('{$group}',true) AS nbtblseq";
		}else{
			$sql = "SELECT \"{$this->emaj_schema}\".emaj_create_group('{$group}',false) AS nbtblseq";
		}			

		return $data->selectField($sql,'nbtblseq');
	}

	/**
	 * Drops a group
	 */
	function dropGroup($group) {
		global $data;

		$sql = "SELECT \"{$this->emaj_schema}\".emaj_drop_group('{$group}') AS nbtblseq";

		return $data->selectField($sql,'nbtblseq');
	}

	/**
	 * Alters a group
	 */
	function alterGroup($group) {
		global $data;

		$sql = "SELECT \"{$this->emaj_schema}\".emaj_alter_group('{$group}') AS nbtblseq";

		return $data->selectField($sql,'nbtblseq');
	}

	/**
	 * Sets a comment for a group
	 */
	function setCommentGroup($group,$comment) {
		global $data;

		$data->clean($group);
		$data->clean($comment);

		$sql = "SELECT \"{$this->emaj_schema}\".emaj_comment_group('{$group}','{$comment}')";

		return $data->execute($sql);
	}

	/**
	 * Determines whether or not a mark name is valid as a new mark to set for a group
	 * Returns 1 if the mark name is not already known, 0 otherwise.
	 */
	function isNewMarkValidGroup($group,$mark) {

		global $data;

		$data->clean($group);
		$data->clean($mark);

		$sql = "SELECT CASE WHEN 
				(SELECT COUNT(*) FROM \"{$this->emaj_schema}\".emaj_mark WHERE mark_group = '{$group}' AND mark_name = '{$mark}')
				= 0 THEN 1 ELSE 0 END AS result";

		return $data->selectField($sql,'result');
	}

	/**
	 * Determines whether or not a mark name is valid as a new mark to set for a groups array
	 * Returns 1 if the mark name is not already known, 0 otherwise.
	 */
	function isNewMarkValidGroups($groups,$mark) {

		global $data;

		$data->clean($groups);
		$groups="ARRAY['".str_replace(', ',"','",$groups)."']";
		$data->clean($mark);

		$sql = "SELECT CASE WHEN 
				(SELECT COUNT(*) FROM \"{$this->emaj_schema}\".emaj_mark 
				   WHERE mark_group = ANY ({$groups}) AND mark_name = '{$mark}')
				= 0 THEN 1 ELSE 0 END AS result";

		return $data->selectField($sql,'result');
	}

	/**
	 * Computes the number of active mark in a group.
	 */
	function nbActiveMarkGroup($group) {

		global $data;

		$data->clean($group);

		$sql = "SELECT COUNT(*) as result FROM \"{$this->emaj_schema}\".emaj_mark WHERE mark_group = '{$group}'";

		return $data->selectField($sql,'result');
	}

	/**
	 * Determines whether or not a mark name is the first mark of its group
	 * Returns 1 if the mark is the oldest of its group, 0 otherwise.
	 */
	function isFirstMarkGroup($group,$mark) {

		global $data;

		$data->clean($group);
		$data->clean($mark);

		$sql = "SELECT CASE WHEN mark_datetime = 
						(SELECT MIN (mark_datetime) FROM \"{$this->emaj_schema}\".emaj_mark WHERE mark_group = '{$group}')
						THEN 1 ELSE 0 END AS result
				FROM \"{$this->emaj_schema}\".emaj_mark
				WHERE mark_group = '{$group}' AND mark_name = '{$mark}'";

		return $data->selectField($sql,'result');
	}

	/**
	 * Starts a group
	 */
	function startGroup($group,$mark,$resetLog) {
		global $data;

		$data->clean($group);
		$data->clean($mark);

		if ($resetLog){
			$sql = "SELECT \"{$this->emaj_schema}\".emaj_start_group('{$group}','{$mark}') AS nbtblseq";
		}else{
			$sql = "SELECT \"{$this->emaj_schema}\".emaj_start_group('{$group}','{$mark}',false) AS nbtblseq";
		}

		return $data->selectField($sql,'nbtblseq');
	}

	/**
	 * Starts several groups
	 */
	function startGroups($groups,$mark,$resetLog) {
		global $data;

		$data->clean($groups);
		$groups="ARRAY['".str_replace(', ',"','",$groups)."']";
		$data->clean($mark);

		if ($resetLog){
			$sql = "SELECT \"{$this->emaj_schema}\".emaj_start_groups({$groups},'{$mark}') AS nbtblseq";
		}else{
			$sql = "SELECT \"{$this->emaj_schema}\".emaj_start_groups({$groups},'{$mark}',false) AS nbtblseq";
		}

		return $data->selectField($sql,'nbtblseq');
	}

	/**
	 * Stops a group
	 */
	function stopGroup($group,$mark,$forceStop) {
		global $data;

		$data->clean($group);
		$data->clean($mark);
		
		if ($forceStop){
			$sql = "SELECT \"{$this->emaj_schema}\".emaj_force_stop_group('{$group}') AS nbtblseq";
		}else{
			if ($mark == ""){
				$sql = "SELECT \"{$this->emaj_schema}\".emaj_stop_group('{$group}') AS nbtblseq";
			}else{
				$sql = "SELECT \"{$this->emaj_schema}\".emaj_stop_group('{$group}','{$mark}') AS nbtblseq";
			}
		}

		return $data->selectField($sql,'nbtblseq');
	}

	/**
	 * Stops several groups at once
	 */
	function stopGroups($groups) {
		global $data;

		$data->clean($groups);
		$groups="ARRAY['".str_replace(', ',"','",$groups)."']";
		$data->clean($mark);
		
		if ($mark == ""){
			$sql = "SELECT \"{$this->emaj_schema}\".emaj_stop_groups({$groups}) AS nbtblseq";
		}else{
			$sql = "SELECT \"{$this->emaj_schema}\".emaj_stop_groups({$groups},'{$mark}') AS nbtblseq";
		}

		return $data->selectField($sql,'nbtblseq');
	}

	/**
	 * Resets a group
	 */
	function resetGroup($group) {
		global $data;

		$data->clean($group);

		$sql = "SELECT \"{$this->emaj_schema}\".emaj_reset_group('{$group}') AS nbtblseq";

		return $data->selectField($sql,'nbtblseq');
	}

	/**
	 * Sets a mark for a group
	 */
	function setMarkGroup($group,$mark) {
		global $data;

		$data->clean($group);
		$data->clean($mark);

		$sql = "SELECT \"{$this->emaj_schema}\".emaj_set_mark_group('{$group}','{$mark}') AS nbtblseq";

		return $data->execute($sql);
	}

	/**
	 * Sets a mark for several groups
	 */
	function setMarkGroups($groups,$mark) {
		global $data;

		$data->clean($groups);
		$groups="ARRAY['".str_replace(', ',"','",$groups)."']";
		$data->clean($mark);

		$sql = "SELECT \"{$this->emaj_schema}\".emaj_set_mark_groups({$groups},'{$mark}') AS nbtblseq";

		return $data->execute($sql);
	}

	/**
	 * Gets properties of one mark 
	 */
	function getMark($group,$mark) {
		global $data;

		$data->fieldClean($schema);
		$data->clean($group);
		$data->clean($mark);

		$sql = "SELECT mark_name, mark_group, mark_comment 
				FROM \"{$this->emaj_schema}\".emaj_mark
				WHERE mark_group = '{$group}' AND mark_name = '{$mark}'";
		return $data->selectSet($sql);
	}

	/**
	 * Sets a comment for a mark of a group
	 */
	function setCommentMarkGroup($group,$mark,$comment) {
		global $data;

		$data->clean($group);
		$data->clean($mark);
		$data->clean($comment);

		$sql = "SELECT \"{$this->emaj_schema}\".emaj_comment_mark_group('{$group}','{$mark}','{$comment}')";

		return $data->execute($sql);
	}

	/**
	 * Deletes a mark for a group
	 */
	function deleteMarkGroup($group,$mark) {
		global $data;

		$data->clean($group);
		$data->clean($mark);

		$sql = "SELECT \"{$this->emaj_schema}\".emaj_delete_mark_group('{$group}','{$mark}')";

		return $data->execute($sql);
	}

	/**
	 * Deletes all marks before a mark for a group
	 */
	function deleteBeforeMarkGroup($group,$mark) {
		global $data;

		$data->clean($group);
		$data->clean($mark);

		$sql = "SELECT \"{$this->emaj_schema}\".emaj_delete_before_mark_group('{$group}','{$mark}') as nbmark";

		return $data->selectField($sql,'nbmark');
	}

	/**
	 * Renames a mark for a group
	 */
	function renameMarkGroup($group,$mark,$newMark) {
		global $data;

		$data->clean($group);
		$data->clean($mark);
		$data->clean($newMark);

		$sql = "SELECT \"{$this->emaj_schema}\".emaj_rename_mark_group('{$group}','{$mark}','{$newMark}')";

		return $data->execute($sql);
	}

	/**
	 * Returns the list of marks usable to rollback a group.
	 */
	function getRollbackMarkGroup($group) {

		global $data;

		$data->clean($group);

		$sql = "SELECT mark_name, mark_datetime FROM \"{$this->emaj_schema}\".emaj_mark 
				WHERE ";
		if ($this->getNumEmajVersion() >= 10100){	// version >= 1.1.0
			$sql .= "NOT mark_is_deleted";
		}else{
			$sql .= "mark_state = 'ACTIVE'";
		}
		$sql .= " AND mark_group = '$group'
				ORDER BY mark_datetime DESC";

		return $data->selectSet($sql);
	}

	/**
	 * Determines whether or not a mark name is valid as a mark to rollback to for a group
	 * Returns 1 if the mark name is known and in ACTIVE state, 0 otherwise.
	 */
	function isRollbackMarkValidGroup($group,$mark) {

		global $data;

		$data->clean($group);
		$data->clean($mark);

		$sql = "SELECT CASE WHEN 
				(SELECT COUNT(*) FROM \"{$this->emaj_schema}\".emaj_mark WHERE mark_group = '{$group}' AND mark_name = '{$mark}' AND ";
		if ($this->getNumEmajVersion() >= 10100){	// version >= 1.1.0
			$sql .= "NOT mark_is_deleted";
		}else{
			$sql .= "mark_state = 'ACTIVE'";
		}
		$sql .= ") = 1 THEN 1 ELSE 0 END AS result";

		return $data->selectField($sql,'result');
	}

	/**
	 * Rollbacks a group to a mark
	 */
	function rollbackGroup($group,$mark,$isLogged) {
		global $data;

		$data->clean($group);
		$data->clean($mark);

		if ($isLogged){
			$sql = "SELECT\"{$this->emaj_schema}\".emaj_logged_rollback_group('{$group}','{$mark}') AS nbtblseq";
		}else{
			$sql = "SELECT \"{$this->emaj_schema}\".emaj_rollback_group('{$group}','{$mark}') AS nbtblseq";
		}

		return $data->selectField($sql,'nbtblseq');
	}

	/**
	 * rollbacks asynchronously a group to a mark, using a single session
	 */
	function asyncRollbackGroup($group,$mark,$isLogged,$psqlExe,$tempDir) {
		global $data, $misc;

		$data->clean($group);
		$data->clean($mark);
		$data->clean($psqlExe);
		$data->clean($tempDir);

		// Initialize the rollback operation and get its rollback id
		$isL = $isLogged ? 'true' : 'false';
		$sql = "SELECT \"{$this->emaj_schema}\"._rlbk_init(array['{$group}'], '{$mark}', {$isL}, 1, false) as rlbk_id";
		$rlbkId = $data->selectField($sql,'rlbk_id');

		// Build the psql report file name, the SQL command and submit the rollback execution asynchronously
		$psqlReport = "rlbk_{$rlbkId}_report";
		$sql = "SELECT \"{$this->emaj_schema}\"._rlbk_async({$rlbkId},false)";
		$this->execPsqlInBackground($psqlExe,$sql,$tempDir,$psqlReport);

		return $rlbkId;
	}

	/**
	 * Execute an external psql command in background
	 */
	function execPsqlInBackground($psqlExe,$stmt,$tempDir,$psqlReport) {
		global $misc;

		// Set environmental variables that psql needs to connect
		$server_info = $misc->getServerInfo();
		putenv('PGPASSWORD=' . $server_info['password']);
		putenv('PGUSER=' . $server_info['username']);
		$hostname = $server_info['host'];
		if ($hostname !== null && $hostname != '') {
			putenv('PGHOST=' . $hostname);
		}
		$port = $server_info['port'];
		if ($port !== null && $port != '') {
			putenv('PGPORT=' . $port);
		}

		// Build and submit the psql command
		if (substr(php_uname(), 0, 3) == "Win"){
			$psqlCmd = "{$psqlExe} -d {$_REQUEST['database']} -c \"{$stmt}\" -o \"{$tempDir}\\{$psqlReport}\" 2>&1";
			pclose(popen("start /b \"\" ". $psqlCmd, "r"));
		} else {
			$psqlCmd = "{$psqlExe} -d {$_REQUEST['database']} -c \"{$stmt}\" -o \"{$tempDir}/{$psqlReport}\" 2>&1";
			exec($psqlCmd . " > /dev/null &");  
		}
	}

	/**
	 * Returns the list of marks usable to rollback a groups array.
	 */
	function getRollbackMarkGroups($groups) {

		global $data;

		$data->clean($groups);
		$groups="ARRAY['".str_replace(', ',"','",$groups)."']";

// Attention, this statement needs postgres 8.4+, because of array_agg() function use
		$sql = "SELECT t.mark_name, t.mark_datetime 
				FROM (SELECT mark_name, mark_datetime, array_agg (mark_group) AS groups 
					FROM \"{$this->emaj_schema}\".emaj_mark,\"{$this->emaj_schema}\".emaj_group 
					WHERE mark_group = group_name AND ";
		if ($this->getNumEmajVersion() >= 10100){	// version >= 1.1.0
			$sql .= "NOT mark_is_deleted";
		}else{
			$sql .= "mark_state = 'ACTIVE'";
		}
		$sql .= " AND group_is_rollbackable GROUP BY 1,2) AS t 
				WHERE t.groups @> $groups
				ORDER BY mark_datetime DESC";

		return $data->selectSet($sql);
	}

	/**
	 * Determines whether or not a mark name is valid as a mark to rollback to for a groups array
	 * Returns 1 if the mark name is known and in ACTIVE state and represents the same timestamp for all groups, 0 otherwise.
	 */
	function isRollbackMarkValidGroups($groups,$mark) {

		global $data;

		$data->clean($groups);
		$groups="ARRAY['".str_replace(', ',"','",$groups)."']";
		$data->clean($mark);
		$nbGroups = substr_count($groups,',') + 1;

		$sql = "SELECT CASE WHEN 
				(SELECT COUNT(*) FROM \"{$this->emaj_schema}\".emaj_mark, \"{$this->emaj_schema}\".emaj_group 
					WHERE mark_group = group_name 
						AND mark_group = ANY ({$groups}) AND group_is_rollbackable AND mark_name = '{$mark}' 
						AND ";
		if ($this->getNumEmajVersion() >= 10100){	// version >= 1.1.0
			$sql .= "NOT mark_is_deleted";
		}else{
			$sql .= "mark_state = 'ACTIVE'";
		}
		$sql .= ") = {$nbGroups} THEN 1 ELSE 0 END AS result";

		return $data->selectField($sql,'result');
	}

	/**
	 * Rollbacks a groups array to a mark
	 */
	function rollbackGroups($groups,$mark,$isLogged) {
		global $data;

		$data->clean($groups);
		$groups="ARRAY['".str_replace(', ',"','",$groups)."']";
		$data->clean($mark);

		if ($isLogged){
			$sql = "SELECT \"{$this->emaj_schema}\".emaj_logged_rollback_groups({$groups},'{$mark}') AS nbtblseq";
		}else{
			$sql = "SELECT\"{$this->emaj_schema}\".emaj_rollback_groups({$groups},'{$mark}') AS nbtblseq";
		}

		return $data->selectField($sql,'nbtblseq');
	}

	/**
	 * Gets the global rollback statistics for a group and a mark (i.e. total number of log rows to rollback)
	 */
	function getGlobalRlbkStatGroup($group,$mark) {
		global $data;

		$data->clean($group);
		$data->clean($mark);

		$sql = "SELECT coalesce(sum(stat_rows),0) as sumrows, count(*) as nbtables 
					FROM \"{$this->emaj_schema}\".emaj_log_stat_group('{$group}','{$mark}',NULL)
					WHERE stat_rows > 0";

		return $data->selectSet($sql);
	}

	/**
	 * Estimates the rollback duration for a group and a mark
	 */
	function estimateRollbackGroup($group,$mark) {
		global $data;

		$data->clean($group);
		$data->clean($mark);

		$sql = "SELECT to_char(\"{$this->emaj_schema}\".";
		if ($this->getNumEmajVersion() >= 10100){	// version >= 1.1.0
			$sql .= "emaj_estimate_rollback_group('{$group}','{$mark}',false)";
		}else{
			$sql .= "emaj_estimate_rollback_duration('{$group}','{$mark}')";
		}
		$sql .= "+'1 second'::interval,'HH24:MI:SS') as duration";

		return $data->selectField($sql,'duration');
	}

	/**
	 * Gets the list of lastest completed rollback operations
	 */
	function getCompletedRlbk($nb,$retention) {
		global $data;

		$data->clean($nb);
		$data->clean($retention);

// first cleanup recently completed rollback operation status
		$sql = "SELECT emaj.emaj_cleanup_rollback_state()";
		$data->execute($sql);

// get the latest rollback operations
		$sql = "SELECT rlbk_id, array_to_string(rlbk_groups,',') as rlbk_groups_list, rlbk_status,
					rlbk_start_datetime, rlbk_end_datetime,
					to_char(rlbk_end_datetime - rlbk_start_datetime,'HH24:MI:SS') as rlbk_duration, 
					rlbk_mark, rlbk_mark_datetime, rlbk_is_logged, rlbk_nb_session, rlbk_eff_nb_table,
					rlbk_nb_sequence 
				FROM (SELECT * FROM emaj.emaj_rlbk 
				WHERE rlbk_status IN ('COMPLETED','COMMITTED','ABORTED')";
		if ($retention > 0)
			$sql .= " AND rlbk_end_datetime > current_timestamp - '{$retention} hours'::interval "; 
		$sql .= " ORDER BY rlbk_id DESC ";
		if ($nb > 0)
			$sql .= "LIMIT {$nb}";
		$sql .= ") AS t";

		return $data->selectSet($sql);
	}

	/**
	 * Gets the list of in progress rollback operations
	 */
	function getInProgressRlbk() {
		global $data;

		$sql = "SELECT rlbk_id, array_to_string(rlbk_groups,',') as rlbk_groups_list, rlbk_mark,
					rlbk_mark_datetime, rlbk_is_logged,	rlbk_nb_session, rlbk_nb_table, rlbk_nb_sequence,
					rlbk_eff_nb_table, rlbk_status, rlbk_start_datetime,
					to_char(rlbk_elapse,'HH24:MI:SS') as rlbk_current_elapse, rlbk_remaining,
					rlbk_completion_pct 
				FROM emaj.emaj_rollback_activity() 
				ORDER BY rlbk_id DESC";

		return $data->selectSet($sql);
	}

	/**
	 * Gets the global log statistics for a group between 2 marks
	 * It also delivers the sql queries to look at the corresponding log rows
	 * It creates a temp table to easily compute aggregates for the same conversation
	 */
	function getLogStatGroup($group,$firstMark,$lastMark) {
		global $data;

		$data->clean($group);
		$data->clean($firstMark);
		$data->clean($lastMark);

		if ($lastMark==''){
			$sql = "CREATE TEMP TABLE tmp_stat AS
					SELECT stat_group, stat_schema, stat_table, stat_rows, 
					'select * from ' || ";
			if ($this->getNumEmajVersion() >= 10000){	// version >= 1.0.0
				$sql .= "quote_ident(rel_log_schema)";
			}else{
				$sql .= "'{$this->emaj_schema}'";
			}
			$sql .= " || '.' || quote_ident(stat_schema || '_' || stat_table || '_log') || 
						' where emaj_gid > ' || strtmark.mark_global_seq ||
						' order by emaj_gid' as sql_text
					FROM \"{$this->emaj_schema}\".emaj_log_stat_group('{$group}','{$firstMark}',NULL), 
						\"{$this->emaj_schema}\".emaj_mark strtmark, \"{$this->emaj_schema}\".emaj_relation
					WHERE stat_rows > 0 
						and strtmark.mark_group = '{$group}' 
						and strtmark.mark_name = '{$firstMark}' 
						and rel_schema = stat_schema and rel_tblseq = stat_table";
		}else{
			$sql = "CREATE TEMP TABLE tmp_stat AS
					SELECT stat_group, stat_schema, stat_table, stat_rows, 
					'select * from ' || ";
			if ($this->getNumEmajVersion() >= 10000){	// version >= 1.0.0
				$sql .= "quote_ident(rel_log_schema)";
			}else{
				$sql .= "'{$this->emaj_schema}'";
			}
			$sql .= " || '.' || quote_ident(stat_schema || '_' || stat_table || '_log') ||
						' where emaj_gid > ' || strtmark.mark_global_seq ||
						' and emaj_gid <= ' || stopmark.mark_global_seq ||
						' order by emaj_gid' as sql_text
					FROM \"{$this->emaj_schema}\".emaj_log_stat_group('{$group}','{$firstMark}','{$lastMark}'), 
						\"{$this->emaj_schema}\".emaj_mark strtmark , \"{$this->emaj_schema}\".emaj_mark stopmark, \"{$this->emaj_schema}\".emaj_relation
					WHERE stat_rows > 0 
						and strtmark.mark_group = '{$group}' 
						and strtmark.mark_name = '{$firstMark}' 
						and stopmark.mark_group = '{$group}' 
						and stopmark.mark_name = '{$lastMark}' 
						and rel_schema = stat_schema and rel_tblseq = stat_table";
		}

		$data->execute($sql);

		$sql = "SELECT stat_group, stat_schema, stat_table, stat_rows, sql_text FROM tmp_stat
				ORDER BY stat_group, stat_schema, stat_table";

		return $data->selectSet($sql);
	}

	/**
	 * Gets some aggregates from the temporary log_stat table created by the just previously called getLogStatGroup() function
	 */
	function getLogStatSummary() {
		global $data;

		$sql = "SELECT coalesce(sum(stat_rows),0) AS sum_rows, count(distinct stat_table) AS nb_tables 
				FROM tmp_stat";

		return $data->selectSet($sql);
	}

	/**
	 * Gets the detailed log statistics for a group between 2 marks
	 * It also delivers the sql queries to look at the corresponding log rows
	 * It creates a temp table to easily compute aggregates for the same conversation
	 */
	function getDetailedLogStatGroup($group,$firstMark,$lastMark) {
		global $data;

		$data->clean($group);
		$data->clean($firstMark);
		$data->clean($lastMark);

		if ($lastMark==''){
			$sql = "CREATE TEMP TABLE tmp_stat AS
					SELECT stat_group, stat_schema, stat_table, stat_role, stat_verb, stat_rows, 
					'select * from ' || ";
			if ($this->getNumEmajVersion() >= 10000){	// version >= 1.0.0
				$sql .= "quote_ident(rel_log_schema)";
			}else{
				$sql .= "'{$this->emaj_schema}'";
			}
			$sql .=    " || '.' || quote_ident(stat_schema || '_' || stat_table || '_log') || 
						' where emaj_gid > ' || strtmark.mark_global_seq ||
						' and emaj_verb = ' || quote_literal(substring(stat_verb from 1 for 3)) || 
						' order by emaj_gid' as sql_text
					FROM \"{$this->emaj_schema}\".emaj_detailed_log_stat_group('{$group}','{$firstMark}',NULL), 
						\"{$this->emaj_schema}\".emaj_mark strtmark, \"{$this->emaj_schema}\".emaj_relation
					WHERE stat_rows > 0 
						and strtmark.mark_group = '{$group}' 
						and strtmark.mark_name = '{$firstMark}' 
						and rel_schema = stat_schema and rel_tblseq = stat_table";
		}else{
			$sql = "CREATE TEMP TABLE tmp_stat AS
					SELECT stat_group, stat_schema, stat_table, stat_role, stat_verb, stat_rows, 
					'select * from ' || ";
			if ($this->getNumEmajVersion() >= 10000){	// version >= 1.0.0
				$sql .= "quote_ident(rel_log_schema)";
			}else{
				$sql .= "'{$this->emaj_schema}'";
			}
			$sql .=    " || '.' || quote_ident(stat_schema || '_' || stat_table || '_log') || 
						' where emaj_gid > ' || strtmark.mark_global_seq ||
						' and emaj_gid <= ' || stopmark.mark_global_seq ||
						' and emaj_verb = ' || quote_literal(substring(stat_verb from 1 for 3)) || 
						' order by emaj_gid' as sql_text
					FROM \"{$this->emaj_schema}\".emaj_detailed_log_stat_group('{$group}','{$firstMark}','{$lastMark}'), 
						\"{$this->emaj_schema}\".emaj_mark strtmark , \"{$this->emaj_schema}\".emaj_mark stopmark, \"{$this->emaj_schema}\".emaj_relation
					WHERE stat_rows > 0 
						and strtmark.mark_group = '{$group}' 
						and strtmark.mark_name = '{$firstMark}' 
						and stopmark.mark_group = '{$group}' 
						and stopmark.mark_name = '{$lastMark}' 
						and rel_schema = stat_schema and rel_tblseq = stat_table";
		}
		$data->execute($sql);

		$sql = "SELECT stat_group, stat_schema, stat_table, stat_role, stat_verb, stat_rows, sql_text FROM tmp_stat
				ORDER BY stat_group, stat_schema, stat_table, stat_role, stat_verb";

		return $data->selectSet($sql);
	}

	/**
	 * Gets some aggregates from the temporary log_stat table created by the just previously called getDetailedLogStatGroup() function
	 */
	function getDetailedLogStatSummary() {
		global $data;

		$sql = "SELECT coalesce(sum(stat_rows),0) AS sum_rows, count(distinct stat_table) AS nb_tables,
				coalesce((SELECT sum(stat_rows) FROM tmp_stat WHERE stat_verb = 'INSERT'),0) as nb_ins,
				coalesce((SELECT sum(stat_rows) FROM tmp_stat WHERE stat_verb = 'UPDATE'),0) as nb_upd,
				coalesce((SELECT sum(stat_rows) FROM tmp_stat WHERE stat_verb = 'DELETE'),0) as nb_del,
				coalesce((SELECT sum(stat_rows) FROM tmp_stat WHERE stat_verb = 'TRUNCATE'),0) as nb_tru,
				coalesce((SELECT count(distinct stat_role) FROM tmp_stat),0) as nb_roles
				FROM tmp_stat";

		return $data->selectSet($sql);
	}

	/**
	 * Gets distinct roles from the temporary log_stat table created by the just previously called getDetailedLogStatGroup() function
TODO : when 8.3 will not be supported any more, an aggregate function would be to be included into getDetailedLogStatSummary()
array_to_string(array_agg(stat_role), ',') puis (string_agg(stat_role), ',') en 9.0+
	 */
	function getDetailedLogStatRoles() {
		global $data;

		$sql = "SELECT distinct stat_role FROM tmp_stat ORDER BY 1";

		return $data->selectSet($sql);
	}

}
?>
